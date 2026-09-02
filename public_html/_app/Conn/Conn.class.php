<?php
	
class Conn{
	private static $Host = HOST;
	private static $User = USER;
	private static $Pass = PASS;
	private static $Dbsa = DBSA;
	private static $Connect = null;

	private static function Conectar(){
		try{
			if(self::$Connect == null || (!isset($_SESSION['MIGRATE']) || !$_SESSION['MIGRATE'])):

				//echo "conectando <br>";
				
				$dsn = 'mysql:host='.self::$Host.'; dbname='.self::$Dbsa.'';
				$options = [PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES UTF8'];
				self::$Connect = new PDO($dsn, self::$User, self::$Pass, $options);
			else:

				//echo "migrando <br>";
		
				$dsn = 'mysql:host='.HOST2.'; dbname='.DBSA2.'';
				$options = [PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES UTF8'];
				self::$Connect = new PDO($dsn, USER2, PASS2, $options);
			endif;
		}catch (PDOException $e){
			PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
			die;
		}
		
		self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return self::$Connect;
	}
	
	public static function getConn(){
		return self::Conectar();
	}
	
}

?>