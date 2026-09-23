<?php
require_once dirname(__DIR__) . '/includes/security.php';
scl_security_boot();
ob_start();
define('DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
// Secrets are supplied by the server environment, never by a published file.
define('HOME', rtrim(getenv('SCL_HOME') ?: '/', '/') . '/');
define('ROOT', rtrim(parse_url(HOME, PHP_URL_PATH) ?: '/', '/') . '/');
define('HOST', getenv('SCL_DB_HOST') ?: '');
define('USER', getenv('SCL_DB_USER') ?: '');
define('PASS', getenv('SCL_DB_PASSWORD') ?: '');
define('DBSA', getenv('SCL_DB_NAME') ?: '');
define('PREFIX', getenv('SCL_DB_PREFIX') ?: 'scl_');
if (!preg_match('/^[a-zA-Z0-9_]+$/D', PREFIX)) throw new RuntimeException('Invalid database prefix.');
define('JS', ROOT);
$secret = getenv('SCL_RECAPTCHA_SECRET') ?: '';
define('PA', $_SERVER['REQUEST_URI'] ?? '/');
define('QUERY_STRING', $_SERVER['QUERY_STRING'] ?? '');
parse_str(QUERY_STRING, $routeParameters);
define('REDIRECT_URL', $routeParameters['url'] ?? $routeParameters['qs'] ?? '');
date_default_timezone_set('America/Sao_Paulo');
spl_autoload_register(function ($class) {
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/D', $class)) return;
    foreach (['Conn', 'Helpers', 'Models'] as $directory) {
        $path = __DIR__ . '/' . $directory . '/' . $class . '.class.php';
        if (is_file($path)) { require_once $path; return; }
    }
});
define('System_ACCEPT', 'callout-success');
define('System_INFOR', 'callout-info');
define('System_ALERT', 'callout-warning');
define('System_ERROR', 'callout-danger');
function SystemErro($title, $message, $type, $die = false) {
    $type = in_array($type, [System_ACCEPT, System_INFOR, System_ALERT, System_ERROR], true) ? $type : System_ERROR;
    echo '<div class="callout ' . $type . '"><h4>' . htmlspecialchars((string) $title, ENT_QUOTES, 'UTF-8') . '</h4><p>' . htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') . '</p></div>';
    if ($die) exit;
}
function PHPErro($number, $message, $file, $line) {
    if (!(error_reporting() & $number)) return false;
    error_log('SCL PHP error ' . $number . ' at ' . basename($file) . ':' . $line);
    if ($number === E_USER_ERROR) { http_response_code(500); exit('Não foi possível concluir a solicitação.'); }
    return true;
}
set_error_handler('PHPErro');
set_exception_handler(function (Throwable $error) {
    error_log('SCL exception ' . get_class($error) . ' at ' . basename($error->getFile()) . ':' . $error->getLine());
    http_response_code(503);
    exit('Serviço temporariamente indisponível. Tente novamente mais tarde.');
});
// Every administrative entrypoint is protected, including reports and AJAX reads.
$script = strtolower(str_replace('\\', '/', realpath($_SERVER['SCRIPT_FILENAME'] ?? '') ?: ''));
$admin = strtolower(str_replace('\\', '/', DIR) . 'admin/');
if (str_starts_with($script, $admin) && $script !== $admin . 'index.php') scl_admin_require();
define('EMKT_HEADER', '<!doctype html><html lang="pt-BR"><meta charset="utf-8"><body>');
define('EMKT_FOOTER', '<p>Santa Casa de Lorena</p></body></html>');
