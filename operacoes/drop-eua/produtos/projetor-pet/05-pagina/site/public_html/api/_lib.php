<?php
// Base do servidor: config, banco, Stripe (REST por cURL, sem dependências) e API de Conversões do Meta.
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function cfg(): array {
  static $c = null;
  if ($c === null) {
    $p = getenv('PP_CONFIG') ?: dirname(__DIR__, 2) . '/config.php';
    if (!is_file($p)) { out(['error' => 'Checkout is not configured yet.'], 503); }
    $c = require $p;
  }
  return $c;
}
function out(array $d, int $code = 200): never { http_response_code($code); echo json_encode($d); exit; }
function body(): array {
  $raw = file_get_contents('php://input', false, null, 0, 20000) ?: '';
  $j = json_decode($raw, true); return is_array($j) ? $j : [];
}
function only_post(): void { if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(['error' => 'POST only'], 405); }
function ip(): string { return substr($_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '', 0, 45); }
function token(): string { return bin2hex(random_bytes(12)); }

function db(): PDO {
  static $pdo = null;
  if ($pdo) return $pdo;
  $c = cfg();
  $pdo = new PDO($c['db_dsn'], $c['db_user'] ?? null, $c['db_pass'] ?? null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
  $flag = sys_get_temp_dir() . '/pp_schema_' . md5($c['db_dsn']);
  if (!is_file($flag)) { foreach (array_filter(array_map('trim', explode(';', file_get_contents(dirname(__DIR__, 2) . '/schema.sql')))) as $sql) { $pdo->exec($sql); } @touch($flag); }
  return $pdo;
}

// ---------- Stripe ----------
function stripe(string $method, string $path, array $params = [], ?string $idem = null): array {
  $ch = curl_init((cfg()['stripe_base'] ?? 'https://api.stripe.com/v1/') . $path . ($method === 'GET' && $params ? '?' . http_build_query($params) : ''));
  $h = ['Stripe-Version: 2024-06-20'];
  if ($idem) $h[] = 'Idempotency-Key: ' . $idem;
  curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_USERPWD => cfg()['stripe_secret'] . ':', CURLOPT_HTTPHEADER => $h, CURLOPT_TIMEOUT => 20, CURLOPT_CUSTOMREQUEST => $method]);
  if ($method !== 'GET') curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
  $r = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
  $j = json_decode((string)$r, true) ?: ['error' => ['message' => 'Stripe unreachable']];
  $j['_http'] = $code; return $j;
}

// ---------- pedido ----------
function valor_front(string $kit, bool $bump): int { $p = cfg()['precos']; return ($kit === 'two' ? $p['two'] : $p['one']) + ($bump ? $p['bump'] : 0); }
function custo_front(string $kit, bool $bump): int { $c = cfg()['custos']; return ($kit === 'two' ? 2 : 1) * $c['kit'] + ($bump ? $c['bump'] : 0); }
function pedido(string $tok): ?array { $s = db()->prepare('SELECT * FROM pedidos WHERE token = ?'); $s->execute([$tok]); return $s->fetch() ?: null; }
function limpa_pet(mixed $v): string { return $v === 'dog' ? 'dog' : 'cat'; }

// ---------- Meta: API de Conversões ----------
function capi(string $evento, string $eventId, array $user, array $custom = [], string $url = ''): void {
  $c = cfg();
  if (empty($c['meta_pixel_id']) || empty($c['meta_capi_token'])) return;
  $ud = array_filter([
    'em' => !empty($user['email']) ? [hash('sha256', strtolower(trim($user['email'])))] : null,
    'zp' => !empty($user['zip']) ? [hash('sha256', substr(preg_replace('/\D/', '', $user['zip']), 0, 5))] : null,
    'ct' => !empty($user['city']) ? [hash('sha256', preg_replace('/[^a-z]/', '', strtolower($user['city'])))] : null,
    'st' => !empty($user['state']) ? [hash('sha256', strtolower($user['state']))] : null,
    'country' => [hash('sha256', 'us')],
    'external_id' => !empty($user['vid']) ? [hash('sha256', $user['vid'])] : null,
    'client_ip_address' => $user['ip'] ?? null, 'client_user_agent' => $user['ua'] ?? null,
    'fbc' => $user['fbc'] ?? null, 'fbp' => $user['fbp'] ?? null,
  ]);
  $data = ['data' => json_encode([[ 'event_name' => $evento, 'event_time' => time(), 'event_id' => $eventId, 'action_source' => 'website',
    'event_source_url' => $url ?: ($c['site_url'] ?? ''), 'user_data' => $ud, 'custom_data' => $custom ?: new stdClass() ]]), 'access_token' => $c['meta_capi_token']];
  if (!empty($c['meta_test_code'])) $data['test_event_code'] = $c['meta_test_code'];
  $ch = curl_init('https://graph.facebook.com/v21.0/' . $c['meta_pixel_id'] . '/events');
  curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query($data), CURLOPT_TIMEOUT => 4]);
  curl_exec($ch); curl_close($ch);
}

function log_evento(array $e): void {
  $s = db()->prepare('INSERT INTO eventos (criado, visitante_id, sessao_id, evento, event_id, produto, pet, angulo, variante, pagina, disp, utm, dados) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
  $s->execute([gmdate('Y-m-d H:i:s'), substr((string)($e['visitante_id'] ?? ''), 0, 40), substr((string)($e['sessao_id'] ?? ''), 0, 40), substr((string)($e['evento'] ?? ''), 0, 40),
    substr((string)($e['event_id'] ?? ''), 0, 80), 'projetor-pet', substr((string)($e['pet'] ?? ''), 0, 10), substr((string)($e['angulo'] ?? ''), 0, 20), substr((string)($e['variante'] ?? ''), 0, 20),
    substr((string)($e['pagina'] ?? ''), 0, 80), substr((string)($e['disp'] ?? ''), 0, 10), substr(json_encode($e['utm'] ?? []), 0, 1000), substr(json_encode($e['dados'] ?? []), 0, 2000)]);
}

// Fecha o funil: grava total e manda Purchase uma vez só (valor total, event_id = token do pedido).
function finalizar(array $p): bool {
  if (!empty($p['funil_fim']) || $p['status'] !== 'pago') return false;
  $u = db()->prepare("UPDATE pedidos SET funil_fim = ? WHERE token = ? AND funil_fim IS NULL");
  $u->execute([gmdate('Y-m-d H:i:s'), $p['token']]);
  if ($u->rowCount() === 0) return false;                       // outro processo já fechou
  $total = (int)$p['valor_front'] + (int)$p['valor_upsell'] + (int)$p['valor_downsell'];
  $ctx = json_decode((string)$p['ctx'], true) ?: [];
  capi('Purchase', 'purchase.' . $p['token'], ['email' => $p['email'], 'zip' => $p['cep'], 'city' => $p['cidade'], 'state' => $p['estado'], 'vid' => $ctx['visitante_id'] ?? '', 'ip' => $p['ip'], 'ua' => $p['ua'], 'fbc' => $ctx['fbc'] ?? null, 'fbp' => $ctx['fbp'] ?? null],
    ['value' => round($total / 100, 2), 'currency' => 'USD', 'content_ids' => ['projetor-pet-' . $p['pet']], 'content_type' => 'product', 'num_items' => $p['kit'] === 'two' ? 2 : 1]);
  log_evento(['visitante_id' => $ctx['visitante_id'] ?? '', 'evento' => 'funnel_complete', 'event_id' => 'purchase.' . $p['token'], 'pet' => $p['pet'], 'angulo' => $p['angulo'], 'dados' => ['pedido' => $p['token'], 'total' => $total]]);
  return true;
}
