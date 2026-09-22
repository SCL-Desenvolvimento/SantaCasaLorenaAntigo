<?php
require __DIR__.'/emilia.php';
$before=$count;$fixture=require __DIR__.'/fixtures/facilities.php';
$renderFacility=function($page,$data) {Read::$fixtures=$data;$getPagina=new Read();$r_DIR=array('page'=>$page,'info'=>array('titulo'=>'Teste'));ob_start();require DIR.'includes/header.php';require DIR.'includes/topo_paginas.php';require DIR.'includes/paginas/'.str_replace('-','_',$page).'.php';require DIR.'includes/footer.php';return ob_get_clean();};
foreach(array('centro-diagnostico-por-imagem','unidades-de-internacao','particular-convenio') as $page) {
 $html=$renderFacility($page,$fixture);
 verify(!str_contains($html,'jQuery') && !str_contains($html,'bootstrap.min') && !str_contains($html,'owlCarousel'),'No legacy libraries: '.$page);
 verify(str_contains($html,'facilities.css') && substr_count($html,'<h1>')===1,'Modern assets and one heading');
 verify(str_contains($html,'/hospital/fale-conosco'),'Contact URL');
 $empty=$renderFacility($page,array());verify(str_contains($empty,'about-empty'),'Empty state: '.$page);
 $alias=$renderFacility(str_replace('-','_',$page),$fixture);verify(str_contains($alias,'facilities.css') && !str_contains($alias,'jQuery'),'Underscore route');
}
Read::$queries=array();$wards=$renderFacility('unidades-de-internacao',$fixture);
verify(str_contains($wards,'Primeiro bloco') && str_contains($wards,'Segundo bloco'),'Both ward body blocks');
verify(substr_count($wards,'data-about-gallery')===2 && substr_count($wards,'<dialog')===2,'Two independent galleries');
libxml_use_internal_errors(true);$dom=new DOMDocument();$dom->loadHTML('<?xml encoding="UTF-8">'.$wards);$xp=new DOMXPath($dom);
foreach(array(0,1) as $i) {
 $links=$xp->query('//*[@id="ward-gallery-'.$i.'"]//a');verify($links->length===3,'Three images per unit');
 foreach($links as $j=>$link) verify($link->getAttribute('href')===scl_link($fixture['unidade_internacao_imagem'][$i*3+$j]['img']),'Correct image-to-unit association');
}
$imageQueries=array_values(array_filter(Read::$queries,fn($q)=>str_contains($q['sql'],'unidade_internacao_imagem ')));
verify(count($imageQueries)===2 && $imageQueries[0]['params']==='unit=1' && $imageQueries[1]['params']==='unit=2' && str_contains($imageQueries[0]['sql'],'= :unit ORDER BY data_criacao DESC'),'Bound unit IDs and original image order');
$bad=$fixture;$bad['unidade_internacao'][0]['id_unidade_internacao']='1 OR 1=1';$bad['unidade_internacao'][0]['descricao']='<p onclick="bad()">Descrição segura</p><script>bad()</script>';$safe=$renderFacility('unidades-de-internacao',$bad);
verify(!str_contains($safe,'onclick=') && !str_contains($safe,'<script>bad') && str_contains($safe,'Descrição segura'),'Sanitized description');
verify(substr_count($safe,'data-about-gallery')===1,'Invalid unit identifier does not query images');
$partial=$fixture;$partial['unidade_internacao_imagem']=array();$partialHtml=$renderFacility('unidades-de-internacao',$partial);verify(str_contains($partialHtml,'Descrição demonstrativa') && !str_contains($partialHtml,'<dialog'),'Units without photos preserve descriptions');
$diagnosis=$renderFacility('centro-diagnostico-por-imagem',$fixture);verify(str_contains($diagnosis,'conteúdo oficial') && substr_count($diagnosis,'class="about-photo-link"')===6,'Diagnostic content and gallery');
$private=$renderFacility('particular-convenio',$fixture);verify(str_contains($private,'brinquedoteca e cantinho do café') && !str_contains($private,'agenciamd.com'),'Original private copy without broken remote images');
verify(substr_count($private,'class="facility-plan"')===8,'Plans from existing CMS');
$badPlans=array('convenio'=>array(array('nome'=>'<script>bad()</script>Plano','img'=>'javascript:bad()')));$safe=$renderFacility('particular-convenio',$badPlans);verify(!str_contains($safe,'javascript:') && !str_contains($safe,'<script>bad'),'Safe plan fields');
echo 'OK: '.($count-$before)." facilities checks.\n";
