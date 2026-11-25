<?php
// Painel do funil (Little Catch Co.). Pedido do Kenui (24/11): métricas, funil inteiro, páginas com preview, conversão global, em tempo real.
// Régua: lucro por visitante. Custo real da CJ vem de api/_precos.php (gravado em cada pedido).
// Senha: no primeiro acesso o Kenui cria a dele; o hash fica em ../../painel.pass (fora da pasta pública). Nada de dado pessoal de cliente aqui.
declare(strict_types=1);
$raiz = dirname(__DIR__, 2);
$passf = $raiz . '/painel.pass';
session_name('lcc_painel');
session_set_cookie_params(['lifetime' => 0, 'path' => '/' . basename(__DIR__) . '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict']);
session_start();
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');
header('X-Frame-Options: DENY');

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES); }
function tela(string $titulo, string $corpo): never {
  header('Content-Type: text/html; charset=utf-8');
  echo '<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex"><title>' . h($titulo) . '</title>
<style>body{font:16px/1.5 system-ui,sans-serif;background:#fff8ef;color:#232046;display:grid;place-items:center;min-height:100vh;margin:0}form{background:#fff;padding:28px;border-radius:18px;box-shadow:0 10px 30px rgba(35,32,70,.1);width:min(360px,90vw)}input{width:100%;padding:12px;font:inherit;border:1px solid #ddd;border-radius:10px;box-sizing:border-box;margin:6px 0}button{width:100%;padding:12px;font:inherit;font-weight:700;border:0;border-radius:999px;background:#ff6b3d;color:#fff;margin-top:10px}.m{color:#b42318;font-size:14px}</style></head><body>' . $corpo . '</body></html>';
  exit;
}

// ---------- senha ----------
$msg = '';
if (!is_file($passf)) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova'])) {
    $a = (string)$_POST['nova']; $b = (string)($_POST['nova2'] ?? '');
    if (strlen($a) < 10) $msg = 'Use pelo menos 10 caracteres.';
    elseif ($a !== $b) $msg = 'As duas senhas não batem.';
    elseif (file_put_contents($passf, password_hash($a, PASSWORD_DEFAULT)) === false) $msg = 'Não consegui salvar. Avise o Claude.';
    else { @chmod($passf, 0600); session_regenerate_id(true); $_SESSION['ok'] = 1; header('Location: ./'); exit; }
  }
  tela('Criar senha do painel', '<form method="post" autocomplete="off"><h2 style="margin:0 0 6px">Criar senha do painel</h2><p style="font-size:14px;color:#4b4868">Primeiro acesso. Escolha a senha que você vai usar para entrar. Ela fica só no servidor.</p>' . ($msg ? '<p class="m">' . h($msg) . '</p>' : '') . '<input type="password" name="nova" placeholder="Senha (10+ caracteres)" required autofocus><input type="password" name="nova2" placeholder="Repita a senha" required><button>Salvar e entrar</button></form>');
}
if (isset($_GET['sair'])) { $_SESSION = []; session_destroy(); header('Location: ./'); exit; }
if (empty($_SESSION['ok'])) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['senha'])) {
    $t = $_SESSION['tent'] ?? [0, 0];
    if ($t[0] >= 5 && time() - $t[1] < 600) $msg = 'Muitas tentativas. Espere 10 minutos.';
    elseif (password_verify((string)$_POST['senha'], trim((string)file_get_contents($passf)))) { session_regenerate_id(true); $_SESSION = ['ok' => 1]; header('Location: ./'); exit; }
    else { $_SESSION['tent'] = [($t[0] ?? 0) + 1, time()]; usleep(600000); $msg = 'Senha errada.'; }
  }
  tela('Painel', '<form method="post"><h2 style="margin:0 0 10px">Painel Little Catch Co.</h2>' . ($msg ? '<p class="m">' . h($msg) . '</p>' : '') . '<input type="password" name="senha" placeholder="Senha" required autofocus><button>Entrar</button></form>');
}

// ---------- dados ----------
if (isset($_GET['api'])) {
  $_SERVER['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'] ?? 'GET';
  require dirname(__DIR__) . '/api/_lib.php';           // db(), cfg(), precos()
  require dirname(__DIR__) . '/api/_ads.php';           // gasto_ads(): gasto do Meta por campanha e dia
  $c = cfg(); $d = db();
  $dias = ['hoje' => 0, '7d' => 7, '30d' => 30, 'tudo' => 3650][$_GET['p'] ?? '7d'] ?? 7;
  $desde = $dias === 0 ? gmdate('Y-m-d 00:00:00', time() - 4 * 3600 + 4 * 3600) : gmdate('Y-m-d H:i:s', time() - $dias * 86400);
  if ($dias === 0) { $loc = new DateTime('now', new DateTimeZone('America/New_York')); $loc->setTime(0, 0); $loc->setTimezone(new DateTimeZone('UTC')); $desde = $loc->format('Y-m-d H:i:s'); }
  $camp = (string)($_GET['c'] ?? '');

  // eventos: visitantes distintos por etapa
  $ev = $d->prepare("SELECT evento, visitante_id, utm, variante, pagina FROM eventos WHERE criado >= ? AND dados NOT LIKE '%\"bot\":1%'");
  $ev->execute([$desde]);
  $por = []; $campanhas = []; $vivos = []; $agora = gmdate('Y-m-d H:i:s', time() - 300);
  foreach ($ev->fetchAll() as $r) {
    $u = json_decode((string)$r['utm'], true) ?: [];
    $cp = (string)($u['utm_campaign'] ?? '(sem campanha)');
    $campanhas[$cp] = 1;
    if ($camp !== '' && $cp !== $camp) continue;
    $por[$r['evento']][$r['visitante_id']] = 1;
  }
  $vv = $d->prepare("SELECT COUNT(DISTINCT visitante_id) FROM eventos WHERE criado >= ? AND dados NOT LIKE '%\"bot\":1%'"); $vv->execute([$agora]);
  $n = fn($e) => count($por[$e] ?? []);

  // pedidos
  $pq = $d->prepare("SELECT * FROM pedidos WHERE status = 'pago' AND pago_em >= ? ORDER BY pago_em DESC");
  $pq->execute([$desde]);
  $ped = []; foreach ($pq->fetchAll() as $p) {
    $x = json_decode((string)$p['ctx'], true) ?: []; $cp = (string)($x['utm']['utm_campaign'] ?? '(sem campanha)');
    $campanhas[$cp] = 1; if ($camp !== '' && $cp !== $camp) continue; $p['_camp'] = $cp; $ped[] = $p;
  }
  $tot = ['pedidos' => count($ped), 'receita' => 0, 'custo' => 0, 'taxas' => 0, 'reembolso' => 0, 'bump' => 0, 'up' => 0, 'down' => 0, 'contest' => 0, 'kit2' => 0, 'extra' => 0, 'down_kit' => 0];
  $porDia = []; $porCamp = []; $porVar = []; $porPos = []; $lista = [];
  foreach ($ped as $p) {
    $rec = (int)$p['valor_front'] + (int)$p['valor_upsell'] + (int)$p['valor_downsell'] + (int)($p['valor_extra'] ?? 0);
    $cus = (int)$p['custo_front'] + (int)$p['custo_upsell'] + (int)$p['custo_downsell'] + (int)($p['custo_extra'] ?? 0);
    $cob = 1 + ($p['upsell'] === 'aceito' ? 1 : 0) + (str_starts_with((string)$p['downsell'], 'aceito') ? 1 : 0) + (($p['extra'] ?? '') === 'aceito' ? 1 : 0);
    $tax = (int)round($rec * 0.06) + 10 * $cob;              // Stripe BR ~6% + US$0,10 por cobrança (inclui câmbio aproximado)
    $tot['receita'] += $rec; $tot['custo'] += $cus; $tot['taxas'] += $tax; $tot['reembolso'] += (int)$p['reembolsado'];
    $tot['bump'] += (int)$p['bump']; $tot['up'] += $p['upsell'] === 'aceito' ? 1 : 0; $tot['down'] += str_starts_with((string)$p['downsell'], 'aceito') ? 1 : 0;
    $tot['contest'] += (int)$p['contestado']; $tot['kit2'] += $p['kit'] === 'two' ? 1 : 0;
    $tot['extra'] += ($p['extra'] ?? '') === 'aceito' ? 1 : 0; $tot['down_kit'] += $p['downsell'] === 'aceito-kit' ? 1 : 0;
    $luc = $rec - $cus - $tax - (int)$p['reembolsado'];
    $dia = substr((string)$p['pago_em'], 0, 10);
    $porDia[$dia] = ['r' => ($porDia[$dia]['r'] ?? 0) + $rec, 'l' => ($porDia[$dia]['l'] ?? 0) + $luc, 'n' => ($porDia[$dia]['n'] ?? 0) + 1];
    $porCamp[$p['_camp']] = ['n' => ($porCamp[$p['_camp']]['n'] ?? 0) + 1, 'r' => ($porCamp[$p['_camp']]['r'] ?? 0) + $rec, 'l' => ($porCamp[$p['_camp']]['l'] ?? 0) + $luc];
    $v = $p['variante'] ?: '(sem teste)';
    $porVar[$v] = ['n' => ($porVar[$v]['n'] ?? 0) + 1, 'r' => ($porVar[$v]['r'] ?? 0) + $rec, 'l' => ($porVar[$v]['l'] ?? 0) + $luc];
    // teste do pós-compra: só o que vem depois do pagamento (upsell + downsell + extra), por comprador
    $vp = $p['variante_pos'] ?? ''; $vp = $vp ?: '(sem teste)';
    $rp = (int)$p['valor_upsell'] + (int)$p['valor_downsell'] + (int)($p['valor_extra'] ?? 0);
    $lp = $rp - ((int)$p['custo_upsell'] + (int)$p['custo_downsell'] + (int)($p['custo_extra'] ?? 0)) - (int)round($rp * 0.06) - 10 * ($cob - 1);
    $porPos[$vp] = ['n' => ($porPos[$vp]['n'] ?? 0) + 1, 'r' => ($porPos[$vp]['r'] ?? 0) + $rp, 'l' => ($porPos[$vp]['l'] ?? 0) + $lp];
    if (count($lista) < 20) $lista[] = ['quando' => $p['pago_em'], 'pet' => $p['pet'], 'kit' => $p['kit'], 'bump' => (int)$p['bump'], 'upsell' => $p['upsell'] ?: '-', 'downsell' => $p['downsell'] ?: '-', 'extra' => ($p['extra'] ?? '') ?: '-', 'total' => $rec, 'lucro' => $luc, 'reemb' => (int)$p['reembolsado'], 'contest' => (int)$p['contestado'], 'camp' => $p['_camp'], 'estado' => $p['estado'] ?: ''];
  }
  // visitantes por campanha (para lucro por visitante da campanha)
  $visCamp = [];
  $ev->execute([$desde]);
  foreach ($ev->fetchAll() as $r) if ($r['evento'] === 'page_view') { $u = json_decode((string)$r['utm'], true) ?: []; $visCamp[(string)($u['utm_campaign'] ?? '(sem campanha)')][$r['visitante_id']] = 1; }
  foreach ($porCamp as $k => $v) $porCamp[$k]['vis'] = count($visCamp[$k] ?? []);
  // gasto com anúncios (Meta). Casa por utm_campaign={{campaign.id}}; aceita o nome como reserva.
  $ads = gasto_ads($desde, $dias === 3650);
  $gCamp = fn(string $k) => $ads['id'][$k] ?? $ads['nome'][$k] ?? 0;
  $nomeCamp = fn(string $k) => $ads['ids'][$k] ?? '';
  foreach ($ads['ids'] as $id => $nm) { $campanhas[$id] = 1; if (!isset($porCamp[$id]) && ($camp === '' || $camp === $id)) $porCamp[$id] = ['n' => 0, 'r' => 0, 'l' => 0, 'vis' => count($visCamp[$id] ?? [])]; }
  foreach ($porCamp as $k => $v) { $g = $gCamp((string)$k); $porCamp[$k]['g'] = $g; $porCamp[$k]['la'] = $v['l'] - $g; $porCamp[$k]['nome'] = $nomeCamp((string)$k); }
  $gasto = $camp === '' ? $ads['total'] : $gCamp($camp);
  if ($camp === '') foreach ($ads['dia'] as $dd => $g) { $porDia[$dd] = ($porDia[$dd] ?? ['r' => 0, 'l' => 0, 'n' => 0]) + ['g' => 0]; $porDia[$dd]['g'] = $g; }
  ksort($porDia);
  $tot['lucro'] = $tot['receita'] - $tot['custo'] - $tot['taxas'] - $tot['reembolso'];
  $tot['gasto'] = $gasto; $tot['lucro_ads'] = $tot['lucro'] - $gasto;
  $vis = $n('page_view');
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode([
    'modo' => $c['modo'] ?? '?', 'gerado' => gmdate('c'), 'desde' => $desde, 'agora' => (int)$vv->fetchColumn(), 'campanhas' => array_map('strval', array_keys($campanhas)),
    'precos' => precos(), 'custos' => $c['custos'] ?? [],
    'ads' => ['ligado' => $ads['ligado'], 'erro' => $ads['erro'], 'moeda' => $ads['moeda'], 'acrescimo' => $ads['acrescimo'] ?? 0, 'nomes' => $ads['ids'], 'atualizado' => $ads['atualizado'] ?? ''],
    'tot' => $tot + ['visitantes' => $vis],
    'funil' => [
      ['Visitou a página', $vis], ['Viu o produto (3 s)', $n('view_content')], ['Escolheu um kit', $n('kit_select')],
      ['Abriu o checkout', $n('initiate_checkout')], ['Clicou em pagar', $n('add_payment_info')], ['Pagou', max($n('purchase_front'), count($ped))],
      ['Viu o upsell', $n('upsell_view')], ['Aceitou o upsell', $n('upsell_accept')], ['Viu o downsell', $n('downsell_view')], ['Aceitou o downsell', $n('downsell_accept')], ['Viu a oferta extra', $n('extra_view')], ['Aceitou a oferta extra', $n('extra_accept')],
    ],
    'dia' => $porDia, 'camp' => $porCamp, 'var' => $porVar, 'pos' => $porPos, 'lista' => $lista,
  ]);
  exit;
}
header('Content-Type: text/html; charset=utf-8');
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title>Painel · Little Catch Co.</title>
<style>
:root{--ink:#232046;--ink2:#5b5878;--bg:#f6f4fb;--card:#fff;--line:#e7e3f0;--act:#ff6b3d;--ok:#1f8f6a;--bad:#c2410c;--mint:#d8f3ea}
*{box-sizing:border-box}body{margin:0;font:15px/1.45 system-ui,-apple-system,"Segoe UI",sans-serif;background:var(--bg);color:var(--ink)}
header{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:space-between;padding:14px 20px;background:var(--ink);color:#fff;position:sticky;top:0;z-index:5}
header b{font-size:18px}header .r{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
select,button{font:inherit;border-radius:999px;border:0;padding:7px 12px;background:#3a3663;color:#fff}
.badge{font-size:12px;font-weight:800;background:#ffe066;color:#3b2f00;padding:3px 9px;border-radius:999px}
.live{font-size:13px;color:#cfcbe6}.live i{display:inline-block;width:8px;height:8px;border-radius:50%;background:#3ee08f;margin-right:6px;animation:p 1.6s infinite}
@keyframes p{50%{opacity:.3}}
main{max-width:1200px;margin:0 auto;padding:18px}
.grid{display:grid;gap:14px}.k{grid-template-columns:repeat(auto-fit,minmax(170px,1fr))}
.card{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:16px}
.kpi small{display:block;color:var(--ink2);font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.05em}
.kpi b{display:block;font-size:26px;margin-top:4px}.kpi>span{font-size:12px;color:var(--ink2)}
.kpi.main{background:var(--ink);color:#fff}.kpi.main small,.kpi.main>span{color:#cfcbe6}
h2{font-size:16px;margin:0 0 12px}
.two{grid-template-columns:minmax(0,1.3fr) minmax(0,1fr)}@media(max-width:860px){.two{grid-template-columns:1fr}}
.fun .row{display:grid;grid-template-columns:170px 1fr 70px 70px;gap:10px;align-items:center;margin:7px 0;font-size:14px}
.fun .bar{height:24px;background:#eeeaf6;border-radius:8px;overflow:hidden}.fun .bar i{display:block;height:100%;background:linear-gradient(90deg,var(--act),#ffa07a);border-radius:8px}
.fun .row.up .bar i{background:linear-gradient(90deg,var(--ok),#5fd0a5)}
.fun em{font-style:normal;color:var(--ink2);font-size:12px;text-align:right}
@media(max-width:560px){.fun .row{grid-template-columns:110px 1fr 46px;font-size:12px}.fun .row em:last-child{display:none}}
table{width:100%;border-collapse:collapse;font-size:13px}th,td{padding:7px 6px;border-bottom:1px solid var(--line);text-align:left;white-space:nowrap}th{color:var(--ink2);font-weight:700}
.tw{overflow-x:auto}.pos{color:var(--ok);font-weight:700}.neg{color:var(--bad);font-weight:700}
.chart{display:flex;align-items:flex-end;gap:4px;height:140px;padding-top:10px}.chart div{flex:1;background:var(--act);border-radius:5px 5px 0 0;position:relative;min-height:2px}
.chart div span{position:absolute;bottom:-18px;left:50%;transform:translateX(-50%);font-size:10px;color:var(--ink2)}
.pages{grid-template-columns:repeat(auto-fit,minmax(150px,1fr))}
.pg{cursor:zoom-in}.pg img{width:100%;height:260px;object-fit:cover;object-position:top;border-radius:12px;border:1px solid var(--line);background:#fff}.pg b{display:block;font-size:13px;margin-top:6px}.pg span{font-size:12px;color:var(--ink2)}
#lb{position:fixed;inset:0;background:rgba(20,18,40,.85);display:none;overflow:auto;z-index:9;padding:20px;text-align:center}#lb img{max-width:420px;width:100%;border-radius:12px}
.note{font-size:12px;color:var(--ink2);margin-top:8px}
</style></head><body>
<header><b>🐾 Little Catch Co. · Painel</b>
<div class="r"><span class="live"><i></i><span id="agora">-</span> agora no site</span><span class="badge" id="modo" hidden>MODO TESTE</span>
<select id="p"><option value="hoje">Hoje</option><option value="7d" selected>7 dias</option><option value="30d">30 dias</option><option value="tudo">Tudo</option></select>
<select id="c"><option value="">Todas as campanhas</option></select><button onclick="location='?sair=1'">Sair</button></div></header>
<main>
<div class="grid k" id="kpis"></div>
<div class="grid two" style="margin-top:14px">
  <div class="card fun"><h2>Funil (pessoas diferentes em cada etapa)</h2><div id="fun"></div><p class="note">% = quantos passaram da etapa anterior para esta. Upsell conta sobre quem pagou; downsell sobre quem recusou o upsell; oferta extra sobre quem aceitou o upsell.</p></div>
  <div class="card"><h2>Ofertas</h2><div id="ofe"></div><h2 style="margin-top:18px">Receita por dia</h2><div class="chart" id="chart"></div></div>
</div>
<div class="card" style="margin-top:14px"><h2>Páginas do funil</h2><div class="grid pages">
  <div class="pg" data-full="prints/pagina.jpg"><img src="prints/topo.jpg" alt=""><b>Página de vendas</b><span>galeria, kits, bônus, checkout</span></div>
  <div class="pg" data-full="prints/kits.jpg"><img src="prints/kits.jpg" alt=""><b>Kits + bump + checkout</b><span>bump e bônus no resumo</span></div>
  <div class="pg" data-full="prints/upsell.jpg"><img src="prints/upsell.jpg" alt=""><b>Upsell</b><span>2º kit, um clique</span></div>
  <div class="pg" data-full="prints/downsell.jpg"><img src="prints/downsell.jpg" alt=""><b>Downsell</b><span>só se recusou o upsell</span></div><div class="pg" data-full="prints/extra.jpg"><img src="prints/extra.jpg" alt=""><b>Oferta extra</b><span>só se aceitou o upsell</span></div>
  <div class="pg" data-full="prints/obrigado.jpg"><img src="prints/obrigado.jpg" alt=""><b>Obrigado</b><span>recibo e downloads</span></div>
</div><p class="note">Fotos da última versão publicada (atualizadas a cada mudança de página). Clique para ver inteira.</p></div>
<div class="grid two" style="margin-top:14px">
  <div class="card"><h2>Por campanha (UTM)</h2><div class="tw" id="camp"></div></div>
  <div class="card"><h2>Por versão de teste A/B</h2><h3 class="note" style="margin:0 0 6px">Teste da página (sorteio por visitante)</h3><div class="tw" id="var"></div><h3 class="note" style="margin:14px 0 6px">Teste do pós-compra (sorteio por pedido; decide o lucro por comprador)</h3><div class="tw" id="pos"></div></div>
</div>
<div class="card" style="margin-top:14px"><h2>Últimos pedidos (sem dados pessoais)</h2><div class="tw" id="lista"></div></div>
<p class="note" id="rod"></p>
</main>
<div id="lb" onclick="this.style.display='none'"><img alt=""></div>
<script>
var $=function(s){return document.querySelector(s)};
function m(c){return (c<0?"−$":"$")+(Math.abs(c)/100).toLocaleString("en-US",{minimumFractionDigits:2,maximumFractionDigits:2})}
function pc(a,b){return b?(100*a/b).toFixed(1)+"%":"–"}
function cls(v){return v>=0?"pos":"neg"}
document.querySelectorAll(".pg").forEach(function(p){p.onclick=function(){$("#lb img").src=p.dataset.full;$("#lb").style.display="block";$("#lb").scrollTop=0}});
function load(){
  fetch("?api=1&p="+$("#p").value+"&c="+encodeURIComponent($("#c").value),{cache:"no-store"}).then(function(r){if(r.status!==200)location.reload();return r.json()}).then(function(j){
    var t=j.tot,A=j.ads,L=A.ligado&&!A.erro?t.lucro_ads:t.lucro,lpv=t.visitantes?L/t.visitantes:0,nm=function(c){c=String(c);return (A.nomes[c]||c).replace(/</g,"")};
    $("#modo").hidden=j.modo!=="teste"; $("#agora").textContent=j.agora;
    var sel=$("#c"),cur=sel.value; sel.innerHTML='<option value="">Todas as campanhas</option>'+j.campanhas.map(function(c){return '<option value="'+c.replace(/"/g,"")+'"'+(c===cur?' selected':'')+'>'+nm(c)+'</option>'}).join("");
    var semAds=!A.ligado?"Meta ainda não ligado: sem o gasto com anúncios":(A.erro?"Meta com erro: "+A.erro:"");
    var K=[["main","Lucro por visitante",m(lpv),semAds||"já descontado o gasto com anúncios"],["","Lucro",'<span class="'+cls(L)+'">'+m(L)+'</span>',semAds?"receita − CJ − Stripe − reembolso":"receita − CJ − Stripe − reembolso − anúncios"],
      ["","Gasto com anúncios",A.ligado&&!A.erro?m(t.gasto):"–",A.ligado&&!A.erro?("ROAS "+(t.gasto?(t.receita/t.gasto).toFixed(2):"–")+" · custo por compra "+(t.pedidos&&t.gasto?m(t.gasto/t.pedidos):"–")+(A.moeda&&A.moeda!=="USD"?" · convertido de "+A.moeda:"")):"liga com o token de leitura do Meta"],
      ["","Receita",m(t.receita),t.pedidos+" pedidos"],["","Visitantes",t.visitantes,"pessoas diferentes"],["","Conversão",pc(t.pedidos,t.visitantes),"pedidos ÷ visitantes"],
      ["","Ticket médio",t.pedidos?m(t.receita/t.pedidos):"–","por pedido, com upsell"],["","Custo CJ",m(t.custo),"kit "+m(j.custos.kit||0)+" cada"],["","Taxas Stripe",m(t.taxas),"~6% + $0,10 por cobrança"],
      ["","Reembolsos",m(t.reembolso),t.contest+" contestações"]];
    $("#kpis").innerHTML=K.map(function(k){return '<div class="card kpi '+k[0]+'"><small>'+k[1]+'</small><b>'+k[2]+'</b><span>'+k[3]+'</span></div>'}).join("");
    var top=j.funil[0][1]||1,prev=null;
    $("#fun").innerHTML=j.funil.map(function(f,i){var r='<div class="row'+(i>=6?' up':'')+'"><span>'+f[0]+'</span><div class="bar"><i style="width:'+Math.max(1,100*f[1]/top)+'%"></i></div><b>'+f[1]+'</b><em>'+(i===0?"":pc(f[1],i===6?j.funil[5][1]:(i===8?j.funil[6][1]-j.funil[7][1]:(i===10?j.funil[7][1]:prev))))+'</em></div>';prev=f[1];return r}).join("");
    $("#ofe").innerHTML='<table><tr><th>Oferta</th><th>Preço</th><th>Aceite</th></tr>'+
      '<tr><td>Kit</td><td>'+m(j.precos.one)+'</td><td>–</td></tr><tr><td>2 kits</td><td>'+m(j.precos.two)+'</td><td>'+pc(t.kit2,t.pedidos)+'</td></tr>'+
      '<tr><td>Bump (pelúcias)</td><td>'+m(j.precos.bump)+'</td><td>'+pc(t.bump,t.pedidos)+'</td></tr><tr><td>Upsell (2º kit)</td><td>'+m(j.precos.upsell)+'</td><td>'+pc(t.up,t.pedidos)+'</td></tr>'+
      '<tr><td>Downsell'+(t.down_kit?' ('+t.down_kit+' com 2º kit)':'')+'</td><td>'+m(j.precos.downsell)+'</td><td>'+pc(t.down,j.funil[8][1])+'</td></tr>'+
      '<tr><td>Oferta extra (pelúcias do 2º kit)</td><td>'+m(j.precos.extra||0)+'</td><td>'+pc(t.extra,j.funil[10][1])+'</td></tr></table>';
    var D=Object.keys(j.dia),mx=Math.max.apply(null,D.map(function(d){return j.dia[d].r}).concat([1]));
    $("#chart").innerHTML=D.length?D.slice(-30).map(function(d){return '<div title="'+d+": "+m(j.dia[d].r)+" · "+j.dia[d].n+' pedidos'+(j.dia[d].g?" · anúncios "+m(j.dia[d].g):"")+'" style="height:'+(100*j.dia[d].r/mx)+'%"><span>'+d.slice(8)+'</span></div>'}).join(""):'<p class="note">Sem vendas no período.</p>';
    function tab(o,nome){var ks=Object.keys(o);if(!ks.length)return '<p class="note">Sem vendas no período.</p>';
      var C=nome==="Campanha";
      return '<table><tr><th>'+nome+'</th><th>Pedidos</th><th>Receita</th>'+(C?'<th>Anúncios</th><th>ROAS</th>':'')+'<th>Lucro'+(C?' (após anúncios)':'')+'</th>'+(C?'<th>Visit.</th><th>Lucro/visit.</th>':'')+'</tr>'+ks.map(function(k){var v=o[k],lc=C?v.la:v.l;return '<tr><td>'+(C?nm(k):k.replace(/</g,""))+'</td><td>'+v.n+'</td><td>'+m(v.r)+'</td>'+(C?'<td>'+m(v.g)+'</td><td>'+(v.g?(v.r/v.g).toFixed(2):"–")+'</td>':'')+'<td class="'+cls(lc)+'">'+m(lc)+'</td>'+(C?'<td>'+v.vis+'</td><td>'+(v.vis?m(lc/v.vis):"–")+'</td>':'')+'</tr>'}).join("")+'</table>'}
    $("#camp").innerHTML=tab(j.camp,"Campanha"); $("#var").innerHTML=tab(j.var,"Versão");
    var P=Object.keys(j.pos);$("#pos").innerHTML=P.length?'<table><tr><th>Versão</th><th>Compradores</th><th>Receita pós-compra</th><th>Lucro pós-compra</th><th>Lucro por comprador</th></tr>'+P.map(function(k){var v=j.pos[k];return '<tr><td>'+k.replace(/</g,"")+'</td><td>'+v.n+'</td><td>'+m(v.r)+'</td><td class="'+cls(v.l)+'">'+m(v.l)+'</td><td><b>'+m(v.l/v.n)+'</b></td></tr>'}).join("")+'</table>':'<p class="note">Sem vendas no período.</p>';
    $("#lista").innerHTML=j.lista.length?'<table><tr><th>Quando (UTC)</th><th>Pet</th><th>Kit</th><th>Bump</th><th>Upsell</th><th>Downsell</th><th>Extra</th><th>Total</th><th>Lucro</th><th>Estado</th><th>Campanha</th></tr>'+j.lista.map(function(p){return '<tr><td>'+p.quando+'</td><td>'+p.pet+'</td><td>'+(p.kit==="two"?"2":"1")+'</td><td>'+(p.bump?"✓":"")+'</td><td>'+p.upsell+'</td><td>'+p.downsell+'</td><td>'+p.extra+'</td><td>'+m(p.total)+(p.reemb?' <span class="neg">−'+m(p.reemb)+'</span>':'')+(p.contest?' ⚠️':'')+'</td><td class="'+cls(p.lucro)+'">'+m(p.lucro)+'</td><td>'+p.estado+'</td><td>'+nm(p.camp)+'</td></tr>'}).join("")+'</table>':'<p class="note">Sem pedidos no período.</p>';
    $("#rod").textContent="Atualizado "+new Date(j.gerado).toLocaleTimeString("pt-BR")+" · atualiza sozinho a cada 30 s · "+(A.ligado&&!A.erro?"lucro já descontado o gasto do Meta ("+(A.atualizado?"gasto atualizado em "+new Date(A.atualizado).toLocaleString("pt-BR"):"atualiza a cada 15 min")+(A.acrescimo?", com "+(100*A.acrescimo).toFixed(2)+"% de imposto":"")+")":"lucro ainda sem o gasto com anúncios (entra quando chegar o primeiro envio do Meta)")+".";
  });
}
$("#p").onchange=load; $("#c").onchange=load; load(); setInterval(load,30000);
</script>
</body></html>
