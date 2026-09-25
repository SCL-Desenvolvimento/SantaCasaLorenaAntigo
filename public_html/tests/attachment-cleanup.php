<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__) . '/includes/attachment_cleanup.php';
$root = sys_get_temp_dir() . '/scl-attachment-test-' . bin2hex(random_bytes(8));
mkdir($root); mkdir($root . '/arquivos');
define('DIR', $root . '/'); define('PREFIX', 'test_');
$db = new PDO('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
class Conn { public static $db; public static function getConn() { return self::$db; } }
Conn::$db=$db;
require dirname(__DIR__) . '/_app/Conn/Delete.class.php';
$db->exec('CREATE TABLE test_noticia (id_noticia INTEGER, img TEXT, descricao TEXT); CREATE TABLE test_tag_noticia (id_noticia INTEGER); CREATE TABLE test_anexo (id_anexo INTEGER, url TEXT); CREATE TABLE test_galeria_anexo (id_anexo INTEGER,id_galeria INTEGER); CREATE TABLE test_capacidade (id_capacidade INTEGER); CREATE TABLE test_capacidade_imagem (id_capacidade INTEGER,img TEXT)');
$checks=0;
function verify($condition,$message) { global $checks; if(!$condition) throw new RuntimeException($message); $checks++; }
function fixture($name) { file_put_contents(DIR.'arquivos/'.$name, 'temporary fixture'); return 'arquivos/'.$name; }
try {
    $cover=fixture('cover.png'); $inline=fixture('inline.pdf');
    $q=$db->prepare('INSERT INTO test_noticia VALUES (?,?,?)');
    $q->execute([1,$cover,'<a href="https://site.example/arquivos/inline.pdf">PDF</a>']);
    $q->execute([2,$cover,'']);
    $rows=$db->query('SELECT * FROM test_noticia WHERE id_noticia=1')->fetchAll();
    $delete=new Delete();$delete->ExeDelete('test_noticia','WHERE id_noticia=:id','id=1');
    verify($delete->getResult(), 'Delete must succeed');
    scl_cleanup_attachments($db,scl_attachment_candidates($rows));
    verify(is_file(DIR.$cover),'Shared cover must survive');
    verify(!is_file(DIR.$inline),'Embedded PDF must be removed');
    $db->exec('DELETE FROM test_noticia');
    scl_cleanup_attachments($db,[$cover]);
    verify(!is_file(DIR.$cover),'Last reference removal must delete image');
    $rollback=fixture('rollback.png');
    $q->execute([3,$rollback,'']);$db->beginTransaction();
    $delete->ExeDelete('test_noticia','WHERE id_noticia=:id','id=3');
    scl_cleanup_attachments($db,[$rollback]);
    verify(is_file(DIR.$rollback),'Uncommitted delete must preserve file');
    $db->rollBack();scl_cleanup_attachments($db,[$rollback]);
    verify(is_file(DIR.$rollback),'Rolled back delete must preserve file');
    $gallery=fixture('gallery.png');
    $db->exec("INSERT INTO test_anexo VALUES (1,'$gallery'); INSERT INTO test_galeria_anexo VALUES (1,1),(1,2)");
    $db->exec('DELETE FROM test_galeria_anexo WHERE id_galeria=1');
    verify(scl_release_gallery_attachments($db,[1])===[], 'Other gallery must preserve attachment metadata');
    $db->exec('DELETE FROM test_galeria_anexo');
    $released=scl_release_gallery_attachments($db,[1]);
    scl_cleanup_attachments($db,scl_attachment_candidates($released));
    verify(!is_file(DIR.$gallery),'Unused gallery attachment must be deleted');
    $child=fixture('child.png');$db->exec("INSERT INTO test_capacidade VALUES (1); INSERT INTO test_capacidade_imagem VALUES (1,'$child')");
    $delete->ExeDelete('test_capacidade','WHERE id_capacidade=:id','id=1');
    verify($delete->getResult() && !$db->query('SELECT COUNT(*) FROM test_capacidade_imagem')->fetchColumn(),'Parent deletion must remove child records');
    scl_cleanup_attachments($db,[$child]);verify(!is_file(DIR.$child),'Child file must be removed');
    verify(scl_attachment_path('arquivos/../outside.pdf')===false,'Traversal must be refused');
    verify(scl_attachment_path('resources/logo.png')===false,'Application assets must be preserved');
    fixture('program.php');verify(scl_attachment_path('arquivos/program.php')===false,'Executable file must be preserved');
    echo "OK: $checks attachment cleanup checks.\n";
} finally {
    if($db->inTransaction())$db->rollBack();
    foreach(glob($root.'/arquivos/*') as $file)unlink($file);
    rmdir($root.'/arquivos');rmdir($root);
}
