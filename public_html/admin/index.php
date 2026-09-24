<?php
require __DIR__ . '/../_app/Config.inc.php';
header('Cache-Control: no-store');
header('Referrer-Policy: no-referrer');
$notice = '';
$token = (string) ($_POST['token'] ?? $_GET['token'] ?? '');
if (!preg_match('/^[a-f0-9]{64}$/D', $token)) $token = '';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!scl_csrf_valid($_POST['_csrf'] ?? null)) scl_deny();
    switch ($_POST['action'] ?? '') {
        case 'login':
            if (scl_authenticate((string) ($_POST['usuario'] ?? ''), (string) ($_POST['senha'] ?? ''))) {
                header('Location: painel.php', true, 303); exit;
            }
            $notice = 'Não foi possível entrar. Confira seus dados ou tente novamente mais tarde.';
            break;
        case 'request':
            scl_reset_request((string) ($_POST['email'] ?? ''));
            $notice = 'Se houver uma conta ativa com esse e-mail, você receberá um link válido por 30 minutos.';
            break;
        case 'reset':
            try {
                $allowed = scl_rate_allow(Conn::getConn(), 'reset-consume', $_SERVER['REMOTE_ADDR'] ?? '', 10);
                $done = $allowed && scl_reset_complete($token, (string) ($_POST['senha'] ?? ''));
                $notice = $done ? 'Senha atualizada. Entre com sua nova senha.' : 'Link inválido ou expirado. Solicite um novo link.';
                if ($done) { $token = ''; scl_logout(); }
            } catch (InvalidArgumentException $e) { $notice = $e->getMessage(); }
            break;
        default: scl_deny(400);
    }
}
function login_escape($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Acesso administrativo | Santa Casa de Lorena</title>
<link rel="stylesheet" href="../resources/css/admin-login.css"></head><body><aside class="login-story"><a href="../" aria-label="Santa Casa de Lorena"><img src="../resources/img/logo-footer.svg" alt="Santa Casa de Lorena"></a><span>ADMINISTRAÇÃO DO SITE</span><h2>Cuidar da informação.<br>Conectar pessoas.</h2><p>Um espaço para manter a comunidade informada e os canais de atendimento próximos de quem precisa.</p></aside><main>
<p class="login-kicker">BEM-VINDO À SANTA CASA</p><h1><?= $token ? 'Criar nova senha' : 'Acesso administrativo' ?></h1>
<?php if ($notice): ?><p class="notice" role="status"><?= login_escape($notice) ?></p><?php endif ?>
<form method="post" action="index.php">
<input type="hidden" name="_csrf" value="<?= login_escape(scl_csrf_token()) ?>">
<input type="hidden" name="action" value="<?= $token ? 'reset' : 'login' ?>">
<?php if ($token): ?><input type="hidden" name="token" value="<?= login_escape($token) ?>"><?php else: ?><label for="usuario">Usuário</label><input id="usuario" name="usuario" autocomplete="username" maxlength="190" required><?php endif ?>
<label for="senha"><?= $token ? 'Nova senha' : 'Senha' ?></label><input id="senha" type="password" name="senha" autocomplete="<?= $token ? 'new-password' : 'current-password' ?>" <?= $token ? 'minlength="12" maxlength="72"' : '' ?> required>
<?php if ($token): ?><small>Use de 12 a 72 caracteres. Prefira uma frase longa e exclusiva.</small><?php endif ?>
<button class="login-submit" type="submit"><?= $token ? 'Salvar nova senha' : 'Entrar' ?></button></form>
<details <?= ($_POST['action']??'')==='request'?'open':'' ?>><summary>Esqueci minha senha</summary><form method="post" action="index.php"><input type="hidden" name="_csrf" value="<?= login_escape(scl_csrf_token()) ?>"><input type="hidden" name="action" value="request"><label for="email">E-mail cadastrado</label><input id="email" name="email" type="email" autocomplete="email" maxlength="254" required><button type="submit">Receber link de recuperação</button></form></details>
<p class="login-back"><a href="../">← Voltar ao site</a></p></main></body></html>
