<?php
require_once dirname(__DIR__).'/community_forms.php';
$channels = array('contato'=>'Ouvidoria', 'trabalhe_conosco'=>'Trabalhe conosco', 'pesquisa'=>'Pesquisa de atendimento');
unset($_SESSION['tb'], $_SESSION['pa']);
$selected = $_GET['canal'] ?? 'contato';
$mode = is_string($selected) && isset($channels[$selected]) ? $selected : 'contato';
$state = scl_process_form(array_keys($channels), $secret ?? '');
if ($state['mode']) $mode = $state['mode'];
$state['mode'] = $mode;
$pageKeys = array('contato'=>'ouvidoria', 'trabalhe_conosco'=>'trabalhe_conosco', 'pesquisa'=>'pesquisa_atendimento');
$readContact = new Read();
$readContact->fullRead('SELECT * FROM '.PREFIX.'paginas WHERE url_amigavel = :channel', 'channel='.$pageKeys[$mode]);
$description = $readContact->getResult()[0]['descricao'] ?? '';
$address = trim(strip_tags($localizacao['localizacao'] ?? ''));
$email = filter_var($localizacao['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = $localizacao['telefone'] ?? '';
$intros = array('contato'=>'Envie sua dúvida, sugestão, elogio ou manifestação. Sua participação nos ajuda a melhorar.', 'trabalhe_conosco'=>'Faça parte de uma história de cuidado. Encaminhe seu currículo para nossa equipe de Recursos Humanos.', 'pesquisa'=>'Conte como foi seu atendimento. Sua avaliação contribui para melhorar a experiência na Santa Casa.');
?>
<section class="section-space community-page"><div class="site-container">
<nav class="community-channels" aria-label="Canais de contato">
<?php foreach ($channels as $key=>$label): ?><a href="<?= scl_escape(scl_contact_url($key)) ?>"<?= $mode === $key ? ' aria-current="page"' : '' ?>><span><?= scl_icon($key === 'trabalhe_conosco' ? 'people' : ($key === 'pesquisa' ? 'heart' : 'file')) ?></span><strong><?= scl_escape($label) ?></strong><span aria-hidden="true">↗</span></a><?php endforeach; ?>
</nav><div class="community-layout"><div class="community-panel" id="formulario"><span class="eyebrow">Estamos aqui para ouvir</span><h2><?= scl_escape($channels[$mode]) ?></h2><p class="community-lead"><?= scl_escape($intros[$mode]) ?></p>
<?php if ($description): ?><div class="about-prose"><?= scl_about_content($description) ?></div><?php endif; ?>
<?php require dirname(__DIR__).'/community_form_view.php'; ?>
</div><aside class="community-aside"><div class="community-info" id="localizacao" tabindex="-1"><span class="eyebrow">Fale com a Santa Casa</span><h2>Encontre o seu caminho</h2><p>Você também pode entrar em contato pelos canais da instituição.</p>
<?php if ($phone): ?><a class="contact-detail" href="tel:<?= scl_escape(preg_replace('/[^0-9+]/', '', $phone)) ?>"><?= scl_icon('phone') ?><span><small>Telefone</small><?= scl_escape($phone) ?></span></a><?php endif; ?>
<?php if (preg_replace('/\D/', '', $phone) !== '1231593344'): ?><a class="contact-detail" href="tel:+551231593344"><?= scl_icon('phone') ?><span><small>Telefone</small>(12) 3159-3344</span></a><?php endif; ?>
<?php if ($email): ?><a class="contact-detail" href="mailto:<?= scl_escape($email) ?>"><?= scl_icon('file') ?><span><small>E-mail</small><?= scl_escape($email) ?></span></a><?php endif; ?>
<?php if ($address): ?><div class="contact-detail"><?= scl_icon('pin') ?><span><small>Localização</small><?= scl_escape($address) ?></span></div><a class="text-link" href="https://www.google.com/maps/search/?api=1&amp;query=<?= rawurlencode($address.' Santa Casa de Lorena') ?>" target="_blank" rel="noopener noreferrer">Ver no mapa ↗</a><?php endif; ?>
</div><div class="community-help"><h3>Informações para sua visita</h3><p>Consulte as orientações para pacientes e acompanhantes.</p><a class="text-link" href="<?= scl_url('servicos/manual-do-paciente-e-visitantes') ?>">Acessar o manual <?= scl_icon('arrow') ?></a></div></aside></div>
</div></section>
