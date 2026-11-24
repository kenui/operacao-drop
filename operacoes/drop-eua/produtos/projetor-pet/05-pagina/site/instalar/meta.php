<?php
// Página de uso único para o Kenui colar o token da API de Conversões do Meta SEM passar pela conversa.
// Sobe em public_html/ com nome aleatório (meta-<token>.php). Confere o token no Meta (acesso ao pixel),
// grava meta_pixel_id e meta_capi_token em ../config.php (fora da pasta pública) e se apaga.
declare(strict_types=1);
header('X-Robots-Tag: noindex');
header('Cache-Control: no-store');
$raiz  = dirname(__DIR__);
$cfgf  = $raiz . '/config.php';
$pixel = '545543757913721';

$msg = ''; $ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tk = trim((string)($_POST['tk'] ?? ''));
  $cfg = is_file($cfgf) ? (string)file_get_contents($cfgf) : '';
  if (!preg_match('/^EA[A-Za-z0-9]{50,400}$/', $tk)) {
    $msg = 'Isso não parece um token do Meta (começa com EAA e é bem longo). Nada foi salvo.';
  } elseif ($cfg === '' || !preg_match("/'meta_capi_token'\s*=>\s*''/", $cfg)) {
    $msg = 'O token já foi salvo antes ou o config.php não foi encontrado. Nada foi salvo.';
  } else {
    // O token do Gerenciador de Eventos só tem permissão para ENVIAR eventos (não para ler o pixel).
    // Teste: um evento marcado com código de teste, que só aparece na aba "Testar eventos" e não conta em campanha.
    $ev = json_encode([['event_name' => 'PageView', 'event_time' => time(), 'action_source' => 'website', 'event_source_url' => 'https://littlecatchco.com/',
      'user_data' => ['client_ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', 'client_user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'install', 0, 200)]]]);
    $ch = curl_init("https://graph.facebook.com/v21.0/$pixel/events");
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20, CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query(['data' => $ev, 'test_event_code' => 'TEST' . random_int(10000, 99999), 'access_token' => $tk])]);
    $r = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    $j = json_decode((string)$r, true) ?: [];
    if ($code !== 200 || (int)($j['events_received'] ?? 0) < 1) {
      $msg = 'O Meta recusou o token para o pixel ' . $pixel . ' (' . htmlspecialchars((string)($j['error']['message'] ?? "HTTP $code")) . '). Confira se o token foi gerado nesse pixel (Gerenciador de Eventos, Configurações, API de Conversões, Gerar token de acesso). Nada foi salvo.';
    } else {
      $novo = preg_replace("/'meta_pixel_id'\s*=>\s*'[^']*'/", "'meta_pixel_id'   => '$pixel'", $cfg, 1);
      $novo = preg_replace("/'meta_capi_token'\s*=>\s*''/", "'meta_capi_token' => '" . addslashes($tk) . "'", (string)$novo, 1);
      if (!$novo || file_put_contents($cfgf, $novo) === false) {
        $msg = 'Não consegui gravar o config.php. Avise o Claude.';
      } else {
        @chmod($cfgf, 0600);
        file_put_contents($raiz . '/instalacao.log', gmdate('c') . " meta ok pixel=$pixel teste=" . (int)($j['events_received'] ?? 0) . "\n", FILE_APPEND);
        @unlink(__FILE__);
        $ok = true;
        $msg = 'Pronto. Token conferido no Meta e salvo fora da pasta pública. Esta página já se apagou. Pode avisar o Claude.';
      }
    }
  }
}
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title>Token do Meta</title>
<style>body{font:16px/1.5 system-ui,sans-serif;max-width:520px;margin:40px auto;padding:0 16px;color:#222}input{width:100%;padding:12px;font:inherit;border:1px solid #bbb;border-radius:8px;box-sizing:border-box}button{margin-top:14px;padding:12px 18px;font:inherit;font-weight:600;border:0;border-radius:8px;background:#222;color:#fff;width:100%}.m{padding:12px;border-radius:8px;background:#f4f4f4;margin:16px 0}.ok{background:#e6f6ea}</style></head><body>
<h1>Token da API de Conversões (Meta)</h1>
<?php if ($msg): ?><p class="m<?= $ok ? ' ok' : '' ?>"><?= $msg ?></p><?php endif; ?>
<?php if (!$ok): ?>
<p>Pixel <b><?= $pixel ?></b>. Cole o token que começa com <b>EAA</b> (Gerenciador de Eventos, o pixel, Configurações, API de Conversões, Gerar token de acesso). Ele vai direto para o servidor, não passa pela conversa, e esta página se apaga depois de salvar.</p>
<form method="post" autocomplete="off"><input type="password" name="tk" placeholder="EAA..." required autofocus><button type="submit">Salvar</button></form>
<?php endif; ?>
</body></html>
