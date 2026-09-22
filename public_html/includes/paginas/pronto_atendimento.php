<?php
require_once __DIR__ . '/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM ' . PREFIX . 'pagina_pronto_atendimento ORDER BY data DESC LIMIT 1');
$care = ($getPagina->getResult() ?: array())[0] ?? array();
$galleryQuery = new Read();
$galleryQuery->fullRead('SELECT * FROM ' . PREFIX . 'pronto_atendimento ORDER BY data_criacao ASC');
$photos = array();
foreach ($galleryQuery->getResult() ?: array() as $photo) {
    if (empty($photo['img']) || scl_link($photo['img']) === '') continue;
    $photo['descricao'] = $photo['titulo'] ?? $photo['descricao'] ?? '';
    $photos[] = $photo;
}
$hasIntro = !empty($care['bloco1']) || !empty($care['bloco2']) || !empty($care['bloco3']);
// Preserve the four categories and waiting times published in the original page.
$classifications = array(
    array('red', 'Vermelho', 'Emergência', 'Alto risco de vida', 'Atendimento imediato'),
    array('yellow', 'Amarelo', 'Urgência', 'Risco moderado', 'Atendimento em até 60 minutos'),
    array('green', 'Verde', 'Pouca urgência', 'Risco baixo', 'Atendimento em até 2 horas'),
    array('blue', 'Azul', 'Não urgência', 'Situação não aguda', 'Atendimento em até 4 horas')
);
?>
<div class="urgent-care-page">
<nav class="about-section-nav site-container" aria-label="Nesta página">
    <span>Nesta página</span>
    <?php if ($hasIntro): ?><a href="#pa-atendimento">Sobre o atendimento</a><?php endif; ?>
    <a href="#pa-classificacao">Classificação dos atendimentos</a>
    <?php if ($photos): ?><a href="#pa-galeria">Conheça o espaço</a><?php endif; ?>
    <a href="#pa-contato">Informações e contato</a>
</nav>
<section id="pa-atendimento" class="site-container care-intro" aria-labelledby="care-title">
    <div class="care-heading"><span class="care-symbol" aria-hidden="true"><?= scl_icon('cross') ?></span><div><span class="eyebrow">PRONTO ATENDIMENTO SUS</span><h2 id="care-title"><?= !empty($care['bloco1']) ? scl_escape(strip_tags(preg_replace('~<br\s*/?>~i', "\n", $care['bloco1']))) : 'Conheça o atendimento' ?></h2></div></div>
    <?php if (!empty($care['bloco2'])): ?><div class="about-prose care-copy"><?= scl_about_content($care['bloco2']) ?></div><?php endif; ?>
    <?php if (!empty($care['bloco3'])): ?><div class="care-highlight about-prose"><?= scl_about_content($care['bloco3']) ?></div><?php endif; ?>
    <?php if (!$hasIntro): ?><p class="about-empty">As informações sobre o pronto atendimento serão disponibilizadas nesta página. Para saber mais, <a href="<?= scl_url('fale-conosco') ?>">fale com a nossa equipe</a>.</p><?php endif; ?>
</section>
<section id="pa-classificacao" class="care-classification" aria-labelledby="care-classification-title"><div class="site-container">
    <div class="section-heading"><div><span class="eyebrow">ENTENDA A CLASSIFICAÇÃO</span><h2 id="care-classification-title">Classificação dos atendimentos</h2></div></div>
    <?php if (!empty($care['bloco4'])): ?><div class="about-prose care-classification-copy"><?= scl_about_content($care['bloco4']) ?></div><?php endif; ?>
    <div class="care-priority-grid">
    <?php foreach ($classifications as $priority): ?>
        <article class="care-priority care-priority-<?= $priority[0] ?>">
            <span class="care-color"><span aria-hidden="true"></span><?= $priority[1] ?></span>
            <h3><?= $priority[2] ?></h3><p><?= $priority[3] ?></p><strong class="care-time"><?= $priority[4] ?></strong>
        </article>
    <?php endforeach; ?>
    </div>
    <?php if (!empty($care['bloco5'])): ?><div class="about-prose care-classification-note"><?= scl_about_content($care['bloco5']) ?></div><?php endif; ?>
</div></section>
<?php if ($photos): ?>
<section id="pa-galeria" class="site-container care-gallery-section" aria-labelledby="care-gallery-title">
    <div class="section-heading"><div><span class="eyebrow">NOSSO ESPAÇO</span><h2 id="care-gallery-title">Pronto atendimento em imagens.</h2><p class="care-gallery-hint">Selecione uma imagem para ampliar e conhecer o espaço.</p></div></div>
    <?php $galleryId = 'care-gallery-track'; $galleryLabel = 'Galeria do pronto atendimento SUS'; require __DIR__ . '/../institutional_gallery.php'; ?>
</section>
<?php endif; ?>
<section id="pa-contato" class="site-container care-contact" aria-labelledby="care-contact-title">
    <div><span class="eyebrow">INFORMAÇÕES AO SEU ALCANCE</span><h2 id="care-contact-title">Conte com a Santa Casa.</h2><p>Acesse as orientações para pacientes e visitantes ou encontre os canais de contato da instituição.</p></div>
    <div class="hero-actions"><a class="scl-button" href="<?= scl_url('servicos/manual-do-paciente-e-visitantes') ?>">Guia do paciente <?= scl_icon('arrow') ?></a><a class="text-link" href="<?= scl_url('fale-conosco') ?>">Localização e contato <?= scl_icon('arrow') ?></a></div>
</section>
</div>
