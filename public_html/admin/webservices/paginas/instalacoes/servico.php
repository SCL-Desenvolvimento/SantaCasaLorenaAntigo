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

			case 'getUnidadeInternacaoTextos':

				$getUnidadeInternacaoTextos = new Read;
				$getUnidadeInternacaoTextos->fullRead("SELECT UT.* FROM ".PREFIX."pagina_unidade_internacao AS UT ORDER BY id_pagina DESC LIMIT 1");

				echo json_encode($getUnidadeInternacaoTextos->getResult()[0] ?? new stdClass());
			break;

			case 'getImagesUnidadeInternacao':

				$getImage = new Read;
				$getImage->fullRead("SELECT I.* FROM ".PREFIX."unidade_internacao_imagem AS I WHERE id_unidade_internacao = {$dados['id_unidade_internacao']}");

				echo json_encode($getImage->getResult() ?: []);
			break;

			case 'excluiImagemUnidadeInternacao':

				$Delete = new Delete();
				$Delete->ExeDelete(PREFIX."unidade_internacao_imagem", "WHERE id_unidade_internacao_imagem = :id_unidade_internacao_imagem", "id_unidade_internacao_imagem={$dados['id_imagem']}");

				if($Delete->getResult()):
					echo 1;
				endif;
			break;

			case 'alteraStatusUnidadeInternacao':

				$status = array("status" => $dados['status']);

				$Update = new Update();
				$Update->ExeUpdate(PREFIX."unidade_internacao_imagem", $status, "WHERE id_unidade_internacao_imagem = :id_unidade_internacao_imagem", "id_unidade_internacao_imagem={$dados['id_imagem']}");

				if($Update->getResult()):
					echo 1;
				endif;
			break;

			case 'insereImagemUnidadeInternacao':

				if(isset($_FILES['img']) && $_FILES['img']['name'] != ""){

					$dadosImg = array();

					$dadosImg['img'] = Check::insertImg($_FILES['img'], "unidade_internacao_imagem");
					$dadosImg['id_unidade_internacao'] = $dados['id'];
					$dadosImg['status'] = 0;
					$dadosImg['id_usuario'] = $_SESSION['UsuarioLogin']['id_usuario'];
					$dadosImg['data_criacao'] = date("Y-m-d H:i:s");

					$insertImage = new Create();
					$insertImage->ExeCreate(PREFIX."unidade_internacao_imagem", $dadosImg);
					if($insertImage->getResult()){
						$dadosImg['id'] = $insertImage->getResult();
						echo json_encode($dadosImg, true);
					}
				}
			break;

			case 'getProntoAtendimentoTextos':

				$getProntoAtendimentoTextos = new Read;
				$getProntoAtendimentoTextos->fullRead("SELECT PA.* FROM ".PREFIX."pagina_pronto_atendimento AS PA ORDER BY id_pagina DESC LIMIT 1");

				echo json_encode($getProntoAtendimentoTextos->getResult()[0] ?? new stdClass());
			break;

			case 'getHotelariaTextos':

				$getHotelariaTextos = new Read;
				$getHotelariaTextos->fullRead("SELECT H.* FROM ".PREFIX."pagina_hotelaria AS H ORDER BY id_pagina DESC LIMIT 1");

				echo json_encode($getHotelariaTextos->getResult()[0] ?? new stdClass());
			break;

			case 'getClinicaEmiliaTextos':

				$getClinicaEmiliaTextos = new Read;
				$getClinicaEmiliaTextos->fullRead("SELECT CE.* FROM ".PREFIX."pagina_clinica_emilia AS CE ORDER BY id_pagina DESC LIMIT 1");

				echo json_encode($getClinicaEmiliaTextos->getResult()[0] ?? new stdClass());
			break;

			case 'getCentroDiagnosticoImagem':

				$getCentroDiagnosticoImagem = new Read;
				$getCentroDiagnosticoImagem->fullRead("SELECT CDI.* FROM ".PREFIX."pagina_centro_diagnostico_por_imagem AS CDI ORDER BY id_pagina DESC LIMIT 1");

				echo json_encode($getCentroDiagnosticoImagem->getResult()[0] ?? new stdClass());
			break;

		endswitch;
	endif;
	
?>