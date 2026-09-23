<?php
if (PHP_SAPI !== 'cli-server' || !in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1','::1'], true) || !getenv('SCL_SECURITY_TEST')) { http_response_code(404); exit; }
define('DIR', dirname(getenv('SCL_SECURITY_TEST')) . '/uploads/');
require __DIR__ . '/../_app/Helpers/Upload.class.php';
$upload = new Upload('arquivos');
if (($_POST['mode'] ?? '') === 'image') $upload->Image($_FILES['file'] ?? [], null, 100, $_POST['folder'] ?? 'imagens');
else $upload->File($_FILES['file'] ?? [], null, $_POST['folder'] ?? 'documentos');
$file = $upload->getResult();
$response = ['success'=>(bool)$file, 'error'=>$upload->getError(), 'random'=>$file ? preg_match('#/[a-f0-9]{48}\.(pdf|jpg|png)$#D', $file)===1 : false];
if ($file && is_file(DIR.$file)) unlink(DIR.$file);
header('Content-Type: application/json'); echo json_encode($response);
