<?php
require_once __DIR__ . '/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM ' . PREFIX . 'pagina_clinica_emilia ORDER BY data DESC LIMIT 1');
$emilia = ($getPagina->getResult() ?: array())[0] ?? array();
$galleryQuery = new Read();
$galleryQuery->fullRead('SELECT * FROM ' . PREFIX . 'clinica_emilia ORDER BY data_criacao ASC');
$photos = array();
foreach ($galleryQuery->getResult() ?: array() as $photo) {
    if (empty($photo['img']) || scl_link($photo['img']) === '') continue;
    $photo['descricao'] = $photo['titulo'] ?? $photo['descricao'] ?? '';
    $photos[] = $photo;
}
$hasContent = !empty($emilia['bloco1']) || !empty($emilia['bloco2']);
?>
<div class="emilia-page">
<nav class="about-section-nav site-container" aria-label="Nesta página">
    <span>Nesta página</span>
    <a href="#clinica-emilia-apresentacao">Sobre a clínica</a>
    <?php if ($photos): ?><a href="#clinica-emilia-galeria">Conheça os ambientes</a><?php endif; ?>
    <a href="#clinica-emilia-orientacoes">Informações e contato</a>
</nav>
<section id="clinica-emilia-apresentacao" class="site-container emilia-intro" aria-labelledby="emilia-title">
    <div class="emilia-heading"><span class="emilia-symbol" aria-hidden="true"><?= scl_icon('heart') ?></span><span class="eyebrow">CLÍNICA EMÍLIA</span><h2 id="emilia-title"><?= !empty($emilia['bloco1']) ? scl_escape(strip_tags(preg_replace('~<br\s*/?>~i', "\n", $emilia['bloco1']))) : 'Conheça a Clínica Emília' ?></h2></div>
    <div class="emilia-copy">
        <?php if (!empty($emilia['bloco2'])): ?><div class="about-prose"><?= scl_about_content($emilia['bloco2']) ?></div><?php endif; ?>
        <?php if (!$hasContent): ?><p class="about-empty">As informações sobre a Clínica Emília serão disponibilizadas nesta página. Para saber mais, <a href="<?= scl_url('fale-conosco') ?>">fale com a nossa equipe</a>.</p><?php endif; ?>
    </div>
</section>
<?php if ($photos): ?>
<section id="clinica-emilia-galeria" class="emilia-environments" aria-labelledby="emilia-gallery-title"><div class="site-container">
    <div class="section-heading"><div><span class="eyebrow">CONHEÇA OS AMBIENTES</span><h2 id="emilia-gallery-title">Clínica Emília em imagens.</h2><p>Explore os registros da Clínica Emília da Santa Casa. Selecione uma imagem para ampliar.</p></div><span class="emilia-photo-count"><?= count($photos) ?> <?= count($photos) === 1 ? 'imagem' : 'imagens' ?></span></div>
    <?php $galleryId = 'emilia-gallery-track'; $galleryLabel = 'Galeria da Clínica Emília'; require __DIR__ . '/../institutional_gallery.php'; ?>
</div></section>
<?php endif; ?>
<section id="clinica-emilia-orientacoes" class="site-container emilia-resources" aria-labelledby="emilia-resources-title">
    <div class="section-heading"><div><span class="eyebrow">PARA PACIENTES E ACOMPANHANTES</span><h2 id="emilia-resources-title">Informações para sua visita.</h2></div></div>
    <div class="emilia-resource-grid">
        <a class="emilia-resource" href="<?= scl_url('servicos/manual-do-paciente-e-visitantes') ?>"><span class="emilia-resource-icon" aria-hidden="true"><?= scl_icon('file') ?></span><h3>Guia do paciente e visitante</h3><p>Consulte as orientações disponibilizadas pela instituição.</p><span class="text-link">Acessar o guia <?= scl_icon('arrow') ?></span></a>
        <a class="emilia-resource" href="<?= scl_url('fale-conosco') ?>"><span class="emilia-resource-icon" aria-hidden="true"><?= scl_icon('people') ?></span><h3>Fale com a nossa equipe</h3><p>Encontre os canais de contato e a localização da Santa Casa.</p><span class="text-link">Ver informações de contato <?= scl_icon('arrow') ?></span></a>
    </div>
</section>
</div>
