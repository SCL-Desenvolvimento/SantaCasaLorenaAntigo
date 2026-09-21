<?php
// Demonstration text, not clinical guidance. Never used by production templates.
$fixtureRoot = dirname(__DIR__, 2);
$files = glob($fixtureRoot . '/arquivos/programa_nacional_seguranca/*/*/*.{png,jpg,jpeg}', GLOB_BRACE);
return array('pagina_programa_nacional_seguranca'=>array(array(
    'bloco1'=>'<p>Este é um texto demonstrativo da apresentação do Programa Nacional de Segurança do Paciente. No ambiente conectado, este espaço exibe integralmente o conteúdo institucional cadastrado no painel.</p><p>A nova interface preserva os textos oficiais e sua formatação básica, com leitura adaptada ao computador e ao celular.</p>',
    'bloco2'=>"<p>As informações sobre o Núcleo de Segurança do Paciente cadastradas pela Santa Casa serão apresentadas neste espaço.</p><p>Esta prévia demonstra somente a organização visual. Não apresenta protocolos, recomendações clínicas ou políticas oficiais da instituição.</p>",
    'img1'=>isset($files[0]) ? str_replace('\\','/',substr($files[0],strlen($fixtureRoot)+1)) : ''
)));
