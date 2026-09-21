<?php
require_once __DIR__ . '/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM ' . PREFIX . 'pagina_acoes_sociais_ambientais ORDER BY data DESC LIMIT 1');
$social = ($getPagina->getResult() ?: array())[0] ?? array();
$galleryQuery = new Read();
$galleryQuery->fullRead('SELECT * FROM ' . PREFIX . 'galeria_acao ORDER BY data_criacao ASC');
$photos = array_values(array_filter($galleryQuery->getResult() ?: array(), function ($photo) {
    return !empty($photo['img']) && scl_link($photo['img']) !== '';
}));
// EXISTS avoids duplicate articles when multiple tag associations match.
$newsQuery = new Read();
$newsQuery->fullRead('SELECT N.* FROM ' . PREFIX . 'noticia AS N
    WHERE N.status = 1 AND EXISTS (
        SELECT 1 FROM ' . PREFIX . 'tag_noticia AS TN
        INNER JOIN ' . PREFIX . 'tag AS T ON T.id_tag = TN.id_tag
        WHERE TN.id_noticia = N.id_noticia AND T.url = :tag
    ) ORDER BY N.data_criacao DESC LIMIT 3', 'tag=acoes-sociais');
$relatedNews = $newsQuery->getResult() ?: array();
$featureImage = scl_link($social['img1'] ?? '');
$hasSupport = !empty($social['bloco1']) || $featureImage;
$hasVolunteering = !empty($social['bloco2']) || !empty($social['bloco3']) || !empty($social['bloco4']) || $photos;
?>
<div class="social-actions-page">
<nav class="about-section-nav site-container" aria-label="Nesta página">
    <span>Nesta página</span>
    <?php if ($hasSupport): ?><a href="#acoes-acolhimento">Apoio religioso</a><?php endif; ?>
    <?php if ($hasVolunteering): ?><a href="#acoes-voluntariado">Voluntariado</a><?php endif; ?>
    <a href="#acoes-noticias">Notícias das ações</a>
</nav>
<?php if (!$hasSupport && !$hasVolunteering): ?>
<section class="site-container section-space">
    <h2>Ações sociais e ambientais</h2>
    <p class="about-empty">As informações sobre nossas ações serão disponibilizadas nesta página. Para saber mais, <a href="<?= scl_url('fale-conosco') ?>">fale com a nossa equipe</a>.</p>
</section>
<?php endif; ?>
<?php if ($hasSupport): ?>
<section id="acoes-acolhimento" class="site-container social-support" aria-labelledby="social-support-title">
    <div class="social-support-heading"><span class="social-section-icon" aria-hidden="true"><?= scl_icon('heart') ?></span><div><span class="eyebrow">ACOLHIMENTO</span><h2 id="social-support-title">Ministro da Eucaristia e demais religiões</h2></div></div>
    <?php if (!empty($social['bloco1'])): ?><div class="about-prose social-support-copy"><?= scl_about_content($social['bloco1']) ?></div><?php endif; ?>
    <?php if ($featureImage): ?><figure class="social-feature"><img src="<?= scl_escape($featureImage) ?>" alt="Registro das ações sociais e ambientais da Santa Casa de Lorena" width="1240" height="600" loading="lazy" decoding="async"></figure><?php endif; ?>
</section>
<?php endif; ?>
<?php if ($hasVolunteering): ?>
<section id="acoes-voluntariado" class="social-volunteering" aria-labelledby="social-volunteering-title"><div class="site-container">
    <div class="section-heading"><div><span class="eyebrow">SOLIDARIEDADE E PARTICIPAÇÃO</span><h2 id="social-volunteering-title">Voluntariado</h2></div></div>
    <?php if (!empty($social['bloco2'])): ?><div class="about-prose social-volunteer-intro"><?= scl_about_content($social['bloco2']) ?></div><?php endif; ?>
    <?php if ($photos || !empty($social['bloco3'])): ?>
    <div class="social-volunteer-grid <?= !$photos || empty($social['bloco3']) ? 'social-single-column' : '' ?>">
        <?php if ($photos): ?>
        <div class="social-gallery-wrap">
            <?php $galleryId = 'social-gallery-track'; $galleryLabel = 'Galeria de voluntariado'; require __DIR__ . '/../institutional_gallery.php'; ?>
            <p class="about-gallery-hint">Selecione uma imagem para ampliar.<?php if (count($photos) > 1): ?> Use as setas para explorar a galeria.<?php endif; ?></p>
        </div>
        <?php endif; ?>
        <?php if (!empty($social['bloco3'])): ?><div class="about-prose social-volunteer-story"><?= scl_about_content($social['bloco3']) ?></div><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($social['bloco4'])): ?><div class="about-prose social-volunteer-details"><?= scl_about_content($social['bloco4']) ?></div><?php endif; ?>
</div></section>
<?php endif; ?>
<section id="acoes-noticias" class="site-container social-news" aria-labelledby="social-news-title">
    <div class="section-heading"><div><span class="eyebrow">ACOMPANHE NOSSAS AÇÕES</span><h2 id="social-news-title">Notícias das ações sociais</h2></div><a class="text-link" href="<?= scl_url('noticias/acoes-sociais') ?>">Ver notícias da categoria <?= scl_icon('arrow') ?></a></div>
    <?php if ($relatedNews): ?><div class="news-grid">
    <?php foreach ($relatedNews as $news):
        $newsImage = scl_link($news['img'] ?? '') ?: scl_url('resources/img/no-image.png');
        $newsDate = !empty($news['data_criacao']) ? strtotime($news['data_criacao']) : false;
    ?>
    <article class="news-card"><a href="<?= scl_escape(scl_url('noticias/' . ($news['link'] ?? ''))) ?>">
        <img src="<?= scl_escape($newsImage) ?>" alt="" loading="lazy" decoding="async" width="420" height="260">
        <div class="news-copy">
            <?php if ($newsDate !== false): ?><time datetime="<?= date('Y-m-d', $newsDate) ?>"><?= date('d/m/Y', $newsDate) ?></time><?php endif; ?>
            <h3><?= scl_escape($news['titulo'] ?? '') ?></h3>
            <?php if (!empty($news['subtitulo'])): ?><p><?= scl_escape(strip_tags($news['subtitulo'])) ?></p><?php endif; ?>
            <span class="text-link">Ler notícia <?= scl_icon('arrow') ?></span>
        </div>
    </a></article>
    <?php endforeach; ?></div>
    <?php else: ?><p class="empty-state">Ainda não há notícias publicadas nesta categoria. <a href="<?= scl_url('noticias') ?>">Veja as notícias da Santa Casa.</a></p><?php endif; ?>
</section>
<section class="site-container social-contact"><div><span class="eyebrow">FALE COM A SANTA CASA</span><h2>Quer saber mais sobre nossas ações?</h2><p>Entre em contato com a equipe para obter informações.</p></div><a class="scl-button" href="<?= scl_url('fale-conosco') ?>">Fale conosco <?= scl_icon('arrow') ?></a></section>
</div>
