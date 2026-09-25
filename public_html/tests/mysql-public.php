<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require dirname(__DIR__).'/_app/Config.inc.php';
if(!in_array(HOST,['127.0.0.1','localhost','::1'],true)||!preg_match('/^scl_test_[a-f0-9]{16}_$/D',PREFIX))throw new RuntimeException('Temporary MySQL copy required.');
require dirname(__DIR__).'/includes/ui.php';require dirname(__DIR__).'/includes/community_forms.php';
class LocalMailCapture {public $CharSet,$Subject;public function __call($name,$args){return true;}}
$count=0;foreach(['contato','deposito','boleto','pesquisa'] as $mode){$_SESSION['community_token']='mysql-test';unset($_SESSION['community_last_sent']);$_SERVER['REQUEST_METHOD']='POST';$_POST=['form'=>$mode,'community_token'=>'mysql-test','g-recaptcha-response'=>'test-fixture','nome'=>str_repeat('Nome ',28),'email'=>'validation@example.invalid','cidade'=>str_repeat('Cidade ',15),'assunto'=>'Assunto de teste','razao'=>'Elogio','mensagem'=>str_repeat('Mensagem completa. ',200)];if($mode==='pesquisa')foreach(require dirname(__DIR__).'/includes/contact_questions.php' as [$field,$label,$options])$_POST[$field]=$options[0];
 $result=scl_process_form([$mode],'synthetic',fn()=>true,fn()=>new LocalMailCapture());if(!$result['success'])throw new RuntimeException($mode.': '.json_encode($result['errors']));$count++;
 $table=['contato'=>'ouvidoria','deposito'=>'doacoes','boleto'=>'doacoes','pesquisa'=>'pesquisa_atendimento'][$mode];$q=Conn::getConn()->prepare('SELECT COUNT(*) FROM '.PREFIX.$table.' WHERE mensagem=?');$q->execute([$_POST['mensagem']]);if(!$q->fetchColumn())throw new RuntimeException('Message truncated.');$count++;
}echo 'OK: '.$count." public MySQL persistence checks, injected CAPTCHA verifier and mail capture; no external messages.\n";
