<?php
// POST /api/upsell.php {order, etapa: upsell|downsell, acao: aceitar|recusar, pet?}
// Aceitar = nova cobrança off-session no cartão salvo (consentimento dado no checkout). Nunca insiste mais de uma vez.
require __DIR__ . '/_lib.php';
only_post();
$c = cfg(); $b = body(); $p = pedido((string)($b['order'] ?? ''));
$etapa = ($b['etapa'] ?? '') === 'downsell' ? 'downsell' : 'upsell';
if (!$p) out(['error' => 'Order not found.'], 404);

// o webhook pode chegar depois do redirecionamento: confere direto na Stripe
if ($p['status'] === 'aberto') {
  $pi = stripe('GET', 'payment_intents/' . $p['pi_front']);
  if (($pi['status'] ?? '') === 'succeeded') { marcar_pago($p, $pi); $p = pedido($p['token']); }
}
if ($p['status'] !== 'pago') out(['error' => 'Payment not confirmed yet. Please wait a moment and try again.'], 409);
if (!empty($p[$etapa]) && $p[$etapa] !== 'pendente') out(['ok' => true, 'ja' => $p[$etapa], 'next' => proximo($p, $etapa, $p[$etapa])]);
if ($etapa === 'downsell' && empty($c['downsell_on'])) out(['ok' => true, 'next' => 'obrigado']);

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

$preco = $c['precos'][$etapa];
$upet = limpa_pet($b['pet'] ?? $p['pet']);
$pi = stripe('POST', 'payment_intents', [
  'amount' => $preco, 'currency' => 'usd', 'customer' => $p['customer'], 'payment_method' => $p['payment_method'],
  'off_session' => 'true', 'confirm' => 'true',
  'description' => $etapa === 'upsell' ? "Second hunt kit ($upet), ships in the same box" : 'The 10-Minute Hunt (PDF guide)',
  'receipt_email' => $p['email'] ?: null,
  'metadata[pedido]' => $p['token'], 'metadata[etapa]' => $etapa, 'metadata[pet]' => $upet,
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

function proximo(array $p, string $etapa, string $res): string {
  if ($etapa === 'upsell' && $res !== 'aceito' && !empty(cfg()['downsell_on'])) return 'downsell';
  return 'obrigado';
}
function marcar_pago(array $p, array $pi): void { require_once __DIR__ . '/_pago.php'; registrar_pago($pi); }
function gravar_aceite(array $p, string $etapa, array $pi, array $c): void {
  if ($etapa === 'upsell') db()->prepare("UPDATE pedidos SET upsell='aceito', pi_upsell=?, upsell_pet=?, valor_upsell=?, custo_upsell=? WHERE token=?")->execute([$pi['id'], $pi['metadata']['pet'] ?? $p['pet'], (int)$pi['amount'], $c['custos']['kit'], $p['token']]);
  else db()->prepare("UPDATE pedidos SET downsell='aceito', pi_downsell=?, valor_downsell=? WHERE token=?")->execute([$pi['id'], (int)$pi['amount'], $p['token']]);
}
