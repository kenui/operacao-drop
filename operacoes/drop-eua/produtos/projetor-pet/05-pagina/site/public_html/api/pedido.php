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
if (!empty($_GET['fim'])) { finalizar($p); $p = pedido($p['token']); }
$pr = cfg()['precos'];
$linhas = [['Hunt kit (' . ($p['kit'] === 'two' ? '2 kits, ' . $p['mix'] : '1 kit, ' . $p['pet']) . ')', ($p['kit'] === 'two' ? $pr['two'] : $pr['one'])]];
if ($p['bump']) $linhas[] = ['Spare plush pack', $pr['bump']];
if ($p['upsell'] === 'aceito') $linhas[] = ['Second kit (' . $p['upsell_pet'] . '), same box', (int)$p['valor_upsell']];
if ($p['downsell'] === 'aceito') $linhas[] = ['The 10-Minute Hunt (PDF)', (int)$p['valor_downsell']];
out(['status' => $p['status'], 'pet' => $p['pet'], 'email' => $p['email'], 'linhas' => $linhas,
  'total' => array_sum(array_column($linhas, 1)), 'upsell' => $p['upsell'], 'downsell' => $p['downsell'], 'event_id' => 'purchase.' . $p['token']]);
