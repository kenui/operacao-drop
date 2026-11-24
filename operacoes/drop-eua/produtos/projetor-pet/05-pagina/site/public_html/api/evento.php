<?php
// POST /api/evento.php -> eventos de comportamento da página. Repassa ao Meta (API de Conversões) com o mesmo event_id do pixel.
// v3: leva junto a identidade já digitada no checkout (e-mail, telefone, nome, cidade, estado, CEP), com hash no capi(),
// e os dados do evento (valor, moeda, produto). Quem pediu limite (GPC ou "Your Privacy Choices") vai com LDU, não é cortado.
// A identidade NÃO é gravada no log de eventos.
require __DIR__ . '/_lib.php';
only_post();
$e = body();
if (empty($e['evento']) || !preg_match('/^[a-z0-9_]{2,40}$/', (string)$e['evento'])) out(['error' => 'bad event'], 400);
$ud = is_array($e['ud'] ?? null) ? $e['ud'] : []; unset($e['ud']);
log_evento($e);
$map = ['page_view' => 'PageView', 'view_content' => 'ViewContent', 'kit_select' => 'AddToCart', 'initiate_checkout' => 'InitiateCheckout', 'add_payment_info' => 'AddPaymentInfo'];
if (isset($map[$e['evento']])) {
  $s = fn($k, $n = 120) => isset($ud[$k]) && is_scalar($ud[$k]) ? substr((string)$ud[$k], 0, $n) : null;
  $user = ['vid' => $e['visitante_id'] ?? '', 'ip' => ip(), 'ua' => $_SERVER['HTTP_USER_AGENT'] ?? '', 'fbc' => ($e['fbc'] ?? '') ?: null, 'fbp' => ($e['fbp'] ?? '') ?: null,
    'email' => $s('em'), 'phone' => $s('ph', 20), 'fn' => $s('fn', 60), 'ln' => $s('ln', 60), 'city' => $s('ct', 60), 'state' => $s('st', 4), 'zip' => $s('zp', 10), 'ldu' => !empty($e['optout'])];
  // dados do evento: só campos conhecidos
  $m = is_array($e['meta'] ?? null) ? $e['meta'] : []; $custom = [];
  if (isset($m['value']) && is_numeric($m['value'])) $custom['value'] = round((float)$m['value'], 2);
  if (!empty($m['currency'])) $custom['currency'] = 'USD';
  if (!empty($m['content_ids']) && is_array($m['content_ids'])) $custom['content_ids'] = array_slice(array_map(fn($x) => substr((string)$x, 0, 40), $m['content_ids']), 0, 5);
  if (!empty($m['content_type'])) $custom['content_type'] = 'product';
  if (isset($m['num_items']) && is_numeric($m['num_items'])) $custom['num_items'] = (int)$m['num_items'];
  $url = (string)($e['url'] ?? ''); if (!preg_match('#^https://#', $url)) $url = (string)($_SERVER['HTTP_REFERER'] ?? '');
  capi($map[$e['evento']], (string)$e['event_id'], $user, $custom, substr($url, 0, 500));
}
out(['ok' => true]);
