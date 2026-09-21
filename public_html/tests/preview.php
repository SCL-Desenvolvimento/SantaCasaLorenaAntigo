<?php
// Local visual fixture: never connects to the production database.
if (PHP_SAPI !== 'cli-server') { http_response_code(404); exit; }
$root = dirname(__DIR__);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ((str_starts_with($path, '/resources/') && preg_match('~\\.(css|js|png|jpe?g|svg|gif|webp|woff2?|ttf|otf|ico)$~i', $path)) || $path === '/favicon.ico') return false;
chdir($root);
session_start();
// The preview must never process submissions.
$_POST = array();
define('ROOT', '/'); define('HOME', '/'); define('DIR', $root . '/'); define('PREFIX', 'scl_');
class Read {
    private $rows = array();
    public function fullRead($sql, $params = null) {
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
if ($r_DIR) http_response_code(404);
require 'includes/header.php';
echo '<aside style="background:#fff3cd;color:#55451a;padding:8px 20px;text-align:center;font:12px sans-serif">Prévia visual local · Notícias demonstrativas · Dados, envios e páginas internas não conectados</aside>';
require 'includes/navbar.php';
echo '<main id="conteudo" tabindex="-1">';
if (!$r_DIR) require 'includes/home.php';
else {require 'includes/topo_paginas.php'; require 'includes/paginas/404.php';}
echo '</main>';require 'includes/footer.php';
