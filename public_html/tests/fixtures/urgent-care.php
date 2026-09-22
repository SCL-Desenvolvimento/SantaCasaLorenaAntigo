<?php
// Demonstration copy isolated from production. Images belong to the local archive.
$fixtureRoot = dirname(__DIR__, 2);
$files = array_slice(glob($fixtureRoot . '/arquivos/pronto_atendimento/*/*/*.{png,jpg,jpeg}', GLOB_BRACE), 0, 5);
return array(
 'pagina_pronto_atendimento'=>array(array(
  'bloco1'=>'Conheça o pronto atendimento SUS.',
  'bloco2'=>'<p>Texto demonstrativo: este espaço apresenta as informações sobre o pronto atendimento cadastradas pela Santa Casa.</p><p>Na versão conectada ao banco, o conteúdo institucional será exibido integralmente aqui.</p>',
  'bloco3'=>'Destaque demonstrativo: as informações complementares cadastradas pela instituição aparecem neste bloco.',
  'bloco4'=>'<p>Este texto demonstrativo representa a introdução cadastrada sobre a classificação dos atendimentos. As categorias e os tempos abaixo foram preservados da página original.</p>',
  'bloco5'=>'Observação demonstrativa: este espaço mantém as orientações complementares cadastradas no painel.'
 )),
 'pronto_atendimento'=>array_map(function($file) use ($fixtureRoot) { return array('img'=>str_replace('\\','/',substr($file,strlen($fixtureRoot)+1)), 'titulo'=>'Acervo do pronto atendimento · legenda demonstrativa'); },$files)
);
