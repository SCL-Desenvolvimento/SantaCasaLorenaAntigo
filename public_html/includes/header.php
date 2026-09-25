<?php
require_once __DIR__ . '/ui.php';
$isHome = empty($r_DIR);
$isAbout = in_array($r_DIR['page'] ?? '', array('sobre-a-santa-casa', 'sobre_a_santa_casa'), true);
$isHumanization = ($r_DIR['page'] ?? '') === 'humanizacao';
$isSocialActions = in_array($r_DIR['page'] ?? '', array('acoes-sociais-ambientais', 'acoes_sociais_ambientais'), true);
$isPatientSafety = in_array($r_DIR['page'] ?? '', array('programa-nacional-seguranca', 'programa_nacional_seguranca'), true);
$isTransparency = in_array($r_DIR['page'] ?? '', array('portal-transparencia', 'portal_transparencia'), true);
$isUrgentCare = in_array($r_DIR['page'] ?? '', array('pronto-atendimento', 'pronto_atendimento'), true);
$isHospitality = ($r_DIR['page'] ?? '') === 'hotelaria';
$isEmilia = in_array($r_DIR['page'] ?? '', array('clinica-emilia', 'clinica_emilia'), true);
$isFacility = in_array($r_DIR['page'] ?? '', array('centro-diagnostico-por-imagem', 'centro_diagnostico_por_imagem', 'unidades-de-internacao', 'unidades_de_internacao'), true);
$isService = in_array($r_DIR['page'] ?? '', array('convenios', 'especialidades', 'capacidade-instalacao-producao', 'capacidade_instalacao_producao', 'manual-do-paciente-e-visitantes', 'manual_do_paciente_e_visitantes'), true);
$isNewsListing = ($r_DIR['page'] ?? '') === 'noticias';
$isArticle = ($r_DIR['page'] ?? '') === 'noticia';
$isContact = in_array($r_DIR['page'] ?? '', array('fale-conosco', 'fale_conosco', 'doacoes'), true);
$pageTitle = $r_DIR['info']['titulo'] ?? ($isHome ? 'Cuidado que acolhe. Saúde que transforma.' : 'Página não encontrada');
$description = strip_tags(($isArticle ? ($r_DIR['noticia']['subtitulo'] ?? null) : null) ?? $r_DIR['info']['seo'] ?? $r_DIR['info']['descricao_pagina'] ?? 'Santa Casa de Lorena: conheça nossos serviços, encontre orientações para pacientes e acompanhe as notícias da instituição.');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#145c50">
<title><?= scl_escape(strip_tags($pageTitle)) ?> | Santa Casa de Lorena</title>
<meta name="description" content="<?= scl_escape($description) ?>">
<meta property="og:type" content="<?= $isArticle ? 'article' : 'website' ?>">
<meta property="og:title" content="<?= scl_escape(strip_tags($pageTitle)) ?> | Santa Casa de Lorena">
<meta property="og:description" content="<?= scl_escape($description) ?>">
<?php if (!empty($r_DIR['info']['imagem'])): ?>
<meta property="og:image" content="<?= scl_escape(scl_asset($r_DIR['info']['imagem'])) ?>">
<?php endif; ?>
<link rel="icon" href="<?= scl_url('favicon.ico') ?>">
<link rel="stylesheet" href="<?= scl_url('resources/css/modern.css') ?>?v=<?= filemtime(DIR . 'resources/css/modern.css') ?>">
<script src="<?= scl_url('resources/js/modern.js') ?>?v=2" defer></script>
<script src="<?= scl_url('resources/js/carousel.js') ?>?v=<?= filemtime(DIR . 'resources/js/carousel.js') ?>" defer></script>
<?php if ($isAbout || $isHumanization || $isSocialActions || $isUrgentCare || $isHospitality || $isEmilia || $isFacility || $isService || $isArticle || $isContact): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/about.css') ?>?v=<?= filemtime(DIR . 'resources/css/about.css') ?>">
<script src="<?= scl_url('resources/js/about.js') ?>?v=<?= filemtime(DIR . 'resources/js/about.js') ?>" defer></script>
<?php endif; ?>
<?php if ($isHumanization): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/humanization.css') ?>?v=1">
<?php endif; ?>
<?php if ($isSocialActions): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/social-actions.css') ?>?v=1">
<?php endif; ?>
<?php if ($isPatientSafety): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/about.css') ?>?v=<?= filemtime(DIR . 'resources/css/about.css') ?>">
<link rel="stylesheet" href="<?= scl_url('resources/css/patient-safety.css') ?>?v=1">
<?php endif; ?>
<?php if ($isTransparency): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/transparency.css') ?>?v=1">
<script src="<?= scl_url('resources/js/transparency.js') ?>?v=1" defer></script>
<?php endif; ?>
<?php if ($isUrgentCare): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/urgent-care.css') ?>?v=1">
<?php endif; ?>
<?php if ($isHospitality): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/hospitality.css') ?>?v=<?= filemtime(DIR . 'resources/css/hospitality.css') ?>">
<?php endif; ?>
<?php if ($isEmilia): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/emilia.css') ?>?v=<?= filemtime(DIR . 'resources/css/emilia.css') ?>">
<?php endif; ?>
<?php if ($isFacility || $isService): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/facilities.css') ?>?v=<?= filemtime(DIR . 'resources/css/facilities.css') ?>">
<?php endif; ?>
<?php if ($isService): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/services.css') ?>?v=1">
<script src="<?= scl_url('resources/js/services.js') ?>?v=1" defer></script>
<?php endif; ?>
<?php if ($isNewsListing || $isArticle): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/news-listing.css') ?>?v=1">
<?php endif; ?>
<?php if ($isArticle || $isContact): ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/community.css') ?>?v=2">
<script src="<?= scl_url('resources/js/community.js') ?>?v=2" defer></script>
<?php endif; ?>
</head>
<body class="scl-site <?= $isHome ? 'scl-home' : 'scl-inner' ?>">
<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>

