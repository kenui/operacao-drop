<?php
// POST /api/webhook.php -> fonte da verdade da venda. Confere a assinatura da Stripe.
require __DIR__ . '/_lib.php';
require __DIR__ . '/_pago.php';
$payload = file_get_contents('php://input') ?: '';
$sig = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
$parts = []; foreach (explode(',', $sig) as $kv) { [$k, $v] = array_pad(explode('=', $kv, 2), 2, ''); $parts[$k][] = $v; }
$t = (int)($parts['t'][0] ?? 0);
$ok = false; foreach ($parts['v1'] ?? [] as $v1) { if (hash_equals(hash_hmac('sha256', $t . '.' . $payload, cfg()['stripe_webhook']), $v1)) $ok = true; }
if (!$ok || abs(time() - $t) > 300) out(['error' => 'bad signature'], 400);

$ev = json_decode($payload, true); $obj = $ev['data']['object'] ?? [];
try { db()->prepare('INSERT INTO webhooks (id, criado) VALUES (?, ?)')->execute([$ev['id'], gmdate('Y-m-d H:i:s')]); }
catch (Throwable $e) { out(['ok' => true, 'dup' => true]); }   // já processado

switch ($ev['type']) {
  case 'payment_intent.succeeded':
    if (($obj['metadata']['etapa'] ?? '') === 'front') registrar_pago($obj);
    break;
  case 'payment_intent.payment_failed':
    log_evento(['evento' => 'payment_failed_srv', 'event_id' => $ev['id'], 'dados' => ['pedido' => $obj['metadata']['pedido'] ?? '', 'motivo' => $obj['last_payment_error']['code'] ?? '']]);
    break;
  case 'charge.refunded':
    db()->prepare('UPDATE pedidos SET reembolsado = reembolsado + ? WHERE pi_front = ? OR pi_upsell = ? OR pi_downsell = ?')->execute([(int)$obj['amount_refunded'], $obj['payment_intent'], $obj['payment_intent'], $obj['payment_intent']]);
    log_evento(['evento' => 'refund', 'event_id' => $ev['id'], 'dados' => ['pi' => $obj['payment_intent'], 'valor' => $obj['amount_refunded']]]);
    break;
  case 'charge.dispute.created':
    db()->prepare('UPDATE pedidos SET contestado = 1 WHERE pi_front = ? OR pi_upsell = ? OR pi_downsell = ?')->execute([$obj['payment_intent'], $obj['payment_intent'], $obj['payment_intent']]);
    log_evento(['evento' => 'dispute', 'event_id' => $ev['id'], 'dados' => ['pi' => $obj['payment_intent'], 'motivo' => $obj['reason'] ?? '']]);
    break;
  case 'radar.early_fraud_warning.created':
    log_evento(['evento' => 'fraud_warning', 'event_id' => $ev['id'], 'dados' => ['charge' => $obj['charge'] ?? '']]);
    break;
}
out(['ok' => true]);
