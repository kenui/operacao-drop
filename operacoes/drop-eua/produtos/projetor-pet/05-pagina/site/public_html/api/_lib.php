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
    if (is_file(__DIR__ . '/_precos.php')) $c = array_replace($c, require __DIR__ . '/_precos.php'); // preços/custos/prazo do Git
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
  try { $pdo->query('SELECT variante FROM pedidos LIMIT 1'); }
  catch (Throwable $e) { foreach (array_filter(array_map('trim', explode(';', file_get_contents(dirname(__DIR__, 2) . '/schema.sql')))) as $sql) { $pdo->exec($sql); } }
  try { $pdo->query('SELECT telefone FROM pedidos LIMIT 1'); }   // v3: telefone para a correspondência do Meta e avisos de entrega
  catch (Throwable $e) { $pdo->exec('ALTER TABLE pedidos ADD COLUMN telefone VARCHAR(30)'); }
  try { $pdo->query('SELECT extra FROM pedidos LIMIT 1'); }      // oferta extra pós-upsell + teste do pós-compra
  catch (Throwable $e) { foreach (['variante_pos VARCHAR(40)', 'extra VARCHAR(20)', 'pi_extra VARCHAR(64)', 'valor_extra INTEGER DEFAULT 0', 'custo_extra INTEGER DEFAULT 0'] as $col) $pdo->exec("ALTER TABLE pedidos ADD COLUMN $col"); }
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

// ---------- teste A/B (FNV-1a igual ao de config.js) ----------
function fnv(string $s): int { $x = 0x811c9dc5; for ($i = 0; $i < strlen($s); $i++) { $x ^= ord($s[$i]); $x = ($x * 16777619) & 0xffffffff; } return $x; }
function variante(string $vid): string {
  $t = cfg()['testes'] ?? []; $id = $t['active'] ?? '';
  if ($id === '' || $vid === '' || empty($t['list'][$id])) return $id === '' ? '' : $id . ':A';
  return $id . ':' . ((fnv($id . ':' . $vid) % 100) < ($t['list'][$id]['split'] ?? 50) ? 'B' : 'A');
}
// teste do pós-compra: sorteio pelo pedido (não pelo visitante), gravado uma vez em pedidos.variante_pos
function variante_pos(string $tok): string {
  $t = cfg()['testes_pos'] ?? []; $id = $t['active'] ?? '';
  if ($id === '' || empty($t['list'][$id])) return '';
  return $id . ':' . ((fnv($id . ':' . $tok) % 100) < ($t['list'][$id]['split'] ?? 50) ? 'B' : 'A');
}
function garantir_pos(array $p): array {
  if ($p['variante_pos'] !== null && $p['variante_pos'] !== '') return $p;
  $v = variante_pos($p['token']); if ($v === '') return $p;
  db()->prepare("UPDATE pedidos SET variante_pos = ? WHERE token = ? AND (variante_pos IS NULL OR variante_pos = '')")->execute([$v, $p['token']]);
  return pedido($p['token']);
}
function precos_p(array $p): array { return precos((string)$p['variante'], (string)($p['variante_pos'] ?? '')); }
// preços (e tipo do downsell) da versão do visitante e do pedido
function precos(?string $var = '', string $varPos = ''): array {
  $c = cfg(); $p = $c['precos'] + ['downsell_kind' => $c['downsell_kind'] ?? 'plush', 'extra_kind' => $c['extra_kind'] ?? 'plush'];
  if ($var && str_ends_with($var, ':B')) { $id = substr($var, 0, -2); $p = array_merge($p, $c['testes']['list'][$id]['B'] ?? []); }
  if ($varPos && str_ends_with($varPos, ':B')) { $id = substr($varPos, 0, -2); $p = array_merge($p, $c['testes_pos']['list'][$id]['B'] ?? []); }
  return $p;
}

// ---------- pedido ----------
function valor_front(string $kit, bool $bump, string $var = ''): int { $p = precos($var); return ($kit === 'two' ? $p['two'] : $p['one']) + ($bump ? $p['bump'] : 0); }
function custo_front(string $kit, bool $bump): int { $c = cfg()['custos']; return ($kit === 'two' ? 2 : 1) * $c['kit'] + ($bump ? $c['bump'] : 0); }
function pedido(string $tok): ?array { $s = db()->prepare('SELECT * FROM pedidos WHERE token = ?'); $s->execute([$tok]); return $s->fetch() ?: null; }
function limpa_pet(mixed $v): string { return $v === 'dog' ? 'dog' : 'cat'; }

// ---------- Meta: API de Conversões ----------
// telefone no padrão do Meta: só dígitos, com código do país (EUA = 1)
function tel_meta(?string $t): string { $d = preg_replace('/\D/', '', (string)$t); if (strlen($d) === 10) $d = '1' . $d; return strlen($d) >= 11 ? $d : ''; }
function nome_meta(string $n): string { return preg_replace('/[^\p{Ll}]/u', '', mb_strtolower(trim($n))); }
function capi(string $evento, string $eventId, array $user, array $custom = [], string $url = ''): void {
  $c = cfg();
  if (empty($c['meta_pixel_id']) || empty($c['meta_capi_token'])) return;
  $ud = array_filter([
    'em' => !empty($user['email']) ? [hash('sha256', strtolower(trim($user['email'])))] : null,
    'ph' => ($ph = tel_meta($user['phone'] ?? '')) ? [hash('sha256', $ph)] : null,
    'fn' => !empty($user['fn']) ? [hash('sha256', nome_meta($user['fn']))] : null,
    'ln' => !empty($user['ln']) ? [hash('sha256', nome_meta($user['ln']))] : null,
    'zp' => !empty($user['zip']) ? [hash('sha256', substr(preg_replace('/\D/', '', $user['zip']), 0, 5))] : null,
    'ct' => !empty($user['city']) ? [hash('sha256', preg_replace('/[^a-z]/', '', strtolower($user['city'])))] : null,
    'st' => !empty($user['state']) ? [hash('sha256', substr(preg_replace('/[^a-z]/', '', strtolower($user['state'])), 0, 2))] : null,
    'country' => [hash('sha256', 'us')],
    'external_id' => !empty($user['vid']) ? [hash('sha256', $user['vid'])] : null,
    'client_ip_address' => $user['ip'] ?? null, 'client_user_agent' => $user['ua'] ?? null,
    'fbc' => $user['fbc'] ?? null, 'fbp' => $user['fbp'] ?? null,
  ]);
  $ev = [ 'event_name' => $evento, 'event_time' => time(), 'event_id' => $eventId, 'action_source' => 'website',
    'event_source_url' => $url ?: ($c['site_url'] ?? ''), 'user_data' => $ud, 'custom_data' => $custom ?: new stdClass() ];
  // GPC ou "Your Privacy Choices": Limited Data Use, o Meta descobre o estado pela localização (0, 0)
  if (!empty($user['ldu'])) { $ev['data_processing_options'] = ['LDU']; $ev['data_processing_options_country'] = 0; $ev['data_processing_options_state'] = 0; }
  $data = ['data' => json_encode([$ev]), 'access_token' => $c['meta_capi_token']];
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
  $total = (int)$p['valor_front'] + (int)$p['valor_upsell'] + (int)$p['valor_downsell'] + (int)($p['valor_extra'] ?? 0);
  $ctx = json_decode((string)$p['ctx'], true) ?: [];
  $nm = preg_split('/\s+/', trim((string)$p['nome'])) ?: [];
  capi('Purchase', 'purchase.' . $p['token'], ['email' => $p['email'], 'phone' => $p['telefone'] ?? '', 'fn' => $nm[0] ?? '', 'ln' => count($nm) > 1 ? end($nm) : '', 'zip' => $p['cep'], 'city' => $p['cidade'], 'state' => $p['estado'], 'vid' => $ctx['visitante_id'] ?? '', 'ip' => $p['ip'], 'ua' => $p['ua'], 'fbc' => $ctx['fbc'] ?? null, 'fbp' => $ctx['fbp'] ?? null, 'ldu' => !empty($ctx['optout'])],
    ['value' => round($total / 100, 2), 'currency' => 'USD', 'content_ids' => ['projetor-pet-' . $p['pet']], 'content_type' => 'product', 'num_items' => $p['kit'] === 'two' ? 2 : 1]);
  enviar_confirmacao(pedido($p['token']));
  log_evento(['visitante_id' => $ctx['visitante_id'] ?? '', 'evento' => 'funnel_complete', 'event_id' => 'purchase.' . $p['token'], 'pet' => $p['pet'], 'angulo' => $p['angulo'], 'dados' => ['pedido' => $p['token'], 'total' => $total]]);
  return true;
}

// nome de cada oferta pós-compra no recibo, no e-mail e na Stripe
function nome_extra(string $etapa, string $estado, string $pet, int $custo = 0): string {
  if ($etapa === 'extra') return $custo > 1000 ? 'Third kit' . ($pet ? " ($pet)" : '') . ', same box' : 'Spare plush pack for the second kit, same box';
  return match ($estado) { 'aceito-guide' => 'The Indoor Hunt Playbook (PDF)', 'aceito-kit' => "Second kit ($pet), same box", default => 'Spare plush pack' };
}

// ---------- produtos digitais ----------
// arquivos ficam em site/digital/ (fora da pasta pública). Link assinado: só quem tem o pedido baixa.
function digitais(array $p): array {
  $c = cfg(); $pet = $p['pet'] === 'dog' ? 'dog' : 'cat'; $d = [];
  if (!empty($c['bonus_on'])) $d['first-hunt'] = ['First Hunt Guide (PDF)', "first-hunt-guide-$pet.pdf"];
  if (!empty($c['bonus_on'])) $d['rainy-day'] = ['Rainy Day Play Plan (PDF)', "rainy-day-play-plan-$pet.pdf"];   // só oferece se o arquivo existir (cachorro por enquanto)
  if ($p['downsell'] === 'aceito-guide') $d['playbook'] = ['The Indoor Hunt Playbook (PDF)', "indoor-hunt-playbook-$pet.pdf"];
  return $d;
}
// PDFs: fora da pasta pública (../digital, da instalação) ou em public_html/_digital (vem pelo Git, acesso web negado).
function arquivo_digital(string $arq): string {
  foreach ([dirname(__DIR__, 2) . '/digital/', dirname(__DIR__) . '/_digital/'] as $d) if (is_file($d . $arq)) return $d . $arq;
  return '';
}
function assinatura(string $tok, string $f): string { return substr(hash_hmac('sha256', "$tok|$f", cfg()['stripe_webhook'] ?? 'x'), 0, 20); }
function links_download(array $p): array {
  $base = rtrim(cfg()['site_url'] ?? '', '/'); $out = [];
  foreach (digitais($p) as $id => [$nome, $arq]) {
    if (!arquivo_digital($arq)) continue;        // só oferece o que existe
    $out[] = [$nome, $base . '/api/download.php?o=' . $p['token'] . '&f=' . $id . '&s=' . assinatura($p['token'], $id)];
  }
  return $out;
}

// ---------- e-mail de confirmação (uma vez, no fim do funil) ----------
function enviar_confirmacao(?array $p): void {
  $c = cfg();
  if (!$p || empty($c['email_from']) || empty($p['email']) || (int)$p['email_enviado'] === 1) return;
  $pr = precos_p($p); $m = fn($v) => '$' . number_format($v / 100, 2);
  $l = ["Hunt kit (" . ($p['kit'] === 'two' ? "2 kits, {$p['mix']}" : "1 kit, {$p['pet']}") . ")  " . $m((int)$p['valor_front'] - ($p['bump'] ? $pr['bump'] : 0))];
  if ($p['bump']) $l[] = 'Spare plush pack  ' . $m($pr['bump']);
  if ($p['upsell'] === 'aceito') $l[] = "Second kit ({$p['upsell_pet']}), same box  " . $m((int)$p['valor_upsell']);
  if (str_starts_with((string)$p['downsell'], 'aceito')) $l[] = nome_extra('downsell', (string)$p['downsell'], (string)$p['upsell_pet']) . '  ' . $m((int)$p['valor_downsell']);
  if (($p['extra'] ?? '') === 'aceito') $l[] = nome_extra('extra', 'aceito', (string)$p['upsell_pet'], (int)$p['custo_extra']) . '  ' . $m((int)$p['valor_extra']);
  $total = (int)$p['valor_front'] + (int)$p['valor_upsell'] + (int)$p['valor_downsell'] + (int)($p['valor_extra'] ?? 0);
  $dl = links_download($p);
  $prazo = $c['prazo'] ? "It should arrive in {$c['prazo']}." : "We'll email your tracking number as soon as it ships.";
  $nome = $c['email_nome'] ?? 'Order';
  $corpo = "Hi" . ($p['nome'] ? ' ' . explode(' ', $p['nome'])[0] : '') . ",\n\nThanks for your order. Here's exactly what you were charged:\n\n" . implode("\n", $l) . "\nTotal  " . $m($total) .
    "\n\nEach item may show as a separate charge from $nome on your statement.\n\n$prazo If it's going to be late, we'll tell you before that date and you can cancel for a full refund.\n" .
    ($dl ? "\nYour downloads:\n" . implode("\n", array_map(fn($d) => "- {$d[0]}: {$d[1]}", $dl)) . "\n" : '') .
    "\nFirst night: dim the lights, use a smooth floor, keep it to 10-15 minutes, and finish with the plush.\n\nChanged your mind? Reply to this email within 30 days of delivery and we'll refund you. Please write to us before calling your bank. It's faster for you.\n\n$nome\n";
  $h = "From: $nome <{$c['email_from']}>\r\nReply-To: {$c['email_from']}\r\nContent-Type: text/plain; charset=UTF-8";
  if (@mail($p['email'], "Your order is confirmed ($nome)", $corpo, $h, '-f' . $c['email_from'])) {
    db()->prepare('UPDATE pedidos SET email_enviado = 1 WHERE token = ?')->execute([$p['token']]);
  }
}
