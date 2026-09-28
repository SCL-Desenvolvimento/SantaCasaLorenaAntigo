<?php
// Enviar como /home/santacasalorena/scl-config.php, fora de public_html.
// Substitua PREENCHA pelos dados da hospedagem antes de publicar.
return [
    'SCL_HOME' => 'https://www.santacasalorena.org.br/',
    'SCL_DB_HOST' => 'localhost', // Confirme no cPanel/K2Host.
    'SCL_DB_NAME' => 'PREENCHA_NOME_COMPLETO_DO_BANCO',
    'SCL_DB_USER' => 'PREENCHA_USUARIO_COMPLETO_DO_BANCO',
    'SCL_DB_PASSWORD' => 'PREENCHA_SENHA_DO_BANCO',
    'SCL_DB_PREFIX' => 'scl_',
    'SCL_PRIVATE_DIR' => '/home/santacasalorena/santa-casa-private',
    'SCL_RECAPTCHA_SECRET' => 'PREENCHA_SECRET_DA_CHAVE_JA_USADA_NO_SITE',
    'SCL_MAIL_FROM' => 'webmaster@santacasalorena.org.br',
    'SCL_SMTP_HOST' => 'PREENCHA_SERVIDOR_SMTP',
    'SCL_SMTP_PORT' => '587', // 587 STARTTLS ou 465 SSL, conforme o provedor.
    'SCL_SMTP_USER' => 'PREENCHA_CONTA_SMTP',
    'SCL_SMTP_PASSWORD' => 'PREENCHA_SENHA_SMTP',
];
