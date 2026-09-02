<?php
	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
	require('../../../../_app/Config.inc.php');
	
	$login = new Login(3);

	if(!$login->CheckLogin()):
		unset($_SESSION['UsuarioLogin']);
		header("Location: index.php?exe=Restrito");
	else:
		$usuarioLogin = $_SESSION['UsuarioLogin'];
	endif;

	
	if(isset($dados['acao'])):
		switch ($dados['acao']):

			case 'getSobre':

				$getSobre = new Read;
				$getSobre->fullRead("SELECT S.* FROM ".PREFIX."pagina_sobre AS S ORDER BY id_pagina DESC LIMIT 1");

				if($getSobre->getResult()){
					echo json_encode($getSobre->getResult()[0]);
				}
			break;

			case 'getProgramaNacionalSeguranca':

				$getProgramaNacionalSeguranca = new Read;
				$getProgramaNacionalSeguranca->fullRead("SELECT PNS.* FROM ".PREFIX."pagina_programa_nacional_seguranca AS PNS ORDER BY id_programa_nacional_seguranca DESC LIMIT 1");

				if($getProgramaNacionalSeguranca->getResult()){
					echo json_encode($getProgramaNacionalSeguranca->getResult()[0]);
				}
			break;

			case 'getAcoesSociaisAmbientais':

				$getAcoesSociaisAmbientais = new Read;
				$getAcoesSociaisAmbientais->fullRead("SELECT ASA.* FROM ".PREFIX."pagina_acoes_sociais_ambientais AS ASA ORDER BY id_acoes_sociais_ambientais DESC LIMIT 1");

				if($getAcoesSociaisAmbientais->getResult()){
					echo json_encode($getAcoesSociaisAmbientais->getResult()[0]);
				}
			break;

			case 'getHumanizacao':

				$getHumanizacao = new Read;
				$getHumanizacao->fullRead("SELECT H.* FROM ".PREFIX."pagina_humanizacao AS H ORDER BY id_pagina DESC LIMIT 1");

				if($getHumanizacao->getResult()){
					echo json_encode($getHumanizacao->getResult()[0]);
				}
			break;

		endswitch;
	endif;
	
?>