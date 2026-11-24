<?php
// Página de uso único para o Kenui colar a chave secreta da Stripe SEM passar pela conversa.
// Sobe em public_html/ com nome aleatório (configurar-<token>.php) junto da pasta _subir/.
// Ao salvar: confere a chave na Stripe, cria o webhook (e guarda o segredo), registra o domínio
// para Apple Pay/Google Pay, grava ../config.php, move schema/cron/PDFs um nível acima e se apaga.
declare(strict_types=1);
header('X-Robots-Tag: noindex');
header('Cache-Control: no-store');
$raiz = dirname(__DIR__);                 // /home/USUARIO/domains/DOMINIO
$sub  = __DIR__ . '/_subir';
$dom  = 'littlecatchco.com';
$eventos = ['payment_intent.succeeded','payment_intent.payment_failed','charge.refunded','charge.dispute.created','radar.early_fraud_warning.created'];

function st(string $sk, string $m, string $path, array $p = []): array {
  $ch = curl_init('https://api.stripe.com/v1/' . $path . ($m === 'GET' && $p ? '?' . http_build_query($p) : ''));
  curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_USERPWD => $sk . ':', CURLOPT_TIMEOUT => 25,
    CURLOPT_CUSTOMREQUEST => $m, CURLOPT_HTTPHEADER => ['Stripe-Version: 2024-06-20']]);
  if ($m !== 'GET') curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($p));
  $r = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
  $j = json_decode((string)$r, true) ?: [];
  return [$code, $j];
}
function apagar(string $d): void {
  if (!is_dir($d)) return;
  foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($d, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $f) { $f->isDir() ? rmdir($f->getPathname()) : unlink($f->getPathname()); }
  rmdir($d);
}

$msg = ''; $ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sk = trim((string)($_POST['sk'] ?? ''));
  if (!preg_match('/^(sk|rk)_test_[A-Za-z0-9]{20,}$/', $sk)) {
    $msg = 'Essa não parece uma chave secreta de TESTE (começa com sk_test_). Nada foi salvo.';
  } elseif (is_file($raiz . '/config.php') || !is_file($sub . '/config.php')) {
    $msg = 'A instalação já foi feita ou falta a pasta _subir. Nada foi salvo.';
  } else {
    [$c, $acc] = st($sk, 'GET', 'account');
    if ($c !== 200) {
      $msg = 'A Stripe recusou a chave (' . htmlspecialchars($acc['error']['message'] ?? "HTTP $c") . '). Nada foi salvo.';
    } else {
      $p = ['url' => "https://$dom/api/webhook.php", 'description' => 'Little Catch Co. (site)', 'api_version' => '2024-06-20'];
      foreach ($eventos as $i => $e) $p["enabled_events[$i]"] = $e;
      [$c2, $wh] = st($sk, 'POST', 'webhook_endpoints', $p);
      if ($c2 !== 200 || empty($wh['secret'])) {
        $msg = 'Não consegui criar o webhook (' . htmlspecialchars($wh['error']['message'] ?? "HTTP $c2") . '). Nada foi salvo.';
      } else {
        [$c3, $pmd] = st($sk, 'POST', 'payment_method_domains', ['domain_name' => $dom]);
        $cfg = file_get_contents($sub . '/config.php');
        $cfg = str_replace(['__SK__', '__WHSEC__'], [addslashes($sk), addslashes($wh['secret'])], $cfg);
        if (file_put_contents($raiz . '/config.php', $cfg) === false) {
          $msg = 'Não consegui gravar o config.php. Avise o Claude.';
        } else {
          chmod($raiz . '/config.php', 0600);
          foreach (['schema.sql', 'cron-finalizar.php'] as $f) { if (is_file("$sub/$f")) rename("$sub/$f", "$raiz/$f"); }
          if (!is_dir("$raiz/digital")) mkdir("$raiz/digital", 0755);
          foreach (glob("$sub/digital/*.pdf") as $f) rename($f, "$raiz/digital/" . basename($f));
          // registro sem segredo nenhum, para o Claude conferir depois
          file_put_contents($raiz . '/instalacao.log', gmdate('c') . " ok conta=" . ($acc['id'] ?? '?') . " webhook=" . ($wh['id'] ?? '?') . " applepay=" . ($c3 === 200 ? 'ok' : 'falhou:' . ($pmd['error']['message'] ?? $c3)) . "\n", FILE_APPEND);
          apagar($sub);
          @unlink(__FILE__);
          $ok = true;
          $msg = 'Pronto. Chave conferida na Stripe, webhook criado e config.php salvo fora da pasta pública. Esta página já se apagou. Pode avisar o Claude.';
        }
      }
    }
  }
}
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title>Configurar Stripe</title>
<style>body{font:16px/1.5 system-ui,sans-serif;max-width:520px;margin:40px auto;padding:0 16px;color:#222}input{width:100%;padding:12px;font:inherit;border:1px solid #bbb;border-radius:8px;box-sizing:border-box}button{margin-top:14px;padding:12px 18px;font:inherit;font-weight:600;border:0;border-radius:8px;background:#222;color:#fff;width:100%}.m{padding:12px;border-radius:8px;background:#f4f4f4;margin:16px 0}.ok{background:#e6f6ea}</style></head><body>
<h1>Chave secreta da Stripe (teste)</h1>
<?php if ($msg): ?><p class="m<?= $ok ? ' ok' : '' ?>"><?= $msg ?></p><?php endif; ?>
<?php if (!$ok): ?>
<p>Cole a chave que começa com <b>sk_test_</b> (Stripe, Desenvolvedores, Chaves de API, modo de teste). Ela vai direto para o servidor, não passa pela conversa e esta página se apaga depois de salvar.</p>
<form method="post" autocomplete="off"><input type="password" name="sk" placeholder="sk_test_..." required autofocus><button type="submit">Salvar</button></form>
<?php endif; ?>
</body></html>
