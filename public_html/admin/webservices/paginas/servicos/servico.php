<?php
	require(__DIR__ . '/../../../../_app/Config.inc.php');
scl_admin_require();
	$dados = scl_admin_input();
	
	$login = new Login(3);

	if(!$login->CheckLogin()):
		unset($_SESSION['UsuarioLogin']);
		header("Location: index.php?exe=Restrito");
	exit;
	else:
		$usuarioLogin = $_SESSION['UsuarioLogin'];
	endif;

	
	if(isset($dados['acao'])):
		switch ($dados['acao']):

			case 'getEspecialidadesTextos':

				$getEspecialidades = new Read;
				$getEspecialidades->fullRead("SELECT ES.* FROM ".PREFIX."pagina_especialidades AS ES ORDER BY id_pagina DESC LIMIT 1");

				if($getEspecialidades->getResult()){
					echo json_encode($getEspecialidades->getResult()[0]);
				}
			break;

			case 'getCapacidadeTextos':

				$getCapacidade = new Read;
				$getCapacidade->fullRead("SELECT CIP.* FROM ".PREFIX."pagina_capacidade_instalacao_producao AS CIP ORDER BY id_pagina DESC LIMIT 1");

				if($getCapacidade->getResult()){
					echo json_encode($getCapacidade->getResult()[0]);
				}
			break;

			case 'getManualPacienteTextos':

				$getManual = new Read;
				$getManual->fullRead("SELECT MPV.* FROM ".PREFIX."pagina_manual_paciente_visitante AS MPV ORDER BY id_pagina DESC LIMIT 1");

				if($getManual->getResult()){
					echo json_encode($getManual->getResult()[0]);
				}
			break;

			case 'getImagesCapacidade':

				$getImage = new Read;
				$getImage->fullRead("SELECT I.* FROM ".PREFIX."capacidade_imagem AS I WHERE id_capacidade = {$dados['id_capacidade']}");

				if($getImage->getResult()){

					echo json_encode($getImage->getResult());
				}
			break;

			case 'excluiImagemCapacidade':

				$Delete = new Delete();
				$Delete->ExeDelete(PREFIX."capacidade_imagem", "WHERE id_capacidade_imagem = :id_capacidade_imagem", "id_capacidade_imagem={$dados['id_imagem']}");

				if($Delete->getResult()):
					echo 1;
				endif;
			break;

			case 'alteraStatusImagemCapacidade':

				$status = array("status" => $dados['status']);

				$Update = new Update();
				$Update->ExeUpdate(PREFIX."capacidade_imagem", $status, "WHERE id_capacidade_imagem = :id_capacidade_imagem", "id_capacidade_imagem={$dados['id_imagem']}");

				if($Update->getResult()):
					echo 1;
				endif;
			break;

			case 'insereImagemCapacidade':

				if(isset($_FILES['img']) && $_FILES['img']['name'] != ""){

					$dadosImg = array();

					$dadosImg['img'] = Check::insertImg($_FILES['img'], "capacidade_imagem");
					$dadosImg['id_capacidade'] = $dados['id'];
					$dadosImg['status'] = 0;
					$dadosImg['id_usuario'] = $_SESSION['UsuarioLogin']['id_usuario'];
					$dadosImg['data_criacao'] = date("Y-m-d H:i:s");

					$insertImage = new Create();
					$insertImage->ExeCreate(PREFIX."capacidade_imagem", $dadosImg);
					if($insertImage->getResult()){
						$dadosImg['id'] = $insertImage->getResult();
						echo json_encode($dadosImg, true);
					}
				}
			break;

		endswitch;
	endif;
	
?>