<?php
// Programa de parceiros (25/11/2026). US$12 por venda pelo link littlecatchco.com/r/<handle>.
// POST {acao:"entrar", ...}  -> cadastra e devolve o link e a chave do painel do parceiro.
// GET  ?k=<chave>            -> cliques e vendas do link desse parceiro (sem dado de cliente).
// O link vira utm_source=creator&utm_medium=affiliate&utm_campaign=<handle> (r.php), que o site já guarda e o painel já mostra.
require __DIR__ . '/_lib.php';
const COMISSAO = 12;        // dólares por pedido pago
const CARENCIA_DIAS = 14;   // paga toda sexta as vendas com mais de 14 dias; reembolso depois disso fica por nossa conta

function tabela(): void {
  db()->exec('CREATE TABLE IF NOT EXISTS parceiros (handle VARCHAR(40) PRIMARY KEY, criado VARCHAR(20), nome VARCHAR(120), rede VARCHAR(20),
    perfil VARCHAR(200), email VARCHAR(190), paypal VARCHAR(190), tipo VARCHAR(20), chave VARCHAR(32), ip VARCHAR(45), status VARCHAR(20) DEFAULT \'ativo\')');
}
function limpa_handle(string $h): string { return substr(preg_replace('/[^a-z0-9._-]/', '', strtolower(ltrim(trim($h), '@'))), 0, 40); }

tabela();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $k = preg_replace('/[^a-f0-9]/', '', (string)($_GET['k'] ?? ''));
  if (strlen($k) !== 24) out(['error' => 'Link not valid.'], 404);
  $s = db()->prepare('SELECT handle, nome, criado FROM parceiros WHERE chave = ?'); $s->execute([$k]); $p = $s->fetch();
  if (!$p) out(['error' => 'Link not valid.'], 404);
  $h = $p['handle']; $agulha = '%"utm_campaign":"' . $h . '"%';
  $v = db()->prepare("SELECT COUNT(DISTINCT visitante_id) n FROM eventos WHERE evento = 'page_view' AND utm LIKE ? AND utm LIKE '%\"utm_source\":\"creator\"%' AND dados NOT LIKE '%\"bot\":1%'");
  $v->execute([$agulha]); $visitas = (int)$v->fetch()['n'];
  $q = db()->prepare("SELECT pago_em, reembolsado, ctx FROM pedidos WHERE status = 'pago' AND ctx LIKE ?"); $q->execute([$agulha]);
  $vendas = []; $limite = gmdate('Y-m-d H:i:s', time() - CARENCIA_DIAS * 86400);
  foreach ($q->fetchAll() as $r) {
    $u = (json_decode((string)$r['ctx'], true) ?: [])['utm'] ?? [];
    if (($u['utm_campaign'] ?? '') !== $h || ($u['utm_source'] ?? '') !== 'creator') continue;
    if ((int)$r['reembolsado'] && $r['pago_em'] >= $limite) continue;    // reembolso dentro da carência não conta
    $vendas[] = ['quando' => substr((string)$r['pago_em'], 0, 10), 'liberada' => $r['pago_em'] < $limite];
  }
  $lib = count(array_filter($vendas, fn($x) => $x['liberada']));
  out(['handle' => $h, 'nome' => $p['nome'], 'desde' => substr((string)$p['criado'], 0, 10), 'link' => 'https://littlecatchco.com/r/' . $h,
    'visitas' => $visitas, 'vendas' => count($vendas), 'ganho' => count($vendas) * COMISSAO, 'liberado' => $lib * COMISSAO,
    'kit_gratis' => count($vendas) >= 3, 'lista' => array_slice(array_reverse($vendas), 0, 50)]);
}

only_post();
$b = body();
if (!empty($b['site'])) out(['ok' => true]);    // campo-isca: robô preenche, gente não vê
$ip = ip(); $db = db();
$t = $db->prepare('SELECT COUNT(*) n FROM tentativas WHERE ip = ? AND criado > ?'); $t->execute([$ip, gmdate('Y-m-d H:i:s', time() - 3600)]);
if ((int)$t->fetch()['n'] >= 8) out(['error' => 'Too many tries. Please wait an hour.'], 429);
$db->prepare('INSERT INTO tentativas (ip, criado) VALUES (?, ?)')->execute([$ip, gmdate('Y-m-d H:i:s')]);

$h = limpa_handle((string)($b['handle'] ?? ''));
$nome = trim(substr(strip_tags((string)($b['nome'] ?? '')), 0, 120));
$rede = in_array($b['rede'] ?? '', ['tiktok', 'instagram', 'youtube', 'facebook', 'other'], true) ? $b['rede'] : 'other';
$email = filter_var(trim((string)($b['email'] ?? '')), FILTER_VALIDATE_EMAIL) ?: '';
$paypal = filter_var(trim((string)($b['paypal'] ?? '')), FILTER_VALIDATE_EMAIL) ?: '';
$tipo = ($b['tipo'] ?? '') === 'dono' ? 'dono' : 'curador';
if (strlen($h) < 2 || $nome === '' || $email === '') out(['error' => 'Please fill in your name, handle and email.'], 400);
if (empty($b['aceite'])) out(['error' => 'Please accept the partner rules.'], 400);
$s = $db->prepare('SELECT handle, email, chave FROM parceiros WHERE handle = ?'); $s->execute([$h]); $ja = $s->fetch();
if ($ja) {
  if (strcasecmp($ja['email'], $email) !== 0) out(['error' => 'This handle is already registered. If it is yours, email us.'], 409);
  out(['ok' => true, 'link' => 'https://littlecatchco.com/r/' . $h, 'painel' => 'https://littlecatchco.com/partner-stats.html?k=' . $ja['chave']]);
}
$chave = token();
$db->prepare('INSERT INTO parceiros (handle, criado, nome, rede, perfil, email, paypal, tipo, chave, ip) VALUES (?,?,?,?,?,?,?,?,?,?)')
   ->execute([$h, gmdate('Y-m-d H:i:s'), $nome, $rede, substr((string)($b['perfil'] ?? ''), 0, 200), $email, $paypal ?: $email, $tipo, $chave, $ip]);
log_evento(['evento' => 'parceiro_novo', 'dados' => ['handle' => $h, 'rede' => $rede, 'tipo' => $tipo]]);
out(['ok' => true, 'link' => 'https://littlecatchco.com/r/' . $h, 'painel' => 'https://littlecatchco.com/partner-stats.html?k=' . $chave]);
