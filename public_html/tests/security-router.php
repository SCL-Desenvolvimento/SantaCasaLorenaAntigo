<?php
if (PHP_SAPI !== 'cli-server' || !in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1','::1'], true) || !getenv('SCL_SECURITY_TEST')) { http_response_code(404); exit; }
require __DIR__ . '/security-db.php';
Conn::$db = new SecurityTestPDO(getenv('SCL_SECURITY_TEST') . '.sqlite');
security_fixture(Conn::$db);
if(getenv('SCL_STAGE4_TEST')){require __DIR__.'/admin-db.php';admin_fixture(Conn::$db);}
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path === '/__fixture') {
    require_once __DIR__ . '/../includes/security.php';
    scl_security_boot();
    if (isset($_GET['account'])) {
        $q=Conn::$db->prepare('SELECT * FROM scl_usuario WHERE id_usuario=?'); $q->execute([(int)$_GET['account']]);
        scl_login_session($q->fetch());
        if (isset($_GET['expired'])) $_SESSION['auth_seen']=time()-1800;
    }
    header('Content-Type: application/json');
    echo json_encode(['csrf'=>scl_csrf_token(), 'users'=>(int)Conn::$db->query('SELECT COUNT(*) FROM scl_usuario')->fetchColumn()]); exit;
}
$fixtureTarget = realpath(dirname(__DIR__) . $path);
if (!$fixtureTarget || !str_starts_with($fixtureTarget, dirname(__DIR__) . DIRECTORY_SEPARATOR) || pathinfo($fixtureTarget, PATHINFO_EXTENSION) !== 'php') { http_response_code(404); exit; }
$_SERVER['SCRIPT_FILENAME']=$fixtureTarget;
chdir(dirname($fixtureTarget));
require $fixtureTarget;
