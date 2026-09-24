<?php
/** Isolated SQLite adapter; never loads production configuration or credentials. */
if (PHP_SAPI !== 'cli' && (PHP_SAPI !== 'cli-server' || !in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true))) { http_response_code(404); exit; }
class SecurityTestPDO extends \Pdo\Sqlite {
    public function __construct(string $file = ':memory:') {
        parent::__construct('sqlite:' . $file, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        $this->createFunction('IF', fn($condition, $yes, $no) => $condition ? $yes : $no, 3);
    }
    public function prepare(string $query, array $options = []): PDOStatement|false {
        $query = str_replace(' FOR UPDATE', '', $query);
        if (str_contains($query, 'ON DUPLICATE KEY UPDATE attempts')) {
            $query = preg_replace('/ON DUPLICATE KEY UPDATE.*$/', 'ON CONFLICT(bucket) DO UPDATE SET attempts = CASE WHEN window_start = excluded.window_start THEN attempts + 1 ELSE 1 END, window_start = excluded.window_start', $query);
        } elseif (str_contains($query, 'ON DUPLICATE KEY UPDATE token_hash')) {
            $query = preg_replace('/ON DUPLICATE KEY UPDATE.*$/', 'ON CONFLICT(id_usuario) DO UPDATE SET token_hash = excluded.token_hash, expires_at = excluded.expires_at', $query);
        }
        return parent::prepare($query, $options);
    }
}
class Conn {
    public static PDO $db;
    public static function getConn(): PDO { return self::$db; }
}
function security_fixture(PDO $db): void {
    $db->exec('CREATE TABLE IF NOT EXISTS scl_usuario (id_usuario INTEGER PRIMARY KEY AUTOINCREMENT, usuario TEXT UNIQUE, email TEXT, nome TEXT, senha TEXT, nivel INTEGER, status INTEGER, security_version INTEGER DEFAULT 0, img TEXT DEFAULT "", cadastro TEXT, criado_por INTEGER, alterado_por INTEGER, data_alteracao TEXT)');
    $db->exec('CREATE TABLE IF NOT EXISTS scl_password_reset (id_usuario INTEGER PRIMARY KEY, token_hash TEXT UNIQUE, expires_at INTEGER)');
    $db->exec('CREATE TABLE IF NOT EXISTS scl_auth_attempt (bucket TEXT PRIMARY KEY, window_start INTEGER, attempts INTEGER)');
    $q = $db->prepare('INSERT OR IGNORE INTO scl_usuario (id_usuario, usuario, email, nome, senha, nivel, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
    foreach ([[1, 'admin', 3, 1], [2, 'disabled', 3, 0], [3, 'limited', 1, 1], [4, 'editor', 2, 1]] as [$id, $username, $level, $status]) {
        $q->execute([$id, $username, $username . '@example.invalid', 'Synthetic fixture', md5('fixture-pass'), $level, $status]);
    }
}
