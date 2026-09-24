<?php
$newsletterMessage = '';
$newsletterSuccess = false;
if (empty($_SESSION['newsletter_token'])) {
    $_SESSION['newsletter_token'] = bin2hex(random_bytes(32));
}
if (isset($_POST['newsletter']) && is_string($_POST['newsletter'])) {
    $email = trim($_POST['newsletter']);
    if (!isset($_POST['newsletter_token']) || !is_string($_POST['newsletter_token']) || !hash_equals($_SESSION['newsletter_token'], $_POST['newsletter_token'])) {
        $newsletterMessage = 'Atualize a página e tente novamente.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $newsletterMessage = 'Informe um endereço de e-mail válido.';
    } else {
        $readNewsletter = new Read();
        $readNewsletter->fullRead('SELECT email FROM ' . PREFIX . 'newsletter WHERE email = :email', http_build_query(array('email' => $email)));
        if ($readNewsletter->getResult()) {
            $newsletterMessage = 'Este e-mail já está cadastrado. Obrigado!';
            $newsletterSuccess = true;
        } else {
            $createNewsletter = new Create();
            $createNewsletter->ExeCreate(PREFIX . 'newsletter', array('email' => $email, 'data' => date('Y-m-d')));
            $newsletterSuccess = (bool) $createNewsletter->getResult();
            $newsletterMessage = $newsletterSuccess ? 'Cadastro realizado. Obrigado por se conectar à Santa Casa!' : 'Não foi possível cadastrar agora. Tente novamente mais tarde.';
        }
    }
}
?>
<footer class="site-footer" id="footer"><div class="site-container"><div class="footer-grid">
<div class="footer-brand"><a href="<?= scl_url() ?>"><img src="<?= scl_url('resources/img/logo-footer.svg') ?>" alt="Santa Casa de Lorena" width="175" height="80" loading="lazy"></a><p>Cuidado que acolhe.<br>Uma história dedicada à vida.</p><div class="social-links"><a href="https://www.facebook.com/santacasadelorena/" aria-label="Santa Casa no Facebook">Facebook ↗</a><a href="https://www.instagram.com/santacasadelorena/" aria-label="Santa Casa no Instagram">Instagram ↗</a></div></div>
<div><h2>Acesso rápido</h2><a href="<?= scl_url('servicos/manual-do-paciente-e-visitantes') ?>">Pacientes e visitantes</a><a href="<?= scl_url('institucional/portal-transparencia') ?>">Portal da transparência</a><a href="http://www.emendasdasantacasadelorena.com.br/" target="_blank" rel="noopener noreferrer">Emendômetro ↗</a><a href="<?= scl_escape(scl_contact_url()) ?>">Contato e ouvidoria</a><a href="<?= scl_escape(scl_contact_url('trabalhe_conosco')) ?>">Trabalhe conosco</a><a href="<?= scl_escape(scl_contact_url('pesquisa')) ?>">Pesquisa de atendimento</a></div>
<div><h2>Encontre a Santa Casa</h2><p class="footer-contact"><?= scl_icon('pin') ?><span><?= scl_escape(strip_tags($localizacao['localizacao'] ?? 'Lorena · São Paulo')) ?></span></p><?php if (!empty($localizacao['telefone'])): ?><a class="footer-contact" href="tel:<?= scl_escape(preg_replace('/[^0-9+]/', '', $localizacao['telefone'])) ?>"><?= scl_icon('phone') ?><?= scl_escape($localizacao['telefone']) ?></a><?php endif; ?><a class="footer-contact" href="tel:+551231593349"><?= scl_icon('phone') ?>(12) 3159-3349</a><a class="footer-location" href="<?= scl_escape(scl_contact_url('localizacao')) ?>">Localização e contatos →</a></div>
<div><h2>Vamos manter contato?</h2><p>Receba novidades e acompanhe nossas ações.</p><form method="post" action="#footer" id="form_news"><label for="newsletter">Seu e-mail</label><div class="newsletter-field"><input type="email" id="newsletter" name="newsletter" autocomplete="email" placeholder="voce@exemplo.com" required maxlength="254"><button type="submit" aria-label="Cadastrar e-mail"><?= scl_icon('arrow') ?></button></div><input type="hidden" name="newsletter_token" value="<?= scl_escape($_SESSION['newsletter_token']) ?>"><?php if ($newsletterMessage): ?><p class="newsletter-message <?= $newsletterSuccess ? 'success' : 'error' ?>" role="status"><?= scl_escape($newsletterMessage) ?></p><?php endif; ?></form></div>
</div><div class="footer-bottom"><span>© <?= date('Y') ?> Santa Casa de Lorena. Todos os direitos reservados.</span><a href="#conteudo">Voltar ao topo ↑</a></div></div></footer>
<?php if (!defined('SCL_PREVIEW') && in_array($r_DIR['page'] ?? '', array('fale-conosco', 'fale_conosco', 'doacoes'), true)): ?><script src="https://www.google.com/recaptcha/api.js" async defer></script><?php endif; ?>
</body>
</html>
