<?php
ob_start();
session_start();

//Local
/*
define('HOME', 'http://172.16.0.124/SCL/');
define('ROOT', "http://172.16.0.124/SCL/");
define('DIR', 'D:/xampp/htdocs/SCL/');
define('HOST', 'mysql');
define('USER', 'root');
define('PASS', '');
define('DBSA', 'scl');
define('PREFIX', 'scl_');
define('JS', '/SCL/');
*/
//Teste
/*
define('HOME', 'http://servidorpentaxial.com.br/scl/');
define('ROOT', "/scl/");
define('DIR', '/home/servidor/public_html/scl/');
define('HOST', 'servidorpentaxial.com.br');
define('USER', 'servidor_scl');
define('PASS', 'oklKFrFCNsEz');
define('DBSA', 'servidor_scl');
define('PREFIX', 'scl_');
define('JS', '/scl/');
*/

//Produção
define('HOME', 'http://docker-w3.sp.santacasalorena.org.br:8080/');
define('ROOT', "/");
define('DIR', '/var/www/html/');

define('HOST', 'mysql57');
define('USER', 'usr_www-santacasalorena-org-br');
define('PASS', 'BUm50Q4V-K7_9xA');
define('DBSA', 'db_www-santacasalorena-org-br');

define('PREFIX', 'scl_');
define('JS', '/');

// sua chave secreta
$secret = "6LfM4TkUAAAAANX5puo7Yau-AI_jZ-DdoFrjY79i";

define('PA', $_SERVER['REQUEST_URI']);
define('QUERY_STRING', (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : ""));
$REDIRECT_URL = explode("=", QUERY_STRING);
define('REDIRECT_URL', (isset($REDIRECT_URL[1]) && $REDIRECT_URL[1] != "" ? $REDIRECT_URL[1] : ""));

date_default_timezone_set('America/Sao_Paulo');

function __autoload($Class){

	$cDir = array("Conn", "Helpers", "Models", "PHPMailer-master");
	$iDir = null;

	foreach ($cDir as $dirName):
		if (!$iDir && file_exists(__DIR__ . "/{$dirName}/{$Class}.class.php") && !is_dir(__DIR__ . "/{$dirName}/{$Class}.class.php")):
			include_once __DIR__ . "/{$dirName}/{$Class}.class.php";
			$iDir = true;
		endif;
	endforeach;

	if (!$iDir):
		trigger_error(__DIR__ . "/{$dirName}/{$Class}.class.php", E_USER_ERROR);
		die;
	endif;
}

define('System_ACCEPT', 'callout-success');
define('System_INFOR', 'callout-info');
define('System_ALERT', 'callout-warning');
define('System_ERROR', 'callout-danger');

function SystemErro($ErrTitle, $ErrMsg, $ErrNo, $ErrDie = null) {
	$CssClass = ($ErrNo == E_USER_NOTICE ? System_INFOR : ($ErrNo == E_USER_WARNING ? System_ALERT : ($ErrNo == E_USER_ERROR ? System_ERROR : $ErrNo)));
	echo "
			<div class='callout {$CssClass}'>
				<h4>{$ErrTitle}</h4>
				<p>{$ErrMsg}</p>
			</div>
		";

	if ($ErrDie):
		die;
	endif;
}

function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
	$CssClass = ($ErrNo == E_USER_NOTICE ? System_INFOR : ($ErrNo == E_USER_WARNING ? System_ALERT : ($ErrNo == E_USER_ERROR ? System_ERROR : $ErrNo)));
	echo "<p class=\"trigger {$CssClass}\">";
	echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
	echo "<small>{$ErrFile}</small>";
	echo "<span class=\"ajax_close\"></span></p>";

	if ($ErrNo == E_USER_ERROR):
		die;
	endif;
}

set_error_handler('PHPErro');

if (!function_exists('mb_strlen')) {
	function mb_strlen($str) {
		return strlen($str);
	}
}

if (!function_exists('mb_substr')) {
	function mb_substr($str, $p1, $p2) {
		return substr($str, $p1, $p2);
	}
}

$getLocalizacao = new Read;
$getLocalizacao->fullRead("SELECT * FROM ".PREFIX."pagina_localizacao ORDER BY data DESC LIMIT 1");
$localizacao = $getLocalizacao->getResult()[0];

define('EMKT_HEADER', "<!DOCTYPE html>
	<html>

    <head>

        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
        <title>Santa Casa de Lorena</title>
        <meta name='viewport' content='width=device-width,initial-scale=1'>
		<link href='https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600' rel='stylesheet'>
		<link href='https://fonts.googleapis.com/css?family=Domine:400,700' rel='stylesheet'>
        
        <style type='text/css'>
        body { background-color: #eceff1; }
        h2, p{ margin: 0; padding: 0; border: 0; }
        h2 {color: #333; font-family: 'Montserrat', sans-serif; font-size: 14px; margin: 0; font-weight: 600;}
        p { color: #616161; font-family: 'Domine', sans-serif; font-size: 13px;}
        .conteudo.white {	background-color: #fff; }
        .linha { height: 1px; background-color: #ddd; }
        .link { color: inherit; font-weight: 600; text-decoration: none;}
        .azul { color: #29b6f6 !important; }

        @media only screen and (max-device-width: 480px) {
            table[class=conteudo] { 
                width:320px !important;
            }
            
        }
        </style>

        
    </head>
<body>");

define('EMKT_FOOTER', "<table width='700' border='0' cellspacing='30' cellpadding='0' class='conteudo' align='center'>
	<tr>
		<td align='left'>
			<p>Esta mensagem foi enviada para <a class='link azul' href='santacasalorena.org.br' style='color: #29b6f6;'>santacasalorena.org.br</a>.</p>
		</td>
	</tr>
	<tr>
		<td>
			<p>{$localizacao['localizacao']}</p>
		</td>
	</tr>
</table></body></html>");

//Se você não quer receber estes e-mails da Santa Casa de Lorena no futuro você pode <a class='link' href='#cancelar' style='color: #29b6f6;'>cancelar sua assinatura aqui</a>