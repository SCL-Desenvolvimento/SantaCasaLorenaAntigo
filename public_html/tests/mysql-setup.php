<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require dirname(__DIR__).'/_app/Config.inc.php';
set_exception_handler(function(Throwable $e){fwrite(STDERR,get_class($e).' at '.basename($e->getFile()).':'.$e->getLine().PHP_EOL);exit(1);});
if(!in_array(HOST,['127.0.0.1','localhost','::1'],true))throw new RuntimeException('Loopback database required.');
$stateFile=$argv[2]??'';$parent=realpath(dirname($stateFile));if(!$parent||!str_starts_with(strtolower($parent),strtolower(realpath(sys_get_temp_dir())).DIRECTORY_SEPARATOR))throw new RuntimeException('Temporary state path required.');
$db=Conn::getConn();
if(($argv[1]??'')==='drop'){$state=json_decode(file_get_contents($stateFile),true);if(!preg_match('/^scl_test_[a-f0-9]{16}_$/D',$state['prefix']))throw new RuntimeException('Unexpected test prefix.');foreach($db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) as $table)if(str_starts_with($table,$state['prefix']))$db->exec('DROP TABLE '.$table);exit("Test tables removed.
");}
$name='scl_test_'.bin2hex(random_bytes(8)).'_';
$password=bin2hex(random_bytes(20));$username='scl_validation_'.bin2hex(random_bytes(4));file_put_contents($stateFile,json_encode(['database'=>DBSA,'prefix'=>$name,'username'=>$username,'password'=>$password]));chmod($stateFile,0600);
$db->exec("SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO'");$count=0;
foreach($db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) as $table){if(!str_starts_with($table,PREFIX)||!preg_match('/^[a-zA-Z0-9_]+$/D',$table))continue;$target=$name.substr($table,strlen(PREFIX));$db->exec('CREATE TABLE '.$target.' LIKE '.$table);$db->exec('INSERT INTO '.$target.' SELECT * FROM '.$table);$count++;}
$q=$db->prepare('INSERT INTO '.$name.'usuario (usuario,senha,nome,email,status,nivel,cadastro) VALUES (?,?,?,?,1,3,NOW())');$q->execute([$username,password_hash($password,PASSWORD_DEFAULT),'Validação local','validation@example.invalid']);
$state=json_decode(file_get_contents($stateFile),true);$state['userId']=(int)$db->lastInsertId();file_put_contents($stateFile,json_encode($state));echo 'Cloned '.$count." tables; temporary administrator created in isolated copy.\n";
