<?php
// Marca o pedido como pago a partir de um PaymentIntent da Stripe (usado pelo webhook e como reserva no upsell).
function registrar_pago(array $pi): void {
  $tok = $pi['metadata']['pedido'] ?? ''; if (!$tok) return;
  $sh = $pi['shipping'] ?? []; $a = $sh['address'] ?? [];
  $email = $pi['receipt_email'] ?? null;
  $pm = is_array($pi['payment_method'] ?? null) ? $pi['payment_method']['id'] : ($pi['payment_method'] ?? null);
  $u = db()->prepare("UPDATE pedidos SET status='pago', pago_em=?, payment_method=?, email=COALESCE(?, email), nome=?, cep=?, cidade=?, estado=?, endereco=? WHERE token=? AND status='aberto'");
  $u->execute([gmdate('Y-m-d H:i:s'), $pm, $email, $sh['name'] ?? null, $a['postal_code'] ?? null, $a['city'] ?? null, $a['state'] ?? null, json_encode($a), $tok]);
  if ($u->rowCount()) {
    $p = pedido($tok); $ctx = json_decode((string)$p['ctx'], true) ?: [];
    log_evento(['visitante_id' => $ctx['visitante_id'] ?? '', 'evento' => 'purchase_front', 'event_id' => 'front.' . $tok, 'pet' => $p['pet'], 'angulo' => $p['angulo'], 'dados' => ['pedido' => $tok, 'valor' => (int)$pi['amount']]]);
  }
}
