<?php
require __DIR__ . '/../_app/Config.inc.php';
scl_admin_require();
header('Content-Type: application/json; charset=utf-8');
echo json_encode(Conn::getConn()->query('SELECT id_galeria, nome FROM '.PREFIX.'galeria ORDER BY nome')->fetchAll());
