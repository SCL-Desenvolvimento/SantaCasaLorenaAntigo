<?php
require __DIR__ . '/../../_app/Config.inc.php';
scl_admin_require();
$source = (string) ($_GET['src'] ?? '');
if (in_array($source, ['../resources/img/user.png', '/img/user.png'], true)) $source = 'resources/img/user.png';
if (!preg_match('#^(arquivos/[a-zA-Z0-9_./-]+|resources/img/user.png)$#D', $source) || str_contains($source, '..')) scl_deny(404);
$file = realpath(DIR . $source);
$root = realpath(DIR);
if (!$file || !str_starts_with($file, $root . DIRECTORY_SEPARATOR) || !is_file($file)) scl_deny(404);
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($file);
if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], true)) scl_deny(404);
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($file));
readfile($file);
exit;
