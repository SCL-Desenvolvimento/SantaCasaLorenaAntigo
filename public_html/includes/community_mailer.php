<?php
require_once __DIR__.'/../_app/vendor/phpmailer/src/Exception.php';
require_once __DIR__.'/../_app/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__.'/../_app/vendor/phpmailer/src/SMTP.php';
function scl_community_mailer() { return new \PHPMailer\PHPMailer\PHPMailer(true); }
