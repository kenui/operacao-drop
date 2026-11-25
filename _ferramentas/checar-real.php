<?php
// Cron (CLI), uma vez: confere a cobrança real sem cobrar cartão nenhum. Escreve public_html/_status/real.txt (web negado).
// 1) conta Stripe live (país, liberações, pendências); 2) webhook live na Stripe; 3) checkout de verdade pelo próprio site
// (cria Customer + PaymentIntent live SEM confirmar, depois cancela e apaga o pedido de teste); 4) webhook do site com
// assinatura válida (200) e inválida (400); 5) e-mail de teste pelo mesmo mail() da confirmação. Sem segredo na saída.
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
$raiz = dirname(__DIR__, 2); $pub = $raiz . '/public_html'; $dir = $pub . '/_status';
if (!is_dir($dir)) mkdir($dir, 0755);
file_put_contents($dir . '/.htaccess', "Require all denied\n");
$_SERVER['REQUEST_METHOD'] = 'CLI';
ob_start(); require $pub . '/api/_lib.php'; ob_end_clean();
$c = cfg(); $o = ['gerado' => gmdate('c'), 'modo' => $c['modo'] ?? '?', 'chave_live' => str_starts_with((string)$c['stripe_secret'], 'sk_live_') || str_starts_with((string)$c['stripe_secret'], 'rk_live_')];
$site = 'https://' . ($argv[1] ?? 'littlecatchco.com');
function post(string $url, string $body, array $h): array {
  $ch = curl_init($url);
  curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => $body, CURLOPT_HTTPHEADER => $h, CURLOPT_TIMEOUT => 30, CURLOPT_USERAGENT => 'checar-real']);
  $r = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); $err = curl_error($ch); curl_close($ch);
  return [$code, (string)$r, $err];
}
try {
  $a = stripe('GET', 'account');
  $o['conta'] = ['id' => substr((string)($a['id'] ?? ''), 0, 12) . '…', 'pais' => $a['country'] ?? null, 'cobra' => $a['charges_enabled'] ?? null, 'saca' => $a['payouts_enabled'] ?? null,
    'moeda' => $a['default_currency'] ?? null, 'descritor' => $a['settings']['payments']['statement_descriptor'] ?? null,
    'pendente_agora' => $a['requirements']['currently_due'] ?? null, 'atrasado' => $a['requirements']['past_due'] ?? null,
    'prazo' => isset($a['requirements']['current_deadline']) ? gmdate('c', (int)$a['requirements']['current_deadline']) : null,
    'motivo_bloqueio' => $a['requirements']['disabled_reason'] ?? null, 'erros' => array_map(fn($e) => ($e['requirement'] ?? '') . ': ' . ($e['code'] ?? ''), $a['requirements']['errors'] ?? [])];
  $w = stripe('GET', 'webhook_endpoints', ['limit' => 10]);
  $o['webhooks_stripe'] = array_map(fn($e) => ['url' => $e['url'], 'status' => $e['status'], 'live' => $e['livemode'], 'eventos' => $e['enabled_events']], $w['data'] ?? []);
  $d = stripe('GET', 'payment_method_domains', ['limit' => 10]);
  $o['dominios_pagamento'] = array_map(fn($e) => ['dominio' => $e['domain_name'], 'ativo' => $e['enabled'], 'apple_pay' => $e['apple_pay']['status'] ?? null, 'google_pay' => $e['google_pay']['status'] ?? null], $d['data'] ?? []);
  $ev = stripe('GET', 'events', ['limit' => 10]);
  $o['eventos_stripe'] = array_map(fn($e) => [gmdate('c', $e['created']), $e['type'], 'pendentes' => $e['pending_webhooks']], $ev['data'] ?? []);
} catch (Throwable $e) { $o['erro_stripe'] = $e->getMessage(); }

// 3) checkout de verdade pelo site
try {
  [$code, $r, $err] = post($site . '/api/checkout.php', json_encode(['kit' => 'one', 'pet' => 'dog', 'bump' => false, 'ctx' => ['visitante_id' => 'checarreal' . time()]]), ['Content-Type: application/json']);
  $j = json_decode($r, true) ?: [];
  $o['checkout'] = ['http' => $code, 'erro_curl' => $err, 'tem_client_secret' => !empty($j['clientSecret']), 'valor' => $j['amount'] ?? null, 'resposta_erro' => $j['error'] ?? null];
  if (!empty($j['order'])) {
    $p = db()->prepare('SELECT pi_front, customer FROM pedidos WHERE token = ?'); $p->execute([$j['order']]); $row = $p->fetch();
    if ($row) {
      $pi = stripe('GET', 'payment_intents/' . $row['pi_front']);
      $o['checkout']['pi_live'] = $pi['livemode'] ?? null; $o['checkout']['pi_status'] = $pi['status'] ?? null; $o['checkout']['metodos'] = $pi['payment_method_types'] ?? null;
      $cx = stripe('POST', 'payment_intents/' . $row['pi_front'] . '/cancel', ['cancellation_reason' => 'abandoned']);
      $o['checkout']['cancelado'] = $cx['status'] ?? ($cx['error']['message'] ?? null);
      $dc = stripe('DELETE', 'customers/' . $row['customer']); $o['checkout']['cliente_apagado'] = $dc['deleted'] ?? null;
      db()->prepare('DELETE FROM pedidos WHERE token = ?')->execute([$j['order']]);
      $o['checkout']['pedido_teste_apagado'] = true;
    }
  }
} catch (Throwable $e) { $o['erro_checkout'] = $e->getMessage(); }

// 4) webhook do site: assinatura válida e inválida
try {
  $id = 'evt_checarreal_' . time();
  $payload = json_encode(['id' => $id, 'type' => 'checar.real', 'data' => ['object' => []]]);
  $t = time(); $sig = hash_hmac('sha256', $t . '.' . $payload, $c['stripe_webhook']);
  [$c1, $r1] = post($site . '/api/webhook.php', $payload, ['Content-Type: application/json', "Stripe-Signature: t=$t,v1=$sig"]);
  [$c2, $r2] = post($site . '/api/webhook.php', $payload, ['Content-Type: application/json', "Stripe-Signature: t=$t,v1=" . str_repeat('0', 64)]);
  $o['webhook_site'] = ['assinatura_certa' => [$c1, $r1], 'assinatura_errada' => [$c2, $r2]];
  db()->prepare('DELETE FROM webhooks WHERE id = ?')->execute([$id]);
} catch (Throwable $e) { $o['erro_webhook'] = $e->getMessage(); }

// 5) e-mail pelo mesmo caminho da confirmação
$para = $argv[2] ?? '';
if ($para !== '' && !empty($c['email_from'])) {
  $nome = $c['email_nome'] ?? 'Order';
  $h = "From: $nome <{$c['email_from']}>\r\nReply-To: {$c['email_from']}\r\nContent-Type: text/plain; charset=UTF-8";
  $o['email'] = ['remetente' => $c['email_from'], 'aceito_pelo_servidor' => @mail($para, "Teste de envio ($nome) " . gmdate('H:i'), "Teste automático do e-mail de confirmação do site. Pode apagar.\n", $h, '-f' . $c['email_from'])];
}
$o['crons'] = is_file($raiz . '/cron-finalizar.php');
file_put_contents($dir . '/real.txt', json_encode($o, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo "ok\n";
