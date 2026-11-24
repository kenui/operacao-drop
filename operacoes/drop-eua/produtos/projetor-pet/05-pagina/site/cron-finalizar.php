<?php
// Rodar a cada 5 min pelo cron da Hostinger: php /home/USUARIO/domains/DOMINIO/cron-finalizar.php
// Fecha funis pagos há mais de 15 min sem decisão no upsell e manda o Purchase com o valor total.
// Também lista os pedidos prontos para mandar à CJ (uma caixa só por pedido).
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
$_SERVER['REQUEST_METHOD'] = 'CLI';
ob_start(); require __DIR__ . '/public_html/api/_lib.php'; ob_end_clean();
$lim = gmdate('Y-m-d H:i:s', time() - 900);
$s = db()->prepare("SELECT * FROM pedidos WHERE status='pago' AND funil_fim IS NULL AND pago_em < ?"); $s->execute([$lim]);
$n = 0; foreach ($s->fetchAll() as $p) { if (finalizar($p)) $n++; }
echo "funis fechados: $n\n";
