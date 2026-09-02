<?php 
	
	session_start();
	$_SESSION['tb'] = true;
	Header("Location: ".ROOT."fale-conosco");

?>