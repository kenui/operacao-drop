<?php
// Página de uso único para LIGAR A COBRANÇA REAL (Stripe live) sem a chave secreta passar pela conversa.
// Sobe em public_html/ com nome aleatório (real-<token>.php). Confere: chave live, mesma conta da chave de teste,
// conta liberada para cobrar. Cria o webhook live, registra o domínio (Apple/Google Pay), troca a chave em ../config.php
// (guarda a de teste como stripe_secret_teste), põe modo = real e tira o código de teste do Meta. A pk_live (pública)
// fica em _dados/pk_live.txt (pasta bloqueada na web) para o Claude pôr em config.js. Depois se apaga.
declare(strict_types=1);
header('X-Robots-Tag: noindex');
header('Cache-Control: no-store');
$raiz = dirname(__DIR__); $cfgf = $raiz . '/config.php'; $dom = 'littlecatchco.com';
$eventos = ['payment_intent.succeeded','payment_intent.payment_failed','charge.refunded','charge.dispute.created','radar.early_fraud_warning.created'];
function st(string $sk, string $m, string $path, array $p = []): array {
  $ch = curl_init('https://api.stripe.com/v1/' . $path . ($m === 'GET' && $p ? '?' . http_build_query($p) : ''));
  curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_USERPWD => $sk . ':', CURLOPT_TIMEOUT => 25, CURLOPT_CUSTOMREQUEST => $m, CURLOPT_HTTPHEADER => ['Stripe-Version: 2024-06-20']]);
  if ($m !== 'GET') curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($p));
  $r = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
  return [$code, json_decode((string)$r, true) ?: []];
}
$msg = ''; $ok = false; $confirmar = null;
$cfg = is_file($cfgf) ? (string)file_get_contents($cfgf) : '';
if ($cfg !== '' && preg_match("/'modo'\s*=>\s*'real'/", $cfg)) { $msg = 'A cobrança real já está ligada. Nada a fazer aqui.'; $ok = true; @unlink(__FILE__); }
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sk = trim((string)($_POST['sk'] ?? '')); $pk = trim((string)($_POST['pk'] ?? ''));
  $c = $cfg ? (require $cfgf) : [];
  try {
    if (!preg_match('/^(sk|rk)_live_[A-Za-z0-9]{20,}$/', $sk)) throw new RuntimeException('a chave secreta precisa começar com sk_live_');
    if (!preg_match('/^pk_live_[A-Za-z0-9]{20,}$/', $pk)) throw new RuntimeException('a chave publicável precisa começar com pk_live_');
    [$c1, $acc] = st($sk, 'GET', 'account');
    if ($c1 !== 200) throw new RuntimeException('a Stripe recusou a chave: ' . ($acc['error']['message'] ?? "HTTP $c1"));
    // As chaves de teste podem vir de uma "sandbox", que na Stripe tem outro número de conta. Por isso não exige o mesmo id:
    // mostra a conta live (nome e país) e pede confirmação. Conta de fora do Brasil é recusada.
    if (strtoupper((string)($acc['country'] ?? '')) !== 'BR') throw new RuntimeException('essa chave é de uma conta da Stripe de fora do Brasil (' . (string)($acc['country'] ?? '?') . '). Use as chaves da conta brasileira, a do seu CNPJ');
    if (strpos($pk, substr((string)($acc['id'] ?? ''), 6)) === false) throw new RuntimeException('a chave publicável (pk_live) e a secreta (sk_live) são de contas diferentes. Copie as duas da mesma tela da Stripe');
    if (empty($acc['charges_enabled'])) throw new RuntimeException('a conta ainda não está liberada para cobrar (Stripe, Ativar pagamentos: termine o cadastro e a conta bancária)');
    if (empty($_POST['confirmo'])) {
      $nomeConta = (string)($acc['business_profile']['name'] ?? $acc['settings']['dashboard']['display_name'] ?? $acc['company']['name'] ?? '(sem nome)');
      $confirmar = ['nome' => $nomeConta, 'pais' => (string)$acc['country'], 'id' => substr((string)$acc['id'], 0, 10) . '…', 'sk' => $sk, 'pk' => $pk];
      throw new RuntimeException('__confirmar__');
    }
    $p = ['url' => "https://$dom/api/webhook.php", 'description' => 'Little Catch Co. (site, real)', 'api_version' => '2024-06-20'];
    foreach ($eventos as $i => $e) $p["enabled_events[$i]"] = $e;
    [$c2, $wh] = st($sk, 'POST', 'webhook_endpoints', $p);
    if ($c2 !== 200 || empty($wh['secret'])) throw new RuntimeException('não consegui criar o webhook: ' . ($wh['error']['message'] ?? "HTTP $c2"));
    [$c3] = st($sk, 'POST', 'payment_method_domains', ['domain_name' => $dom]);
    $q = fn($s) => "'" . addslashes($s) . "'";
    $novo = preg_replace("/'stripe_secret'\s*=>\s*'[^']*'/", "'stripe_secret'   => " . $q($sk) . ", 'stripe_secret_teste' => " . $q((string)($c['stripe_secret'] ?? '')), $cfg, 1);
    $novo = preg_replace("/'stripe_webhook'\s*=>\s*'[^']*'/", "'stripe_webhook'  => " . $q($wh['secret']), (string)$novo, 1);
    $novo = preg_replace("/'modo'\s*=>\s*'[^']*'/", "'modo'            => 'real'", (string)$novo, 1);
    $novo = preg_replace("/'meta_test_code'\s*=>\s*'[^']*'/", "'meta_test_code' => ''", (string)$novo);
    if (!$novo || !str_contains($novo, $sk) || file_put_contents($cfgf, $novo) === false) throw new RuntimeException('não consegui gravar no servidor; avise o Claude');
    @chmod($cfgf, 0600);
    @mkdir(__DIR__ . '/_dados', 0755); file_put_contents(__DIR__ . '/_dados/pk_live.txt', $pk);
    file_put_contents($raiz . '/instalacao.log', gmdate('c') . " stripe REAL conta=" . ($acc['id'] ?? '?') . " webhook=" . ($wh['id'] ?? '?') . " applepay=" . ($c3 === 200 ? 'ok' : 'falhou') . "\n", FILE_APPEND);
    @unlink(__FILE__);
    $ok = true; $msg = 'Pronto. Chave live conferida, webhook real criado e servidor em modo real. Esta página já se apagou. Avise o Claude: ele troca a chave pública na página e faz a compra de teste com reembolso.';
  } catch (Throwable $e) { if ($e->getMessage() !== '__confirmar__') { $msg = 'Nada foi salvo: ' . htmlspecialchars($e->getMessage()) . '.'; $confirmar = null; } }
}
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title>Ligar cobrança real</title>
<style>body{font:16px/1.5 system-ui,sans-serif;max-width:540px;margin:40px auto;padding:0 16px;color:#222}input{width:100%;padding:12px;font:inherit;border:1px solid #bbb;border-radius:8px;box-sizing:border-box;margin-top:8px}button{margin-top:14px;padding:12px 18px;font:inherit;font-weight:600;border:0;border-radius:8px;background:#222;color:#fff;width:100%}.m{padding:12px;border-radius:8px;background:#f4f4f4;margin:16px 0}.ok{background:#e6f6ea}</style></head><body>
<h1>Ligar a cobrança real (Stripe)</h1>
<?php if ($msg): ?><p class="m<?= $ok ? ' ok' : '' ?>"><?= $msg ?></p><?php endif; ?>
<?php if (!$ok): ?>
<p>Na Stripe, desligue o "modo de teste", vá em <b>Desenvolvedores, Chaves de API</b> e copie as duas chaves. Elas vão direto para o servidor, não passam pela conversa, e esta página se apaga depois de salvar.</p>
<?php if ($confirmar): ?>
<p class="m">Chave aceita. Confira se esta é a conta da sua empresa:<br><b><?= htmlspecialchars($confirmar['nome']) ?></b> · país <?= htmlspecialchars($confirmar['pais']) ?> · <?= htmlspecialchars($confirmar['id']) ?></p>
<form method="post" autocomplete="off"><input type="hidden" name="pk" value="<?= htmlspecialchars($confirmar['pk']) ?>"><input type="hidden" name="sk" value="<?= htmlspecialchars($confirmar['sk']) ?>"><input type="hidden" name="confirmo" value="1">
<button type="submit">Sim, é a minha conta: ligar a cobrança real</button></form>
<p><a href="">Não é essa conta, voltar</a></p>
<?php else: ?>
<form method="post" autocomplete="off">
<input type="text" name="pk" placeholder="Chave publicável: pk_live_..." required>
<input type="password" name="sk" placeholder="Chave secreta: sk_live_..." required>
<button type="submit">Conferir</button></form>
<?php endif; ?>
<?php endif; ?>
</body></html>
