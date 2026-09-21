<?php
// Demonstration copy only. Production reads these fields from the existing database.
$fixtureRoot = dirname(__DIR__, 2);
$galleryFiles = array_slice(glob($fixtureRoot . '/arquivos/galeria_sobre/*/*/*.{jpg,png,jpeg}', GLOB_BRACE), 0, 3);
$gallery = array_map(function ($file) use ($fixtureRoot) {
    return array('img'=>str_replace('\\', '/', substr($file, strlen($fixtureRoot)+1)), 'descricao'=>'Acervo da Santa Casa de Lorena · imagem de demonstração');
}, $galleryFiles);
return array(
    'pagina_sobre'=>array(array(
        'bloco1'=>'Uma história de cuidado, construída por muitas mãos.',
        'bloco2'=>"<p>Esta é uma prévia de apresentação do conteúdo institucional. No site conectado, este espaço exibe o texto cadastrado pela Santa Casa.</p><p>A nova organização permite uma leitura confortável da história da instituição, tanto no computador quanto no celular.</p>",
        'bloco3'=>"Os textos originais permanecem sob gestão do painel administrativo.\n\nEste conteúdo demonstrativo não substitui a história oficial da Santa Casa.",
        'bloco4'=>'A galeria reúne imagens do acervo disponível no projeto. As legendas oficiais serão exibidas conforme o cadastro do painel.',
        'missao'=>'Texto demonstrativo: a missão institucional cadastrada será apresentada neste espaço.',
        'visao'=>'Texto demonstrativo: a visão institucional cadastrada será apresentada neste espaço.',
        'valor'=>'<ul><li>Primeiro valor cadastrado</li><li>Segundo valor cadastrado</li><li>Terceiro valor cadastrado</li></ul>',
        'provedor'=>'O histórico de provedores será apresentado na ordem cadastrada, preservando os nomes e períodos de gestão.'
    )),
    'galeria_sobre'=>$gallery,
    'provedor'=>array(
        array('nome'=>'Nome do provedor · demonstração', 'data1'=>'Período inicial', 'data2'=>'Período final', 'img'=>''),
        array('nome'=>'Outro registro · demonstração', 'data1'=>'Período inicial', 'data2'=>'Período final', 'img'=>'')
    )
);
