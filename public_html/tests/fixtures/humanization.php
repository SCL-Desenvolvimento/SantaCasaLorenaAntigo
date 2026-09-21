<?php
// Isolated preview data; never loaded by production templates.
$fixtureRoot = dirname(__DIR__, 2);
$files = array_slice(glob($fixtureRoot . '/arquivos/galeria_humanizacao/*/*/*.{png,jpg,jpeg}', GLOB_BRACE), 0, 5);
return array(
    'pagina_humanizacao'=>array(array(
        'bloco1'=>'Acolher faz parte do cuidado.',
        'bloco2'=>'<p>Este é um texto demonstrativo para a apresentação da página de Humanização. No ambiente conectado, este espaço exibe o conteúdo institucional cadastrado no painel.</p><p>A nova página mantém a organização e a formatação básica dos textos originais.</p>',
        'bloco3'=>"O segundo bloco de conteúdo é apresentado com espaçamento e tamanho de letra adequados à leitura.\n\nAs informações oficiais não foram alteradas por esta prévia.",
        'bloco4'=>'<p>Este espaço apresenta as melhorias cadastradas pela instituição.</p><ul><li>Primeira melhoria cadastrada — demonstração.</li><li>Segunda melhoria cadastrada — demonstração.</li><li>Terceira melhoria cadastrada — demonstração.</li></ul>'
    )),
    'galeria_humanizacao'=>array_map(function ($file) use ($fixtureRoot) {
        return array('img'=>str_replace('\\','/',substr($file,strlen($fixtureRoot)+1)), 'descricao'=>'Acervo de humanização · legenda demonstrativa');
    }, $files)
);
