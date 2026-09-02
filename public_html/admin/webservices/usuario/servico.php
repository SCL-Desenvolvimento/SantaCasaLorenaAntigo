<?php
	
	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
	require('../../../_app/Config.inc.php');
	
	$login = new Login(1);

	if(!$login->CheckLogin() || (isset($access) && $access['admin'] != 1)):
		unset($_SESSION['UsuarioLogin']);
		header("Location: index.php?exe=Restrito");
	else:
		$usuarioLogin = $_SESSION['UsuarioLogin'];
	endif;
	
	if(isset($dados['acao'])):
		switch ($dados['acao']):

			case 'listUsers':

				//if((isset($dados['nivel']) && $_SESSION['UsuarioLogin']['nivel'] >= $dados['nivel']) || $_SESSION['UsuarioLogin']['nivel'] == 1){

	        		$read = new Read;

	        		if($_SESSION['UsuarioLogin']['nivel'] == 1){
	        			$read->ExeRead(PREFIX.'usuario', "WHERE nivel = :nivel AND id_loja = :id_loja AND id_usuario != :id_usuario", "nivel=1&id_loja={$_SESSION['UsuarioLogin']['id_loja']}&id_usuario={$_SESSION['UsuarioLogin']['id_usuario']}");
	        		}else{
						$read->ExeRead(PREFIX.'usuario', "WHERE nivel = :nivel ", "nivel=3");
	        		}

	        		if($read->getResult()):
	        			echo json_encode($read->getResult());
	        		else:
	        			echo 0;
	        		endif;
	        	//}

			break;

			case 'getUser':

				if(($_SESSION['UsuarioLogin']['nivel'] == 3 || ($subUsuario = Check::MeuLojista($dados['id_usuario'])) || $_SESSION['UsuarioLogin']['id_usuario'] == $dados['id_usuario'])){

	        		$read = new Read;
					$read->ExeRead(PREFIX.'usuario', "WHERE id_usuario = :id_usuario AND nivel <= :nivel", "nivel={$_SESSION['UsuarioLogin']['nivel']}&id_usuario={$dados['id_usuario']}");
	        		
	        		if($read->getResult()[0]['nivel'] == 3 && $_SESSION['UsuarioLogin']['nivel'] != 3){
	        			echo 0;
	        		}else{

	        			$usuario = $read->getResult()[0];
	        			if($_SESSION['UsuarioLogin']['nivel'] != 3 && !$subUsuario)
	        				$usuario['mostra_status'] = $subUsuario;

	        			echo json_encode($usuario);

	        		}
	        		
	        	}

			break;

			case 'excluiUser':

				if($_SESSION['UsuarioLogin']['nivel'] == 3):
					$Delete = new Delete();
					$Delete->ExeDelete(PREFIX."usuario", "WHERE id_usuario = :id_usuario", "id_usuario={$dados['id_usuario']}");
		
					if($Delete->getResult()):
						echo 1;
					endif;
				else:
					echo 0;
				endif;

			break;

			case 'createUser':

				if($_SESSION['UsuarioLogin']['nivel'] == 1)
					$dados['id_loja'] = $_SESSION['UsuarioLogin']['id_loja'];

				if(isset($_FILES['img'])){ $file = $_FILES['img']; }
				if(isset($file)):
	                if(!empty($file['name'])){
	                    $Upload = new Upload("arquivos");
	                    $Upload->Image($file, Check::urlAmigavel(Check::urlAmigavel($dados['nome'])."-".date("dmYHis")), NULL,"/fotousuario");
	                    $dados['img'] = $Upload->getResult();
	                }
	            endif;


	        	unset($dados['acao']);
    			$dados['status'] = (isset($dados['status']) ? 1 : 0 );

    			$dados['criado_por'] = $_SESSION['UsuarioLogin']['id_usuario'];
    			$dados['cadastro'] = date("Y-m-d H:i:s");
    			$dados['nivel'] = 3;
	        	$dados['senha'] = md5($dados['senha']);

	        	$Create = new Create();
	        	$Create->ExeCreate(PREFIX."usuario", $dados);

	        	if (!$Create->getResult()):
	            	echo "0";
	        	else:
					echo $Create->getResult();
	        	endif;

			break;

			case 'updateUser':

				if($_SESSION['UsuarioLogin']['nivel'] == 1)
					$dados['id_loja'] = $_SESSION['UsuarioLogin']['id_loja'];

				if($_SESSION['UsuarioLogin']['nivel'] == 3 || $_SESSION['UsuarioLogin']['id_usuario'] == $dados['id_usuario'] || Check::MeuLojista($dados['id_usuario'])){

					if(isset($_FILES['img'])) $file = $_FILES['img'];

					if(isset($file)):
	                    if (!empty($file['name'])){
	                        $Upload = new Upload("arquivos");
	                        $Upload->Image($file, Check::urlAmigavel($dados['nome'])."-".date("dmYHis") , NULL,"/fotousuario");
	                        $dados['img'] = $Upload->getResult();
	                    }
	        		endif;

	    			unset($dados['acao']);
	    			$dados['status'] = (isset($dados['status']) ? 1 : 0 );

	    			$dados['alterado_por'] = $_SESSION['UsuarioLogin']['id_usuario'];
	    			$dados['data_alteracao'] = date("Y-m-d H:i:s");
	    			$dados['nivel'] = 3;
	    			if($dados['senha'] != ""){
	    				$dados['senha'] = md5($dados['senha']);
	    			}else{
	    				unset($dados['senha']);
	    			}	

	    			if($_SESSION['UsuarioLogin']['nivel'] != 3 && $_SESSION['UsuarioLogin']['id_usuario'] == $dados['id_usuario'])
	    				unset($dados['status']);


					$Update = new Update();
					$Update->ExeUpdate(PREFIX."usuario", $dados, "WHERE id_usuario= :id_usuario", "id_usuario={$dados['id_usuario']}");
					if(!$Update->getResult()):
	            		echo $Update->getResult();
	        		else:
						if($_SESSION['UsuarioLogin']['id_usuario'] == $dados['id_usuario']):
							$_SESSION['UsuarioLogin']['nome'] = $dados['nome'];
							$_SESSION['UsuarioLogin']['email'] = $dados['email'];
							if(isset($dados['img'])):
								$_SESSION['UsuarioLogin']['img'] = $dados['img'];
							endif;
		           		endif;
		           		echo 1;
	        		endif;
	        	}

			break;

			case 'alteraStatus':

        		$read = new Read;
				$read->ExeRead(PREFIX.'usuario', "WHERE id_usuario = :id_usuario", "id_usuario={$dados['id_usuario']}");

				if($read->getResult()):
					$update = new Update();
					$dadosUpdate = array();
					if($read->getResult()[0]['status'] == 0):
						$dadosUpdate['status'] = 1;
						$update->ExeUpdate(PREFIX."usuario", $dadosUpdate, "WHERE id_usuario = :id_usuario", "id_usuario={$dados['id_usuario']}");
						if($update->getResult()):
							echo 1;
						endif;
					else:
						$dadosUpdate['status'] = 0;
						$update->ExeUpdate(PREFIX."usuario", $dadosUpdate, "WHERE id_usuario = :id_usuario", "id_usuario={$dados['id_usuario']}");
						if($update->getResult()):
							echo 0;
						endif;
					endif;
				endif;

			break;

			case 'ValidarEmail':

		        $read = new Read;
				$read->ExeRead(PREFIX.'usuario', "WHERE email = :e_mail", "e_mail={$dados['email']}");
				
				if($read->getResult()):

					if(isset($dados['atual']) && $read->getResult()[0]['id_usuario'] == $dados['atual'])
						echo 1;
					else
						echo 0;

				else:
					echo 1;
				endif;

		    break;
		    
		    case 'ValidarLogin':

		        $read = new Read;
				$read->ExeRead(PREFIX.'usuario', "WHERE usuario = :user", "user={$dados['login']}");
				
				if($read->getResult()):

					if(isset($dados['atual']) && $read->getResult()[0]['id_usuario'] == $dados['atual'])
						echo 1;
					else
						echo 0;
					
				else:
					echo 1;
				endif;

		    break;
		    
		   case 'ValidarRG':

		        $read = new Read;
				$read->ExeRead(PREFIX.'usuario', "WHERE rg = :rg", "rg={$dados['rg']}");
				
				if($read->getResult()):

					if(isset($dados['atual']) && $read->getResult()[0]['id_usuario'] == $dados['atual'])
						echo 1;
					else
						echo 0;
					
				else:
					echo 1;
				endif;
		    
		    break;
		    
		    case 'ValidarCPF':

		        $read = new Read;
				$read->ExeRead(PREFIX.'usuario', "WHERE cpf = :cpf", "cpf={$dados['cpf']}");
				
		    	if($read->getResult()):

					if(isset($dados['atual']) && $read->getResult()[0]['id_usuario'] == $dados['atual'])
						echo 1;
					else
						echo 0;
					
				else:
					echo 1;
				endif;

		    break;

		endswitch;
	endif;
	
?>