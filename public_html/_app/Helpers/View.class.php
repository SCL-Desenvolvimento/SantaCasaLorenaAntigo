<?php

class View{

	private static $Data;
	private static $Keys;
	private static $Values;
	private static $Template;
	
	public static function Load($Template){
		self::$Template = (string) file_get_contents("./_mvc/{$Template}.tpl.html");
		//var_dump(self::$Template);
	}
	
	public static function Show(array $Data){
		self::setKeys($Data);
		self::setValues();	
		self::showView();
	}
	
	public static function Request($File, array $Data){
		extract($Data);
		require ("./_mvc/{$File}.inc.php");
	}

	private static function setKeys($Data) {
		self::$Data = $Data;
		self::$Keys = explode("&", "#".implode("#&#", array_keys(self::$Data))."#");
		//var_dump(self::$Keys);
	}
	
	private static function setValues() {
		self::$Values = array_values(self::$Data);
	}
	
	private static function showView(){
		echo str_replace(self::$Keys, self::$Values,self::$Template);
	}
}

?>