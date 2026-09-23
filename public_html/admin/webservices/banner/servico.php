<?php
	require(__DIR__ . '/../../../_app/Config.inc.php');
scl_admin_require();

	$login = new Login(3);

	if(!$login->CheckLogin()):
		unset($_SESSION['UsuarioLogin']);
		header("Location: index.php?exe=Restrito");
	exit;
	else:
		$usuarioLogin = $_SESSION['UsuarioLogin'];
	endif;

	//Pego dados post
	$dados = scl_admin_input();

	if(isset($dados['acao'])):
		//Apaga categoria do produto
		switch ($dados['acao']) {

			case 'getListBanner':
				$Read = new Read();
				$Read->fullRead("SELECT * FROM ".PREFIX."banner");
				echo json_encode($Read->getResult());
			break;

			case 'NewBanner':

				unset($dados['acao']);

				//Validate
				$titulop = $dados['titulo'];
				$Read = new Read();
				$Read->fullRead("SELECT * FROM ".PREFIX."banner WHERE titulo = :tit", "tit={$titulop}");

				if($Read->getRowCount() > 0){
					echo 'Não foi possível cadastrar, pois ja existe um banner com este nome!';
					exit;
				}

				if(!isset($dados['status'])){
					$dados['status'] = 0;
				}

				if(isset($_FILES['img'])):
					$file = $_FILES['img'];
				endif;

				if(isset($file)):
					$Upload = new Upload("arquivos");
					$Upload->Image($file, Check::urlAmigavel($dados['titulo']), 1336, "/banner");
                    if (!$Upload->getResult()) scl_deny(422);
					$img = $Upload->getResult();
					$dados['img'] = $img;
				endif;

				$Create = new Create;
				$Create->ExeCreate(PREFIX."banner", $dados);

				if (!$Create->getResult()):
				 	echo "error";
				else:
					echo $Create->getResult();
				endif;
			break;

			case 'GetBanner':

				$idbanner = $dados['IdBanner'];

				$Read = new Read();
				$Read->fullRead("SELECT * FROM ".PREFIX."banner WHERE id_banner =:idbanner", "idbanner=$idbanner)");
				$response = $Read->getResult();

				echo json_encode($response);

			break;

			case 'UpdateBanner':

				if (!isset($dados['status'])){
					$dados['status'] = 0;
				}

				unset($dados['acao']);

				if(isset($_FILES['newimagem'])):
					$file = $_FILES['newimagem'];
				endif;

				if(isset($file)):
					if (!empty($file['name'])){
						if (!empty($dados['img'])) {
							/* Previous media retained for an audited cleanup. */
						}
						$Upload = new Upload("arquivos");
						$Upload->Image($file, Check::urlAmigavel($dados['titulo']), 1336, "/banner");
                    if (!$Upload->getResult()) scl_deny(422);
						$img = $Upload->getResult();
						$dados['img'] = $img;
					}
				endif;

				$Update = new Update;

				$idBanner = $dados['id_banner'];

				if (isset($dados['site'])){
					$sites = $dados['site'];
					unset($dados['site']);
				}


				$Update->ExeUpdate(PREFIX."banner", $dados, "WHERE id_banner = :id", "id=$idBanner");

				if (!$Update->getResult()):
					echo  "<b>Ops:</b> Houve um erro ao atualizar a pagina.";

				else:
					echo $Update->getResult();
				endif;

			break;

			case 'DeleteBanner':

				$Read = new Read();

				//Valido se a pagina/topico tem filhos

				$idbanner = $dados['IdBanner'];

				$Read = new Read();
				$Read->fullRead("select img from ".PREFIX."banner WHERE id_banner =:idbanner", "idbanner=$idbanner)");
				$imagen = $Read->getResult()[0]['img'];

				$Delete = new Delete();

				$Delete->ExeDelete(PREFIX."banner", "WHERE id_banner = :id_banner", "id_banner=$idbanner");

		      	echo 'ok';

			break;

		}

	endif;
?>