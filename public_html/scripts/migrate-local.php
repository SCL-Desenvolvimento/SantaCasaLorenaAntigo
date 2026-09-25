<?php
/** Explicit, local-only backup and idempotent migration. Never served over HTTP. */
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require dirname(__DIR__).'/_app/Config.inc.php';
if(!in_array(HOST,['localhost','127.0.0.1','::1'],true))throw new RuntimeException('Only a loopback database is allowed.');
if(!in_array('--apply',$argv,true))exit("Use --apply to back up and migrate the configured local database.\n");
$db=Conn::getConn();$backupDir=dirname(__DIR__,2).'/backups';if(!is_dir($backupDir))mkdir($backupDir,0700,true);
$file=$backupDir.'/local-before-migration-'.date('Ymd-His').'-'.bin2hex(random_bytes(3)).'.sql';$out=fopen($file,'xb');
fwrite($out,"SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\nSET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n");
$db->exec('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ');$db->beginTransaction();$count=0;
foreach($db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) as $table){if(!str_starts_with($table,PREFIX)||!preg_match('/^[a-zA-Z0-9_]+$/D',$table))continue;
 $ddl=$db->query('SHOW CREATE TABLE `'.$table.'`')->fetch(PDO::FETCH_NUM)[1];fwrite($out,'DROP TABLE IF EXISTS `'.$table."`;\n".$ddl.";\n");
 foreach($db->query('SELECT * FROM `'.$table.'`') as $row){$values=array_map(fn($v)=>$v===null?'NULL':$db->quote((string)$v),array_values($row));fwrite($out,'INSERT INTO `'.$table.'` VALUES ('.implode(',',$values).");\n");}$count++;
}
$db->commit();fwrite($out,"SET FOREIGN_KEY_CHECKS=1;\n");fclose($out);chmod($file,0600);
$mode=$db->query('SELECT @@SESSION.sql_mode')->fetchColumn();$db->exec('SET SESSION sql_mode='.$db->quote(implode(',',array_diff(explode(',',$mode),['NO_ZERO_DATE','NO_ZERO_IN_DATE']))));
try{
 $columns=array_column($db->query('SHOW COLUMNS FROM '.PREFIX.'usuario')->fetchAll(),null,'Field');
 if($columns['senha']['Type']!=='varchar(255)')$db->exec('ALTER TABLE '.PREFIX.'usuario MODIFY senha VARCHAR(255) NOT NULL');
 $db->exec('ALTER TABLE '.PREFIX."usuario MODIFY nome VARCHAR(150) NOT NULL DEFAULT '', MODIFY usuario VARCHAR(100) NOT NULL DEFAULT '', MODIFY email VARCHAR(254) NOT NULL DEFAULT ''");
 $db->exec('ALTER TABLE '.PREFIX.'galeria MODIFY nome VARCHAR(150) NULL');
 if(!isset($columns['security_version']))$db->exec('ALTER TABLE '.PREFIX.'usuario ADD security_version INT UNSIGNED NOT NULL DEFAULT 0');
 $db->exec('CREATE TABLE IF NOT EXISTS '.PREFIX.'password_reset (id_usuario INT NOT NULL PRIMARY KEY,token_hash CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL UNIQUE,expires_at BIGINT NOT NULL) ENGINE=InnoDB');
 $db->exec('CREATE TABLE IF NOT EXISTS '.PREFIX.'auth_attempt (bucket CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL PRIMARY KEY,window_start BIGINT NOT NULL,attempts INT UNSIGNED NOT NULL DEFAULT 0,INDEX(window_start)) ENGINE=InnoDB');
 foreach(['ouvidoria','doacoes','trabalhe_conosco'] as $table)$db->exec('ALTER TABLE '.PREFIX.$table.' MODIFY nome VARCHAR(150) NULL, MODIFY email VARCHAR(254) NULL, MODIFY cidade VARCHAR(150) NULL');
 foreach(['ouvidoria','doacoes','pesquisa_atendimento'] as $table)$db->exec('ALTER TABLE '.PREFIX.$table.' MODIFY mensagem TEXT NULL');
 $columns=array_column($db->query('SHOW COLUMNS FROM '.PREFIX.'galeria_anexo')->fetchAll(),'Field');
 if(!in_array('ordem',$columns,true))$db->exec('ALTER TABLE '.PREFIX.'galeria_anexo ADD ordem INT NOT NULL DEFAULT 0');
 if(!in_array('legenda',$columns,true))$db->exec('ALTER TABLE '.PREFIX.'galeria_anexo ADD legenda VARCHAR(255) NULL DEFAULT NULL');
 foreach($db->query('SHOW TABLE STATUS')->fetchAll() as $table)if(str_starts_with($table['Name'],PREFIX)&&strtolower($table['Engine'])!=='innodb')$db->exec('ALTER TABLE `'.$table['Name'].'` ENGINE=InnoDB');
}finally{$db->exec('SET SESSION sql_mode='.$db->quote($mode));}
echo json_encode(['backup'=>$file,'tables'=>$count,'migration'=>'complete'],JSON_UNESCAPED_SLASHES).PHP_EOL;
