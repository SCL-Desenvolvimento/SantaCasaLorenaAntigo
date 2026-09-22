<?php
$introTitle = $r_DIR['noticia']['titulo'] ?? $r_DIR['info']['titulo'] ?? 'Página não encontrada';
$introSubtitle = $r_DIR['info']['sub_titulo'] ?? '';
$introDescription = $r_DIR['info']['descricao_pagina'] ?? '';
$articleDate = !empty($r_DIR['noticia']['data_criacao']) ? strtotime($r_DIR['noticia']['data_criacao']) : false;
$introImage = empty($r_DIR['noticia']) ? ($r_DIR['info']['imagem'] ?? '') : '';
?>
<section class="page-intro"><div class="site-container">
<nav class="breadcrumbs" aria-label="Você está aqui"><a href="<?= scl_url() ?>">Início</a><span aria-hidden="true">/</span><?php if (!empty($r_DIR['info']['sessao'])): ?><span><?= scl_escape(strip_tags($r_DIR['info']['sessao'])) ?></span><span aria-hidden="true">/</span><?php endif; ?><span aria-current="page"><?= scl_escape(strip_tags($introTitle)) ?></span></nav>
<div class="page-intro-grid <?= $introImage ? 'with-image' : '' ?>"><div><h1><?= scl_escape(strip_tags($introTitle)) ?></h1>
<?php if ($introSubtitle && empty($r_DIR['noticia'])): ?><p class="page-subtitle"><?= scl_escape(strip_tags($introSubtitle)) ?></p><?php endif; ?>
<?php if ($introDescription && empty($r_DIR['noticia'])): ?><p class="page-description"><?= scl_escape(strip_tags($introDescription)) ?></p><?php endif; ?>
<?php if (!empty($r_DIR['noticia'])): ?><p class="article-meta"><?php if ($articleDate !== false): ?><time datetime="<?= date('Y-m-d', $articleDate) ?>"><?= date('d/m/Y', $articleDate) ?></time><?php endif; ?><?php if (!empty($r_DIR['noticia']['criador'])): ?><?= $articleDate !== false ? ' · ' : '' ?> <?= scl_escape($r_DIR['noticia']['criador']) ?><?php endif; ?></p><?php endif; ?>
</div><?php if ($introImage): ?><img src="<?= scl_escape(scl_asset($introImage)) ?>" alt="" width="600" height="360"><?php endif; ?></div>
</div></section>
