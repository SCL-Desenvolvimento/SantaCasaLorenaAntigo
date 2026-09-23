<?php
/** Offline HTTP upload harness. Serve explicitly on loopback with PHP's built-in server. */
if (PHP_SAPI !== 'cli-server' || !in_array($_SERVER['REMOTE_ADDR'] ?? '', array('127.0.0.1','::1'), true)) { http_response_code(404); exit; }
$project=dirname(__DIR__);
define('DIR',sys_get_temp_dir().'/scl-community-upload-check/');define('ROOT','/');define('PREFIX','test_');
if (!is_dir(DIR)) mkdir(DIR,0750,true);
putenv('SCL_PRIVATE_DIR='.sys_get_temp_dir().'/scl-community-private-check');
require $project.'/includes/ui.php';require $project.'/includes/community_forms.php';require $project.'/includes/community_mailer.php';
$_SESSION=array('community_token'=>'offline-test');
class Create { public static $data; public function ExeCreate($table,$data){self::$data=$data;} public function getResult(){return isset($_GET['fail']) ? null : 1;} }
class UploadTestMailer extends \PHPMailer\PHPMailer\PHPMailer { public static $mime=''; public function send(){ $ok=$this->preSend(); self::$mime=$this->getSentMIMEMessage();return $ok; } }
$result=scl_process_form(array('trabalhe_conosco'),'offline',fn()=>true,fn()=>new UploadTestMailer(true));
$stored=Create::$data['curriculum'] ?? '';
$path=$stored ? scl_resume_path($stored) : false;
$response=array('success'=>$result['success'],'errors'=>$result['errors'],'mime_attachment'=>str_contains(UploadTestMailer::$mime,'curriculo.pdf'),'random_pdf'=>preg_match('~^private/curriculuns/[a-f0-9]{48}\.pdf$~',$stored)===1,'stored'=>(bool)$path);
if($path)unlink($path);
header('Content-Type: application/json');echo json_encode($response);
