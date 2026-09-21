<?php
require_once __DIR__ . '/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM ' . PREFIX . 'pagina_programa_nacional_seguranca ORDER BY data DESC LIMIT 1');
$safety = ($getPagina->getResult() ?: array())[0] ?? array();
$hasProgram = !empty($safety['bloco1']);
$coreImage = scl_link($safety['img1'] ?? '');
$hasCore = !empty($safety['bloco2']) || $coreImage;
?>
<div class="patient-safety-page">
<?php if ($hasProgram || $hasCore): ?>
<nav class="about-section-nav site-container" aria-label="Nesta página">
    <span>Nesta página</span>
    <?php if ($hasProgram): ?><a href="#seguranca-programa">O programa</a><?php endif; ?>
    <?php if ($hasCore): ?><a href="#seguranca-nucleo">Núcleo de segurança</a><?php endif; ?>
    <a href="#seguranca-orientacoes">Informações e contato</a>
</nav>
<?php else: ?>
<section class="site-container section-space">
    <h2>Segurança do paciente</h2>
    <p class="about-empty">As informações sobre o programa serão disponibilizadas nesta página. Para saber mais, <a href="<?= scl_url('fale-conosco') ?>">fale com a nossa equipe</a>.</p>
</section>
<?php endif; ?>
<?php if ($hasProgram): ?>
<section id="seguranca-programa" class="site-container safety-program" aria-labelledby="safety-program-title">
    <div class="safety-section-heading"><span class="safety-symbol" aria-hidden="true"><?= scl_icon('shield') ?></span><div><span class="eyebrow">SEGURANÇA DO PACIENTE</span><h2 id="safety-program-title">Programa nacional de segurança do paciente</h2></div></div>
    <div class="about-prose safety-program-copy"><?= scl_about_content($safety['bloco1']) ?></div>
</section>
<?php endif; ?>
<?php if ($hasCore): ?>
<section id="seguranca-nucleo" class="safety-core" aria-labelledby="safety-core-title"><div class="site-container">
    <div class="section-heading"><div><span class="eyebrow">NA SANTA CASA</span><h2 id="safety-core-title">Núcleo de segurança do paciente</h2></div></div>
    <div class="safety-core-grid <?= !$coreImage || empty($safety['bloco2']) ? 'safety-single-column' : '' ?>">
        <?php if ($coreImage): ?>
        <figure class="safety-image">
            <a href="<?= scl_escape($coreImage) ?>" aria-label="Abrir imagem do núcleo de segurança do paciente em tamanho original"><img src="<?= scl_escape($coreImage) ?>" alt="Imagem institucional do núcleo de segurança do paciente" width="800" height="560" loading="lazy" decoding="async"></a>
            <figcaption><a class="text-link" href="<?= scl_escape($coreImage) ?>">Ver imagem em tamanho original <?= scl_icon('search') ?></a></figcaption>
        </figure>
        <?php endif; ?>
        <?php if (!empty($safety['bloco2'])): ?><div class="about-prose safety-core-copy"><?= scl_about_content($safety['bloco2']) ?></div><?php endif; ?>
    </div>
</div></section>
<?php endif; ?>
<section id="seguranca-orientacoes" class="site-container safety-resources" aria-labelledby="safety-resources-title">
    <div class="section-heading"><div><span class="eyebrow">PARA PACIENTES E FAMILIARES</span><h2 id="safety-resources-title">Informações ao seu alcance.</h2></div></div>
    <div class="safety-resource-grid">
        <a class="safety-resource" href="<?= scl_url('servicos/manual-do-paciente-e-visitantes') ?>"><span class="safety-resource-icon"><?= scl_icon('file') ?></span><div><h3>Guia do paciente e visitante</h3><p>Acesse o manual disponibilizado pela Santa Casa.</p><span class="text-link">Consultar o guia <?= scl_icon('arrow') ?></span></div></a>
        <a class="safety-resource" href="<?= scl_url('fale-conosco') ?>"><span class="safety-resource-icon"><?= scl_icon('people') ?></span><div><h3>Fale com a nossa equipe</h3><p>Encontre os canais de contato da instituição.</p><span class="text-link">Ver canais de contato <?= scl_icon('arrow') ?></span></div></a>
    </div>
</section>
</div>
