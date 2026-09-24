<?php
/** Deliberately excludes environment values, credentials, phpinfo and personal data. */
function scl_runtime_inventory(?PDO $database = null): array {
    $required = ['pdo_mysql', 'fileinfo', 'gd', 'mbstring', 'openssl', 'dom', 'iconv'];
    $extensions = [];
    foreach ($required as $name) $extensions[$name] = ['loaded' => extension_loaded($name), 'version' => phpversion($name) ?: null];
    return [
        'collected_at' => gmdate(DATE_ATOM),
        'scope' => PHP_SAPI === 'cli' ? 'Processo CLI onde este comando foi executado; não confirma o PHP do servidor web.' : 'Processo web que atendeu esta requisição.',
        'php' => ['version' => PHP_VERSION, 'sapi' => PHP_SAPI, 'os_family' => PHP_OS_FAMILY, 'extensions' => $extensions],
        'web_server' => $_SERVER['SERVER_SOFTWARE'] ?? null,
        'database' => $database ? ['driver' => $database->getAttribute(PDO::ATTR_DRIVER_NAME), 'server_version' => $database->getAttribute(PDO::ATTR_SERVER_VERSION)] : null,
        'limits' => array_combine(['memory_limit','upload_max_filesize','post_max_size','max_execution_time'], array_map('ini_get', ['memory_limit','upload_max_filesize','post_max_size','max_execution_time'])),
    ];
}
