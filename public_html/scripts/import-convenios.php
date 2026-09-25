<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__) . '/_app/Config.inc.php';
if (!in_array(HOST, ['localhost','127.0.0.1','::1'], true)) throw new RuntimeException('Use only on the local database.');
$names = [
 'abet'=>'ABET','abfnv'=>'ABFNV','amil'=>'Amil','associacao-dos-aposentados'=>'Associação dos Aposentados',
 'ativia'=>'Ativia','bradesco'=>'Bradesco Saúde','cabesp'=>'CABESP','cas'=>'CAS','cassi'=>'CASSI','funcesp'=>'Funcesp',
 'fundacao-sagrado-coracao'=>'Fundação Sagrado Coração','iamspe'=>'IAMSPE','inb'=>'INB','intermedica'=>'Intermédica','ipaeas'=>'IPAEAS',
 'medial'=>'Medial','mediservice'=>'Mediservice','notre-dame-intermedica'=>'NotreDame Intermédica',
 'operadora-unicentral-de-planos-de-saude'=>'Operadora Unicentral de Planos de Saúde','particular'=>'Particular',
 'petrobras'=>'Petrobras','plamtel'=>'Plamtel','plano-de-saude-itau'=>'Plano de Saúde Itaú','policlin-saude'=>'Policlin Saúde',
 'porto-seguro'=>'Porto Seguro','santa-casa-saude'=>'Santa Casa Saúde','santa-catarina-de-sena'=>'Inspetoria de Enfermagem Santa Catarina de Sena',
 'spa-saude'=>'SPA Saúde','sulamerica'=>'SulAmérica','universal-saude'=>'Universal Saúde'
];
$db=Conn::getConn();$table=PREFIX.'convenio';
$existing=$db->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
$backup=dirname(rtrim(DIR,'/\\')).'/backups';
if(!is_dir($backup))mkdir($backup,0700,true);
file_put_contents($backup.'/convenios-before-import-'.date('Ymd-His').'-'.bin2hex(random_bytes(3)).'.json',json_encode($existing,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
$db->beginTransaction();$inserted=0;$updated=0;
try {
 foreach($names as $slug=>$name){
  $path='resources/img/convenios/'.$slug.'.png';if(!is_file(DIR.$path))throw new RuntimeException('Missing logo: '.$slug);
  $q=$db->prepare("SELECT id_convenio FROM $table WHERE img=? OR nome=? LIMIT 1");$q->execute([$path,$name]);$id=$q->fetchColumn();
  if($id){$q=$db->prepare("UPDATE $table SET nome=?,img=?,data_alteracao=NOW() WHERE id_convenio=?");$q->execute([$name,$path,$id]);$updated++;}
  else{$q=$db->prepare("INSERT INTO $table (nome,img,descricao,data_criacao) VALUES (?,?,'',NOW())");$q->execute([$name,$path]);$inserted++;}
 }
 $db->commit();echo "Imported $inserted plans; updated $updated existing plans.\n";
}catch(Throwable $e){$db->rollBack();throw $e;}
