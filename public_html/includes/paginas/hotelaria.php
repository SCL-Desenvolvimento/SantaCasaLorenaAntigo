<?php
require_once __DIR__ . '/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM ' . PREFIX . 'pagina_hotelaria ORDER BY data DESC LIMIT 1');
$hospitality = ($getPagina->getResult() ?: array())[0] ?? array();
$galleryQuery = new Read();
$galleryQuery->fullRead('SELECT * FROM ' . PREFIX . 'hotelaria ORDER BY data_criacao ASC');
$photos = array();
foreach ($galleryQuery->getResult() ?: array() as $photo) {
    if (empty($photo['img']) || scl_link($photo['img']) === '') continue;
    $photo['descricao'] = $photo['titulo'] ?? $photo['descricao'] ?? '';
    $photos[] = $photo;
}
$hasContent = !empty($hospitality['bloco1']) || !empty($hospitality['bloco2']);
?>
<div class="hospitality-page">
<nav class="about-section-nav site-container" aria-label="Nesta página">
    <span>Nesta página</span>
    <a href="#hotelaria-apresentacao">Sobre a hotelaria</a>
    <?php if ($photos): ?><a href="#hotelaria-galeria">Conheça os ambientes</a><?php endif; ?>
    <a href="#hotelaria-orientacoes">Informações e contato</a>
</nav>
<section id="hotelaria-apresentacao" class="site-container hospitality-intro" aria-labelledby="hospitality-title">
    <div class="hospitality-heading"><span class="hospitality-symbol" aria-hidden="true"><?= scl_icon('heart') ?></span><span class="eyebrow">HOTELARIA HOSPITALAR</span><h2 id="hospitality-title"><?= !empty($hospitality['bloco1']) ? scl_escape(strip_tags(preg_replace('~<br\s*/?>~i', "\n", $hospitality['bloco1']))) : 'Hotelaria na Santa Casa' ?></h2></div>
    <div class="hospitality-copy">
        <?php if (!empty($hospitality['bloco2'])): ?><div class="about-prose"><?= scl_about_content($hospitality['bloco2']) ?></div><?php endif; ?>
        <?php if (!$hasContent): ?><p class="about-empty">As informações sobre a hotelaria serão disponibilizadas nesta página. Para saber mais, <a href="<?= scl_url('fale-conosco') ?>">fale com a nossa equipe</a>.</p><?php endif; ?>
    </div>
</section>
<?php if ($photos): ?>
<section id="hotelaria-galeria" class="hospitality-environments" aria-labelledby="hospitality-gallery-title"><div class="site-container">
    <div class="section-heading"><div><span class="eyebrow">CONHEÇA OS AMBIENTES</span><h2 id="hospitality-gallery-title">Hotelaria em imagens.</h2><p>Explore os registros da hotelaria da Santa Casa. Selecione uma imagem para ampliar.</p></div><span class="hospitality-photo-count"><?= count($photos) ?> <?= count($photos) === 1 ? 'imagem' : 'imagens' ?></span></div>
    <?php $galleryId = 'hospitality-gallery-track'; $galleryLabel = 'Galeria da hotelaria'; require __DIR__ . '/../institutional_gallery.php'; ?>
</div></section>
<?php endif; ?>
<section id="hotelaria-orientacoes" class="site-container hospitality-resources" aria-labelledby="hospitality-resources-title">
    <div class="section-heading"><div><span class="eyebrow">PARA PACIENTES E ACOMPANHANTES</span><h2 id="hospitality-resources-title">Informações para sua visita.</h2></div></div>
    <div class="hospitality-resource-grid">
        <a class="hospitality-resource" href="<?= scl_url('servicos/manual-do-paciente-e-visitantes') ?>"><span class="hospitality-resource-icon" aria-hidden="true"><?= scl_icon('file') ?></span><h3>Guia do paciente e visitante</h3><p>Consulte as orientações disponibilizadas pela instituição.</p><span class="text-link">Acessar o guia <?= scl_icon('arrow') ?></span></a>
        <a class="hospitality-resource" href="<?= scl_url('fale-conosco') ?>"><span class="hospitality-resource-icon" aria-hidden="true"><?= scl_icon('people') ?></span><h3>Fale com a nossa equipe</h3><p>Encontre os canais de contato e a localização da Santa Casa.</p><span class="text-link">Ver informações de contato <?= scl_icon('arrow') ?></span></a>
    </div>
</section>
</div>
