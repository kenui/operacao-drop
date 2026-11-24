<?php
// POST /api/checkout.php -> cria Customer + PaymentIntent (USD, cartão salvo para o upsell com consentimento).
require __DIR__ . '/_lib.php';
only_post();
$c = cfg(); $b = body(); $db = db();

// limite de tentativas por IP (teste de cartões roubados)
$desde = gmdate('Y-m-d H:i:s', time() - 600);
$q = $db->prepare('SELECT COUNT(*) FROM tentativas WHERE ip = ? AND criado > ?'); $q->execute([ip(), $desde]);
if ((int)$q->fetchColumn() >= ($c['limite_tentativas_ip_10min'] ?? 8)) out(['error' => 'Too many attempts. Please wait a few minutes.'], 429);
$db->prepare('INSERT INTO tentativas (ip, criado) VALUES (?, ?)')->execute([ip(), gmdate('Y-m-d H:i:s')]);

$kit = ($b['kit'] ?? '') === 'two' ? 'two' : 'one';
$pet = limpa_pet($b['pet'] ?? '');
$mix = $kit === 'two' ? implode('+', array_map('limpa_pet', array_slice((array)($b['mix'] ?? [$pet, $pet]), 0, 2))) : $pet;
$bump = !empty($b['bump']);
$ctx = (array)($b['ctx'] ?? []);
$angulo = substr(preg_replace('/[^a-z]/', '', (string)($ctx['angulo'] ?? '')), 0, 20);
$tok = token();
$var = variante(substr(preg_replace('/[^A-Za-z0-9]/', '', (string)($ctx['visitante_id'] ?? '')), 0, 40));
$valor = valor_front($kit, $bump, $var);

$cus = stripe('POST', 'customers', ['metadata[pedido]' => $tok], 'cus-' . $tok);
if (empty($cus['id'])) out(['error' => 'Could not start checkout. Please try again.'], 502);

$pi = stripe('POST', 'payment_intents', [
  'amount' => $valor, 'currency' => 'usd', 'customer' => $cus['id'],
  'setup_future_usage' => 'off_session',
  'automatic_payment_methods[enabled]' => 'true',
  'statement_descriptor_suffix' => substr(strtoupper(preg_replace('/[^A-Za-z0-9 ]/', '', $c['descritor'] ?? '')), 0, 22) ?: null,
  'description' => "Hunt kit ($kit, $mix)" . ($bump ? ' + spare plush' : ''),
  'metadata[pedido]' => $tok, 'metadata[etapa]' => 'front', 'metadata[pet]' => $pet, 'metadata[kit]' => $kit, 'metadata[mix]' => $mix,
  'metadata[bump]' => $bump ? '1' : '0', 'metadata[angulo]' => $angulo, 'metadata[variante]' => $var, 'metadata[utm_campaign]' => substr((string)($ctx['utm']['utm_campaign'] ?? ''), 0, 100),
], 'pi-' . $tok);
if (empty($pi['client_secret'])) out(['error' => 'Could not start checkout. Please try again.'], 502);

$db->prepare('INSERT INTO pedidos (token, criado, status, pi_front, customer, pet, kit, mix, bump, angulo, variante, valor_front, custo_front, ip, ua, ctx) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
   ->execute([$tok, gmdate('Y-m-d H:i:s'), 'aberto', $pi['id'], $cus['id'], $pet, $kit, $mix, $bump ? 1 : 0, $angulo, $var, $valor, custo_front($kit, $bump), ip(), substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255), json_encode($ctx)]);

out(['order' => $tok, 'clientSecret' => $pi['client_secret'], 'amount' => $valor, 'variante' => $var]);
