<?php
// Local visual fixture: never connects to the production database.
if (PHP_SAPI !== 'cli-server') { http_response_code(404); exit; }
$root = dirname(__DIR__);
$aboutFixtures = array_merge(require __DIR__ . '/fixtures/about.php', require __DIR__ . '/fixtures/humanization.php', require __DIR__ . '/fixtures/social-actions.php', require __DIR__ . '/fixtures/patient-safety.php');
if (($_GET['fixture'] ?? '') === 'empty') $aboutFixtures = array_fill_keys(array_keys($aboutFixtures), array());
if (($_GET['fixture'] ?? '') === 'single') $aboutFixtures['galeria_sobre'] = array_slice($aboutFixtures['galeria_sobre'], 0, 1);
if (($_GET['fixture'] ?? '') === 'single') $aboutFixtures['galeria_humanizacao'] = array_slice($aboutFixtures['galeria_humanizacao'], 0, 1);
if (($_GET['fixture'] ?? '') === 'single') $aboutFixtures['galeria_acao'] = array_slice($aboutFixtures['galeria_acao'], 0, 1);
if (($_GET['fixture'] ?? '') === 'no-image' && !empty($aboutFixtures['pagina_programa_nacional_seguranca'][0])) $aboutFixtures['pagina_programa_nacional_seguranca'][0]['img1'] = '';
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (((str_starts_with($path, '/resources/') || str_starts_with($path, '/arquivos/galeria_sobre/') || str_starts_with($path, '/arquivos/galeria_humanizacao/') || str_starts_with($path, '/arquivos/galeria_acao/') || str_starts_with($path, '/arquivos/acoes_sociais_ambientais/') || str_starts_with($path, '/arquivos/programa_nacional_seguranca/')) && preg_match('~\\.(css|js|png|jpe?g|svg|gif|webp|woff2?|ttf|otf|ico)$~i', $path)) || $path === '/favicon.ico') return false;
chdir($root);
session_start();
// The preview must never process submissions.
$_POST = array();
define('ROOT', '/'); define('HOME', '/'); define('DIR', $root . '/'); define('PREFIX', 'scl_');
class Read {
    private $rows = array();
    public function fullRead($sql, $params = null) {
        global $aboutFixtures;
        foreach ($aboutFixtures as $table=>$rows) {
            if ($table === 'noticia' && !str_contains($sql, 'T.url = :tag')) continue;
            if (str_contains($sql, PREFIX . $table)) { $this->rows=$rows; return; }
        }
        if (str_contains($sql, 'noticia')) $this->rows = array(
            array('titulo'=>'Cuidado e acolhimento em cada encontro', 'subtitulo'=>'Acompanhe as ações da nossa instituição e sua conexão com a comunidade.', 'img'=>'resources/img/santa-casa-home/pronto-atendimento.png', 'link'=>'exemplo', 'data_criacao'=>'2026-09-21'),
            array('titulo'=>'Uma história feita por pessoas', 'subtitulo'=>'Conheça a Santa Casa e as ações de humanização.', 'img'=>'resources/img/santa-casa-home/acoes-sociais-ambientais-img.png', 'link'=>'exemplo-2', 'data_criacao'=>'2026-09-20'),
            array('titulo'=>'Informações para pacientes e familiares', 'subtitulo'=>'Saiba mais sobre os serviços e orientações para sua visita.', 'img'=>'resources/img/santa-casa-home/unidade-de-internacao.png', 'link'=>'exemplo-3', 'data_criacao'=>'2026-09-19')
        );
        elseif (str_contains($sql, 'convenio')) $this->rows = array_map(fn($f)=>array('img'=>$f,'nome'=>pathinfo($f, PATHINFO_FILENAME)), array_slice(glob('resources/img/convenios/*.png'),0,8));
        else $this->rows = array();
    }
    public function getResult() {return $this->rows;}
}
$localizacao = array('localizacao'=>'Lorena · São Paulo','telefone'=>'(12) 3159-3349');
$r_DIR = $path === '/' ? null : array('page'=>'404', 'info'=>array('titulo'=>'Página não encontrada','sub_titulo'=>'Vamos ajudar você a encontrar o caminho.','descricao_pagina'=>''));
if (in_array(rtrim($path, '/'), array('/institucional/sobre-a-santa-casa','/institucional/sobre_a_santa_casa'), true)) {
    $r_DIR = array('page'=>'sobre-a-santa-casa', 'info'=>array('titulo'=>'Sobre a Santa Casa', 'sessao'=>'Institucional', 'sub_titulo'=>'Conheça nossa história e o que orienta nosso cuidado.', 'descricao_pagina'=>''));
}
if (rtrim($path, '/') === '/institucional/humanizacao') {
    $r_DIR = array('page'=>'humanizacao', 'info'=>array('titulo'=>'Humanização', 'sessao'=>'Institucional', 'sub_titulo'=>'Acolhimento e respeito em cada encontro.', 'descricao_pagina'=>''));
}
if (in_array(rtrim($path, '/'), array('/institucional/acoes-sociais-ambientais', '/institucional/acoes_sociais_ambientais'), true)) {
    $r_DIR = array('page'=>'acoes-sociais-ambientais', 'info'=>array('titulo'=>'Ações sociais e ambientais', 'sessao'=>'Institucional', 'sub_titulo'=>'Conheça as ações e o voluntariado da Santa Casa.', 'descricao_pagina'=>''));
}
if (in_array(rtrim($path, '/'), array('/institucional/programa-nacional-seguranca', '/institucional/programa_nacional_seguranca'), true)) {
    $r_DIR = array('page'=>'programa-nacional-seguranca', 'info'=>array('titulo'=>'Programa de segurança do paciente', 'sessao'=>'Institucional', 'sub_titulo'=>'Conheça o programa e o núcleo de segurança do paciente da Santa Casa.', 'descricao_pagina'=>''));
}
if (($r_DIR['page'] ?? '') === '404') http_response_code(404);
require 'includes/header.php';
echo '<aside style="background:#fff3cd;color:#55451a;padding:8px 20px;text-align:center;font:12px sans-serif">Prévia visual local · Conteúdo demonstrativo · Banco de dados e envios não conectados</aside>';
require 'includes/navbar.php';
echo '<main id="conteudo" tabindex="-1">';
if (!$r_DIR) require 'includes/home.php';
else {
    require 'includes/topo_paginas.php';
    if ($r_DIR['page'] === 'sobre-a-santa-casa') { $getPagina = new Read(); require 'includes/paginas/sobre_a_santa_casa.php'; }
    elseif ($r_DIR['page'] === 'humanizacao') { $getPagina = new Read(); require 'includes/paginas/humanizacao.php'; }
    elseif ($r_DIR['page'] === 'acoes-sociais-ambientais') { $getPagina = new Read(); require 'includes/paginas/acoes_sociais_ambientais.php'; }
    elseif ($r_DIR['page'] === 'programa-nacional-seguranca') { $getPagina = new Read(); require 'includes/paginas/programa_nacional_seguranca.php'; }
    else require 'includes/paginas/404.php';
}
echo '</main>';require 'includes/footer.php';
