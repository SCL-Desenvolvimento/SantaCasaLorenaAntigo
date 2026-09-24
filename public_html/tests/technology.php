<?php
/** Offline checks for hosting configuration and diagnostics; no production DB. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__) . '/includes/environment.php';
require dirname(__DIR__) . '/includes/runtime_inventory.php';
$checks = 0;
function technology_check(bool $condition, string $message): void {
    global $checks;
    if (!$condition) throw new RuntimeException($message);
    $checks++;
}
$saved = [];
foreach (['SCL_HOME','SCL_DB_PASSWORD','SCL_PRIVATE_DIR'] as $key) { $saved[$key] = getenv($key); putenv($key); }
$file = tempnam(sys_get_temp_dir(), 'scl-config-test-');
try {
    file_put_contents($file, '<?php return ' . var_export(['SCL_HOME'=>'https://fixture.invalid/','SCL_DB_PASSWORD'=>'synthetic-secret','PATH'=>'must-not-override'], true) . ';');
    $path = getenv('PATH');
    scl_load_environment($file);
    technology_check(getenv('SCL_HOME') === 'https://fixture.invalid/', 'Load external configuration');
    technology_check(getenv('SCL_DB_PASSWORD') === 'synthetic-secret', 'Load configured secret without output');
    technology_check(getenv('PATH') === $path, 'Only approved application settings are imported');
    putenv('SCL_HOME=https://environment.invalid/');
    scl_load_environment($file);
    technology_check(getenv('SCL_HOME') === 'https://environment.invalid/', 'Server environment takes precedence');
    $denied = false;
    try { scl_load_environment(__FILE__); } catch (RuntimeException $e) { $denied = true; }
    technology_check($denied, 'Reject configuration inside public directory');
    file_put_contents($file, '<?php return "invalid";');
    $denied = false;
    try { scl_load_environment($file); } catch (RuntimeException $e) { $denied = true; }
    technology_check($denied, 'Reject malformed configuration');
    $inventory = scl_runtime_inventory(new PDO('sqlite::memory:'));
    technology_check($inventory['php']['version'] === PHP_VERSION, 'Report actual runtime version');
    technology_check($inventory['database']['driver'] === 'sqlite', 'Report actual database driver');
    technology_check(!str_contains(json_encode($inventory), 'synthetic-secret'), 'Never expose secrets in diagnostics');
    technology_check(count($inventory['php']['extensions']) === 7, 'Report all required extensions');
    require dirname(__DIR__) . '/includes/community_mailer.php';
    technology_check(class_exists('PHPMailer\\PHPMailer\\PHPMailer'), 'Composer autoload resolves current mailer');
    echo "OK: $checks technology checks; isolated configuration and database.\n";
} finally {
    unlink($file);
    foreach ($saved as $key => $value) putenv($value === false ? $key : $key . '=' . $value);
}
