<?php
// Local visual fixture: never connects to the production database.
if (PHP_SAPI !== 'cli-server') { http_response_code(404); exit; }
$root = dirname(__DIR__);
$aboutFixtures = array_merge(require __DIR__ . '/fixtures/about.php', require __DIR__ . '/fixtures/humanization.php', require __DIR__ . '/fixtures/social-actions.php', require __DIR__ . '/fixtures/patient-safety.php', require __DIR__ . '/fixtures/urgent-care.php', require __DIR__ . '/fixtures/hospitality.php', require __DIR__ . '/fixtures/emilia.php', require __DIR__ . '/fixtures/facilities.php', require __DIR__ . '/fixtures/services.php');
if (($_GET['fixture'] ?? '') === 'empty') $aboutFixtures = array_fill_keys(array_keys($aboutFixtures), array());
if (($_GET['fixture'] ?? '') === 'single') $aboutFixtures['galeria_sobre'] = array_slice($aboutFixtures['galeria_sobre'], 0, 1);
if (($_GET['fixture'] ?? '') === 'single') $aboutFixtures['galeria_humanizacao'] = array_slice($aboutFixtures['galeria_humanizacao'], 0, 1);
if (($_GET['fixture'] ?? '') === 'single') $aboutFixtures['galeria_acao'] = array_slice($aboutFixtures['galeria_acao'], 0, 1);
if (($_GET['fixture'] ?? '') === 'no-image' && !empty($aboutFixtures['pagina_programa_nacional_seguranca'][0])) $aboutFixtures['pagina_programa_nacional_seguranca'][0]['img1'] = '';
if (($_GET['fixture'] ?? '') === 'single') $aboutFixtures['pronto_atendimento'] = array_slice($aboutFixtures['pronto_atendimento'], 0, 1);
if (($_GET['fixture'] ?? '') === 'single') $aboutFixtures['hotelaria'] = array_slice($aboutFixtures['hotelaria'], 0, 1);
if (($_GET['fixture'] ?? '') === 'single') $aboutFixtures['clinica_emilia'] = array_slice($aboutFixtures['clinica_emilia'], 0, 1);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (((str_starts_with($path, '/resources/') || str_starts_with($path, '/arquivos/capacidade_imagem/') || str_starts_with($path, '/arquivos/centro_diagnostico_por_imagem/') || str_starts_with($path, '/arquivos/unidade_internacao_imagem/') || str_starts_with($path, '/arquivos/convenio/') || str_starts_with($path, '/arquivos/clinica_emilia/') || str_starts_with($path, '/arquivos/hotelaria/') || str_starts_with($path, '/arquivos/pronto_atendimento/') || str_starts_with($path, '/arquivos/galeria_sobre/') || str_starts_with($path, '/arquivos/galeria_humanizacao/') || str_starts_with($path, '/arquivos/galeria_acao/') || str_starts_with($path, '/arquivos/acoes_sociais_ambientais/') || str_starts_with($path, '/arquivos/programa_nacional_seguranca/')) && preg_match('~\\.(css|js|png|jpe?g|svg|gif|webp|woff2?|ttf|otf|ico)$~i', $path)) || $path === '/favicon.ico') return false;
if (str_starts_with($path, '/includes/paginas/transparencia/') && !str_contains(rawurldecode($path), '..') && preg_match('~\.(pdf|docx?|xlsx?|ods|csv|pptx?|odt|rtf|txt|zip)$~i', $path)) return false;
if (str_starts_with($path, '/arquivos/download_manual_paciente/') && !str_contains(rawurldecode($path), '..') && preg_match('~\.pdf$~i', $path)) return false;
chdir($root);
session_start();
// The preview must never process submissions.
$_POST = array();
define('SCL_PREVIEW', true);
define('ROOT', '/'); define('HOME', '/'); define('DIR', $root . '/'); define('PREFIX', 'scl_');
require_once $root.'/includes/contact_routes.php';
scl_redirect_contact(basename(rtrim($path, '/')));
class Read {
    private $rows = array();
    public function fullRead($sql, $params = null) {
        global $aboutFixtures, $previewNewsRows;
        if (str_contains($sql, 'SELECT DISTINCT N.*')) { $items=require __DIR__.'/fixtures/news-listing.php'; parse_str($params ?? '', $values); $this->rows=array_slice(array_values(array_filter($items,fn($item)=>(int)$item['id_noticia'] !== (int)($values['article'] ?? 0))),0,3);return; }
        if (str_contains($sql, 'SELECT T.*')) { $this->rows=array(array('id_tag'=>1,'nome'=>'Institucional','url'=>'institucional')); return; }
        if (str_contains($sql, PREFIX.'galeria_anexo')) { $this->rows=array(array('url'=>'resources/img/santa-casa-home/pronto-atendimento.png','descricao'=>'Imagem demonstrativa da Santa Casa'),array('url'=>'resources/img/santa-casa-home/unidade-de-internacao.png','descricao'=>'Ambientes de cuidado'));return; }
        if (str_contains($sql, PREFIX.'pagina_doacao')) { $this->rows=array(array('bloco1'=>'Cada gesto ajuda a cuidar de mais pessoas','bloco2'=>'Apoie a Santa Casa de Lorena e fortaleça o cuidado com a nossa comunidade.','bloco3'=>'Prévia demonstrativa. Consulte a instituição para obter os dados bancários oficiais.'));return; }
        if (str_contains($sql, 'AS listing_total')) { $this->rows=array(array('listing_total'=>count($previewNewsRows ?? array())));return; }
        if (str_contains($sql, 'SELECT DISTINCT T.nome')) { $this->rows=array(array('nome'=>'Institucional','url'=>'institucional'),array('nome'=>'Ações sociais','url'=>'acoes-sociais'));return; }
        if (str_contains($sql, PREFIX.'unidade_internacao_imagem ')) {
            parse_str($params ?? '', $queryParams);
            $this->rows = array_values(array_filter($aboutFixtures['unidade_internacao_imagem'] ?? array(), fn($row)=>(string)$row['id_unidade_internacao'] === (string)($queryParams['unit'] ?? ''))); return;
        }
        if (str_contains($sql, PREFIX.'capacidade_imagem ')) { parse_str($params ?? '', $queryParams); $this->rows=array_values(array_filter($aboutFixtures['capacidade_imagem'] ?? array(),fn($row)=>(string)$row['id_capacidade']===(string)($queryParams['unit'] ?? '')));return; }
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
if (in_array(rtrim($path, '/'), array('/institucional/portal-transparencia','/institucional/portal_transparencia'), true)) {
    $r_DIR = array('page'=>'portal-transparencia', 'info'=>array('titulo'=>'Portal da transparência', 'sessao'=>'Institucional', 'sub_titulo'=>'Acesso à informação. Compromisso com a comunidade.', 'descricao_pagina'=>''));
}
if (in_array(rtrim($path, '/'), array('/instalacoes/pronto-atendimento', '/instalacoes/pronto_atendimento'), true)) {
    $r_DIR = array('page'=>'pronto-atendimento', 'info'=>array('titulo'=>'Pronto atendimento SUS', 'sessao'=>'Atendimento', 'sub_titulo'=>'Conheça o atendimento, a classificação e o espaço da Santa Casa.', 'descricao_pagina'=>''));
}
if (rtrim($path, '/') === '/instalacoes/hotelaria') {
    $r_DIR = array('page'=>'hotelaria', 'info'=>array('titulo'=>'Hotelaria', 'sessao'=>'Atendimento', 'sub_titulo'=>'Conheça a hotelaria e os ambientes da Santa Casa.', 'descricao_pagina'=>''));
}
if (in_array(rtrim($path, '/'), array('/instalacoes/clinica-emilia','/instalacoes/clinica_emilia'), true)) {
    $r_DIR = array('page'=>'clinica-emilia', 'info'=>array('titulo'=>'Clínica Emília', 'sessao'=>'Atendimento', 'sub_titulo'=>'Conheça a Clínica Emília e seus ambientes.', 'descricao_pagina'=>''));
}
foreach (array('centro-diagnostico-por-imagem'=>'Diagnóstico por imagem', 'unidades-de-internacao'=>'Unidades de internação', 'particular-convenio'=>'Particular / Convênio') as $slug=>$title) {
    if (in_array(rtrim($path, '/'), array('/instalacoes/'.$slug, '/instalacoes/'.str_replace('-', '_', $slug)), true)) $r_DIR = array('page'=>$slug, 'info'=>array('titulo'=>$title, 'sessao'=>'Atendimento', 'sub_titulo'=>'Conheça os serviços e encontre informações para seu atendimento.', 'descricao_pagina'=>''));
}
foreach(array('convenios'=>'Convênios','especialidades'=>'Especialidades','capacidade-instalacao-producao'=>'Capacidade de instalação e produção','manual-do-paciente-e-visitantes'=>'Manual do paciente e visitante') as $slug=>$title){if(in_array(rtrim($path,'/'),array('/servicos/'.$slug,'/servicos/'.str_replace('-','_',$slug)),true))$r_DIR=array('page'=>$slug,'info'=>array('titulo'=>$title,'sessao'=>'Para você','sub_titulo'=>'Encontre informações da Santa Casa para seu atendimento.','descricao_pagina'=>''));}
if (preg_match('~^/noticias(?:/(institucional|acoes-sociais))?(?:/([0-9]+))?/?$~', $path, $newsMatch)) {
    $previewNewsTag=$newsMatch[1] ?? '';$previewNewsPage=max(1,(int)($newsMatch[2] ?? 1));
    $previewNewsRows=require __DIR__.'/fixtures/news-listing.php';
    if($previewNewsTag!=='')$previewNewsRows=array_values(array_filter($previewNewsRows,fn($row)=>$row['url_tag']===$previewNewsTag));
    if(($_GET['fixture'] ?? '')==='empty')$previewNewsRows=array();
    $Url=new class($previewNewsTag) { private $tag; public function __construct($tag){$this->tag=$tag;} public function getNoticiaTag(){return $this->tag;} };
    $r_DIR=array('page'=>'noticias','info'=>array('titulo'=>'Notícias','sub_titulo'=>'Acompanhe as novidades da Santa Casa.','descricao_pagina'=>''),'noticias'=>array_slice($previewNewsRows,($previewNewsPage-1)*5,5),'limit'=>5,'paginacao'=>array('pag'=>$previewNewsPage),'termos'=>'SELECT * FROM '.PREFIX.'noticia LIMIT :limit OFFSET :offset','places'=>'limit=5&offset='.(($previewNewsPage-1)*5));
}
if (in_array(rtrim($path,'/'),array('/fale-conosco','/fale_conosco','/doacoes'),true)) {
    $isDonation=str_contains($path,'doacoes');
    $r_DIR=array('page'=>$isDonation?'doacoes':'fale-conosco','info'=>array('titulo'=>$isDonation?'Doações':'Fale conosco','sub_titulo'=>$isDonation?'Sua solidariedade fortalece o cuidado.':'Um espaço para ouvir, acolher e orientar.'));
}
if (preg_match('~^/noticias/noticia-demonstrativa-([0-9]+)/?$~',$path,$match)) {
    $items=require __DIR__.'/fixtures/news-listing.php'; $item=$items[max(0,min(count($items)-1,(int)$match[1]-1))];
    $item['id_noticia']=(int)$match[1];$item['criador']='Comunicação · Conteúdo demonstrativo';
    $item['descricao']='<p>Esta é uma notícia demonstrativa para conferir a experiência de leitura da Santa Casa de Lorena.</p><h2>Cuidado que se constrói em comunidade</h2><p>Informação, acolhimento e participação aproximam a instituição das pessoas. Este espaço reúne os registros das ações e novidades da Santa Casa.</p><blockquote>Uma história feita de pessoas e de cuidado.</blockquote><p>Veja os registros desta publicação na galeria abaixo.</p><div class="ck-galleria"><input value="1"></div><h2>Acompanhe a Santa Casa</h2><p>Encontre outras publicações na <a href="noticias">listagem de notícias</a>.</p>';
    if(($_GET['fixture']??'')==='empty'){$item['img']='';$item['data_criacao']='';$item['descricao']='';}
    $r_DIR=array('page'=>'noticia','info'=>array('titulo'=>$item['titulo'],'sessao'=>'Notícias'),'noticia'=>$item);
}
if (($r_DIR['page'] ?? '') === '404') http_response_code(404);
require 'includes/header.php';
echo '<aside style="background:#fff3cd;color:#55451a;padding:8px 20px;text-align:center;font:12px sans-serif">Prévia visual local · ' . (($r_DIR['page'] ?? '') === 'portal-transparencia' ? 'Acervo real de arquivos locais' : 'Conteúdo demonstrativo') . ' · Banco de dados e envios não conectados</aside>';
require 'includes/navbar.php';
echo '<main id="conteudo" tabindex="-1">';
if (!$r_DIR) require 'includes/home.php';
else {
    require 'includes/topo_paginas.php';
    if ($r_DIR['page'] === 'sobre-a-santa-casa') { $getPagina = new Read(); require 'includes/paginas/sobre_a_santa_casa.php'; }
    elseif ($r_DIR['page'] === 'humanizacao') { $getPagina = new Read(); require 'includes/paginas/humanizacao.php'; }
    elseif ($r_DIR['page'] === 'acoes-sociais-ambientais') { $getPagina = new Read(); require 'includes/paginas/acoes_sociais_ambientais.php'; }
    elseif ($r_DIR['page'] === 'programa-nacional-seguranca') { $getPagina = new Read(); require 'includes/paginas/programa_nacional_seguranca.php'; }
    elseif ($r_DIR['page'] === 'portal-transparencia') require 'includes/paginas/portal_transparencia.php';
    elseif ($r_DIR['page'] === 'pronto-atendimento') { $getPagina = new Read(); require 'includes/paginas/pronto_atendimento.php'; }
    elseif ($r_DIR['page'] === 'hotelaria') { $getPagina = new Read(); require 'includes/paginas/hotelaria.php'; }
    elseif ($r_DIR['page'] === 'clinica-emilia') { $getPagina = new Read(); require 'includes/paginas/clinica_emilia.php'; }
    elseif (in_array($r_DIR['page'], array('centro-diagnostico-por-imagem','unidades-de-internacao','particular-convenio'), true)) { $getPagina = new Read(); require 'includes/paginas/'.str_replace('-', '_', $r_DIR['page']).'.php'; }
    elseif(in_array($r_DIR['page'],array('convenios','especialidades','capacidade-instalacao-producao','manual-do-paciente-e-visitantes'),true)){$getPagina=new Read();require 'includes/paginas/'.str_replace('-','_',$r_DIR['page']).'.php';}
    elseif(in_array($r_DIR['page'],array('noticia','fale-conosco','doacoes'),true))require 'includes/paginas/'.str_replace('-','_',$r_DIR['page']).'.php';
    elseif($r_DIR['page']==='noticias')require 'includes/paginas/noticias.php';
    else require 'includes/paginas/404.php';
}
echo '</main>';require 'includes/footer.php';
