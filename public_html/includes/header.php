<?php
require_once __DIR__ . '/ui.php';
$isHome = empty($r_DIR);
$pageTitle = $r_DIR['info']['titulo'] ?? ($isHome ? 'Cuidado que acolhe. Saúde que transforma.' : 'Página não encontrada');
$description = strip_tags($r_DIR['info']['seo'] ?? $r_DIR['info']['descricao_pagina'] ?? 'Santa Casa de Lorena: conheça nossos serviços, encontre orientações para pacientes e acompanhe as notícias da instituição.');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#145c50">
<title><?= scl_escape(strip_tags($pageTitle)) ?> | Santa Casa de Lorena</title>
<meta name="description" content="<?= scl_escape($description) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= scl_escape(strip_tags($pageTitle)) ?> | Santa Casa de Lorena">
<meta property="og:description" content="<?= scl_escape($description) ?>">
<?php if (!empty($r_DIR['info']['imagem'])): ?>
<meta property="og:image" content="<?= scl_escape(scl_asset($r_DIR['info']['imagem'])) ?>">
<?php endif; ?>
<link rel="icon" href="<?= scl_url('favicon.ico') ?>">
<?php if (!$isHome): // Compatibility for existing CMS forms and galleries. ?>
<link rel="stylesheet" href="<?= scl_url('resources/bootstrap/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= scl_url('resources/plugins/owlcarousel/owl.carousel.min.css') ?>">
<link rel="stylesheet" href="<?= scl_url('resources/plugins/owlcarousel/owl.theme.default.min.css') ?>">
<link rel="stylesheet" href="<?= scl_url('resources/css/style.css') ?>">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<script src="<?= scl_url('resources/plugins/jQuery/jQuery-2.1.4.min.js') ?>"></script>
<script src="<?= scl_url('resources/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?= scl_url('resources/plugins/owlcarousel/owl.carousel.min.js') ?>"></script>
<?php endif; ?>
<link rel="stylesheet" href="<?= scl_url('resources/css/modern.css') ?>?v=1">
<script src="<?= scl_url('resources/js/modern.js') ?>?v=1" defer></script>
</head>
<body class="scl-site <?= $isHome ? 'scl-home' : 'scl-inner' ?>">
<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>

