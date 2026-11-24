<?php
// Cron (CLI): escreve public_html/_status/status.txt (acesso web negado) para o Claude ler pela API da Hostinger.
// Sem segredo e sem dado pessoal do cliente: só estado da instalação, pedidos (valores/etapas) e contagens.
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
$raiz = dirname(__DIR__, 2);                      // .../domains/DOMINIO
$pub = $raiz . '/public_html';
$dir = $pub . '/_status';
if (!is_dir($dir)) mkdir($dir, 0755);
file_put_contents($dir . '/.htaccess', "Require all denied\n");
$o = ['gerado' => gmdate('c'), 'config' => is_file($raiz . '/config.php'), 'schema' => is_file($raiz . '/schema.sql'),
      'cron' => is_file($raiz . '/cron-finalizar.php'), 'pdfs' => array_map('basename', glob($raiz . '/digital/*.pdf') ?: []),
      'instalacao' => is_file($raiz . '/instalacao.log') ? trim(file_get_contents($raiz . '/instalacao.log')) : null];
if ($o['config']) {
  $c = require $raiz . '/config.php';
  $o['modo'] = $c['modo'] ?? '?';
  $o['chave'] = substr((string)($c['stripe_secret'] ?? ''), 0, 8) . '…';
  $o['webhook_ok'] = str_starts_with((string)($c['stripe_webhook'] ?? ''), 'whsec_');
  try {
    $_SERVER['REQUEST_METHOD'] = 'CLI';
    ob_start(); require $pub . '/api/_lib.php'; ob_end_clean();
    $d = db();
    $o['pedidos'] = $d->query("SELECT criado,pago_em,funil_fim,status,pet,kit,mix,bump,variante,valor_front,upsell,valor_upsell,downsell,valor_downsell,reembolsado,contestado,email_enviado,substr(pi_front,1,12) pi FROM pedidos ORDER BY criado DESC LIMIT 15")->fetchAll();
    $o['eventos'] = $d->query("SELECT evento, COUNT(*) n FROM eventos GROUP BY evento ORDER BY n DESC")->fetchAll();
    $o['webhooks'] = $d->query("SELECT id, criado FROM webhooks ORDER BY criado DESC LIMIT 10")->fetchAll();
  } catch (Throwable $e) { $o['erro_banco'] = $e->getMessage(); }
}
file_put_contents($dir . '/status.txt', json_encode($o, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
