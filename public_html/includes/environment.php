<?php
/** Optional shared-hosting configuration, always outside the public directory. */
function scl_load_environment(string $file): void {
    if (!is_file($file)) return;
    $root = realpath(dirname(__DIR__));
    $real = realpath($file);
    if (!$root || !$real || str_starts_with(strtolower(str_replace('\\', '/', $real)), strtolower(str_replace('\\', '/', $root)) . '/')) {
        throw new RuntimeException('Configuration must be outside the public directory.');
    }
    $settings = require $real;
    if (!is_array($settings)) throw new RuntimeException('Invalid external configuration.');
    $allowed = ['SCL_HOME','SCL_DB_HOST','SCL_DB_USER','SCL_DB_PASSWORD','SCL_DB_NAME','SCL_DB_PREFIX','SCL_RECAPTCHA_SECRET','SCL_PRIVATE_DIR','SCL_MAIL_FROM','SCL_SMTP_HOST','SCL_SMTP_PORT','SCL_SMTP_USER','SCL_SMTP_PASSWORD'];
    foreach ($allowed as $key) {
        if (getenv($key) !== false || !array_key_exists($key, $settings)) continue;
        if (!is_string($settings[$key]) || str_contains($settings[$key], "\0")) throw new RuntimeException('Invalid configuration value.');
        putenv($key . '=' . $settings[$key]);
    }
}
