<?php
// GET /api/pedido.php?o=TOKEN&fim=1 -> resumo para as páginas de upsell e obrigado. fim=1 fecha o funil (Purchase).
require __DIR__ . '/_lib.php';
$p = pedido((string)($_GET['o'] ?? ''));
if (!$p) out(['error' => 'Order not found.'], 404);
if ($p['status'] === 'aberto') {
  require __DIR__ . '/_pago.php';
  $pi = stripe('GET', 'payment_intents/' . $p['pi_front']);
  if (($pi['status'] ?? '') === 'succeeded') { registrar_pago($pi); $p = pedido($p['token']); }
}
if ($p['status'] === 'pago') $p = garantir_pos($p);
if (!empty($_GET['fim'])) { finalizar($p); $p = pedido($p['token']); }
$pr = precos_p($p);
$linhas = [['Hunt kit (' . ($p['kit'] === 'two' ? '2 kits, ' . $p['mix'] : '1 kit, ' . $p['pet']) . ')', (int)$p['valor_front'] - ($p['bump'] ? $pr['bump'] : 0)]];
if ($p['bump']) $linhas[] = ['Spare plush pack', $pr['bump']];
if ($p['upsell'] === 'aceito') $linhas[] = ['Second kit (' . $p['upsell_pet'] . '), same box', (int)$p['valor_upsell']];
if (str_starts_with((string)$p['downsell'], 'aceito')) $linhas[] = [nome_extra('downsell', (string)$p['downsell'], (string)$p['upsell_pet']), (int)$p['valor_downsell']];
if (($p['extra'] ?? '') === 'aceito') $linhas[] = [nome_extra('extra', 'aceito', (string)$p['upsell_pet'], (int)$p['custo_extra']), (int)$p['valor_extra']];
$downloads = $p['status'] === 'pago' ? links_download($p) : [];
out(['downloads' => $downloads, 'status' => $p['status'], 'pet' => $p['pet'], 'email' => $p['email'], 'linhas' => $linhas,
  'total' => array_sum(array_column($linhas, 1)), 'upsell' => $p['upsell'], 'downsell' => $p['downsell'], 'extra' => $p['extra'] ?? null, 'upsell_pet' => $p['upsell_pet'], 'bump' => (int)$p['bump'],
  // o que as páginas pós-compra mostram (o servidor decide preço e tipo; a página só exibe)
  'oferta' => ['upsell' => $pr['upsell'], 'downsell' => $pr['downsell'], 'downsell_kind' => $pr['downsell_kind'], 'extra' => $pr['extra'] ?? 0, 'extra_kind' => $pr['extra_kind'] ?? 'plush', 'regular' => $pr['one']], 'event_id' => 'purchase.' . $p['token']]);
