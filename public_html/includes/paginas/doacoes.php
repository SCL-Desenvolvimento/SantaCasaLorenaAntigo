<?php
require_once dirname(__DIR__).'/community_forms.php';
$readDonation = new Read();
$readDonation->fullRead('SELECT * FROM '.PREFIX.'pagina_doacao ORDER BY data DESC LIMIT 1');
$donation = $readDonation->getResult()[0] ?? array();
$selected = $_GET['tipo'] ?? 'deposito';
$mode = is_string($selected) && in_array($selected, array('deposito','boleto'), true) ? $selected : 'deposito';
$state = scl_process_form(array('deposito','boleto'), $secret ?? '');
if ($state['mode']) $mode = $state['mode'];
$state['mode'] = $mode;
?>
<section class="section-space community-page donation-page"><div class="site-container">
<div class="donation-intro"><div><span class="eyebrow">Solidariedade que aproxima</span><h2><?= scl_escape(strip_tags($donation['bloco1'] ?? '') ?: 'Sua contribuição faz parte do cuidado') ?></h2><div class="about-prose"><?= scl_about_content($donation['bloco2'] ?? '') ?></div></div><div class="donation-symbol"><?= scl_icon('heart') ?><p>Juntos pela<br><strong>Santa Casa de Lorena</strong></p></div></div>
<div class="community-layout"><div class="community-panel" id="doar"><span class="eyebrow">Como contribuir</span><h2>Escolha uma forma de apoiar</h2><p class="community-lead">Entre em contato para receber orientações sobre sua doação.</p>
<nav class="donation-methods" aria-label="Formas de doação"><a href="<?= scl_url('doacoes') ?>?tipo=deposito#doar"<?= $mode === 'deposito' ? ' aria-current="page"' : '' ?>>Depósito bancário</a><a href="<?= scl_url('doacoes') ?>?tipo=boleto#doar"<?= $mode === 'boleto' ? ' aria-current="page"' : '' ?>>Boleto bancário</a></nav>
<h3><?= $mode === 'boleto' ? 'Solicite orientações sobre o boleto' : 'Fale conosco sobre seu depósito' ?></h3><p>O formulário envia uma mensagem à instituição. <?= $mode === 'boleto' ? 'O boleto não é gerado automaticamente nesta página.' : 'Preencha os dados para que a equipe possa orientar você.' ?></p>
<?php require dirname(__DIR__).'/community_form_view.php'; ?>
</div><aside class="community-aside"><div class="community-info"><span class="eyebrow">Orientações da instituição</span><h2>Informações para doar</h2>
<?php if (trim($donation['bloco3'] ?? '') !== ''): ?><div class="about-prose donation-instructions"><?= scl_about_content($donation['bloco3']) ?></div><?php else: ?><p>Entre em contato com a Santa Casa para consultar as informações de doação.</p><?php endif; ?>
</div><div class="community-help"><h3>Precisa de ajuda?</h3><p>Converse com a equipe da Santa Casa para esclarecer suas dúvidas.</p><a class="text-link" href="<?= scl_url('fale-conosco') ?>">Ver canais de contato <?= scl_icon('arrow') ?></a></div></aside></div>
</div></section>
