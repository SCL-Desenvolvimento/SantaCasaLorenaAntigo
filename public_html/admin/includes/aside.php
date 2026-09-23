<?php if (!defined('SCL_ADMIN_PANEL')) { http_response_code(403); exit; } ?>
<?php 
  if($_SESSION['UsuarioLogin']['nivel'] == 3):
    include("includes/aside_administrador.php");
  endif;
?>
