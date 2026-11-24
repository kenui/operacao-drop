<?php
// POST /api/upsell.php {order, etapa: upsell|downsell|extra, acao: aceitar|recusar, pet?}
// Aceitar = nova cobrança off-session no cartão salvo (consentimento dado no checkout). Nunca insiste mais de uma vez.
// Sequência (07-funil.md, D13): upsell (2º kit) → aceitou: extra (pelúcias para o 2º kit) · recusou: downsell → obrigado.
require __DIR__ . '/_lib.php';
only_post();
$c = cfg(); $b = body(); $p = pedido((string)($b['order'] ?? ''));
$etapa = in_array($b['etapa'] ?? '', ['downsell', 'extra'], true) ? $b['etapa'] : 'upsell';
if (!$p) out(['error' => 'Order not found.'], 404);

// o webhook pode chegar depois do redirecionamento: confere direto na Stripe
if ($p['status'] === 'aberto') {
  $pi = stripe('GET', 'payment_intents/' . $p['pi_front']);
  if (($pi['status'] ?? '') === 'succeeded') { marcar_pago($p, $pi); $p = pedido($p['token']); }
}
if ($p['status'] !== 'pago') out(['error' => 'Payment not confirmed yet. Please wait a moment and try again.'], 409);
$p = garantir_pos($p);
if (!empty($p[$etapa]) && $p[$etapa] !== 'pendente') out(['ok' => true, 'ja' => $p[$etapa], 'next' => proximo($p, $etapa, (string)$p[$etapa])]);
if ($etapa === 'downsell' && ($p['upsell'] === 'aceito' || !mostra_downsell($p))) out(['ok' => true, 'next' => 'obrigado']);
if ($etapa === 'extra' && ($p['upsell'] !== 'aceito' || !mostra_extra())) out(['ok' => true, 'next' => 'obrigado']);

// depois da autenticação feita na página: confere na Stripe e registra
if (($b['acao'] ?? '') === 'confirmado') {
  $pi = stripe('GET', 'payment_intents/' . preg_replace('/[^A-Za-z0-9_]/', '', (string)($b['pi'] ?? '')));
  if (($pi['status'] ?? '') === 'succeeded' && ($pi['metadata']['pedido'] ?? '') === $p['token'] && ($pi['metadata']['etapa'] ?? '') === $etapa) {
    gravar_aceite($p, $etapa, $pi, $c); out(['ok' => true, 'next' => proximo($p, $etapa, 'aceito')]);
  }
  db()->prepare("UPDATE pedidos SET $etapa = 'falhou' WHERE token = ?")->execute([$p['token']]);
  out(['ok' => false, 'error' => 'The add-on was not charged. Your original order is fine.', 'next' => proximo($p, $etapa, 'falhou')]);
}

if (($b['acao'] ?? '') !== 'aceitar') {
  db()->prepare("UPDATE pedidos SET $etapa = 'recusado' WHERE token = ?")->execute([$p['token']]);
  out(['ok' => true, 'next' => proximo($p, $etapa, 'recusado')]);
}

$pr = precos_p($p); $preco = (int)$pr[$etapa];
$upet = limpa_pet($b['pet'] ?? $p['pet']);
$tipo = match ($etapa) { 'upsell' => 'kit', 'extra' => 'plush', default => $pr['downsell_kind'] };
$desc = match (true) {
  $etapa === 'upsell', $tipo === 'kit' => "Second hunt kit ($upet), ships in the same box",
  $etapa === 'extra' => 'Spare plush pack for the second kit, same box',
  $tipo === 'guide' => 'The Indoor Hunt Playbook (PDF)',
  default => 'Spare plush pack',
};
$pi = stripe('POST', 'payment_intents', [
  'amount' => $preco, 'currency' => 'usd', 'customer' => $p['customer'], 'payment_method' => $p['payment_method'],
  'off_session' => 'true', 'confirm' => 'true', 'description' => $desc,
  'metadata[tipo]' => $tipo, 'receipt_email' => $p['email'] ?: null,
  'metadata[pedido]' => $p['token'], 'metadata[etapa]' => $etapa, 'metadata[pet]' => $upet, 'metadata[variante_pos]' => (string)$p['variante_pos'],
], $etapa . '-' . $p['token']);

$status = $pi['status'] ?? ($pi['error']['payment_intent']['status'] ?? '');
if ($status === 'succeeded') {
  gravar_aceite($p, $etapa, $pi, $c);
  out(['ok' => true, 'next' => proximo($p, $etapa, 'aceito')]);
}
// o banco pediu autenticação: devolve o segredo para confirmar na página (uma vez)
$sec = $pi['client_secret'] ?? ($pi['error']['payment_intent']['client_secret'] ?? null);
if ($sec && in_array($status, ['requires_action', 'requires_payment_method', 'requires_confirmation'], true) && ($pi['error']['code'] ?? '') === 'authentication_required') {
  $err = $pi['error']['payment_intent'] ?? $pi;
  out(['ok' => false, 'auth' => true, 'clientSecret' => $sec, 'pi' => $err['id'] ?? '', 'pm' => $p['payment_method']]);
}
db()->prepare("UPDATE pedidos SET $etapa = 'falhou' WHERE token = ?")->execute([$p['token']]);
out(['ok' => false, 'error' => 'Your bank declined this add-on. Your original order is fine.', 'next' => proximo($p, $etapa, 'falhou')]);

// downsell só para quem recusou o upsell; o de pelúcias não aparece para quem já pegou o bump (não repetir oferta)
function mostra_downsell(array $p): bool {
  if (empty(cfg()['downsell_on'])) return false;
  $k = precos_p($p)['downsell_kind'];
  return !($k === 'plush' && (int)$p['bump'] === 1);
}
function mostra_extra(): bool { return !empty(cfg()['extra_on']); }
function proximo(array $p, string $etapa, string $res): string {
  if ($etapa !== 'upsell') return 'obrigado';
  if ($res === 'aceito') return mostra_extra() ? 'extra' : 'obrigado';
  return mostra_downsell($p) ? 'downsell' : 'obrigado';
}
function marcar_pago(array $p, array $pi): void { require_once __DIR__ . '/_pago.php'; registrar_pago($pi); }
function gravar_aceite(array $p, string $etapa, array $pi, array $c): void {
  $k = $pi['metadata']['tipo'] ?? 'plush'; $pet = $pi['metadata']['pet'] ?? $p['pet'];
  if ($etapa === 'upsell') db()->prepare("UPDATE pedidos SET upsell='aceito', pi_upsell=?, upsell_pet=?, valor_upsell=?, custo_upsell=? WHERE token=?")->execute([$pi['id'], $pet, (int)$pi['amount'], $c['custos']['kit'], $p['token']]);
  elseif ($etapa === 'extra') db()->prepare("UPDATE pedidos SET extra='aceito', pi_extra=?, valor_extra=?, custo_extra=? WHERE token=?")->execute([$pi['id'], (int)$pi['amount'], $c['custos']['extra'] ?? 450, $p['token']]);
  elseif ($k === 'kit') db()->prepare("UPDATE pedidos SET downsell='aceito-kit', pi_downsell=?, upsell_pet=?, valor_downsell=?, custo_downsell=? WHERE token=?")->execute([$pi['id'], $pet, (int)$pi['amount'], $c['custos']['downsell_kit'] ?? $c['custos']['kit'], $p['token']]);
  else db()->prepare("UPDATE pedidos SET downsell=?, pi_downsell=?, valor_downsell=?, custo_downsell=? WHERE token=?")->execute([$k === 'guide' ? 'aceito-guide' : 'aceito-plush', $pi['id'], (int)$pi['amount'], $c['custos']['downsell_' . ($k === 'guide' ? 'guide' : 'plush')] ?? 0, $p['token']]);
}
