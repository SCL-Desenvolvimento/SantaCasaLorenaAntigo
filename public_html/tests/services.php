<?php
require __DIR__.'/facilities.php';
$before=$count;$serviceFixture=array_merge(require __DIR__.'/fixtures/facilities.php',require __DIR__.'/fixtures/services.php');
foreach(array('convenios','especialidades','capacidade-instalacao-producao','manual-do-paciente-e-visitantes') as $page){
 $html=$renderFacility($page,$serviceFixture);
 verify(!str_contains($html,'jQuery')&&!str_contains($html,'bootstrap.min')&&!str_contains($html,'owlCarousel'),'Modern service assets: '.$page);
 verify(str_contains($html,'services.css')&&substr_count($html,'<h1>')===1,'Service has one page heading');
 $empty=$renderFacility($page,array());verify(str_contains($empty,'about-empty'),'Service empty state');
 $alias=$renderFacility(str_replace('-','_',$page),$serviceFixture);verify(str_contains($alias,'services.css'),'Service alias');
}
$plans=$renderFacility('convenios',array('convenio'=>array(array('nome'=>'Plano de teste','img'=>'resources/img/convenios/amil.png'))));verify(str_contains($plans,'SINEEVALI')&&substr_count($plans,'class="facility-plan"')===2,'Preserve additional legacy plan');
$plans=$renderFacility('convenios',array('convenio'=>array(array('nome'=>'SINEEVALI','img'=>'arquivos/convenio/cas.png'))));verify(substr_count($plans,'class="facility-plan"')===1,'No duplicate legacy plan');
$specialties=$renderFacility('especialidades',$serviceFixture);verify(substr_count($specialties,'<li data-service-item>')===6&&str_contains($specialties,'Buscar especialidade'),'Specialties preserved and searchable');
$capacity=$renderFacility('capacidade-instalacao-producao',$serviceFixture);verify(str_contains($capacity,'Produção demonstrativa')&&str_contains($capacity,'sem imagens'),'Capacity record without photos retains content');
$numbers=$serviceFixture;$numbers['capacidade'][0]['descricao']='<p>123 leitos; 45.678 atendimentos em 2020.</p>';$capacity=$renderFacility('capacidade-instalacao-producao',$numbers);verify(str_contains($capacity,'123 leitos; 45.678 atendimentos em 2020.'),'Preserve quantities and reference period verbatim');
$numbers['capacidade'][0]['id_capacidade']='1 OR 1=1';$capacity=$renderFacility('capacidade-instalacao-producao',$numbers);verify(!str_contains($capacity,'data-about-gallery'),'Invalid capacity ID rejected');
$manual=$renderFacility('manual-do-paciente-e-visitantes',$serviceFixture);verify(substr_count($manual,'<details class="service-topic"')===3,'Native manual disclosures');
verify(substr_count($manual,'target="_blank" rel="noopener"')>=2,'Safe document links');
foreach($serviceFixture['download_manual_paciente'] as $download){verify(str_contains($manual,scl_link($download['pdf'])),'Manual download uses registered file');verify(is_file(DIR.$download['pdf']),'Preview PDF exists');}
$bad=array('manual_paciente'=>array(array('titulo'=>'<img src=x onerror=bad()>','descricao'=>'<p onclick="bad()">Orientação preservada</p><script>bad()</script>')),'download_manual_paciente'=>array(array('id_download_manual_paciente'=>'x','titulo'=>'Arquivo','pdf'=>'javascript:bad()')));
$safe=$renderFacility('manual-do-paciente-e-visitantes',$bad);verify(!str_contains($safe,'<img src=x')&&!str_contains($safe,'onclick=')&&!str_contains($safe,'javascript:')&&str_contains($safe,'Orientação preservada'),'Manual text and document URL sanitized');
verify(str_contains($safe,'arquivo indisponível'),'Unavailable file retains title');
$legacy=$renderFacility('manual-do-paciente-e-visitantes',array('download_manual_paciente'=>array(array('id_download_manual_paciente'=>9,'titulo'=>'Legado'))));verify(str_contains($legacy,'/hospital/servicos/manual-paciente-visitante/file-9'),'Legacy download fallback preserved');
echo 'OK: '.($count-$before)." service checks.\n";
