<?php
require_once __DIR__ . '/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM ' . PREFIX . 'pagina_unidade_internacao ORDER BY data DESC LIMIT 1');
$wardContent = ($getPagina->getResult() ?: array())[0] ?? array();
$wardQuery = new Read();
$wardQuery->fullRead('SELECT * FROM ' . PREFIX . 'unidade_internacao ORDER BY data_criacao ASC');
$wards = $wardQuery->getResult() ?: array();
?>
<div class="facility-page">
<nav class="about-section-nav site-container" aria-label="Nesta página"><span>Nesta página</span><a href="#internacao-apresentacao">Sobre a internação</a><?php if ($wards): ?><a href="#internacao-unidades">Conheça as unidades</a><?php endif; ?><a href="#facility-contato">Informações e contato</a></nav>
<section id="internacao-apresentacao" class="site-container facility-intro" aria-labelledby="wards-title">
<div class="facility-heading"><span class="facility-symbol" aria-hidden="true"><?= scl_icon('heart') ?></span><span class="eyebrow">UNIDADES DE INTERNAÇÃO</span><h2 id="wards-title"><?= !empty($wardContent['bloco1']) ? scl_escape(strip_tags(preg_replace('~<br\s*/?>~i', "\n", $wardContent['bloco1']))) : 'Conheça nossas unidades' ?></h2></div>
<div class="facility-copy">
<?php foreach (array('bloco2','bloco3') as $field): if (!empty($wardContent[$field])): ?><div class="about-prose"><?= scl_about_content($wardContent[$field]) ?></div><?php endif; endforeach; ?>
<?php if (!$wardContent): ?><p class="about-empty">As informações sobre as unidades de internação serão disponibilizadas nesta página. <a href="<?= scl_url('fale-conosco') ?>">Fale com a nossa equipe</a> para saber mais.</p><?php endif; ?>
</div></section>
<?php if ($wards): ?>
<section id="internacao-unidades" class="facility-environments" aria-labelledby="wards-gallery-title"><div class="site-container">
<div class="section-heading"><div><span class="eyebrow">CONHEÇA OS AMBIENTES</span><h2 id="wards-gallery-title">Nossas unidades.</h2><p>Consulte a descrição de cada unidade e selecione uma imagem para ampliar.</p></div></div>
<?php foreach ($wards as $wardIndex=>$ward):
    $wardTitle = trim(strip_tags($ward['titulo'] ?? '')) ?: 'Unidade de internação';
    $photos = array();
    $wardId = filter_var($ward['id_unidade_internacao'] ?? null, FILTER_VALIDATE_INT);
    if ($wardId !== false && $wardId > 0) {
        $imageQuery = new Read();
        $imageQuery->fullRead('SELECT * FROM ' . PREFIX . 'unidade_internacao_imagem WHERE id_unidade_internacao = :unit ORDER BY data_criacao DESC', 'unit=' . $wardId);
        foreach ($imageQuery->getResult() ?: array() as $photo) {
            if (empty($photo['img']) || scl_link($photo['img']) === '') continue;
            $photo['descricao'] = $wardTitle;
            $photos[] = $photo;
        }
    }
?>
<article class="facility-unit" aria-labelledby="ward-title-<?= (int)$wardIndex ?>"><h3 id="ward-title-<?= (int)$wardIndex ?>"><?= scl_escape($wardTitle) ?></h3>
<div class="facility-unit-layout <?= !$photos || empty($ward['descricao']) ? 'is-single' : '' ?>">
<?php if ($photos): ?><div><?php $galleryId='ward-gallery-'.(int)$wardIndex; $galleryLabel='Galeria: '.$wardTitle; require __DIR__.'/../institutional_gallery.php'; ?></div><?php endif; ?>
<?php if (!empty($ward['descricao'])): ?><div class="about-prose"><?= scl_about_content($ward['descricao']) ?></div><?php endif; ?>
<?php if (!$photos && empty($ward['descricao'])): ?><p class="about-empty">Mais informações sobre esta unidade serão disponibilizadas em breve.</p><?php endif; ?>
</div></article>
<?php endforeach; ?>
</div></section><?php endif; ?>
<?php require __DIR__.'/../facility_resources.php'; ?>
</div>
