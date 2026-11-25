<?php
// Gasto com anúncios do Meta para o painel (pedido do Kenui em 24/11: "o lucro no painel também deve descontar o gasto com os anúncios").
// Credencial em ../../meta_ads.php (fora da pasta pública, nunca no Git), gravada pela página de uso único instalar/meta-ads.php:
//   return ['token' => 'EAA...', 'conta' => 'act_123', 'acrescimo' => 0.0];
// 'acrescimo' = imposto/IOF cobrado por cima do gasto informado pelo Meta (fração, ex. 0.1215). Conferir na fatura do Meta.
// Lê /act_X/insights por campanha e por dia, converte para centavos de dólar e guarda em cache por 15 min.
declare(strict_types=1);

function ads_cred(): ?array {
  $f = dirname(__DIR__, 2) . '/meta_ads.php';
  if (!is_file($f)) return null;
  $c = require $f;
  return (is_array($c) && !empty($c['token']) && !empty($c['conta'])) ? $c : null;
}

function ads_get(string $url): array {
  $ch = curl_init($url);
  curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15]);
  $r = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
  $j = json_decode((string)$r, true) ?: [];
  if ($code !== 200) throw new RuntimeException((string)($j['error']['message'] ?? "HTTP $code"));
  return $j;
}

// Dólares por 1 unidade da moeda da conta (BRL etc.). Cache de 12 h; sem rede, usa o último valor salvo.
function ads_cambio(string $moeda, string $cache): float {
  if ($moeda === 'USD') return 1.0;
  $f = "$cache/cambio-$moeda.json";
  $old = is_file($f) ? (json_decode((string)file_get_contents($f), true) ?: []) : [];
  if (!empty($old['t']) && time() - (int)$old['t'] < 43200) return (float)$old['v'];
  try {
    $j = ads_get('https://open.er-api.com/v6/latest/USD');
    $v = 1 / (float)($j['rates'][$moeda] ?? 0);
    file_put_contents($f, json_encode(['t' => time(), 'v' => $v]));
    return $v;
  } catch (Throwable $e) { if (!empty($old['v'])) return (float)$old['v']; throw $e; }
}

/**
 * $desde: início do período em UTC ('Y-m-d H:i:s'); $tudo: período "tudo".
 * Volta ['ligado'=>bool, 'erro'=>?string, 'moeda'=>, 'total'=>centavos USD, 'dia'=>[Y-m-d=>c], 'id'=>[campaign_id=>c], 'nome'=>[campaign_name=>c], 'ids'=>[id=>nome]]
 */
function gasto_ads(string $desde, bool $tudo = false): array {
  $vazio = ['ligado' => false, 'erro' => null, 'moeda' => null, 'total' => 0, 'dia' => [], 'id' => [], 'nome' => [], 'ids' => [], 'conj' => [], 'conj_nomes' => [], 'conj_camp' => [], 'ad' => [], 'ad_nomes' => [], 'ad_camp' => []];
  $cr = ads_cred();
  if (!$cr) return gasto_ads_arquivo($desde, $tudo, $vazio);
  $cache = dirname(__DIR__, 2) . '/_cache';
  if (!is_dir($cache)) @mkdir($cache, 0700, true);
  $ini = (new DateTime($desde, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/New_York'))->format('Y-m-d');
  $fim = (new DateTime('now', new DateTimeZone('America/New_York')))->format('Y-m-d');
  $chave = "$cache/ads-" . md5($cr['conta'] . ($tudo ? 'max' : $ini) . $fim) . '.json';
  if (is_file($chave) && time() - filemtime($chave) < 900) return json_decode((string)file_get_contents($chave), true);
  try {
    $q = ['level' => 'ad', 'time_increment' => 1, 'limit' => 500, 'fields' => 'campaign_id,campaign_name,adset_id,adset_name,ad_id,ad_name,spend,account_currency', 'access_token' => $cr['token']];
    if ($tudo) $q['date_preset'] = 'maximum'; else $q['time_range'] = json_encode(['since' => $ini, 'until' => $fim]);
    $url = 'https://graph.facebook.com/v21.0/' . rawurlencode($cr['conta']) . '/insights?' . http_build_query($q);
    $linhas = [];
    for ($i = 0; $url && $i < 20; $i++) { $j = ads_get($url); $linhas = array_merge($linhas, $j['data'] ?? []); $url = $j['paging']['next'] ?? ''; }
    $moeda = (string)($linhas[0]['account_currency'] ?? 'USD');
    $fx = ads_cambio($moeda, $cache) * (1 + (float)($cr['acrescimo'] ?? 0));
    $r = ['ligado' => true, 'erro' => null, 'moeda' => $moeda, 'acrescimo' => (float)($cr['acrescimo'] ?? 0)] + $vazio;
    foreach ($linhas as $l) {
      ads_nivel($r, $l, (int)round((float)$l['spend'] * $fx * 100));
      $c = (int)round((float)$l['spend'] * $fx * 100);
      $d = (string)($l['date_start'] ?? ''); $id = (string)($l['campaign_id'] ?? ''); $nm = (string)($l['campaign_name'] ?? $id);
      $r['total'] += $c; $r['dia'][$d] = ($r['dia'][$d] ?? 0) + $c;
      $r['id'][$id] = ($r['id'][$id] ?? 0) + $c; $r['nome'][$nm] = ($r['nome'][$nm] ?? 0) + $c; $r['ids'][$id] = $nm;
    }
    file_put_contents($chave, json_encode($r));
    return $r;
  } catch (Throwable $e) {
    return ['ligado' => true, 'erro' => substr($e->getMessage(), 0, 200)] + $vazio;
  }
}

// Caminho B (sem token no servidor): a thread do Meta lê o gasto pelo conector e commita um JSON só com números
// no ramo deploy em _dados/ads-gasto.json (pasta bloqueada por .htaccess). Formato:
// {"atualizado":"2026-11-25T10:00:00Z","fonte":"conector","moeda":"BRL","acrescimo":0.1383,
//  "linhas":[{"campaign_id":"120...","campaign_name":"...","date":"2026-11-24","spend":12.34}]}
// Melhor: uma linha por ANÚNCIO e dia, com "adset_id","adset_name","ad_id","ad_name" (o painel mostra gasto, ROAS e lucro por anúncio e conjunto).
// Não misture no mesmo arquivo linhas por campanha e por anúncio do mesmo dia (somaria duas vezes).
// Opcional: "usd_por_unidade": 0.19 (senão o servidor busca o câmbio do dia). spend na moeda da conta, date no fuso da conta, uma linha por campanha e dia (a última repetida vale).
function gasto_ads_arquivo(string $desde, bool $tudo, array $vazio): array {
  $f = dirname(__DIR__) . '/_dados/ads-gasto.json';
  if (!is_file($f)) return $vazio;
  $j = json_decode((string)file_get_contents($f), true);
  if (!is_array($j) || !isset($j['linhas']) || !is_array($j['linhas'])) return ['ligado' => true, 'erro' => 'ads-gasto.json inválido'] + $vazio;
  $moeda = strtoupper((string)($j['moeda'] ?? 'USD'));
  $acr = (float)($j['acrescimo'] ?? ($moeda === 'BRL' ? 0.1383 : 0));
  $cache = dirname(__DIR__, 2) . '/_cache';
  if (!is_dir($cache)) @mkdir($cache, 0700, true);
  try { $fx = (!empty($j['usd_por_unidade']) ? (float)$j['usd_por_unidade'] : ads_cambio($moeda, $cache)) * (1 + $acr); }
  catch (Throwable $e) { return ['ligado' => true, 'erro' => 'câmbio indisponível'] + $vazio; }
  $ini = (new DateTime($desde, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/New_York'))->format('Y-m-d');
  $u = [];
  foreach ($j['linhas'] as $l) {
    $d = (string)($l['date'] ?? ''); $id = (string)($l['campaign_id'] ?? '');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) || $id === '' || (!$tudo && $d < $ini)) continue;
    $u["$id|" . ($l['adset_id'] ?? '') . '|' . ($l['ad_id'] ?? '') . "|$d"] = $l;
  }
  $r = ['ligado' => true, 'erro' => null, 'moeda' => $moeda, 'acrescimo' => $acr, 'fonte' => (string)($j['fonte'] ?? 'arquivo'), 'atualizado' => (string)($j['atualizado'] ?? '')] + $vazio; $r['ligado'] = true;
  foreach ($u as $l) {
    $c = (int)round((float)($l['spend'] ?? 0) * $fx * 100); ads_nivel($r, $l, $c);
    $d = (string)$l['date']; $id = (string)$l['campaign_id']; $nm = (string)($l['campaign_name'] ?? $id);
    $r['total'] += $c; $r['dia'][$d] = ($r['dia'][$d] ?? 0) + $c;
    $r['id'][$id] = ($r['id'][$id] ?? 0) + $c; $r['nome'][$nm] = ($r['nome'][$nm] ?? 0) + $c; $r['ids'][$id] = $nm;
  }
  return $r;
}

// soma o gasto por conjunto e por anúncio quando a linha traz esses IDs
function ads_nivel(array &$r, array $l, int $c): void {
  $cp = (string)($l['campaign_id'] ?? '');
  if (!empty($l['adset_id'])) { $k = (string)$l['adset_id']; $r['conj'][$k] = ($r['conj'][$k] ?? 0) + $c; $r['conj_nomes'][$k] = (string)($l['adset_name'] ?? $k); $r['conj_camp'][$k] = $cp; }
  if (!empty($l['ad_id'])) { $k = (string)$l['ad_id']; $r['ad'][$k] = ($r['ad'][$k] ?? 0) + $c; $r['ad_nomes'][$k] = (string)($l['ad_name'] ?? $k); $r['ad_camp'][$k] = $cp; }
}
