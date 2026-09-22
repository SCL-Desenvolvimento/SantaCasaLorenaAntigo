<?php
require_once __DIR__.'/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM '.PREFIX.'pagina_especialidades ORDER BY data DESC LIMIT 1');$specialtyContent=($getPagina->getResult() ?: array())[0] ?? array();
$query=new Read();$query->fullRead('SELECT * FROM '.PREFIX.'especialidade ORDER BY data_criacao ASC');$specialties=$query->getResult() ?: array();
?>
<div class="facility-page"><section class="site-container service-section" aria-labelledby="specialties-title" data-service-directory>
<div class="section-heading"><div><span class="eyebrow">ESPECIALIDADES</span><h2 id="specialties-title"><?= !empty($specialtyContent['bloco1']) ? scl_escape(strip_tags(preg_replace('~<br\s*/?>~i', "\n",$specialtyContent['bloco1']))) : 'Conheça nossas especialidades.' ?></h2></div></div>
<?php if ($specialties): ?>
<?php $searchId='specialty-search';$searchLabel='Buscar especialidade';require __DIR__.'/../service_search.php'; ?>
<ul class="service-specialties"><?php foreach($specialties as $specialty): ?><li data-service-item><span aria-hidden="true"><?= scl_icon('cross') ?></span><?= scl_escape($specialty['nome'] ?? '') ?></li><?php endforeach; ?></ul>
<?php else: ?><p class="about-empty">As especialidades serão disponibilizadas nesta página. Entre em contato para obter informações.</p><?php endif; ?>
</section><?php require __DIR__.'/../facility_resources.php'; ?></div>
