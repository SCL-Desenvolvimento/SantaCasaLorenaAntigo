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

//Pego dados noticia
$dados = scl_admin_input();

//========================================================================= Carrega Tags Digitadas ==================================================================
if(isset($_GET['q'])){
	$search = strip_tags(trim($_GET['q']));

	$readTags = new Read;
	$readTags->ExeRead(PREFIX."tag", "WHERE nome LIKE :term", http_build_query(["term" => $search . "%"]));
	if($readTags->getRowCount()){
		foreach ($readTags->getResult() as $key => $value) {
			$data[] = array('id' => $value['id_tag'], 'text' => $value['nome']);
		}
	} else {
		$data[] = array('id' => $search, 'text' => $search);
	}
	echo json_encode($data);
}

if(isset($dados['acao'])):
		//Apaga categoria do produto
	switch ($dados['acao']) {

		case 'getTagsNoticia':

			$readTags = new Read;

			$readTags->fullRead("SELECT DISTINCT
				".PREFIX."tag.id_tag, ".PREFIX."tag_noticia.id_tag_noticia
				FROM ".PREFIX."tag_noticia
				INNER JOIN ".PREFIX."tag ON (".PREFIX."tag_noticia.id_noticia = :id_noticia AND ".PREFIX."tag_noticia.id_tag = ".PREFIX."tag.id_tag)", "id_noticia={$dados['id_noticia']}");

			echo json_encode($readTags->getResult());
		break;

		case 'alteraStatus':

			$read = new Read;
			$read->ExeRead(PREFIX.'noticia', "WHERE id_noticia = :id_noticia", "id_noticia={$dados['id_noticia']}");

			if($read->getResult()):
				$update = new Update();
				$dadosUpdate = array();
				if($read->getResult()[0]['status'] == 0):
					$dadosUpdate['status'] = 1;
					$update->ExeUpdate(PREFIX."noticia", $dadosUpdate, "WHERE id_noticia = :id_noticia", "id_noticia={$dados['id_noticia']}");
					if($update->getResult()):
						echo 1;
					endif;
				else:
					$dadosUpdate['status'] = 0;
					$update->ExeUpdate(PREFIX."noticia", $dadosUpdate, "WHERE id_noticia = :id_noticia", "id_noticia={$dados['id_noticia']}");
					if($update->getResult()):
						echo 0;
					endif;
				endif;
			endif;
		break;

		case 'excluirTag':

			$Delete = new Delete();
			$Delete->ExeDelete(PREFIX."tag_noticia", "WHERE id_tag = :id_tag AND id_noticia = :id_noticia", "id_tag={$dados['id_tag']}&id_noticia={$dados['id_noticia']}");
			if($Delete->getResult()):
				echo 1;
			else:
				echo 0;
			endif;
		break;

		case 'getListNoticia':

			$Read = new Read();
			$Read->fullRead("SELECT * FROM ".PREFIX."noticia");
			echo json_encode($Read->getResult());
		break;

		case 'NewNoticia':

			unset($dados['acao']);

					//Validate
			$titulop = $dados['titulo'];
			$Read = new Read();
			$Read->fullRead("SELECT * FROM ".PREFIX."noticia WHERE titulo = :tit", "tit={$titulop}");

			if ($Read->getRowCount() > 0){
				echo 'Não foi possível cadastrar, pois ja existe uma noticia com este nome!';
				exit;
			}

			if (!isset($dados['status'])){
				$dados['status'] = 0;
			}

			$tag = [];
			if(isset($dados['id_tag'])) {
				$tag = $dados['id_tag'];
				unset($dados['id_tag']);
			}

			if(isset($_FILES['img'])):
				$file = $_FILES['img'];
			endif;

			if(isset($file)):
				$Upload = new Upload("arquivos");
				$Upload->Image($file, Check::urlAmigavel($dados['titulo']), 1920, "/noticia");
                    if (!$Upload->getResult()) scl_deny(422);
				$img = $Upload->getResult();
				$dados['img'] = $img;

			endif;

			$dados['link'] = ($dados['link'] != "" ? Check::urlAmigavel($dados['link']) : Check::urlAmigavel($dados['titulo']) );
			$dados['subtitulo'] = mb_substr(strip_tags($dados['subtitulo'], '<(.*?)>') ,0,250);

			$dados['criador'] = $_SESSION['UsuarioLogin']['id_usuario'];
			$dados['data_criacao'] = date("Y-m-d H:i:s");

			$Create = new Create;
			$Create->ExeCreate(PREFIX."noticia", $dados);

			if (!$Create->getResult()):
				echo "error";
			else:
				if(isset($tag)){
					$id_noticia_return = $Create->getResult();
					foreach($tag as $value){
						if(is_numeric($value)){
							$tag_noticia['id_noticia'] = $id_noticia_return;
							$tag_noticia['id_tag'] = $value;
							$Create->ExeCreate(PREFIX."tag_noticia", $tag_noticia);
						}else {
							$new_tag['nome'] = $value;
							$new_tag['status'] = 1;
							$new_tag['url'] = Check::urlAmigavel($value);
							$Create->ExeCreate(PREFIX."tag",$new_tag);

							$new_tag_noticia['id_noticia'] = $id_noticia_return;
							$new_tag_noticia['id_tag'] = $Create->getResult();
							$Create->ExeCreate(PREFIX."tag_noticia", $new_tag_noticia);
						}


					}
				}
				echo $id_noticia_return;
			endif;

		break;

		case 'GetNoticia':

			$idnoticia = $dados['IdNoticia'];

			$Read = new Read();
			$Read->fullRead("SELECT * FROM ".PREFIX."noticia WHERE id_noticia =:idnoticia", "idnoticia=$idnoticia)");
			$response = $Read->getResult();

			echo json_encode($response);

		break;

		case 'UpdateNoticia':

			if (!isset($dados['status'])){
				$dados['status'] = 0;
			}

			unset($dados['acao']);

			if(isset($_FILES['newimagem'])):
				$file = $_FILES['newimagem'];
				unset($_FILES['newimagem']);
				unset($dados['newimagem']);
			else:
				unset($_FILES['newimagem']);
				unset($dados['newimagem']);
			endif;

					//var_dump($dados);

			if(isset($file)):
				if (!empty($file['name'])){
					if (!empty($dados['img'])) { /* Previous media retained for an audited cleanup. */ }
					$Upload = new Upload("arquivos");
					$Upload->Image($file, Check::urlAmigavel($dados['titulo']), 1920, "/noticia");
                    if (!$Upload->getResult()) scl_deny(422);
					$img = $Upload->getResult();
					$dados['img'] = $img;
				}
			endif;

			$Update = new Update;

			$idNoticia = $dados['id_noticia'];

			$tag = isset($dados['id_tag']) ? $dados['id_tag'] : 0;
			unset($dados['id_tag']);

			$dados['link'] = Check::urlAmigavel($dados['link']);
			$dados['subtitulo'] = mb_substr(strip_tags($dados['subtitulo'], '<(.*?)>') ,0,250);

			$dados['alterador'] = $_SESSION['UsuarioLogin']['id_usuario'];
			$dados['data_alteracao'] = date("Y-m-d H:i:s");

			$Update->ExeUpdate(PREFIX."noticia", $dados, "WHERE id_noticia = :id", "id=$idNoticia");

			if (!$Update->getResult()):
				echo  "<b>Ops:</b> Houve um erro ao atualizar a pagina.";

			else:
				$Create = new Create();
				$Delete = new Delete();
				$Delete->ExeDelete(PREFIX."tag_noticia", "WHERE id_noticia = :id_noticia", "id_noticia=$idNoticia");

				if ($tag > 0){
					if(isset($tag)){
						foreach($tag as $value){
							if(is_numeric($value)){
								$tag_noticia['id_noticia'] = $idNoticia;
								$tag_noticia['id_tag'] = $value;
								$Create->ExeCreate(PREFIX."tag_noticia", $tag_noticia);
							}else {
								$new_tag['nome'] = $value;
								$new_tag['status'] = 1;
								$new_tag['url'] = Check::urlAmigavel($value);
								$Create->ExeCreate(PREFIX."tag",$new_tag);

								$new_tag_noticia['id_noticia'] = $idNoticia;
								$new_tag_noticia['id_tag'] = $Create->getResult();
								$Create->ExeCreate(PREFIX."tag_noticia", $new_tag_noticia);
							}

						}
					}else {
									//echo "Tag nao existe";
					}

				}

				echo $Update->getResult();

			endif;

		break;

		case 'DeleteNoticia':

			$Read = new Read();

			//Valido se a pagina/topico tem filhos

			$idnoticia = $dados['IdNoticia'];

			$Read = new Read();
			$Read->fullRead("select img from ".PREFIX."noticia WHERE id_noticia =:idnoticia", "idnoticia=$idnoticia)");
			$imagen = $Read->getResult()[0]['img'];

			$Delete = new Delete();

			$Delete->ExeDelete(PREFIX."noticia", "WHERE id_noticia = :id_noticia", "id_noticia=$idnoticia");

			if($Delete->getResult()):


				echo 'ok';

			else:

				echo 'Error excluindo o noticia';

			endif;
		break;
	}

endif;
?>