<?php
require_once __DIR__.'/../about_helpers.php';
$planQuery=new Read();$planQuery->fullRead('SELECT * FROM '.PREFIX.'convenio ORDER BY data_criacao ASC');
$plans=$planQuery->getResult() ?: array();
// This additional logo was explicitly published in the original page.
if ($plans && !array_filter($plans,fn($plan)=>str_contains(strtolower($plan['nome'] ?? ''),'sineevali') || str_ends_with($plan['img'] ?? '', '/cas.png'))) $plans[]=array('nome'=>'SINEEVALI','img'=>'arquivos/convenio/cas.png');
?>
<div class="facility-page"><section class="site-container service-section" aria-labelledby="plans-title" data-service-directory>
<div class="section-heading"><div><span class="eyebrow">CONVÊNIOS</span><h2 id="plans-title">Encontre seu convênio.</h2><p>Consulte os convênios cadastrados e entre em contato para informações sobre seu plano e o atendimento desejado.</p></div></div>
<?php if ($plans): ?>
<?php $searchId='plan-search';$searchLabel='Buscar convênio';require __DIR__.'/../service_search.php'; ?>
<div class="facility-plans"><?php foreach ($plans as $plan): $name=trim(strip_tags($plan['nome'] ?? '')) ?: 'Convênio';$img=scl_link($plan['img'] ?? ''); ?>
<figure class="facility-plan" data-service-item><?php if ($img): ?><img src="<?= scl_escape($img) ?>" alt="<?= scl_escape($name) ?>" width="180" height="78" loading="lazy" decoding="async"><?php endif; ?><figcaption><?= scl_escape($name) ?></figcaption></figure>
<?php endforeach; ?></div>
<?php else: ?><p class="about-empty">Consulte os convênios e as informações de atendimento com a nossa equipe.</p><?php endif; ?>
</section><?php require __DIR__.'/../facility_resources.php'; ?></div>
