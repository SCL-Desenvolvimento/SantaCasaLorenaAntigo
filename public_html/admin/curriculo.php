<?php
require __DIR__ . '/../_app/Config.inc.php';
require_once __DIR__ . '/../includes/private_files.php';
scl_admin_require();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) scl_deny(400);
$query = Conn::getConn()->prepare('SELECT curriculum FROM ' . PREFIX . 'trabalhe_conosco WHERE id_trabalhe_conosco = ?');
$query->execute([$id]);
$stored = $query->fetchColumn();
$file = $stored ? scl_resume_path($stored) : false;
if (!$file) scl_deny(404);
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="curriculo-' . $id . '.pdf"');
header('Content-Security-Policy: sandbox');
header('Content-Length: ' . filesize($file));
session_write_close();
readfile($file);
exit;
