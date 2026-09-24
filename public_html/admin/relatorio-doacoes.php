<?php
require __DIR__.'/../_app/Config.inc.php';
scl_admin_require();
require __DIR__.'/../includes/admin_inbox.php';
scl_inbox_export('doacoes');
