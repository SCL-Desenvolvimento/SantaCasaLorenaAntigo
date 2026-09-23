<?php
	require(__DIR__ . '/../../../_app/Config.inc.php');
scl_admin_require();
	$dados = scl_admin_input();

	$login = new Login(2);

	if(!$login->CheckLogin()):
		unset($_SESSION['UsuarioLogin']);
		header("Location: index.php?exe=Restrito");
	exit;
	else:
		$usuarioLogin = $_SESSION['UsuarioLogin'];
	endif;

	if(isset($dados['acao'])):
		switch ($dados['acao']):

			case 'CreateAnexo':

				unset($dados['acao']);

				 if(isset($_FILES['anexo'])):
						$file = $_FILES['anexo'];
				 endif;

				 if(isset($file)):
						$Read = new Read();
						$Read->fullRead("SELECT id_anexo FROM ".PREFIX."anexo ORDER BY id_anexo DESC LIMIT 1");
						$ultimoID = $Read->getResult();
						foreach ($ultimoID as $id) {
							$ultimoID =  $id['id_anexo'];
						}
						$Upload = new Upload("arquivos");
						$Upload->Image($file, Check::urlAmigavel($ultimoID + 1), 1920, '');
                    if (!$Upload->getResult()) scl_deny(422);
						$dados['url'] = $Upload->getResult();
						$dados['tipo'] = $file['type'];
				 endif;

				 date_default_timezone_set('America/Sao_Paulo');

				 $dadosTemp = array();

				 $dadosTemp['url'] = $dados['url'];
				 $dadosTemp['titulo'] = $dados['nome'];
				 $dadosTemp['descricao'] = $dados['nome'];
				 $dadosTemp['nome'] = $dados['nome'];
				 $dadosTemp['tipo'] = 'attachment';
				 $dadosTemp['mime_type'] = $dados['tipo'];
				 $dadosTemp['id_usuario'] = $_SESSION['UsuarioLogin']['id_usuario'];
				 $dadosTemp['data'] = date('Y-m-d H:i:s');

				 $Create = new Create;
				 $Create->ExeCreate(PREFIX."anexo", $dadosTemp);

				 $result = array();

				 if (!$Create->getResult()):
					 $result['erro'] = "erro!!!";
					 echo "error";
				 else:
					 $result['id_anexo'] = $Create->getResult();
					 $result['img'] = (isset($dados['url']) ? $dados['url'] : null);
				 endif;

				 echo json_encode($result);

			break;

			case 'CreateGaleria':

				unset($dados['acao']);

				 $dadosTemp = array();

				 $dadosTemp['nome'] = $dados['nomegaleria'];

				 $Create = new Create;
				 $Create->ExeCreate(PREFIX."galeria", $dadosTemp);

				 $result = array();

				 if (!$Create->getResult()):
					 echo "error";
				 else:
					 echo $Create->getResult();

					   foreach($dados['id_anexo'] as $id_anexo){

						  $Read = new Read();
	 						$Read->fullRead("SELECT id_galeria FROM ".PREFIX."galeria ORDER BY id_galeria DESC LIMIT 1");
	 						$ultimoID = $Read->getResult();
	 						foreach ($ultimoID as $id) {
	 							$ultimoID =  $id['id_galeria'];
	 						}

						 $result['id_galeria'] = $ultimoID;
						 $result['id_anexo'] = $id_anexo;
						 $Create = new Create;
						 $Create->ExeCreate(PREFIX."galeria_anexo", $result);

					 }

				 endif;

			break;

			case 'UpdateGaleria':

				unset($dados['acao']);

				 $dadosTemp = array();

				 $dadosTemp['nome'] = $dados['nomegaleria'];

	 				$idGaleria = $dados['idGaleria'];

	 				$Update = new Update;
	 				$Update->ExeUpdate(PREFIX."galeria", $dadosTemp, "WHERE id_galeria=:id", "id=$idGaleria");

				 $result = array();

				 if (!$Update->getResult()):
					 echo "error";
				 else:
					 //echo $Update->getResult();
					 echo $idGaleria;

					   foreach($dados['id_anexo'] as $id_anexo){

						 $result['id_galeria'] = $idGaleria;
						 $result['id_anexo'] = $id_anexo;
						 $Create = new Create;
						 $Create->ExeCreate(PREFIX."galeria_anexo", $result);

					 }

				 endif;

			break;

			case 'deleteAnexo':

				$Read = new Read();
				$Read->fullRead("DELETE FROM ".PREFIX."anexo WHERE id_anexo={$dados['id']}");
				echo json_encode($Read->getResult());

			break;

			case 'deleteAnexoUpdate':

				// $Read = new Read();
				// $Read->fullRead("DELETE FROM ".PREFIX."anexo WHERE id_anexo={$dados['id']}");

				$ReadDelete = new Read();
				$ReadDelete->fullRead("DELETE FROM ".PREFIX."galeria_anexo WHERE id_anexo={$dados['id']}");

				echo json_encode($Read->getResult());

			break;

		endswitch;
	endif;

?>
