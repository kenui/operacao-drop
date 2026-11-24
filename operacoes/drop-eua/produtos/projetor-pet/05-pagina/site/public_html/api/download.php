<?php
// GET /api/download.php?o=PEDIDO&f=ID&s=ASSINATURA -> entrega o PDF que o pedido tem direito.
require __DIR__ . '/_lib.php';
$p = pedido((string)($_GET['o'] ?? '')); $f = (string)($_GET['f'] ?? '');
if (!$p || $p['status'] !== 'pago' || !hash_equals(assinatura($p['token'], $f), (string)($_GET['s'] ?? ''))) out(['error' => 'Link not valid. Email us and we will resend it.'], 403);
$d = digitais($p)[$f] ?? null; $arq = $d ? dirname(__DIR__, 2) . '/digital/' . $d[1] : '';
if (!$d || !is_file($arq)) out(['error' => 'File not found. Email us and we will resend it.'], 404);
log_evento(['evento' => 'download', 'event_id' => 'dl.' . $p['token'] . '.' . $f, 'pet' => $p['pet'], 'dados' => ['pedido' => $p['token'], 'arquivo' => $f]]);
header_remove('Content-Type');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $d[1] . '"');
header('Content-Length: ' . filesize($arq));
readfile($arq);
