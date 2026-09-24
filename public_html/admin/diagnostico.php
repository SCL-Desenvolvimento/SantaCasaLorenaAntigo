<?php
require __DIR__ . '/../_app/Config.inc.php';
scl_admin_require();
require_once __DIR__ . '/../includes/runtime_inventory.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
echo json_encode(scl_runtime_inventory(Conn::getConn()), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
