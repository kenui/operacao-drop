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
  $vazio = ['ligado' => false, 'erro' => null, 'moeda' => null, 'total' => 0, 'dia' => [], 'id' => [], 'nome' => [], 'ids' => []];
  $cr = ads_cred();
  if (!$cr) return $vazio;
  $cache = dirname(__DIR__, 2) . '/_cache';
  if (!is_dir($cache)) @mkdir($cache, 0700, true);
  $ini = (new DateTime($desde, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/New_York'))->format('Y-m-d');
  $fim = (new DateTime('now', new DateTimeZone('America/New_York')))->format('Y-m-d');
  $chave = "$cache/ads-" . md5($cr['conta'] . ($tudo ? 'max' : $ini) . $fim) . '.json';
  if (is_file($chave) && time() - filemtime($chave) < 900) return json_decode((string)file_get_contents($chave), true);
  try {
    $q = ['level' => 'campaign', 'time_increment' => 1, 'limit' => 500, 'fields' => 'campaign_id,campaign_name,spend,account_currency', 'access_token' => $cr['token']];
    if ($tudo) $q['date_preset'] = 'maximum'; else $q['time_range'] = json_encode(['since' => $ini, 'until' => $fim]);
    $url = 'https://graph.facebook.com/v21.0/' . rawurlencode($cr['conta']) . '/insights?' . http_build_query($q);
    $linhas = [];
    for ($i = 0; $url && $i < 20; $i++) { $j = ads_get($url); $linhas = array_merge($linhas, $j['data'] ?? []); $url = $j['paging']['next'] ?? ''; }
    $moeda = (string)($linhas[0]['account_currency'] ?? 'USD');
    $fx = ads_cambio($moeda, $cache) * (1 + (float)($cr['acrescimo'] ?? 0));
    $r = ['ligado' => true, 'erro' => null, 'moeda' => $moeda, 'acrescimo' => (float)($cr['acrescimo'] ?? 0), 'total' => 0, 'dia' => [], 'id' => [], 'nome' => [], 'ids' => []];
    foreach ($linhas as $l) {
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
