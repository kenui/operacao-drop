<?php
// CLI, uma vez: marca como robô as visitas com fbclid de 25/11 01:23–01:38 UTC. O Meta confirmou zero impressões e cliques
// nos anúncios até 01:46 (thread do Meta), então essas visitas são a revisão automática dos anúncios novos, não gente.
// Não apaga nada: só acrescenta "bot":1 em dados, e o painel deixa de contar.
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
$raiz = dirname(__DIR__, 2); $feito = $raiz . '/robos-revisao.feito';
if (is_file($feito)) { echo "ja feito\n"; exit; }
$_SERVER['REQUEST_METHOD'] = 'CLI';
ob_start(); require $raiz . '/public_html/api/_lib.php'; ob_end_clean();
$w = "criado >= '2026-11-25 01:23:00' AND criado < '2026-11-25 01:38:00' AND utm LIKE '%fbclid%' AND dados NOT LIKE '%\"bot\":1%'";
$v = (int)db()->query("SELECT COUNT(DISTINCT visitante_id) FROM eventos WHERE $w")->fetchColumn();
$n = db()->exec("UPDATE eventos SET dados = IF(dados IN ('', '[]', '{}') OR dados IS NULL, '{\"bot\":1,\"motivo\":\"revisao-meta\"}', CONCAT(LEFT(dados, CHAR_LENGTH(dados) - 1), ',\"bot\":1,\"motivo\":\"revisao-meta\"}')) WHERE $w");
file_put_contents($feito, gmdate('c'));
echo json_encode(['eventos' => $n, 'visitantes' => $v]), "\n";
