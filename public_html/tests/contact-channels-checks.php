<?php
$channelsBefore = $count;
require_once DIR.'includes/contact_routes.php';
require_once DIR.'includes/ouvidoria_queries.php';
foreach (array('ouvidoria'=>'contato','trabalhe_conosco'=>'trabalhe_conosco','pesquisa_atendimento'=>'pesquisa','localizacao'=>'localizacao') as $entry=>$channel) {
    verify(scl_contact_redirect_target($entry) === scl_contact_url($channel), 'Historical contact destination: '.$entry);
    verify(scl_contact_redirect_target(str_replace('_','-',$entry)) === scl_contact_url($channel), 'Hyphenated contact alias: '.$entry);
    verify(str_starts_with(scl_contact_url($channel), '/hospital/fale-conosco'), 'Subdirectory retained: '.$entry);
}
verify(scl_contact_redirect_target('fale-conosco') === null && scl_contact_redirect_target('noticias') === null, 'Canonical contact and unrelated pages do not redirect');
$_SERVER['REQUEST_METHOD']='GET';$_POST=array();$_GET=array();
foreach (array('fale-conosco','fale_conosco','doacoes','noticias') as $page) {
    $r_DIR=array('page'=>$page,'info'=>array('titulo'=>'Teste'));
    ob_start();require DIR.'includes/header.php';require DIR.'includes/footer.php';$markup=ob_get_clean();
    verify(substr_count($markup,'https://www.google.com/recaptcha/api.js') === ($page === 'noticias' ? 0 : 1), 'CAPTCHA loaded exactly once only where required: '.$page);
    verify(!str_contains($markup,'jQuery-2.1.4'), 'CAPTCHA does not require legacy jQuery: '.$page);
}
foreach(array('contato','trabalhe_conosco','pesquisa') as $channel) {
    $_GET=array('canal'=>$channel);$_SESSION['tb']=true;$_SESSION['pa']=true;
    ob_start();require DIR.'includes/paginas/fale_conosco.php';$markup=ob_get_clean();
    verify(str_contains($markup,'name="form" value="'.$channel.'"'), 'Direct channel selects correct form: '.$channel);
    verify(!isset($_SESSION['tb']) && !isset($_SESSION['pa']), 'Historical session flags cleared');
}
$_GET=array();$_SESSION['tb']=true;
ob_start();require DIR.'includes/paginas/fale_conosco.php';$markup=ob_get_clean();
verify(str_contains($markup,'name="form" value="contato"'), 'Stale session does not override direct contact');
verify(str_contains($markup,'id="localizacao" tabindex="-1"'), 'Location has an accessible anchor target');
$r_DIR=array('page'=>'fale-conosco','info'=>array('titulo'=>'Contato'));$needsLegacy=false;
ob_start();require DIR.'includes/navbar.php';require DIR.'includes/footer.php';$markup=ob_get_clean();
foreach(array('contato','trabalhe_conosco','pesquisa','localizacao') as $channel)verify(substr_count($markup,scl_escape(scl_contact_url($channel)))>=2,'Menu and footer share direct link: '.$channel);
[$sql,$params]=scl_ouvidoria_query(array(),true);
verify(str_contains($sql,'FROM scl_ouvidoria AS C')&&!str_contains($sql,'scl_contato')&&str_contains($sql,'data_formatada'),'Admin list uses Ouvidoria with expected date field');
[$sql,$params]=scl_ouvidoria_query(array('data_inicio'=>'22/09/2026','data_fim'=>'22/09/2026'));
parse_str($params,$dates);
verify($dates['start']==='2026-09-22 00:00:00'&&$dates['end']==='2026-09-22 23:59:59','Report includes the entire selected day');
verify(str_contains($sql,'C.data_cadastro >= :start')&&str_contains($sql,'C.data_cadastro <= :end')&&!str_contains($sql,'22/09'),'Report dates are parameterized against the persisted column');
foreach(array(array('data_inicio'=>'31/02/2026'),array('data_fim'=>array('bad')),array('data_inicio'=>'23/09/2026','data_fim'=>'22/09/2026'),array('data_inicio'=>"01/01/2026' OR 1=1")) as $filter){$rejected=false;try{scl_ouvidoria_query($filter);}catch(InvalidArgumentException $error){$rejected=true;}verify($rejected,'Reject malformed or reversed report dates');}
foreach(array('data_inicio','data_fim') as $field){[$sql,$params]=scl_ouvidoria_query(array($field=>'01/01/2026'));parse_str($params,$dates);verify(count($dates)===1,'Support one-sided date filter: '.$field);}
$_GET=array();
echo 'OK: '.($count-$channelsBefore)." contact-channel checks.\n";
