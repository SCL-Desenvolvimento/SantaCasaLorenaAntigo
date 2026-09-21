<?php
require_once __DIR__ . '/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM ' . PREFIX . 'pagina_sobre ORDER BY data DESC LIMIT 1');
$about = ($getPagina->getResult() ?: array())[0] ?? array();
$galleryQuery = new Read();
$galleryQuery->fullRead('SELECT * FROM ' . PREFIX . 'galeria_sobre ORDER BY data_criacao ASC');
$photos = array_values(array_filter($galleryQuery->getResult() ?: array(), function ($photo) { return !empty($photo['img']) && scl_link($photo['img']) !== ''; }));
$providersQuery = new Read();
$providersQuery->fullRead('SELECT * FROM ' . PREFIX . 'provedor ORDER BY data2 ASC');
$providers = $providersQuery->getResult() ?: array();
$hasHistory = !empty($about['bloco1']) || !empty($about['bloco2']) || !empty($about['bloco3']);
$hasGallery = $photos || !empty($about['bloco4']);
$hasPurpose = !empty($about['missao']) || !empty($about['visao']) || !empty($about['valor']);
$hasProviders = $providers || !empty($about['provedor']);
?>
<div class="about-page">
<?php if ($hasHistory || $hasGallery || $hasPurpose || $hasProviders): ?>
<nav class="about-section-nav site-container" aria-label="Nesta página"><span>Nesta página</span>
<?php foreach (array('historia'=>array($hasHistory,'Nossa história'),'memoria'=>array($hasGallery,'Nossa memória'),'essencia'=>array($hasPurpose,'Missão, visão e valores'),'provedores'=>array($hasProviders,'Provedores')) as $id=>$section): if ($section[0]): ?><a href="#<?= $id ?>"><?= $section[1] ?></a><?php endif; endforeach; ?>
</nav>
<?php else: ?>
<section class="site-container section-space"><h2>Conheça a Santa Casa</h2><p class="about-empty">As informações institucionais serão disponibilizadas nesta página. Para saber mais, <a href="<?= scl_url('fale-conosco') ?>">fale com a nossa equipe</a>.</p></section>
<?php endif; ?>
<?php if ($hasHistory): ?>
<section id="historia" class="site-container about-history" aria-labelledby="history-title"><div class="about-history-heading"><span class="eyebrow">NOSSA HISTÓRIA</span><h2 id="history-title"><?= !empty($about['bloco1']) ? scl_escape(strip_tags($about['bloco1'])) : 'Sobre a Santa Casa' ?></h2></div><div class="about-story-columns"><?php foreach (array('bloco2','bloco3') as $block): if (!empty($about[$block])): ?><div class="about-prose"><?= scl_about_content($about[$block]) ?></div><?php endif; endforeach; ?></div></section>
<?php endif; ?>
<?php if ($hasGallery): ?>
<section id="memoria" class="about-memory" aria-labelledby="memory-title"><div class="site-container about-memory-grid <?= !$photos ? 'without-gallery' : '' ?>">
<div class="about-memory-copy"><span class="eyebrow">NOSSA MEMÓRIA</span><h2 id="memory-title">Uma história em imagens.</h2><?php if (!empty($about['bloco4'])): ?><div class="about-prose"><?= scl_about_content($about['bloco4']) ?></div><?php endif; ?><?php if ($photos): ?><p class="about-gallery-hint">Selecione uma imagem para ampliar.</p><?php endif; ?></div>
<?php if ($photos): ?>
<div class="about-gallery" data-about-gallery><div class="about-gallery-track" id="about-gallery-track" tabindex="0" role="region" aria-label="Galeria da Santa Casa; role horizontalmente para ver as imagens">
<?php foreach ($photos as $index=>$photo): $caption = trim(strip_tags($photo['descricao'] ?? '')); ?>
<figure class="about-slide"><a class="about-photo-link" href="<?= scl_escape(scl_link($photo['img'])) ?>" aria-label="Ampliar imagem <?= $index + 1 ?><?= $caption ? ': ' . scl_escape($caption) : '' ?>"><img src="<?= scl_escape(scl_link($photo['img'])) ?>" alt="<?= scl_escape($caption ?: 'Registro da Santa Casa de Lorena') ?>" width="800" height="560" loading="lazy" decoding="async"><span class="about-zoom" aria-hidden="true"><?= scl_icon('search') ?></span></a><?php if ($caption): ?><figcaption><?= scl_escape($caption) ?></figcaption><?php endif; ?></figure>
<?php endforeach; ?>
</div><div class="about-gallery-controls" hidden><button type="button" data-gallery-prev aria-label="Imagem anterior" aria-controls="about-gallery-track">←</button><span data-gallery-count aria-live="polite" aria-atomic="true">1 de <?= count($photos) ?></span><button type="button" data-gallery-next aria-label="Próxima imagem" aria-controls="about-gallery-track">→</button></div></div>
<dialog class="about-lightbox" aria-label="Imagem ampliada da Santa Casa"><div class="about-lightbox-toolbar"><span data-dialog-count aria-live="polite" aria-atomic="true"></span><button type="button" data-dialog-close autofocus>Fechar <span aria-hidden="true">×</span></button></div><figure><img data-dialog-image alt=""><figcaption data-dialog-caption></figcaption></figure><div class="about-dialog-controls"><button type="button" data-dialog-prev aria-label="Imagem anterior">← Anterior</button><button type="button" data-dialog-next aria-label="Próxima imagem">Próxima →</button></div></dialog>
<?php endif; ?>
</div></section>
<?php endif; ?>
<?php if ($hasPurpose): ?>
<section id="essencia" class="site-container about-purpose" aria-labelledby="purpose-title"><div class="section-heading"><div><span class="eyebrow">NOSSA ESSÊNCIA</span><h2 id="purpose-title">O que orienta nosso cuidado.</h2></div></div><div class="about-purpose-grid">
<?php foreach (array('missao'=>array('Missão','cross'),'visao'=>array('Visão','people'),'valor'=>array('Valores','heart')) as $field=>$item): if (!empty($about[$field])): ?>
<article class="about-purpose-card"><span class="about-purpose-icon"><?= scl_icon($item[1]) ?></span><h3><?= $item[0] ?></h3><div class="about-prose"><?= scl_about_content($about[$field]) ?></div></article>
<?php endif; endforeach; ?></div></section>
<?php endif; ?>
<?php if ($hasProviders): ?>
<section id="provedores" class="about-providers" aria-labelledby="providers-title"><div class="site-container"><div class="section-heading"><div><span class="eyebrow">PESSOAS QUE FAZEM HISTÓRIA</span><h2 id="providers-title">Nossos provedores</h2></div></div><?php if (!empty($about['provedor'])): ?><div class="about-prose about-providers-intro"><?= scl_about_content($about['provedor']) ?></div><?php endif; ?><div class="about-provider-grid">
<?php foreach ($providers as $provider): $portrait = scl_link($provider['img'] ?? ''); $period = implode(' — ', array_filter(array(trim((string) ($provider['data1'] ?? '')),trim((string) ($provider['data2'] ?? ''))), 'strlen')); ?>
<article class="about-provider"><?php if ($portrait): ?><img src="<?= scl_escape($portrait) ?>" alt="<?= scl_escape($provider['nome'] ?? '') ?>" loading="lazy" decoding="async" width="120" height="140"><?php else: ?><span class="about-provider-placeholder" aria-hidden="true"><?= scl_icon('people') ?></span><?php endif; ?><div><?php if ($period): ?><p class="about-provider-period"><?= scl_escape($period) ?></p><?php endif; ?><h3><?= scl_escape($provider['nome'] ?? '') ?></h3></div></article>
<?php endforeach; ?></div></div></section>
<?php endif; ?>
<section class="site-container about-contact"><div><span class="eyebrow">CONTINUE CONHECENDO A SANTA CASA</span><h2>Transparência e diálogo com você.</h2></div><div class="hero-actions"><a class="scl-button" href="<?= scl_url('institucional/portal-transparencia') ?>">Portal da transparência <?= scl_icon('arrow') ?></a><a class="text-link" href="<?= scl_url('fale-conosco') ?>">Fale conosco <?= scl_icon('arrow') ?></a></div></section>
</div>
