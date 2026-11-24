<?php
// Página de uso único para o Kenui colar o token de LEITURA de anúncios (ads_read) SEM passar pela conversa.
// Sobe em public_html/ com nome aleatório (ads-<token>.php). Confere o token no Meta, lista as contas de anúncio,
// grava ../meta_ads.php (fora da pasta pública, chmod 600) e se apaga. Usado pelo painel para descontar o gasto do lucro.
declare(strict_types=1);
header('X-Robots-Tag: noindex');
header('Cache-Control: no-store');
$raiz = dirname(__DIR__);
$dest = $raiz . '/meta_ads.php';

function g(string $path, array $q): array {
  $ch = curl_init('https://graph.facebook.com/v21.0/' . $path . '?' . http_build_query($q));
  curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20]);
  $r = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
  $j = json_decode((string)$r, true) ?: [];
  if ($code !== 200) throw new RuntimeException((string)($j['error']['message'] ?? "HTTP $code"));
  return $j;
}

$msg = ''; $ok = false; $contas = []; $tk = '';
if (is_file($dest)) { $msg = 'O token de anúncios já foi salvo antes. Nada a fazer aqui.'; $ok = true; @unlink(__FILE__); }
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tk = trim((string)($_POST['tk'] ?? ''));
  $conta = trim((string)($_POST['conta'] ?? ''));
  if (!preg_match('/^EA[A-Za-z0-9]{50,400}$/', $tk)) {
    $msg = 'Isso não parece um token do Meta (começa com EAA e é bem longo). Nada foi salvo.'; $tk = '';
  } else {
    try {
      $contas = g('me/adaccounts', ['fields' => 'account_id,name,currency', 'limit' => 50, 'access_token' => $tk])['data'] ?? [];
      if (!$contas) throw new RuntimeException('o token não enxerga nenhuma conta de anúncios. No usuário do sistema, atribua a conta de anúncios a ele (Ativos, Contas de anúncios, permissão de ver desempenho) e gere o token de novo');
      if (count($contas) === 1) $conta = 'act_' . $contas[0]['account_id'];
      if ($conta !== '') {
        if (!preg_match('/^act_\d{5,20}$/', $conta)) throw new RuntimeException('conta inválida');
        g($conta . '/insights', ['date_preset' => 'last_7d', 'fields' => 'spend', 'access_token' => $tk]); // confirma leitura do gasto
        $moeda = ''; foreach ($contas as $c) if ('act_' . $c['account_id'] === $conta) $moeda = (string)$c['currency'];
        $php = "<?php\n// Gravado por instalar/meta-ads.php em " . gmdate('c') . ". Só leitura de anúncios (ads_read). Não versionar.\n"
             . "// acrescimo: imposto cobrado por cima do gasto na fatura do Meta (fração). Conferir na fatura e ajustar.\n"
             . 'return ' . var_export(['token' => $tk, 'conta' => $conta, 'moeda' => $moeda, 'acrescimo' => 0.0], true) . ";\n";
        if (file_put_contents($dest, $php) === false) throw new RuntimeException('não consegui gravar no servidor; avise o Claude');
        @chmod($dest, 0600);
        file_put_contents($raiz . '/instalacao.log', gmdate('c') . " meta_ads ok conta=$conta moeda=$moeda\n", FILE_APPEND);
        @unlink(__FILE__);
        $ok = true; $tk = '';
        $msg = 'Pronto. Token conferido no Meta (conta ' . htmlspecialchars($conta) . ', moeda ' . htmlspecialchars($moeda) . ') e salvo fora da pasta pública. Esta página já se apagou. O gasto aparece no painel em até 15 minutos. Pode avisar o Claude.';
      } else {
        $msg = 'Token aceito. Escolha qual conta de anúncios o painel deve ler.';
      }
    } catch (Throwable $e) {
      $msg = 'O Meta recusou (' . htmlspecialchars($e->getMessage()) . '). Nada foi salvo.'; $tk = ''; $contas = [];
    }
  }
}
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title>Token de anúncios</title>
<style>body{font:16px/1.5 system-ui,sans-serif;max-width:560px;margin:40px auto;padding:0 16px;color:#222}input,select{width:100%;padding:12px;font:inherit;border:1px solid #bbb;border-radius:8px;box-sizing:border-box;margin-top:8px}button{margin-top:14px;padding:12px 18px;font:inherit;font-weight:600;border:0;border-radius:8px;background:#222;color:#fff;width:100%}.m{padding:12px;border-radius:8px;background:#f4f4f4;margin:16px 0}.ok{background:#e6f6ea}ol{padding-left:20px}li{margin:6px 0}</style></head><body>
<h1>Gasto com anúncios no painel</h1>
<?php if ($msg): ?><p class="m<?= $ok ? ' ok' : '' ?>"><?= $msg ?></p><?php endif; ?>
<?php if (!$ok): ?>
<?php if (!$contas): ?>
<p>Este token só <b>lê</b> o gasto (não mexe em campanha). Ele vai direto para o servidor, não passa pela conversa, e esta página se apaga depois de salvar.</p>
<ol>
<li>Abra <b>business.facebook.com/settings</b> (Configurações do negócio).</li>
<li>Usuários, <b>Usuários do sistema</b>, Adicionar. Nome: <i>painel</i>. Função: Funcionário.</li>
<li>No usuário criado: <b>Atribuir ativos</b>, Contas de anúncios, marque a sua conta, permissão <b>Ver desempenho</b>. Salvar.</li>
<li>Clique em <b>Gerar novo token</b>. Escolha um app do seu negócio (se a lista vier vazia: developers.facebook.com, Criar app, tipo Empresa, ligado ao seu negócio; depois volte aqui), validade <b>Nunca</b>, marque só <b>ads_read</b>. Gerar.</li>
<li>Copie o token (começa com EAA) e cole abaixo.</li>
</ol>
<form method="post" autocomplete="off"><input type="password" name="tk" placeholder="EAA..." required autofocus><button type="submit">Conferir e salvar</button></form>
<?php else: ?>
<form method="post" autocomplete="off"><input type="hidden" name="tk" value="<?= htmlspecialchars($tk) ?>">
<select name="conta"><?php foreach ($contas as $c): ?><option value="act_<?= htmlspecialchars((string)$c['account_id']) ?>"><?= htmlspecialchars($c['name'] . ' (' . $c['account_id'] . ', ' . $c['currency'] . ')') ?></option><?php endforeach; ?></select>
<button type="submit">Salvar esta conta</button></form>
<?php endif; ?>
<?php endif; ?>
</body></html>
