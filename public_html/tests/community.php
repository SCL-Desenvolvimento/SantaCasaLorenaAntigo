<?php
require __DIR__.'/news-listing.php';
$before=$count;
require DIR.'includes/community_forms.php';
require DIR.'includes/community_mailer.php';
require DIR.'includes/article_helpers.php';
class Update { public static $data; public function ExeUpdate($table,$data,$where,$params){self::$data=$data;} }
class OfflineCommunityMailer extends \PHPMailer\PHPMailer\PHPMailer {
    public static $last;
    public function send(){self::$last=$this;return $this->preSend();}
}
$valid=array('nome'=>'Pessoa de teste','email'=>'teste@example.org','cidade'=>'Lorena','assunto'=>'Mensagem de teste','razao'=>'Sugestão','mensagem'=>'Mensagem <script>nunca executar</script>','unexpected'=>'blocked');
[$clean,$errors]=scl_validate_form('contato',$valid);
verify(!$errors && !isset($clean['unexpected']), 'Contact validates and whitelists database columns');
foreach(array('email'=>'inválido','nome'=>array('invalid'),'mensagem'=>str_repeat('é',5001)) as $field=>$value){$bad=$valid;$bad[$field]=$value;[, $errors]=scl_validate_form('contato',$bad);verify(isset($errors[$field]),'Reject malformed field: '.$field);}
[, $errors]=scl_validate_form('unknown',$valid);verify(isset($errors['form']),'Reject unknown form mode');
$survey=array('mensagem'=>'');foreach(require DIR.'includes/contact_questions.php' as [$key,$label,$options])$survey[$key]=$options[0];
[, $errors]=scl_validate_form('pesquisa',$survey);verify(!$errors,'Survey options match stored values and allow optional observation');
$survey['nota_atendimento']='6';[, $errors]=scl_validate_form('pesquisa',$survey);verify(isset($errors['nota_atendimento']),'Reject unsupported survey rating');
verify(!scl_valid_resume(array()) && !scl_valid_resume(array('error'=>0,'tmp_name'=>__FILE__,'size'=>100,'name'=>'file.pdf')),'Reject missing and non-uploaded resumes');
$_SERVER['REQUEST_METHOD']='POST';$_SESSION['community_token']=str_repeat('b',64);unset($_SESSION['community_last_sent']);
$_POST=$valid+array('form'=>'contato','community_token'=>'invalid','g-recaptcha-response'=>'offline');
$called=0;$verify=function()use(&$called){$called++;return true;};$mailer=fn()=>new OfflineCommunityMailer(true);
$state=scl_process_form(array('contato'),'test',$verify,$mailer);
verify(isset($state['errors']['form'])&&$called===0,'Reject CSRF before CAPTCHA or database access');
$_POST['community_token']=$_SESSION['community_token'];
$state=scl_process_form(array('contato'),'test',fn()=>false,$mailer);verify(isset($state['errors']['captcha']),'Rejected CAPTCHA prevents persistence');
$state=scl_process_form(array('contato'),'test',$verify,$mailer);
verify($state['success']&&isset(Create::$data['razao'])&&!isset(Create::$data['unexpected']),'Persist allowlisted contact and return success');
verify(Create::$table===scl_ouvidoria_table(),'Public form uses the same table as list and report');
verify(OfflineCommunityMailer::$last->getToAddresses()[0][0]==='secretaria@santacasalorena.org.br','Keep institutional recipient');
verify(str_contains(OfflineCommunityMailer::$last->Body,'&lt;script&gt;')&&!str_contains(OfflineCommunityMailer::$last->Body,'<script>'),'Escape user content in notification email');
verify(OfflineCommunityMailer::$last->CharSet==='UTF-8'&&strlen(OfflineCommunityMailer::$last->getSentMIMEMessage())>0,'Build UTF-8 MIME with modern PHPMailer without sending');
$state=scl_process_form(array('contato'),'test',$verify,$mailer);verify(!$state['success']&&isset($state['errors']['form']),'Prevent duplicate post after a successful submission');
foreach(array('deposito','boleto') as $donationMode){unset($_SESSION['community_last_sent']);$_POST=$valid+array('form'=>$donationMode,'community_token'=>$_SESSION['community_token'],'g-recaptcha-response'=>'offline');$state=scl_process_form(array('deposito','boleto'),'test',$verify,$mailer);verify($state['success']&&Create::$data['tipo']===$donationMode&&!isset(Create::$data['razao']),'Preserve donation method: '.$donationMode);}
$_SERVER['REQUEST_METHOD']='GET';$_POST=array();$_GET=array();
Read::$fixtures=array('paginas'=>array(array('descricao'=>'<p>Orientações do cadastro</p>')));
foreach(array('contato','trabalhe_conosco','pesquisa') as $channel){$_GET['canal']=$channel;$r_DIR=array('page'=>'fale-conosco','info'=>array('titulo'=>'Fale conosco'));ob_start();require DIR.'includes/header.php';require DIR.'includes/paginas/fale_conosco.php';$html=ob_get_clean();verify(!str_contains($html,'jQuery')&&substr_count($html,'<form ')===1&&str_contains($html,'value="'.$channel.'"'),'Render independent accessible contact channel: '.$channel);verify(str_contains($html,'Orientações do cadastro'),'Preserve CMS contact description');}
$_GET=array('tipo'=>'boleto');Read::$fixtures=array('pagina_doacao'=>array(array('bloco1'=>'Título do cadastro','bloco2'=>'Apresentação','bloco3'=>"Banco de teste\nAgência: 001\nConta: 0001-2")));
ob_start();require DIR.'includes/paginas/doacoes.php';$html=ob_get_clean();verify(str_contains($html,'Agência: 001')&&str_contains($html,'Conta: 0001-2')&&str_contains($html,'value="boleto"'),'Keep original bank information and selected donation method');
Read::$fixtures=array('galeria_anexo'=>array(array('url'=>'resources/img/santa-casa-home/pronto-atendimento.png','descricao'=>'Legenda preservada')));
$safe=scl_article_content('<h1>Título</h1><p onclick="bad()">Texto <strong>com destaque</strong></p><script>bad()</script><img src="javascript:bad()"><table><tr><td colspan="2">Tabela</td></tr></table><div class="ck-galleria"><input value="4"></div><div class="ck-galleria"><input value="5"></div>');
verify(!str_contains($safe,'onclick')&&!str_contains($safe,'javascript:')&&!str_contains($safe,'<script')&&str_contains($safe,'<h2>Título</h2>'),'Sanitize article HTML and keep one page h1');
verify(str_contains($safe,'colspan="2"')&&str_contains($safe,'<strong>com destaque</strong>'),'Preserve article formatting and tables');
verify(str_contains($safe,'article-gallery-1')&&str_contains($safe,'article-gallery-2')&&substr_count($safe,'<dialog')===2,'Render independent editor galleries without AJAX');
verify(scl_article_content('<div class="ck-galleria"><input value="1 OR 1=1"></div>')==='','Reject invalid gallery identifiers');
$article=array('id_noticia'=>5,'acessos'=>3,'titulo'=>'Notícia de teste','link'=>'teste','descricao'=>'<p>Conteúdo</p>','data_criacao'=>'','img'=>'');
$r_DIR=array('page'=>'noticia','info'=>array('titulo'=>'Notícia'),'noticia'=>$article);Read::$fixtures=array('tag'=>array());
ob_start();require DIR.'includes/header.php';require DIR.'includes/topo_paginas.php';require DIR.'includes/paginas/noticia.php';$html=ob_get_clean();
verify(!str_contains($html,'jQuery')&&!str_contains($html,'magnific')&&str_contains($html,'community.js'),'Article without legacy frontend libraries');
verify(Update::$data['acessos']===4,'Preserve news access counter');
verify(!str_contains($html,'1970')&&!str_contains($html,'src=""'),'Missing article date and cover render safely');
verify(substr_count($html,'<h1>')===1&&str_contains($html,'/hospital/noticias'),'Article routes support subdirectories');
require __DIR__.'/contact-channels-checks.php';
define('SCL_PREVIEW',true);$_SERVER['REQUEST_METHOD']='POST';$_POST=$valid+array('form'=>'contato');$state=scl_process_form(array('contato'),'test',$verify,$mailer);verify(!$state['success']&&str_contains($state['errors']['form'],'prévia'),'Preview cannot send even valid-looking submissions');
$_POST=array();$r_DIR=array('page'=>'fale-conosco','info'=>array('titulo'=>'Contato'));ob_start();require DIR.'includes/header.php';require DIR.'includes/footer.php';$previewMarkup=ob_get_clean();verify(!str_contains($previewMarkup,'https://www.google.com/recaptcha/api.js'),'Preview still excludes external CAPTCHA');
echo 'OK: '.($count-$before)." community and article checks; no real email sent.\n";
