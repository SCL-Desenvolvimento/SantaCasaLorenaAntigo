<?php
require_once __DIR__.'/../_app/vendor/phpmailer/src/Exception.php';
require_once __DIR__.'/../_app/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__.'/../_app/vendor/phpmailer/src/SMTP.php';
function scl_community_mailer() {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    if (getenv('SCL_SMTP_HOST')) {
        $mail->isSMTP();
        $mail->Host = getenv('SCL_SMTP_HOST');
        $mail->Port = (int) (getenv('SCL_SMTP_PORT') ?: 587);
        $mail->SMTPSecure = $mail->Port === 465 ? 'ssl' : 'tls';
        $mail->SMTPAuth = (bool) getenv('SCL_SMTP_USER');
        $mail->Username = getenv('SCL_SMTP_USER') ?: '';
        $mail->Password = getenv('SCL_SMTP_PASSWORD') ?: '';
        $mail->Timeout = 10;
    }
    return $mail;
}
