<?php
// POST /api/evento.php -> eventos de comportamento da página. Repassa ao Meta (API de Conversões) com o mesmo event_id do pixel.
require __DIR__ . '/_lib.php';
only_post();
$e = body();
if (empty($e['evento']) || !preg_match('/^[a-z0-9_]{2,40}$/', (string)$e['evento'])) out(['error' => 'bad event'], 400);
log_evento($e);
$map = ['page_view' => 'PageView', 'view_content' => 'ViewContent', 'kit_select' => 'AddToCart', 'initiate_checkout' => 'InitiateCheckout', 'add_payment_info' => 'AddPaymentInfo'];
if (isset($map[$e['evento']]) && empty($e['optout'])) {
  capi($map[$e['evento']], (string)$e['event_id'], ['vid' => $e['visitante_id'] ?? '', 'ip' => ip(), 'ua' => $_SERVER['HTTP_USER_AGENT'] ?? '', 'fbc' => $e['fbc'] ?: null, 'fbp' => $e['fbp'] ?: null], [], ($_SERVER['HTTP_REFERER'] ?? ''));
}
out(['ok' => true]);
