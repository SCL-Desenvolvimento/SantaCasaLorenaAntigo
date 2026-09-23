<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
ob_start();
require __DIR__ . '/security-db.php';
require __DIR__ . '/../includes/security.php';
require __DIR__ . '/../includes/community_mailer.php';
class ResetTestMailer extends \PHPMailer\PHPMailer\PHPMailer {
    public static array $messages=[];
    public function send() { self::$messages[]=$this->Body; return true; }
}
define('PREFIX', 'scl_'); define('HOME', 'https://hospital.example.invalid/');
putenv('SCL_HOME=https://hospital.example.invalid/');
scl_security_boot();
Conn::$db = new SecurityTestPDO();
$db = Conn::$db;
security_fixture($db);
$checks = 0;
function check_security(bool $ok, string $message): void { global $checks; ++$checks; if (!$ok) throw new RuntimeException($message); }
$password = 'Uma frase exclusiva 2026!';
$hash = scl_password_hash($password);
check_security(password_verify($password, $hash), 'Modern hash verification');
check_security(!scl_password_verify('wrong', $hash), 'Wrong password denied');
check_security(!scl_password_verify(str_repeat('x',73),scl_password_hash(str_repeat('x',72))),'No acceptance through bcrypt truncation');
check_security(scl_password_verify('fixture-pass', md5('fixture-pass')), 'Legacy compatibility');
foreach (['short', str_repeat('x', 73), "twelve\0characters"] as $bad) {
    try { scl_password_hash($bad); check_security(false, 'Invalid password accepted'); } catch (InvalidArgumentException $e) { check_security(true, 'Password policy'); }
}
$token = scl_csrf_token();
check_security(strlen($token) === 64 && scl_csrf_valid($token), 'CSRF token');
check_security(!scl_csrf_valid(null) && !scl_csrf_valid('wrong') && !scl_csrf_valid([$token]), 'CSRF rejects malformed tokens');
$before = session_id();
check_security(scl_authenticate('admin', 'fixture-pass'), 'Legacy login');
check_security($before !== session_id(), 'Session fixation prevention');
$user = $db->query('SELECT * FROM scl_usuario WHERE id_usuario = 1')->fetch();
check_security(password_verify('fixture-pass', $user['senha']) && strlen($user['senha']) > 32, 'MD5 migrated in storage');
check_security(!isset($_SESSION['UsuarioLogin']['senha']), 'No password hash in session');
check_security(scl_session_valid($_SESSION, $user, time()), 'Valid session');
foreach ([['auth_seen', time()-1800], ['auth_started', time()-28800], ['auth_stamp', 'forged']] as [$key,$value]) {
    $session = $_SESSION; $session[$key] = $value;
    check_security(!scl_session_valid($session, $user, time()), 'Expired or modified session denied');
}
foreach ([['status',0], ['nivel',1], ['nivel',2], ['security_version',1], ['senha','changed']] as [$key,$value]) {
    $changed = $user; $changed[$key] = $value;
    check_security(!scl_session_valid($_SESSION, $changed, time()), 'Revocation detected');
}
check_security(!scl_session_valid($_SESSION, false, time()), 'Deleted account denied');
check_security(!scl_session_valid(['UsuarioLogin' => $user], $user, time()), 'Old sessions require new login');
foreach (['disabled','limited','editor','unknown'] as $name) check_security(!scl_authenticate($name, 'fixture-pass'), 'Unauthorized account denied');
check_security(!scl_authenticate("admin' OR 1=1 --", 'fixture-pass'), 'Injected username denied');
for ($i=0;$i<3;$i++) check_security(scl_rate_allow($db, 'unit', 'one', 3), 'Within rate limit');
check_security(!scl_rate_allow($db, 'unit', 'one', 3), 'Over rate limit');
scl_logout();
check_security(!scl_rate_allow($db, 'unit', 'one', 3), 'Rate limit survives logout');
check_security(scl_rate_allow($db, 'unit', 'another', 3), 'Separate rate identity');
function set_reset(string $token, int $expires): void {
    $q=Conn::getConn()->prepare('INSERT INTO scl_password_reset (id_usuario, token_hash, expires_at) VALUES (1, ?, ?) ON CONFLICT(id_usuario) DO UPDATE SET token_hash=excluded.token_hash, expires_at=excluded.expires_at');
    $q->execute([hash('sha256',$token),$expires]);
}
$resetToken = bin2hex(random_bytes(32));
set_reset($resetToken, time()-1);
check_security(!scl_reset_complete($resetToken, $password), 'Expired reset denied');
set_reset($resetToken, time()+1800);
check_security(!scl_reset_complete(str_repeat('0',64), $password), 'Wrong reset denied');
check_security(scl_reset_complete($resetToken, $password), 'Reset completes');
check_security(!scl_reset_complete($resetToken, $password), 'Replay denied');
$updated=$db->query('SELECT * FROM scl_usuario WHERE id_usuario=1')->fetch();
check_security(password_verify($password,$updated['senha']) && $updated['security_version']===1, 'Reset changes hash and revokes sessions');
set_reset($resetToken,time()+1800);
$db->exec("CREATE TRIGGER fail_reset BEFORE UPDATE ON scl_usuario BEGIN SELECT RAISE(ABORT, 'test rollback'); END");
try { scl_reset_complete($resetToken,$password); check_security(false,'Expected failure'); } catch(PDOException $e) { check_security(!$db->inTransaction(),'Transaction rolled back'); }
check_security((int)$db->query('SELECT COUNT(*) FROM scl_password_reset')->fetchColumn()===1,'Token preserved on failed update');
$db->exec('DROP TRIGGER fail_reset');
scl_reset_request('admin@example.invalid', fn()=>new ResetTestMailer(true));
$message=ResetTestMailer::$messages[0] ?? '';
check_security(preg_match('/token=([a-f0-9]{64})/', $message, $match)===1,'Reset email contains a token');
$issued=$match[1];
check_security(!str_contains($message,$password),'Reset never emails a password');
$saved=$db->query('SELECT * FROM scl_password_reset WHERE id_usuario=1')->fetch();
check_security(hash_equals($saved['token_hash'],hash('sha256',$issued)) && $saved['token_hash']!==$issued,'Only token digest stored');
check_security($saved['expires_at']<=time()+1800 && $saved['expires_at']>time(),'Thirty-minute token expiry');
check_security($db->query('SELECT senha FROM scl_usuario WHERE id_usuario=1')->fetchColumn()===$updated['senha'],'Request does not change current password');
scl_reset_request('admin@example.invalid',fn()=>new ResetTestMailer(true));
check_security(!scl_reset_complete($issued,$password),'New request revokes previous reset token');
foreach(['unknown@example.invalid','disabled@example.invalid','limited@example.invalid'] as $email)scl_reset_request($email,fn()=>new ResetTestMailer(true));
check_security(count(ResetTestMailer::$messages)===2,'No recovery email for unknown or unauthorized accounts');
require __DIR__ . '/../includes/private_files.php';
$temporary=sys_get_temp_dir().'/scl-private-unit-'.bin2hex(random_bytes(8));
mkdir($temporary.'/public/arquivos/curriculuns/2020/01',0750,true);
define('DIR',$temporary.'/public/');
putenv('SCL_PRIVATE_DIR='.$temporary.'/storage');
$pdf="%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF";
$legacy='arquivos/curriculuns/2020/01/fixture.PDF';
file_put_contents(DIR.$legacy,$pdf);
check_security(scl_resume_path($legacy)!==false,'Legacy year/month resume remains accessible through protected handler');
check_security(scl_resume_path('arquivos/curriculuns/../../_app/Config.inc.php')===false,'Resume traversal denied');
check_security(scl_resume_path('https://example.invalid/file.pdf')===false,'Remote resume denied');
mkdir($temporary.'/storage/legacy/curriculuns/2020/01',0750,true);
file_put_contents($temporary.'/storage/legacy/curriculuns/2020/01/fixture.PDF',$pdf);
check_security(str_contains(str_replace('\\','/',scl_resume_path($legacy)),'/storage/legacy/'),'Migrated legacy copy preferred');
putenv('SCL_PRIVATE_DIR='.DIR.'private');
try{scl_private_directory();check_security(false,'Storage within public root accepted');}catch(RuntimeException $e){check_security(true,'Public storage rejected');}
function clean_security_fixture($directory){foreach(new FilesystemIterator($directory) as $entry){if($entry->isDir()&&!$entry->isLink())clean_security_fixture($entry->getPathname());else unlink($entry->getPathname());}rmdir($directory);}
if(!str_starts_with($temporary,sys_get_temp_dir().'/scl-private-unit-'))throw new RuntimeException('Invalid fixture cleanup');
clean_security_fixture($temporary);
check_security(!str_contains(scl_report_text('<script>alert(1)</script>'),'<script>'),'Report HTML escaped');
check_security(str_starts_with(html_entity_decode(scl_report_text('=1+1')), "'"),'Spreadsheet formula neutralized');
$params=session_get_cookie_params();
check_security($params['secure'] && $params['httponly'] && $params['samesite']==='Lax','Cookie protection');
check_security(!isset(scl_public_user($user)['senha']) && !isset(scl_public_user($user)['security_version']),'API field allowlist');
session_write_close();
echo "OK: $checks security checks; isolated SQLite, no production database or email.\n";
ob_end_flush();
