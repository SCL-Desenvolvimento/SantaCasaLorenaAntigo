<?php
	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
	require('../../_app/Config.inc.php');

	if(isset($dados['acao'])):
		switch ($dados['acao']):

			case 'getGalleria':

				$getGalleria = new Read();
				$getGalleria->fullRead("SELECT DISTINCT A.*, G.nome AS galeria FROM 
										".PREFIX."galeria_anexo AS GA
										INNER JOIN ".PREFIX."anexo AS A ON (A.id_anexo = GA.id_anexo)
										INNER JOIN ".PREFIX."galeria AS G ON (G.id_galeria = GA.id_galeria)
										WHERE G.id_galeria = :id_galeria",
										"id_galeria={$dados['galleria']}");
				if($getGalleria->getResult()):
					/*foreach($getGalleria->getResult() AS $anexo):
					endforeach;*/
					echo json_encode($getGalleria->getResult());
				endif;

      		break;

		endswitch;
	endif;

?>
