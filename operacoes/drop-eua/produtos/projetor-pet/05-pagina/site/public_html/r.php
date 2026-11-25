<?php
// Link curto de parceiro: /r/<handle> (regra no .htaccess do deploy), /r.php/<handle> ou /r.php?h=<handle>.
// Leva à página com utm_source=creator&utm_medium=affiliate&utm_campaign=<handle>; o site guarda isso e o painel atribui a venda.
$h = (string)($_GET['h'] ?? trim((string)($_SERVER['PATH_INFO'] ?? ''), '/'));
$h = substr(preg_replace('/[^a-z0-9._-]/', '', strtolower(ltrim($h, '@'))), 0, 40);
$q = $h !== '' ? '?utm_source=creator&utm_medium=affiliate&utm_campaign=' . rawurlencode($h) : '';
header('Cache-Control: no-store');
header('Location: /' . $q, true, 302);
