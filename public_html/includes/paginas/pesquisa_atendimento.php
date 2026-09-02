<?php 
	
	session_start();
	$_SESSION['pa'] = true;
	Header("Location: ".ROOT."fale-conosco");

?>