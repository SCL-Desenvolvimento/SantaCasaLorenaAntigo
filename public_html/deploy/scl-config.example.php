<?php
// Copy as scl-config.php ONE LEVEL ABOVE public_html, never inside it.
// Enter the existing hosting credentials privately in that external file.
return [
    'SCL_HOME' => 'https://YOUR-HOSPITAL-DOMAIN/',
    'SCL_DB_HOST' => '',
    'SCL_DB_USER' => '',
    'SCL_DB_PASSWORD' => '',
    'SCL_DB_NAME' => '',
    'SCL_DB_PREFIX' => 'scl_',
    'SCL_RECAPTCHA_SECRET' => '',
    'SCL_PRIVATE_DIR' => __DIR__ . '/santa-casa-private',
    'SCL_MAIL_FROM' => '',
    'SCL_SMTP_HOST' => '',
    'SCL_SMTP_PORT' => '587',
    'SCL_SMTP_USER' => '',
    'SCL_SMTP_PASSWORD' => '',
];
