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
    $o['precos'] = precos('', ''); $o['custos'] = cfg()['custos'] ?? null;
    $o['pedidos'] = $d->query("SELECT criado,pago_em,funil_fim,status,pet,kit,mix,bump,variante,valor_front,upsell,valor_upsell,downsell,valor_downsell,extra,valor_extra,variante_pos,reembolsado,contestado,email_enviado,substr(pi_front,1,12) pi FROM pedidos ORDER BY criado DESC LIMIT 15")->fetchAll();
    $o['eventos'] = $d->query("SELECT evento, COUNT(*) n FROM eventos GROUP BY evento ORDER BY n DESC")->fetchAll();
    $o['webhooks'] = $d->query("SELECT id, criado FROM webhooks ORDER BY criado DESC LIMIT 10")->fetchAll();
    // quem está no site: últimos 30 min, por visitante (id cortado, sem dado pessoal)
    $q = $d->prepare("SELECT criado, visitante_id, evento, pagina, disp, angulo, utm, dados FROM eventos WHERE criado >= ? ORDER BY criado");
    $q->execute([gmdate('Y-m-d H:i:s', time() - 1800)]); $vs = [];
    foreach ($q->fetchAll() as $r) {
      $k = substr((string)$r['visitante_id'], 0, 8); $u = json_decode((string)$r['utm'], true) ?: []; $dd = json_decode((string)$r['dados'], true) ?: [];
      $v = $vs[$k] ?? ['de' => substr($r['criado'], 11, 5), 'n' => 0, 'ev' => [], 'disp' => $r['disp'], 'fonte' => ($u['utm_source'] ?? '') . (!empty($u['fbclid']) ? '+fbclid' : ''), 'ad' => $u['utm_content'] ?? '', 'a' => $r['angulo'], 'ua' => $dd['ua'] ?? null, 'bot' => $dd['bot'] ?? 0];
      $v['ate'] = substr($r['criado'], 11, 5); $v['n']++; $v['ev'][$r['evento']] = 1; $vs[$k] = $v;
    }
    foreach ($vs as $k => $v) $vs[$k]['ev'] = implode(',', array_keys($v['ev']));
    $o['ultimos_30min'] = ['visitantes' => count($vs), 'lista' => $vs];
  } catch (Throwable $e) { $o['erro_banco'] = $e->getMessage(); }
  // Stripe (só leitura): conta, webhook, domínios de pagamento e últimos eventos com entregas pendentes
  try {
    $a = stripe('GET', 'account');
    $o['stripe_conta'] = ['id' => substr((string)($a['id'] ?? ''), 0, 12) . '…', 'live_key' => str_starts_with((string)($c['stripe_secret'] ?? ''), 'sk_live_'), 'pais' => $a['country'] ?? null,
      'cobra' => $a['charges_enabled'] ?? null, 'saca' => $a['payouts_enabled'] ?? null, 'descritor' => $a['settings']['payments']['statement_descriptor'] ?? null,
      'pendente_agora' => $a['requirements']['currently_due'] ?? null, 'atrasado' => $a['requirements']['past_due'] ?? null, 'futuro' => $a['requirements']['eventually_due'] ?? null,
      'prazo' => isset($a['requirements']['current_deadline']) ? gmdate('c', (int)$a['requirements']['current_deadline']) : null,
      'bloqueio' => $a['requirements']['disabled_reason'] ?? null, 'erros' => array_map(fn($e) => ($e['requirement'] ?? '') . ': ' . ($e['code'] ?? ''), $a['requirements']['errors'] ?? []),
      'erro_api' => $a['error']['message'] ?? null];
    $w = stripe('GET', 'webhook_endpoints', ['limit' => 10]);
    $o['stripe_webhooks'] = array_map(fn($e) => ['url' => $e['url'], 'status' => $e['status'], 'live' => $e['livemode'], 'eventos' => $e['enabled_events']], $w['data'] ?? []);
    $d = stripe('GET', 'payment_method_domains', ['limit' => 10]);
    $o['stripe_dominios'] = array_map(fn($e) => ['dominio' => $e['domain_name'], 'ativo' => $e['enabled'], 'apple_pay' => $e['apple_pay']['status'] ?? null, 'google_pay' => $e['google_pay']['status'] ?? null], $d['data'] ?? []);
    $ev = stripe('GET', 'events', ['limit' => 15]);
    $o['stripe_eventos'] = array_map(fn($e) => [gmdate('c', $e['created']), $e['type'], 'pendentes' => $e['pending_webhooks']], $ev['data'] ?? []);
  } catch (Throwable $e) { $o['erro_stripe'] = $e->getMessage(); }
}
file_put_contents($dir . '/status.txt', json_encode($o, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo "ok\n";
