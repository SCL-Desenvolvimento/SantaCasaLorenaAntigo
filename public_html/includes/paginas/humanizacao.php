<?php
require_once __DIR__ . '/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM ' . PREFIX . 'pagina_humanizacao ORDER BY data DESC LIMIT 1');
$humanization = ($getPagina->getResult() ?: array())[0] ?? array();
$galleryQuery = new Read();
$galleryQuery->fullRead('SELECT * FROM ' . PREFIX . 'galeria_humanizacao ORDER BY data_criacao ASC');
$photos = array_values(array_filter($galleryQuery->getResult() ?: array(), function ($photo) {
    return !empty($photo['img']) && scl_link($photo['img']) !== '';
}));
$hasIntroduction = !empty($humanization['bloco1']) || !empty($humanization['bloco2']) || !empty($humanization['bloco3']);
$hasEvolution = !empty($humanization['bloco4']);
?>
<div class="humanization-page">
<?php if ($hasIntroduction || $photos || $hasEvolution): ?>
<nav class="about-section-nav site-container" aria-label="Nesta página">
    <span>Nesta página</span>
    <?php if ($hasIntroduction): ?><a href="#humanizacao-cuidado">Nosso cuidado</a><?php endif; ?>
    <?php if ($photos): ?><a href="#humanizacao-galeria">Humanização em imagens</a><?php endif; ?>
    <?php if ($hasEvolution): ?><a href="#humanizacao-evolucao">Sempre evoluindo</a><?php endif; ?>
</nav>
<?php else: ?>
<section class="site-container section-space">
    <h2>Humanização na Santa Casa</h2>
    <p class="about-empty">As informações sobre humanização serão disponibilizadas nesta página. Para saber mais, <a href="<?= scl_url('fale-conosco') ?>">fale com a nossa equipe</a>.</p>
</section>
<?php endif; ?>
<?php if ($hasIntroduction): ?>
<section id="humanizacao-cuidado" class="site-container humanization-intro" aria-labelledby="humanization-title">
    <div class="humanization-intro-heading">
        <span class="humanization-symbol" aria-hidden="true"><?= scl_icon('heart') ?></span>
        <div><span class="eyebrow">NOSSO CUIDADO</span>
            <h2 id="humanization-title"><?= scl_cms_heading($humanization['bloco1'] ?? '', 'Humanização na Santa Casa') ?></h2><?= scl_cms_introduction($humanization['bloco1'] ?? '') ?>
        </div>
    </div>
    <div class="about-story-columns">
        <?php foreach (array('bloco2', 'bloco3') as $field): if (!empty($humanization[$field])): ?>
        <div class="about-prose"><?= scl_about_content($humanization[$field]) ?></div>
        <?php endif; endforeach; ?>
    </div>
</section>
<?php endif; ?>
<?php if ($photos): ?>
<section id="humanizacao-galeria" class="humanization-gallery-section" aria-labelledby="humanization-gallery-title">
    <div class="site-container humanization-gallery-layout">
        <div class="humanization-gallery-copy">
            <span class="eyebrow">GESTOS QUE ACOLHEM</span>
            <h2 id="humanization-gallery-title">Humanização em imagens.</h2>
            <p>Conheça os registros de humanização da Santa Casa de Lorena.</p>
            <p class="about-gallery-hint">Selecione uma imagem para ampliar.<?php if (count($photos) > 1): ?> Use as setas para explorar a galeria.<?php endif; ?></p>
        </div>
        <?php $galleryId = 'humanization-gallery-track'; $galleryLabel = 'Galeria de humanização'; require __DIR__ . '/../institutional_gallery.php'; ?>
    </div>
</section>
<?php endif; ?>
<?php if ($hasEvolution): ?>
<section id="humanizacao-evolucao" class="site-container humanization-evolution" aria-labelledby="humanization-evolution-title">
    <div class="humanization-evolution-heading">
        <span class="eyebrow">UM COMPROMISSO CONTÍNUO</span>
        <h2 id="humanization-evolution-title">Sempre evoluindo</h2>
        <p>A partir de 2010, várias melhorias foram criadas pela instituição com o objetivo de promover a humanização.</p>
    </div>
    <div class="about-prose humanization-evolution-content"><?= scl_about_content($humanization['bloco4']) ?></div>
</section>
<?php endif; ?>
<section class="site-container humanization-contact">
    <div><span class="eyebrow">ESTAMOS AQUI PARA OUVIR</span><h2>Conte com a nossa equipe.</h2><p>Encontre orientações para pacientes e visitantes ou entre em contato com a Santa Casa.</p></div>
    <div class="hero-actions"><a class="scl-button" href="<?= scl_url('fale-conosco') ?>">Fale conosco <?= scl_icon('arrow') ?></a><a class="text-link" href="<?= scl_url('servicos/manual-do-paciente-e-visitantes') ?>">Guia do paciente e visitante <?= scl_icon('arrow') ?></a></div>
</section>
</div>
