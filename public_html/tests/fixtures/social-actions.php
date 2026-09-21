<?php
// Demonstration records only. Production uses the existing database.
$fixtureRoot = dirname(__DIR__, 2);
$files = array_slice(glob($fixtureRoot . '/arquivos/galeria_acao/*/*/*.{png,jpg,jpeg}', GLOB_BRACE), 0, 4);
$features = glob($fixtureRoot . '/arquivos/acoes_sociais_ambientais/*/*/*.{png,jpg,jpeg}', GLOB_BRACE);
$relative = function ($file) use ($fixtureRoot) { return str_replace('\\', '/', substr($file, strlen($fixtureRoot) + 1)); };
return array(
    'pagina_acoes_sociais_ambientais'=>array(array(
        'bloco1'=>'<p>Este é um texto demonstrativo da seção de apoio religioso. No ambiente conectado, a página apresenta as informações cadastradas pela Santa Casa.</p><p>O conteúdo oficial é mantido sob gestão do painel administrativo.</p>',
        'img1'=>isset($features[0]) ? $relative($features[0]) : '',
        'bloco2'=>'<p>Este espaço apresenta o texto introdutório do voluntariado cadastrado no painel. A nova organização preserva seu conteúdo e facilita a leitura em diferentes telas.</p>',
        'bloco3'=>"Texto demonstrativo que acompanha a galeria de voluntariado.\n\nAs imagens pertencem ao acervo disponível no projeto; as legendas desta prévia são demonstrativas.",
        'bloco4'=>'<p>Informações complementares cadastradas pela instituição aparecem aqui.</p><ul><li>Primeira orientação — demonstração.</li><li>Segunda orientação — demonstração.</li></ul>'
    )),
    'galeria_acao'=>array_map(function ($file) use ($relative) { return array('img'=>$relative($file),'descricao'=>'Acervo de voluntariado · legenda demonstrativa'); }, $files),
    'noticia'=>array(
        array('titulo'=>'Ações junto à comunidade — demonstração', 'subtitulo'=>'Exemplo de apresentação das notícias da categoria de ações sociais.', 'link'=>'acao-demonstrativa-1','img'=>'resources/img/santa-casa-home/acoes-sociais-ambientais-img.png','data_criacao'=>'2026-09-21'),
        array('titulo'=>'Voluntariado em destaque — demonstração', 'subtitulo'=>'Os títulos, datas e imagens reais serão obtidos do conteúdo publicado no painel.', 'link'=>'acao-demonstrativa-2','img'=>'resources/img/santa-casa-home/pronto-atendimento.png','data_criacao'=>'2026-09-20'),
        array('titulo'=>'Acompanhe nossas iniciativas — demonstração', 'subtitulo'=>'Conteúdo exclusivamente demonstrativo para a prévia visual.', 'link'=>'acao-demonstrativa-3','img'=>'','data_criacao'=>'2026-09-19')
    )
);
