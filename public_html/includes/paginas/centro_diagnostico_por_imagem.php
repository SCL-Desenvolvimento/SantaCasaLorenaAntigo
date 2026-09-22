<?php
require_once __DIR__ . '/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM ' . PREFIX . 'pagina_centro_diagnostico_por_imagem ORDER BY data DESC LIMIT 1');
$facility = ($getPagina->getResult() ?: array())[0] ?? array();
$galleryQuery = new Read();
$galleryQuery->fullRead('SELECT * FROM ' . PREFIX . 'centro_diagnostico_por_imagem ORDER BY data_criacao ASC');
$photos = array();
foreach ($galleryQuery->getResult() ?: array() as $photo) {
    if (empty($photo['img']) || scl_link($photo['img']) === '') continue;
    $photo['descricao'] = $photo['titulo'] ?? $photo['descricao'] ?? '';
    $photos[] = $photo;
}
$hasContent = !empty($facility['bloco1']) || !empty($facility['bloco2']);
?>
<div class="facility-page">
<nav class="about-section-nav site-container" aria-label="Nesta página">
    <span>Nesta página</span>
    <a href="#diagnostico-apresentacao">Sobre o serviço</a>
    <?php if ($photos): ?><a href="#diagnostico-galeria">Conheça os ambientes</a><?php endif; ?>
    <a href="#diagnostico-orientacoes">Informações e contato</a>
</nav>
<section id="diagnostico-apresentacao" class="site-container facility-intro" aria-labelledby="facility-title">
    <div class="facility-heading"><span class="facility-symbol" aria-hidden="true"><?= scl_icon('heart') ?></span><span class="eyebrow">DIAGNÓSTICO POR IMAGEM</span><h2 id="facility-title"><?= !empty($facility['bloco1']) ? scl_escape(strip_tags(preg_replace('~<br\s*/?>~i', "\n", $facility['bloco1']))) : 'Conheça o Diagnóstico por imagem' ?></h2></div>
    <div class="facility-copy">
        <?php if (!empty($facility['bloco2'])): ?><div class="about-prose"><?= scl_about_content($facility['bloco2']) ?></div><?php endif; ?>
        <?php if (!$hasContent): ?><p class="about-empty">As informações sobre o Diagnóstico por imagem serão disponibilizadas nesta página. Para saber mais, <a href="<?= scl_url('fale-conosco') ?>">fale com a nossa equipe</a>.</p><?php endif; ?>
    </div>
</section>
<?php if ($photos): ?>
<section id="diagnostico-galeria" class="facility-environments" aria-labelledby="facility-gallery-title"><div class="site-container">
    <div class="section-heading"><div><span class="eyebrow">CONHEÇA OS AMBIENTES</span><h2 id="facility-gallery-title">Conheça nosso espaço.</h2><p>Explore os registros do diagnóstico por imagem da Santa Casa. Selecione uma imagem para ampliar.</p></div><span class="facility-photo-count"><?= count($photos) ?> <?= count($photos) === 1 ? 'imagem' : 'imagens' ?></span></div>
    <?php $galleryId = 'facility-gallery-track'; $galleryLabel = 'Galeria do diagnóstico por imagem'; require __DIR__ . '/../institutional_gallery.php'; ?>
</div></section>
<?php endif; ?>
<section id="diagnostico-orientacoes" class="site-container facility-resources" aria-labelledby="facility-resources-title">
    <div class="section-heading"><div><span class="eyebrow">PARA PACIENTES E ACOMPANHANTES</span><h2 id="facility-resources-title">Informações para sua visita.</h2></div></div>
    <div class="facility-resource-grid">
        <a class="facility-resource" href="<?= scl_url('servicos/manual-do-paciente-e-visitantes') ?>"><span class="facility-resource-icon" aria-hidden="true"><?= scl_icon('file') ?></span><h3>Guia do paciente e visitante</h3><p>Consulte as orientações disponibilizadas pela instituição.</p><span class="text-link">Acessar o guia <?= scl_icon('arrow') ?></span></a>
        <a class="facility-resource" href="<?= scl_url('fale-conosco') ?>"><span class="facility-resource-icon" aria-hidden="true"><?= scl_icon('people') ?></span><h3>Fale com a nossa equipe</h3><p>Encontre os canais de contato e a localização da Santa Casa.</p><span class="text-link">Ver informações de contato <?= scl_icon('arrow') ?></span></a>
    </div>
</section>
</div>
