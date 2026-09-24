<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/includes/runtime_inventory.php';
$database = null;
if (in_array('--database', $argv, true)) {
    require dirname(__DIR__) . '/_app/Config.inc.php';
    $database = Conn::getConn();
}
echo json_encode(scl_runtime_inventory($database), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
