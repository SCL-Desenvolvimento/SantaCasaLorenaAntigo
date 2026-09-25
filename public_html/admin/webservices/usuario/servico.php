<?php
require __DIR__ . '/../../../_app/Config.inc.php';
$user = scl_admin_require();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') scl_deny(405);
$data = scl_admin_input();
$action = $data['acao'] ?? '';
$db = Conn::getConn();
$table = PREFIX . 'usuario';
$id = (int) ($data['id_usuario'] ?? 0);
$select = 'id_usuario, nome, email, usuario, nivel, status, img, cadastro';
if ($action === 'listUsers') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($db->query("SELECT $select FROM $table WHERE nivel = 3")->fetchAll()); exit;
}
if ($action === 'getUser') {
    $q = $db->prepare("SELECT $select FROM $table WHERE id_usuario = ? AND nivel = 3");
    $q->execute([$id]);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($q->fetch() ?: null); exit;
}
$validators = ['ValidarEmail' => ['email', 'email'], 'ValidarLogin' => ['usuario', 'login'], 'ValidarRG' => ['rg', 'rg'], 'ValidarCPF' => ['cpf', 'cpf']];
if (isset($validators[$action])) {
    [$column, $field] = $validators[$action];
    $q = $db->prepare("SELECT id_usuario FROM $table WHERE $column = ? AND id_usuario <> ? LIMIT 1");
    $q->execute([(string) ($data[$field] ?? ''), (int) ($data['atual'] ?? 0)]);
    echo $q->fetch() ? '0' : '1'; exit;
}
if (in_array($action, ['excluiUser', 'alteraStatus'], true)) {
    // Prevent removal of the operator's own access. Other administrators can manage it.
    if ($id < 1 || $id === (int) $user['id_usuario']) scl_deny();
    $sql = $action === 'excluiUser' ? "DELETE FROM $table WHERE id_usuario = ? AND nivel = 3" : "UPDATE $table SET status = IF(status = 1, 0, 1), security_version = security_version + 1 WHERE id_usuario = ? AND nivel = 3";
    $db->beginTransaction();
    $q = $db->prepare($sql); $q->execute([$id]);
    $affected = $q->rowCount();
    $cleanup = $db->prepare('DELETE FROM ' . PREFIX . 'password_reset WHERE id_usuario = ?'); $cleanup->execute([$id]);
    $db->commit();
    if ($action === 'alteraStatus') {
        $q = $db->prepare("SELECT status FROM $table WHERE id_usuario = ?"); $q->execute([$id]); echo (int) $q->fetchColumn();
    } else echo $affected === 1 ? '1' : '0';
    exit;
}
if (!in_array($action, ['createUser', 'updateUser'], true)) scl_deny(400);
$fields = ['nome' => trim((string) ($data['nome'] ?? '')), 'email' => trim((string) ($data['email'] ?? '')), 'usuario' => trim((string) ($data['usuario'] ?? ''))];
if (!$fields['nome'] || mb_strlen($fields['nome']) > 150 || strlen($fields['email'])>254 || !filter_var($fields['email'], FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9_.@-]{3,100}$/D', $fields['usuario'])) scl_deny(422);
if ($action === 'updateUser') {
    $q = $db->prepare("SELECT id_usuario FROM $table WHERE id_usuario = ? AND nivel = 3"); $q->execute([$id]);
    if (!$q->fetch()) scl_deny(404);
}
$q = $db->prepare("SELECT id_usuario FROM $table WHERE (usuario = ? OR email = ?) AND id_usuario <> ? LIMIT 1");
$q->execute([$fields['usuario'], $fields['email'], $action === 'updateUser' ? $id : 0]);
if ($q->fetch()) scl_deny(409);
$password = (string) ($data['senha'] ?? '');
if ($action === 'createUser' || $password !== '') {
    try { $fields['senha'] = scl_password_hash($password); } catch (InvalidArgumentException $e) { scl_deny(422); }
}
if (!empty($_FILES['img']['name'])) {
    $upload = new Upload('arquivos');
    $upload->Image($_FILES['img'], null, 500, '/fotousuario');
    if (!$upload->getResult()) scl_deny(422);
    $fields['img'] = $upload->getResult();
}
$fields['status'] = isset($data['status']) && (string)$data['status']==='1' ? 1 : 0;
if ($action === 'createUser') {
    $fields += ['nivel' => 3, 'criado_por' => $user['id_usuario'], 'cadastro' => date('Y-m-d H:i:s')];
    $q = $db->prepare("INSERT INTO $table (" . implode(',', array_keys($fields)) . ') VALUES (' . implode(',', array_fill(0, count($fields), '?')) . ')');
    $q->execute(array_values($fields)); echo $db->lastInsertId();
} else {
    if ($id === (int) $user['id_usuario']) $fields['status'] = 1;
    $fields += ['alterado_por' => $user['id_usuario'], 'data_alteracao' => date('Y-m-d H:i:s')];
    $sets = implode(', ', array_map(fn($key) => "$key = ?", array_keys($fields)));
    $db->beginTransaction();
    $q = $db->prepare("UPDATE $table SET $sets, security_version = security_version + 1 WHERE id_usuario = ? AND nivel = 3");
    $q->execute([...array_values($fields), $id]);
    $q = $db->prepare('DELETE FROM ' . PREFIX . 'password_reset WHERE id_usuario = ?'); $q->execute([$id]);
    $db->commit();
    // Changing credentials or access revokes existing sessions, including this one.
    if ($id === (int) $user['id_usuario']) scl_logout();
    echo '1';
}
