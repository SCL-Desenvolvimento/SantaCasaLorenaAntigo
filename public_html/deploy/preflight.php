<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/includes/environment.php';
scl_load_environment(dirname(__DIR__, 2) . '/scl-config.php');
$errors=[];
if (PHP_VERSION_ID < 80400) $errors[]='PHP 8.4 ou superior é necessário.';
foreach(['pdo_mysql','fileinfo','gd','mbstring','openssl','dom','iconv'] as $extension) if(!extension_loaded($extension))$errors[]='Extensão ausente: '.$extension;
foreach(['SCL_HOME','SCL_DB_HOST','SCL_DB_USER','SCL_DB_PASSWORD','SCL_DB_NAME','SCL_PRIVATE_DIR','SCL_RECAPTCHA_SECRET'] as $key)if(!getenv($key))$errors[]='Variável ausente: '.$key;
if(!str_starts_with(getenv('SCL_HOME')?:'','https://'))$errors[]='SCL_HOME deve usar o domínio HTTPS definitivo.';
if($errors){foreach($errors as $error)fwrite(STDERR,$error.PHP_EOL);exit(1);}
require dirname(__DIR__).'/_app/Config.inc.php';
require_once dirname(__DIR__).'/includes/private_files.php';
try{
    $private=scl_private_directory();
    if(!is_writable($private))throw new RuntimeException('Private directory is not writable.');
    $db=Conn::getConn();
    $db->query('SELECT ordem, legenda FROM '.PREFIX.'galeria_anexo LIMIT 0');
    $db->query('SELECT security_version FROM '.PREFIX.'usuario LIMIT 0');
    $db->query('SELECT token_hash, expires_at FROM '.PREFIX.'password_reset LIMIT 0');
    $db->query('SELECT bucket, window_start, attempts FROM '.PREFIX.'auth_attempt LIMIT 0');
    $q=$db->prepare('SELECT CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND COLUMN_NAME=?');
    $q->execute([DBSA,PREFIX.'usuario','senha']);
    if((int)$q->fetchColumn()<255)throw new RuntimeException('Password column must support at least 255 characters.');
    $q=$db->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=? AND TABLE_NAME=?');
    $q->execute([DBSA,PREFIX.'usuario']);
    if(strtolower((string)$q->fetchColumn())!=='innodb')throw new RuntimeException('Transactional user table required.');
    echo "OK: runtime, configuração e esquema verificados. Homologue HTTPS, SMTP e bloqueios HTTP separadamente.\n";
}catch(Throwable $e){fwrite(STDERR,"Falha na configuração, armazenamento privado ou migração. Confira deploy/SECURITY.md e deploy/ADMIN.md.\n");exit(1);}
