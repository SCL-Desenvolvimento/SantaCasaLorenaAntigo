<?php
	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
	require('../../../../_app/Config.inc.php');
    require_once __DIR__.'/../../../../includes/ouvidoria_queries.php';
	
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

			case 'getLocalizacao':

				$getLocalizacao = new Read;
				$getLocalizacao->fullRead("SELECT L.* FROM ".PREFIX."pagina_localizacao AS L ORDER BY id_pagina DESC LIMIT 1");

				if($getLocalizacao->getResult()){
					echo json_encode($getLocalizacao->getResult()[0]);
				}
			break;

			case 'listContatos':

				//Trás apenas conteudos que o usuáro tem acesso
	        	$read = new Read;
				$read->fullRead("SELECT *, date_format(`data_cadastro`,'%d/%m/%Y às %Hh%i') AS `data_formatada` FROM ".PREFIX."contato ORDER BY data_cadastro DESC");

	        	if($read->getResult()):
	        		echo json_encode($read->getResult());
	        	else:
	        		echo 0;
	        	endif;
			break;

			case 'listOuvidoria':
                $read = new Read();
                [$sql, $params] = scl_ouvidoria_query(array(), true);
                $read->fullRead($sql, $params);
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode($read->getResult() ?: array());
                break;

            case 'listTrabalheConosco':

				//Trás apenas conteudos que o usuáro tem acesso
	        	$read = new Read;
				$read->fullRead("SELECT *, date_format(`data_cadastro`,'%d/%m/%Y às %Hh%i') AS `data_formatada` FROM ".PREFIX."trabalhe_conosco ORDER BY data_cadastro DESC");

	        	if($read->getResult()):
	        		echo json_encode($read->getResult());
	        	else:
	        		echo 0;
	        	endif;
			break;

			case 'listDoacoes':

				//Trás apenas conteudos que o usuáro tem acesso
	        	$read = new Read;
				$read->fullRead("SELECT *, date_format(`data_cadastro`,'%d/%m/%Y às %Hh%i') AS `data_formatada` FROM ".PREFIX."doacoes ORDER BY data_cadastro DESC");

	        	if($read->getResult()):
	        		echo json_encode($read->getResult());
	        	else:
	        		echo 0;
	        	endif;
			break;

			case 'excluiContato':
				
				$Delete = new Delete();
				$Delete->ExeDelete(PREFIX."contato", "WHERE id_contato = :id_contato", "id_contato={$dados['id_contato']}");
		
				if($Delete->getResult()):
					echo 1;
				endif;
			break;

			case 'getDoacaoTextos':

				$getDoacaoTextos = new Read;
				$getDoacaoTextos->fullRead("SELECT DT.* FROM ".PREFIX."pagina_doacao AS DT ORDER BY id_pagina DESC LIMIT 1");

				if($getDoacaoTextos->getResult()){

					echo json_encode($getDoacaoTextos->getResult()[0]);
				}
			break;

		endswitch;
	endif;
	
?>