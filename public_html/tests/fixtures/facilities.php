<?php
// Preview-only examples. Do not infer real unit names or plan coverage from fixtures.
$fixtureRoot=dirname(__DIR__,2);
$fixturePhotos=function($folder,$title) use($fixtureRoot) {
 return array_map(function($file) use($fixtureRoot,$title){return array('img'=>str_replace('\\','/',substr($file,strlen($fixtureRoot)+1)),'titulo'=>$title);},array_slice(glob($fixtureRoot.'/arquivos/'.$folder.'/2018/01/*.{png,jpg,jpeg}',GLOB_BRACE),0,6));
};
$wardPhotos=$fixturePhotos('unidade_internacao_imagem','');
foreach($wardPhotos as $i=>&$photo) $photo['id_unidade_internacao']=$i < 3 ? 1 : 2;
unset($photo);
return array(
 'pagina_centro_diagnostico_por_imagem'=>array(array('bloco1'=>'Conheça o diagnóstico por imagem.', 'bloco2'=>'<p>Texto demonstrativo: este espaço apresenta as informações cadastradas pela instituição sobre diagnóstico por imagem.</p><p>O conteúdo oficial do painel será exibido integralmente no ambiente conectado.</p>')),
 'centro_diagnostico_por_imagem'=>$fixturePhotos('centro_diagnostico_por_imagem','Diagnóstico por imagem · legenda demonstrativa'),
 'pagina_unidade_internacao'=>array(array('bloco1'=>'Conheça as unidades de internação.', 'bloco2'=>'<p>Primeiro bloco demonstrativo da apresentação institucional.</p>', 'bloco3'=>'<p>Segundo bloco demonstrativo: os textos oficiais serão apresentados aqui quando o banco estiver conectado.</p>')),
 'unidade_internacao'=>array(array('id_unidade_internacao'=>1,'titulo'=>'Unidade demonstrativa A','descricao'=>'<p>Descrição demonstrativa da primeira unidade, preservada junto de sua própria galeria.</p>'),array('id_unidade_internacao'=>2,'titulo'=>'Unidade demonstrativa B','descricao'=>'<p>Descrição demonstrativa da segunda unidade. As fotos de cada unidade ficam separadas.</p>')),
 'unidade_internacao_imagem'=>$wardPhotos,
 'convenio'=>array_map(function($file) use($fixtureRoot){return array('img'=>str_replace('\\','/',substr($file,strlen($fixtureRoot)+1)),'nome'=>'Convênio demonstrativo · '.pathinfo($file,PATHINFO_FILENAME));},array_slice(glob($fixtureRoot.'/resources/img/convenios/*.png'),0,8))
);
