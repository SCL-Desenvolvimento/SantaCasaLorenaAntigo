<?php
require __DIR__.'/../_app/Config.inc.php';
scl_admin_require();
require_once __DIR__.'/../includes/attachment_cleanup.php';
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');
$db=Conn::getConn();$data=scl_admin_input();$action=$_SERVER['REQUEST_METHOD']==='GET'?($_GET['acao']??'list'):($data['acao']??'');
$id=filter_var($data['id_galeria']??$_GET['id_galeria']??0,FILTER_VALIDATE_INT);
$g=PREFIX.'galeria';$a=PREFIX.'anexo';$ga=PREFIX.'galeria_anexo';
function gallery_fail(string $message,int $status=422): never {http_response_code($status);echo json_encode(['error'=>$message]);exit;}
if($action==='list') {echo json_encode($db->query("SELECT G.id_galeria,G.nome,(SELECT COUNT(*) FROM $ga GA WHERE GA.id_galeria=G.id_galeria) AS total FROM $g G ORDER BY G.id_galeria DESC")->fetchAll());exit;}
if(!in_array($action,['get','create','save','upload','delete'],true))gallery_fail('Operação inválida.',400);
if($action!=='get'&&$_SERVER['REQUEST_METHOD']!=='POST')scl_deny(405);
if($action!=='create'){$q=$db->prepare("SELECT * FROM $g WHERE id_galeria=?");$q->execute([$id]);$gallery=$q->fetch();if(!$gallery)gallery_fail('Galeria não encontrada.',404);}
if($action==='get'){$q=$db->prepare("SELECT A.id_anexo,A.url,COALESCE(GA.legenda,A.descricao,'') AS legenda FROM $ga GA INNER JOIN $a A ON A.id_anexo=GA.id_anexo WHERE GA.id_galeria=? ORDER BY GA.ordem,A.id_anexo");$q->execute([$id]);echo json_encode(['gallery'=>$gallery,'photos'=>$q->fetchAll()]);exit;}
if($action==='create'||$action==='save'){$name=trim((string)($data['nome']??''));if($name===''||mb_strlen($name)>150)gallery_fail('Informe um nome de até 150 caracteres.');}
if($action==='create'){$q=$db->prepare("INSERT INTO $g (nome) VALUES (?)");$q->execute([$name]);echo json_encode(['id'=>(int)$db->lastInsertId()]);exit;}
if($action==='upload'){
 $upload=new Upload('arquivos');$upload->Image($_FILES['image']??[],null,1920,'galeria');if(!$upload->getResult())gallery_fail('Selecione uma imagem JPG ou PNG válida, de até 10 MB.');
 $url=$upload->getResult();$mime=(new finfo(FILEINFO_MIME_TYPE))->file(DIR.$url);
 $db->beginTransaction();try{$q=$db->prepare("INSERT INTO $a (url,titulo,descricao,nome,tipo,mime_type,id_usuario,data) VALUES (?,?,?,?,?,?,?,?)");$q->execute([$url,'','','','attachment',$mime,$_SESSION['UsuarioLogin']['id_usuario'],date('Y-m-d H:i:s')]);$photo=(int)$db->lastInsertId();$q=$db->prepare("SELECT COALESCE(MAX(ordem),0)+1 FROM $ga WHERE id_galeria=?");$q->execute([$id]);$order=(int)$q->fetchColumn();$q=$db->prepare("INSERT INTO $ga (id_galeria,id_anexo,ordem,legenda) VALUES (?,?,?,'')");$q->execute([$id,$photo,$order]);$db->commit();echo json_encode(['id_anexo'=>$photo,'url'=>$url,'legenda'=>'']);}catch(Throwable $e){$db->rollBack();throw $e;}exit;
}
$db->beginTransaction();try{
 $q=$db->prepare("SELECT id_anexo FROM $ga WHERE id_galeria=?");$q->execute([$id]);$previousAttachments=$q->fetchAll(PDO::FETCH_COLUMN);
 if($action==='delete'){$q=$db->prepare("DELETE FROM $ga WHERE id_galeria=?");$q->execute([$id]);$q=$db->prepare("DELETE FROM $g WHERE id_galeria=?");$q->execute([$id]);}
 else{$photos=json_decode($data['photos']??'[]',true);if(!is_array($photos)||count($photos)>500)throw new InvalidArgumentException('Lista de imagens inválida.');$ids=[];foreach($photos as $photo){$pid=filter_var($photo['id_anexo']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);if(!$pid||in_array($pid,$ids,true)||!is_string($photo['legenda']??null)||mb_strlen($photo['legenda'])>255)throw new InvalidArgumentException('Revise as legendas e imagens.');$ids[]=$pid;}
 $q=$db->prepare("SELECT id_anexo FROM $ga WHERE id_galeria=?");$q->execute([$id]);$current=array_map('intval',$q->fetchAll(PDO::FETCH_COLUMN));if(array_diff($ids,$current))throw new InvalidArgumentException('Atualize a página antes de salvar.');
 $q=$db->prepare("UPDATE $g SET nome=? WHERE id_galeria=?");$q->execute([$name,$id]);$q=$db->prepare("DELETE FROM $ga WHERE id_galeria=?");$q->execute([$id]);$q=$db->prepare("INSERT INTO $ga (id_galeria,id_anexo,ordem,legenda) VALUES (?,?,?,?)");foreach($photos as $order=>$photo)$q->execute([$id,(int)$photo['id_anexo'],$order,$photo['legenda']]);
 }
 $removedAttachments=scl_release_gallery_attachments($db,$previousAttachments);
 $db->commit();scl_queue_attachment_cleanup($db,$removedAttachments);echo json_encode(['success'=>true]);
}catch(InvalidArgumentException $e){$db->rollBack();gallery_fail($e->getMessage());}catch(Throwable $e){$db->rollBack();throw $e;}
