<?php
	require(__DIR__ . '/../../../_app/Config.inc.php');
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

			case 'getPaginas':

				$readPagina = new Read;
				$readPagina->fullRead("SELECT P.* FROM ".PREFIX."paginas AS P");

				if($readPagina->getResult()){

					echo json_encode($readPagina->getResult());
				}
			break;

			case 'updatePaginas':

				unset($dados['acao']);

				$atualizaPagina = new Update();
				$createPagina = new Create();

				foreach ($dados AS $key => $value) {
					
					$Paginas = array();

					if((!preg_match("/_sub_titulo/", $key) && !preg_match("/_seo/", $key) && !preg_match("/_image/", $key)) || preg_match("/por_imagem/", $key)){

						$Paginas["titulo"] = $value;

						if(isset($dados[$key."_seo"]))
							$Paginas["seo"] = $dados[$key."_seo"];

						if(isset($dados[$key."_sub_titulo"]))
							$Paginas["sub_titulo"] = $dados[$key."_sub_titulo"];

						if(isset($dados[$key."_descricao"]))
							$Paginas["descricao"] = $dados[$key."_descricao"];

						$Paginas["id_usuario"] = $_SESSION['UsuarioLogin']['id_usuario'];
						$Paginas["data_alteracao"] = date("Y-m-d H:i:s");

						if(isset($_FILES[$key."_image"]) && $_FILES[$key."_image"]['name'] != "")
							$Paginas['img_principal'] = Check::insertImg($_FILES[$key."_image"], $key);

						$atualizaPagina->ExeUpdate(PREFIX."paginas", $Paginas, "WHERE url_amigavel = :url_amigavel", "url_amigavel={$key}");
						if($atualizaPagina->getResult()){

							$Paginas["url_amigavel"] = $key;
							$createPagina->ExeCreate(PREFIX."paginas_historico", $Paginas);
						}else{

							exit;
						}
					}

					unset($Paginas);
				}

				$createDados = new Create();
				$getImages = new Read();

				//Verifica são páginas Institucional
				if(isset($dados['sobre_santa_casa_lorena-texto1'])){

					//Pega dados Quem somos
					$dadosGerais = array(
						"bloco1" => $dados['sobre_santa_casa_lorena-texto1'], 
						"bloco2" =>$dados['sobre_santa_casa_lorena-texto2'], 
						"bloco3" =>$dados['sobre_santa_casa_lorena-texto3'],
						"bloco4" =>$dados['sobre_santa_casa_lorena-texto4'],
						"missao" =>$dados['sobre_santa_casa_lorena-missao'],
						"visao" =>$dados['sobre_santa_casa_lorena-visao'],
						"valor" =>$dados['sobre_santa_casa_lorena-valor'],
						"provedor" =>$dados['sobre_santa_casa_lorena-provedor'],
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_sobre", $dadosGerais);

					//Pega dados Humanização
					$dadosGerais = array(
						"bloco1" => $dados['humanizacao-texto1'], 
						"bloco2" =>$dados['humanizacao-texto2'], 
						"bloco3" =>$dados['humanizacao-texto3'],
						"bloco4" =>$dados['humanizacao-texto4'],
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_humanizacao", $dadosGerais);

					//Pega dados Programa Nacional Segurança
					$getImages->fullRead("SELECT * FROM ".PREFIX."pagina_programa_nacional_seguranca ORDER BY id_programa_nacional_seguranca DESC");

					$dadosGerais = array(
						"bloco1" => $dados['programa_nacional_seguranca-texto1'],
						"bloco2" => $dados['programa_nacional_seguranca-texto2'], 
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					if(isset($_FILES["programa_nacional_seguranca_image1"]) && $_FILES["programa_nacional_seguranca_image1"]['name'] != "")
						$dadosGerais['img1'] = Check::insertImg($_FILES["programa_nacional_seguranca_image1"], "programa_nacional_seguranca");
					else
						$dadosGerais['img1'] = $getImages->getResult()[0]['img1'];
					
					$createDados->ExeCreate(PREFIX."pagina_programa_nacional_seguranca", $dadosGerais);

					//Pega dados Ações Sociais e Ambientais
					$getImages->fullRead("SELECT * FROM ".PREFIX."pagina_acoes_sociais_ambientais ORDER BY id_acoes_sociais_ambientais DESC");

					$dadosGerais = array(
						"bloco1" => $dados['acoes_sociais_ambientais-texto1'],
						"bloco2" => $dados['acoes_sociais_ambientais-texto2'], 
						"bloco3" => $dados['acoes_sociais_ambientais-texto3'],
						"bloco4" => $dados['acoes_sociais_ambientais-texto4'], 
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);

					if(isset($_FILES["acoes_sociais_ambientais_image1"]) && $_FILES["acoes_sociais_ambientais_image1"]['name'] != "")
						$dadosGerais['img1'] = Check::insertImg($_FILES["acoes_sociais_ambientais_image1"], "acoes_sociais_ambientais");
					else
						$dadosGerais['img1'] = $getImages->getResult()[0]['img1'];

					if(isset($_FILES["acoes_sociais_ambientais_image2"]) && $_FILES["acoes_sociais_ambientais_image2"]['name'] != "")
						$dadosGerais['img2'] = Check::insertImg($_FILES["acoes_sociais_ambientais_image2"], "acoes_sociais_ambientais");
					else
						$dadosGerais['img2'] = $getImages->getResult()[0]['img2'];
					
					$createDados->ExeCreate(PREFIX."pagina_acoes_sociais_ambientais", $dadosGerais);
				}
				
				//Verifica se são páginas Fale Conosco
				if(isset($dados['localizacao-telefone'])){

					//Pega dados Localização
					$dadosGerais = array(
						"telefone" => $dados['localizacao-telefone'], 
						"email" =>$dados['localizacao-email'], 
						"localizacao" =>$dados['localizacao-localizacao'],
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_localizacao", $dadosGerais);

					//Pega dados Doações
					$dadosGerais = array(
						"bloco1" => $dados['doacoes-texto1'], 
						"bloco2" =>$dados['doacoes-texto2'], 
						"bloco3" =>$dados['doacoes-texto3'],
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_doacao", $dadosGerais);
				}

				//Verifica se são páginas Institucional
				if(isset($dados['especialidades-texto1'])){

					//Pega dados Quem somos
					$dadosGerais = array(
						"bloco1" => $dados['especialidades-texto1'], 
						"bloco2" => $dados['especialidades-texto2'], 
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_especialidades", $dadosGerais);

					//Pega dados Quem somos
					$dadosGerais = array(
						"bloco1" => $dados['capacidade_instalacao_producao-texto1'], 
						"bloco2" => $dados['capacidade_instalacao_producao-texto2'], 
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_capacidade_instalacao_producao", $dadosGerais);

					//Pega dados Quem somos
					$dadosGerais = array(
						"bloco1" => $dados['manual_paciente_visitante-texto1'], 
						"bloco2" => $dados['manual_paciente_visitante-texto2'], 
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_manual_paciente_visitante", $dadosGerais);
				}

				//Verifica se são páginas de Instalações
				if(isset($dados['unidade_internacao-texto1'])){

					//Pega dados Unidade de internação
					$dadosGerais = array(
						"bloco1" => $dados['unidade_internacao-texto1'], 
						"bloco2" => $dados['unidade_internacao-texto2'],
						"bloco3" => $dados['unidade_internacao-texto3'],
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_unidade_internacao", $dadosGerais);

					//Pega dados Pronto Atendimento
					$dadosGerais = array(
						"bloco1" => $dados['pronto_atendimento-texto1'], 
						"bloco2" => $dados['pronto_atendimento-texto2'],
						"bloco3" => $dados['pronto_atendimento-texto3'],
						"bloco4" => $dados['pronto_atendimento-texto4'],
						"emergencia" => $dados['pronto_atendimento-emergencia'],
						"urgencia" => $dados['pronto_atendimento-urgencia'],
						"urgencia_relativa" => $dados['pronto_atendimento-urgencia_relativa'],
						"bloco5" => $dados['pronto_atendimento-texto5'],
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_pronto_atendimento", $dadosGerais);

					//Pega dados Hotelaria
					$dadosGerais = array(
						"bloco1" => $dados['hotelaria-texto1'], 
						"bloco2" => $dados['hotelaria-texto2'],
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_hotelaria", $dadosGerais);

					//Pega dados Clínica Emília
					$dadosGerais = array(
						"bloco1" => $dados['clinica_emilia-texto1'], 
						"bloco2" => $dados['clinica_emilia-texto2'],
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_clinica_emilia", $dadosGerais);

					//Pega dados Centro de diagnóstico por imagem
					$dadosGerais = array(
						"bloco1" => $dados['centro_diagnostico_por_imagem-texto1'], 
						"bloco2" => $dados['centro_diagnostico_por_imagem-texto2'],
						"data" => date("Y-m-d H:i:s"),
						"id_usuario" => $_SESSION['UsuarioLogin']['id_usuario']
					);
					$createDados->ExeCreate(PREFIX."pagina_centro_diagnostico_por_imagem", $dadosGerais);
				}

				echo 1;
			break;

			case 'getLista':

				$dominio = $dados['dominio'];
				if(substr($dominio, -1) == "s")
					$dominio = substr($dominio, 0, strlen($dominio)-1);

				$getLista = new Read;
				$getLista->fullRead("SELECT L.* FROM ".PREFIX."{$dominio} AS L");

				if($getLista->getResult()){

					echo json_encode($getLista->getResult());
				}
			break;

			case 'createConteudo':

				unset($dados['acao']);

				$dominio = $dados['dominio'];

				if(substr($dominio, -1) == "s")
					$dominio = substr($dominio, 0, strlen($dominio)-1);

				if(isset($_FILES[$dados['dominio']."_image"]) && $_FILES[$dados['dominio']."_image"]['name'] != ""){
					if(isset($_FILES['balanco_image']) || isset($_FILES['download_manual_paciente_image'])){
						$dados['pdf'] = $dados['pdf'] = Check::insertFile($_FILES[$dados['dominio']."_image"], $dominio);
					}else{
						$dados['img'] = Check::insertImg($_FILES[$dados['dominio']."_image"], $dominio);
					}
				}

				unset($dados['dominio']);
				$createConteudo = new Create;
				$dados['data_criacao'] = date("Y-m-d H:i:s");
				$createConteudo->ExeCreate(PREFIX."{$dominio}", $dados);

				if($createConteudo->getResult()){
					echo $createConteudo->getResult();
				}else{
					echo 0;
				}
			break;

			case 'getConteudo':

				$dominio = $dados['dominio'];
				if(substr($dominio, -1) == "s")
					$dominio = substr($dominio, 0, strlen($dominio)-1);

				$getConteudo = new Read;
				$getConteudo->fullRead("SELECT C.* FROM ".PREFIX."{$dominio} AS C WHERE id_{$dominio} = {$dados['id']}");

				if($getConteudo->getResult()){

					echo json_encode($getConteudo->getResult()[0]);
				}
			break;

			case 'updateConteudo':

				//var_dump($_FILES[$dados['dominio']."_image"]);
				//exit;

				unset($dados['acao']);

				$dominio = $dados['dominio'];

				if(substr($dominio, -1) == "s")
					$dominio = substr($dominio, 0, strlen($dominio)-1);

				$id = $dados['id'];
				unset($dados['id']);

				if(isset($_FILES[$dados['dominio']."_image"]) && $_FILES[$dados['dominio']."_image"]['name'] != ""){
					if(isset($_FILES['balanco_image']) || isset($_FILES['download_manual_paciente_image'])){
						$dados['pdf'] = Check::insertFile($_FILES[$dados['dominio']."_image"], $dominio);
					}else{
						$dados['img'] = Check::insertImg($_FILES[$dados['dominio']."_image"], $dominio);
					}
					
				}

				unset($dados['dominio']);
				$updateConteudo = new Update;

				//var_dump($dados);
				//exit;

				$dados['data_alteracao'] = date("Y-m-d H:i:s");
				$updateConteudo->ExeUpdate(PREFIX."{$dominio}", $dados, "WHERE id_{$dominio} = :id_{$dominio}", "id_{$dominio}={$id}");

				//var_dump($updateConteudo);

				if($updateConteudo->getResult()){
					echo 1;
				}else{
					echo 0;
				}
			break;

			case 'excluiConteudo':

				$dominio = $dados['dominio'];
				if(substr($dominio, -1) == "s")
					$dominio = substr($dominio, 0, strlen($dominio)-1);

				$Delete = new Delete();
				$Delete->ExeDelete(PREFIX."{$dominio}", "WHERE id_{$dominio} = :id_{$dominio}", "id_{$dominio}={$dados['id']}");

				if($Delete->getResult()):
					echo 1;
				endif;
			break;

		endswitch;
	endif;
	
?>	