<?php 
  if($_SESSION['UsuarioLogin']['nivel'] == 3):
    include("includes/aside_administrador.php");
  endif;
?>
