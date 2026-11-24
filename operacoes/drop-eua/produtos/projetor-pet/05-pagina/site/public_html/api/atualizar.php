<?php
// POST /api/atualizar.php -> troca kit, mistura, bump ou e-mail do pedido aberto e ajusta o valor do PaymentIntent.
require __DIR__ . '/_lib.php';
only_post();
$b = body(); $p = pedido((string)($b['order'] ?? ''));
if (!$p || $p['status'] !== 'aberto') out(['error' => 'This checkout expired. Please refresh the page.'], 409);
$kit = ($b['kit'] ?? '') === 'two' ? 'two' : 'one';
$pet = limpa_pet($b['pet'] ?? $p['pet']);
$mix = $kit === 'two' ? implode('+', array_map('limpa_pet', array_slice((array)($b['mix'] ?? [$pet, $pet]), 0, 2))) : $pet;
$bump = !empty($b['bump']);
$valor = valor_front($kit, $bump);
$params = ['amount' => $valor, 'description' => "Hunt kit ($kit, $mix)" . ($bump ? ' + spare plush' : ''),
  'metadata[pet]' => $pet, 'metadata[kit]' => $kit, 'metadata[mix]' => $mix, 'metadata[bump]' => $bump ? '1' : '0'];
$email = filter_var((string)($b['email'] ?? ''), FILTER_VALIDATE_EMAIL) ?: null;
if ($email) $params['receipt_email'] = $email;
$pi = stripe('POST', 'payment_intents/' . $p['pi_front'], $params);
if (empty($pi['id'])) out(['error' => $pi['error']['message'] ?? 'Could not update your order.'], 502);
if ($email) stripe('POST', 'customers/' . $p['customer'], ['email' => $email]);
db()->prepare('UPDATE pedidos SET pet=?, kit=?, mix=?, bump=?, valor_front=?, custo_front=?, email=COALESCE(?, email) WHERE token=?')
    ->execute([$pet, $kit, $mix, $bump ? 1 : 0, $valor, custo_front($kit, $bump), $email, $p['token']]);
out(['ok' => true, 'amount' => $valor]);
