<div class="utility-bar"><div class="site-container utility-content"><span>Santa Casa de Lorena <span class="utility-motto">· Cuidar faz parte da nossa história</span></span><div><a href="<?= scl_url('institucional/portal-transparencia') ?>">Transparência</a><a href="<?= scl_url('fale-conosco') ?>">Fale conosco</a></div></div></div>
<header class="site-header">
<div class="site-container header-content">
<a class="site-brand" href="<?= scl_url() ?>" aria-label="Santa Casa de Lorena — início"><img src="<?= scl_url('resources/img/logo.svg') ?>" alt="Santa Casa de Lorena" width="170" height="74"></a>
<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-navigation" hidden><span class="menu-lines" aria-hidden="true"></span><span>Menu</span></button>
<nav class="site-navigation" id="site-navigation" aria-label="Navegação principal">
<a class="nav-home" href="<?= scl_url() ?>" <?= $isHome ? 'aria-current="page"' : '' ?>>Início</a>
<?php
$navigation = array(
'A Santa Casa' => array('institucional/sobre-a-santa-casa' => 'Nossa história', 'institucional/humanizacao' => 'Humanização', 'institucional/acoes-sociais-ambientais' => 'Ações sociais e ambientais', 'institucional/programa-nacional-seguranca' => 'Segurança do paciente', 'institucional/portal-transparencia' => 'Portal da transparência'),
'Atendimento' => array('instalacoes/pronto-atendimento' => 'Pronto atendimento SUS', 'instalacoes/clinica-emilia' => 'Clínica Emília', 'instalacoes/centro-diagnostico-por-imagem' => 'Diagnóstico por imagem', 'instalacoes/unidades-de-internacao' => 'Unidades de internação', 'instalacoes/hotelaria' => 'Hotelaria'),
'Para você' => array('servicos/convenios' => 'Convênios atendidos', 'servicos/especialidades' => 'Especialidades', 'servicos/manual-do-paciente-e-visitantes' => 'Guia do paciente e visitante', 'servicos/capacidade-instalacao-producao' => 'Estrutura e produção', 'fale-conosco?canal=contato#formulario' => 'Contato e ouvidoria', 'fale-conosco?canal=trabalhe_conosco#formulario' => 'Trabalhe conosco', 'fale-conosco?canal=pesquisa#formulario' => 'Pesquisa de atendimento', 'fale-conosco#localizacao' => 'Localização'),
);
foreach ($navigation as $label => $links): ?>
<details class="nav-dropdown"><summary><?= $label ?><span aria-hidden="true">⌄</span></summary><div class="nav-dropdown-panel">
<?php foreach ($links as $href => $text): ?><a href="<?= scl_url($href) ?>"><?= $text ?></a><?php endforeach; ?>
</div></details>
<?php endforeach; ?>
<a href="<?= scl_url('noticias') ?>">Notícias</a>
<a class="nav-donate" href="<?= scl_url('doacoes') ?>"><?= scl_icon('heart') ?> Doe</a>
<a class="nav-emendometro" href="http://www.emendasdasantacasadelorena.com.br/" target="_blank" rel="noopener noreferrer">Emendômetro <span aria-hidden="true">↗</span><span class="sr-only"> (abre em nova aba)</span></a>
<a class="scl-button small" href="https://1741prd-vivace-portal.cloudmv.com.br:432/login" target="_blank" rel="noopener noreferrer">Resultados de exames <?= scl_icon('arrow') ?><span class="sr-only"> (abre em nova aba)</span></a>
</nav>
</div>
</header>
