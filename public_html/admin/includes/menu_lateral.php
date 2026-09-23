<?php if (!defined('SCL_ADMIN_PANEL')) { http_response_code(403); exit; } ?>
<?php 
	if($_SESSION['UsuarioLogin']['nivel'] == 3 || $_SESSION['UsuarioLogin']['nivel'] == 2):
    	include("includes/menu_lateral_administrador.php");
	else:
		include("includes/menu_lateral_lojista.php");
  	endif;
?>
