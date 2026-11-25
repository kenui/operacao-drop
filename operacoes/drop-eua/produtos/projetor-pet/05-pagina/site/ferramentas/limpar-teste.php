<?php
// CLI, uma vez só (Kenui autorizou em 25/11: "Pode apagar"): apaga do banco tudo o que é de antes da campanha entrar no ar
// (25/11 01:23 UTC): pedidos e eventos de teste, tentativas e webhooks do modo teste. Grava o resultado em _status/limpeza.txt.
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
$raiz = dirname(__DIR__, 2); $pub = $raiz . '/public_html'; $feito = $raiz . '/limpeza-teste.feita';
if (is_file($feito)) { echo "ja feito\n"; exit; }
$_SERVER['REQUEST_METHOD'] = 'CLI';
ob_start(); require $pub . '/api/_lib.php'; ob_end_clean();
$corte = '2026-11-25 01:23:00'; $o = ['corte_utc' => $corte, 'quando' => gmdate('c')];
foreach (['pedidos', 'eventos', 'tentativas', 'webhooks'] as $t) {
  $q = db()->prepare("DELETE FROM $t WHERE criado < ?"); $q->execute([$corte]); $o[$t] = $q->rowCount();
}
$dir = $pub . '/_status'; if (!is_dir($dir)) mkdir($dir, 0755);
file_put_contents($dir . '/.htaccess', "Require all denied\n");
file_put_contents($dir . '/limpeza.txt', json_encode($o, JSON_PRETTY_PRINT));
file_put_contents($feito, $o['quando']);
echo json_encode($o), "\n";
