<?php
/** Administrative security boundary. Requires PHP 8.2+ and the security migration. */
function scl_security_boot(): void {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    if (session_status() !== PHP_SESSION_ACTIVE) {
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.gc_maxlifetime', '28800');
        session_name('SCLSESSION');
        session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => str_starts_with((string) getenv('SCL_HOME'), 'https://'), 'httponly' => true, 'samesite' => 'Lax']);
        session_start();
    }
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: same-origin');
        header('X-Frame-Options: SAMEORIGIN');
    }
}
function scl_csrf_token(): string {
    return $_SESSION['admin_csrf'] ??= bin2hex(random_bytes(32));
}
function scl_admin_input(): array {
    $data = $_POST;
    unset($data['_csrf']);
    return $data;
}
function scl_report_text(mixed $value): string {
    $value = (string) $value;
    // HTML-based spreadsheet exports must not turn user content into formulas.
    if (preg_match('/^[\s]*[=+@-]/u', $value)) $value = "'" . $value;
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function scl_csrf_valid(mixed $token): bool {
    return is_string($token) && isset($_SESSION['admin_csrf']) && hash_equals($_SESSION['admin_csrf'], $token);
}
function scl_deny(int $status = 403): never {
    http_response_code($status);
    header('Cache-Control: no-store');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => $status === 401 ? 'Sua sessão expirou. Entre novamente.' : 'Operação não permitida.']);
    exit;
}
function scl_password_hash(string $password): string {
    if (strlen($password) < 12 || strlen($password) > 72 || str_contains($password, "\0")) {
        throw new InvalidArgumentException('Use uma senha de 12 a 72 bytes.');
    }
    return password_hash($password, PASSWORD_DEFAULT);
}
function scl_password_verify(string $password, string $hash): bool {
    if (strlen($password) > 4096 || str_contains($password, "\0")) return false;
    if (str_starts_with($hash, '$2') && strlen($password) > 72) return false;
    // Compatibility only: never create another MD5 hash in storage.
    return preg_match('/^[a-f0-9]{32}$/iD', $hash)
        ? hash_equals(strtolower($hash), md5($password)) : password_verify($password, $hash);
}
function scl_session_valid(array $session, array|false $user, int $now): bool {
    return $user && (int) ($user['status'] ?? 0) === 1
        && (int) ($user['nivel'] ?? 0) === 3
        && isset($session['auth_started'], $session['auth_seen'], $session['auth_stamp'])
        && $now - $session['auth_seen'] < 1800 && $now - $session['auth_started'] < 28800
        && hash_equals($session['auth_stamp'], hash('sha256', $user['senha'] . ':' . ($user['security_version'] ?? 0)));
}
function scl_public_user(array $user): array {
    return array_intersect_key($user, array_flip(['id_usuario', 'nome', 'email', 'usuario', 'nivel', 'status', 'img', 'cadastro']));
}
function scl_avatar_url(): string {
    return 'includes/tim.php?src=' . rawurlencode((string) (($_SESSION['UsuarioLogin']['img'] ?? '') ?: 'resources/img/user.png'));
}
function scl_admin_user(): array|false {
    if (empty($_SESSION['UsuarioLogin']['id_usuario'])) return false;
    $query = Conn::getConn()->prepare('SELECT * FROM ' . PREFIX . 'usuario WHERE id_usuario = ?');
    $query->execute([(int) $_SESSION['UsuarioLogin']['id_usuario']]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    if (!scl_session_valid($_SESSION, $user, time())) {
        unset($_SESSION['UsuarioLogin'], $_SESSION['auth_stamp'], $_SESSION['admin_csrf']);
        return false;
    }
    $_SESSION['auth_seen'] = time();
    return $_SESSION['UsuarioLogin'] = scl_public_user($user);
}
function scl_admin_require(): array {
    $user = scl_admin_user();
    if (!$user) scl_deny(empty($_SESSION['UsuarioLogin']) ? 401 : 403);
    header('Cache-Control: no-store');
    if (!in_array($_SERVER['REQUEST_METHOD'] ?? 'GET', ['GET', 'HEAD', 'POST'], true)) scl_deny(405);
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !scl_csrf_valid($_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['_csrf'] ?? null)) scl_deny();
    foreach (array_merge($_GET, $_POST) as $key => $value) {
        if (preg_match('/^(id_|id$|Id[A-Z]|atual$)/', (string) $key)) {
            foreach (is_array($value) ? $value : [$value] as $id) {
                // New tag labels are the one intentional nonnumeric identifier.
                if ($key === 'id_tag') continue;
                if (!is_scalar($id) || !preg_match('/^\d{1,10}$/D', (string) $id)) scl_deny(400);
            }
        }
        if (in_array($key, ['data_inicio', 'data_fim'], true) && $value !== '') {
            $date = is_string($value) ? DateTimeImmutable::createFromFormat('!d/m/Y', $value) : false;
            if (!$date || $date->format('d/m/Y') !== $value) scl_deny(400);
        }
    }
    return $user;
}
function scl_login_session(array $user): void {
    session_regenerate_id(true);
    $_SESSION = ['UsuarioLogin' => scl_public_user($user), 'auth_started' => time(), 'auth_seen' => time(),
        'auth_stamp' => hash('sha256', $user['senha'] . ':' . ($user['security_version'] ?? 0))];
    scl_csrf_token();
}
function scl_logout(): void {
    $_SESSION = [];
    session_regenerate_id(true);
}
/** Atomic, shared limits: independent of cookies and of untrusted proxy headers. */
function scl_rate_allow(PDO $db, string $scope, string $identity, int $limit, int $seconds = 900): bool {
    $bucket = hash('sha256', $scope . ':' . strtolower($identity));
    $slot = intdiv(time(), $seconds) * $seconds;
    $q = $db->prepare('INSERT INTO ' . PREFIX . 'auth_attempt (bucket, window_start, attempts) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE attempts = IF(window_start = VALUES(window_start), attempts + 1, 1), window_start = VALUES(window_start)');
    $q->execute([$bucket, $slot]);
    $q = $db->prepare('SELECT attempts FROM ' . PREFIX . 'auth_attempt WHERE bucket = ?');
    $q->execute([$bucket]);
    if (random_int(1, 100) === 1) $db->exec('DELETE FROM ' . PREFIX . 'auth_attempt WHERE window_start < ' . (time() - 86400));
    return (int) $q->fetchColumn() <= $limit;
}
function scl_authenticate(string $username, string $password): bool {
    $db = Conn::getConn();
    $ip = scl_rate_allow($db, 'login-ip', $_SERVER['REMOTE_ADDR'] ?? '', 30);
    if (!$ip) return false;
    $account = scl_rate_allow($db, 'login-account', trim($username), 10);
    if (!$ip || !$account) return false;
    $q = $db->prepare('SELECT * FROM ' . PREFIX . 'usuario WHERE usuario = ? LIMIT 1');
    $q->execute([trim($username)]);
    $user = $q->fetch(PDO::FETCH_ASSOC);
    $hash = $user['senha'] ?? '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';
    $valid = scl_password_verify($password, $hash);
    if (!$valid || !$user || (int) $user['status'] !== 1 || (int) $user['nivel'] !== 3) return false;
    if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
        // Old short passwords remain valid during the controlled migration.
        if (strlen($password) > 72 || str_contains($password, "\0")) return false;
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $q = $db->prepare('UPDATE ' . PREFIX . 'usuario SET senha = ? WHERE id_usuario = ? AND senha = ?');
        $q->execute([$newHash, $user['id_usuario'], $hash]);
        if ($q->rowCount() !== 1) return false;
        $user['senha'] = $newHash;
    }
    scl_login_session($user);
    return true;
}
function scl_reset_request(string $email, ?callable $mailerFactory = null): void {
    if (!filter_var(HOME, FILTER_VALIDATE_URL) || !str_starts_with(HOME, 'https://')) throw new RuntimeException('Configure the public HTTPS origin for password recovery.');
    $db = Conn::getConn();
    $ip = scl_rate_allow($db, 'reset-ip', $_SERVER['REMOTE_ADDR'] ?? '', 10);
    if (!$ip) return;
    $account = scl_rate_allow($db, 'reset-account', trim($email), 3);
    if (!$ip || !$account || !filter_var($email, FILTER_VALIDATE_EMAIL)) return;
    $q = $db->prepare('SELECT id_usuario, email FROM ' . PREFIX . 'usuario WHERE email = ? AND status = 1 AND nivel = 3 LIMIT 1');
    $q->execute([trim($email)]);
    $user = $q->fetch(PDO::FETCH_ASSOC);
    if (!$user) return;
    $token = bin2hex(random_bytes(32));
    $q = $db->prepare('INSERT INTO ' . PREFIX . 'password_reset (id_usuario, token_hash, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token_hash = VALUES(token_hash), expires_at = VALUES(expires_at)');
    $q->execute([$user['id_usuario'], hash('sha256', $token), time() + 1800]);
    require_once __DIR__ . '/community_mailer.php';
    $mail = $mailerFactory ? $mailerFactory() : scl_community_mailer();
    $mail->CharSet = 'UTF-8';
    $mail->setFrom(getenv('SCL_MAIL_FROM') ?: 'webmaster@santacasalorena.org.br', 'Santa Casa de Lorena');
    $mail->addAddress($user['email']);
    $mail->Subject = 'Redefinição de senha';
    $mail->Body = "Para criar uma nova senha, abra o link em até 30 minutos:\n" . HOME . 'admin/?token=' . $token . "\nSe você não solicitou, ignore esta mensagem.";
    try { $mail->send(); } catch (Throwable $e) { error_log('SCL: falha no envio de recuperação de senha.'); }
}
function scl_reset_complete(string $token, string $password): bool {
    if (!preg_match('/^[a-f0-9]{64}$/D', $token)) return false;
    $hash = scl_password_hash($password);
    $db = Conn::getConn();
    $db->beginTransaction();
    try {
        $q = $db->prepare('SELECT id_usuario, expires_at FROM ' . PREFIX . 'password_reset WHERE token_hash = ? FOR UPDATE');
        $q->execute([hash('sha256', $token)]);
        $reset = $q->fetch(PDO::FETCH_ASSOC);
        if (!$reset || (int) $reset['expires_at'] <= time()) { $db->rollBack(); return false; }
        $q = $db->prepare('UPDATE ' . PREFIX . 'usuario SET senha = ?, security_version = security_version + 1 WHERE id_usuario = ? AND status = 1 AND nivel = 3');
        $q->execute([$hash, $reset['id_usuario']]);
        $changed = $q->rowCount() === 1;
        $q = $db->prepare('DELETE FROM ' . PREFIX . 'password_reset WHERE id_usuario = ?');
        $q->execute([$reset['id_usuario']]);
        $db->commit();
        return $changed;
    } catch (Throwable $e) { if ($db->inTransaction()) $db->rollBack(); throw $e; }
}
