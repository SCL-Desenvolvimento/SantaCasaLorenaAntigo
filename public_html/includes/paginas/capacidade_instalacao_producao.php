<?php
require_once __DIR__ . '/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM ' . PREFIX . 'pagina_capacidade_instalacao_producao ORDER BY data DESC LIMIT 1');
$capacityContent = ($getPagina->getResult() ?: array())[0] ?? array();
$capacityQuery = new Read();
$capacityQuery->fullRead('SELECT * FROM ' . PREFIX . 'capacidade ORDER BY data_criacao ASC');
$capacities = $capacityQuery->getResult() ?: array();
?>
<div class="facility-page">
<nav class="about-section-nav site-container" aria-label="Nesta página"><span>Nesta página</span><a href="#capacidade-apresentacao">Apresentação</a><?php if ($capacities): ?><a href="#capacidade-estruturas">Instalações e produção</a><?php endif; ?><a href="#facility-contato">Informações e contato</a></nav>
<section id="capacidade-apresentacao" class="site-container facility-intro" aria-labelledby="capacities-title">
<div class="facility-heading"><span class="facility-symbol" aria-hidden="true"><?= scl_icon('heart') ?></span><span class="eyebrow">ESTRUTURA E PRODUÇÃO</span><h2 id="capacities-title"><?= !empty($capacityContent['bloco1']) ? scl_escape(strip_tags(preg_replace('~<br\s*/?>~i', "\n", $capacityContent['bloco1']))) : 'Estrutura e produção' ?></h2></div>
<div class="facility-copy">
<?php foreach (array('bloco2') as $field): if (!empty($capacityContent[$field])): ?><div class="about-prose"><?= scl_about_content($capacityContent[$field]) ?></div><?php endif; endforeach; ?>
<?php if (!$capacityContent): ?><p class="about-empty">As informações sobre a capacidade de instalação e produção serão disponibilizadas nesta página. <a href="<?= scl_url('fale-conosco') ?>">Fale com a nossa equipe</a> para saber mais.</p><?php endif; ?>
</div></section>
<?php if ($capacities): ?>
<section id="capacidade-estruturas" class="facility-environments" aria-labelledby="capacities-gallery-title"><div class="site-container">
<div class="section-heading"><div><span class="eyebrow">CONHEÇA OS AMBIENTES</span><h2 id="capacities-gallery-title">Instalações e produção.</h2><p>Consulte a descrição de cada estrutura e selecione uma imagem para ampliar.</p></div></div>
<?php foreach ($capacities as $capacityIndex=>$capacity):
    $capacityTitle = trim(strip_tags($capacity['titulo'] ?? '')) ?: 'Estrutura e produção';
    $photos = array();
    $capacityId = filter_var($capacity['id_capacidade'] ?? null, FILTER_VALIDATE_INT);
    if ($capacityId !== false && $capacityId > 0) {
        $imageQuery = new Read();
        $imageQuery->fullRead('SELECT * FROM ' . PREFIX . 'capacidade_imagem WHERE id_capacidade = :unit ORDER BY data_criacao DESC', 'unit=' . $capacityId);
        foreach ($imageQuery->getResult() ?: array() as $photo) {
            if (empty($photo['img']) || scl_link($photo['img']) === '') continue;
            $photo['descricao'] = $capacityTitle;
            $photos[] = $photo;
        }
    }
?>
<article class="facility-unit" aria-labelledby="capacity-title-<?= (int)$capacityIndex ?>"><h3 id="capacity-title-<?= (int)$capacityIndex ?>"><?= scl_escape($capacityTitle) ?></h3>
<div class="facility-unit-layout <?= !$photos || empty($capacity['descricao']) ? 'is-single' : '' ?>">
<?php if ($photos): ?><div><?php $galleryId='capacity-gallery-'.(int)$capacityIndex; $galleryLabel='Galeria: '.$capacityTitle; require __DIR__.'/../institutional_gallery.php'; ?></div><?php endif; ?>
<?php if (!empty($capacity['descricao'])): ?><div class="about-prose"><?= scl_about_content($capacity['descricao']) ?></div><?php endif; ?>
<?php if (!$photos && empty($capacity['descricao'])): ?><p class="about-empty">Mais informações sobre esta estrutura serão disponibilizadas em breve.</p><?php endif; ?>
</div></article>
<?php endforeach; ?>
</div></section><?php endif; ?>
<?php require __DIR__.'/../facility_resources.php'; ?>
</div>
