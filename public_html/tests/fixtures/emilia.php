<?php
// Isolated demonstration copy; production reads its existing CMS tables.
$fixtureRoot=dirname(__DIR__,2);
$files=array_slice(glob($fixtureRoot.'/arquivos/clinica_emilia/*/*/*.{png,jpg,jpeg}',GLOB_BRACE),0,5);
return array(
 'pagina_clinica_emilia'=>array(array('bloco1'=>'Conheça a Clínica Emília.', 'bloco2'=>'<p>Texto demonstrativo: este espaço apresenta o conteúdo sobre a Clínica Emília cadastrado pela instituição.</p><p>Na versão conectada ao banco, a apresentação original será exibida integralmente, preservando parágrafos e listas.</p>')),
 'clinica_emilia'=>array_map(function($file) use($fixtureRoot){return array('img'=>str_replace('\\','/',substr($file,strlen($fixtureRoot)+1)),'titulo'=>'Acervo da Clínica Emília · legenda demonstrativa');},$files)
);
