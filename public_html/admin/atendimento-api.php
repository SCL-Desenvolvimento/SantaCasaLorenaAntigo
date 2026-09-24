<?php
require __DIR__.'/../_app/Config.inc.php';scl_admin_require();
require __DIR__.'/../includes/admin_inbox.php';
$channel=(string)($_GET['canal']??'ouvidoria');
if(($_GET['exportar']??'')==='csv')scl_inbox_export($channel);
header('Content-Type: application/json; charset=UTF-8');header('Cache-Control: no-store');
try{$rows=scl_inbox_read($channel,$_GET);$page=max(1,min(100000,(int)($_GET['pagina']??1)));echo json_encode(['rows'=>array_slice($rows,($page-1)*25,25),'total'=>count($rows),'page'=>$page,'columns'=>scl_inbox_columns($channel)]);}catch(InvalidArgumentException $e){http_response_code(400);echo json_encode(['error'=>$e->getMessage()]);}
