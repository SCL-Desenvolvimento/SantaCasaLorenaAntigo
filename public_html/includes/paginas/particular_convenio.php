<?php
require_once __DIR__.'/../about_helpers.php';
$planQuery = new Read();
$planQuery->fullRead('SELECT * FROM '.PREFIX.'convenio ORDER BY data_criacao ASC');
$plans = $planQuery->getResult() ?: array();
?>
<div class="facility-page">
<nav class="about-section-nav site-container" aria-label="Nesta página"><span>Nesta página</span><a href="#particular-apresentacao">Sobre o atendimento</a><a href="#particular-convenios">Convênios</a><a href="#facility-contato">Informações e contato</a></nav>
<section id="particular-apresentacao" class="site-container facility-intro" aria-labelledby="private-title">
<div class="facility-heading"><span class="facility-symbol" aria-hidden="true"><?= scl_icon('people') ?></span><span class="eyebrow">PARTICULAR / CONVÊNIO</span><h2 id="private-title">Conheça o atendimento.</h2></div>
<div class="facility-copy about-prose"><p>O Pronto Atendimento da Santa Casa de Lorena possui acomodações amplas e modernas e, ainda, conta com brinquedoteca e cantinho do café.</p><a class="scl-button" href="<?= scl_url('fale-conosco') ?>">Informações sobre atendimento <?= scl_icon('arrow') ?></a></div>
</section>
<section id="particular-convenios" class="facility-environments" aria-labelledby="plans-title"><div class="site-container"><div class="section-heading"><div><span class="eyebrow">CONSULTE AS INFORMAÇÕES</span><h2 id="plans-title">Convênios cadastrados.</h2><p>Para informações sobre seu plano e o atendimento desejado, entre em contato com a instituição.</p></div></div>
<?php if ($plans): ?><div class="facility-plans">
<?php foreach ($plans as $plan): $planName=trim(strip_tags($plan['nome'] ?? '')) ?: 'Convênio'; $planImage=scl_link($plan['img'] ?? ''); ?>
<figure class="facility-plan"><?php if ($planImage): ?><img src="<?= scl_escape($planImage) ?>" alt="<?= scl_escape($planName) ?>" loading="lazy" decoding="async" width="180" height="78"><?php endif; ?><figcaption><?= scl_escape($planName) ?></figcaption></figure>
<?php endforeach; ?></div><?php else: ?><p class="about-empty">Consulte os convênios e as informações de atendimento com a nossa equipe.</p><?php endif; ?>
</div></section>
<?php require __DIR__.'/../facility_resources.php'; ?>
</div>
