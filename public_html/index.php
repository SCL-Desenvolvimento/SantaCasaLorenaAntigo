<?php
require('_app/Config.inc.php');

/*
$login = new Login(1);
if (!$login->CheckLogin()):
	header('Location: http://www.santacasalorena.org.br');
endif;
*/

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$r_QueryString = explode('/', substr(QUERY_STRING, 3));

$Url = new Url;
$r_DIR = $Url->setUrlAmigavel(REDIRECT_URL);

//Inicia o HTML
require_once('includes/header.php');
require_once('includes/navbar.php');

//Checa se existe conteudo ou se é a Home
if(empty($r_DIR)):

require_once('includes/banner.php');

require_once('includes/instalacoes.php');

require_once('includes/convenios.php');

require_once('includes/noticias.php');

require_once('includes/servicos.php');

require_once('includes/institucional.php');

else:

	if(preg_match("/file-/", basename(QUERY_STRING))){

		$file = explode("-", basename(QUERY_STRING));

		if($file[1] > 1){

			if($r_DIR['page'] == 'portal-transparencia'){

				$getBalanco = new Read(); 
				$getBalanco->fullRead("SELECT * FROM ".PREFIX."balanco WHERE id_balanco = :id_balanco", "id_balanco={$file[1]}"); 
				
				if($getBalanco->getResult()){
								
					$file = DIR.$getBalanco->getResult()[0]['pdf'];

					if (file_exists($file)) {
					    header('Content-Description: File Transfer');
					    header('Content-Type: application/octet-stream');
					    header("Content-Type: application/force-download");
					    header('Content-Disposition: attachment; filename=' . urlencode(basename($file)));
					    // header('Content-Transfer-Encoding: binary');
					    header('Expires: 0');
					    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					    header('Pragma: public');
					    header('Content-Length: ' . filesize($file));
					    ob_clean();
					    flush();
					    readfile($file);
					    exit;
					}
				}else{
					Header("Location: ".HOME."404");
				}
			}else{
				
				$getManualDownload = new Read(); 
				$getManualDownload->fullRead("SELECT * FROM ".PREFIX."download_manual_paciente WHERE id_download_manual_paciente = :id_download_manual_paciente", "id_download_manual_paciente={$file[1]}"); 
				
				if($getManualDownload->getResult()){
								
					$file = DIR.$getManualDownload->getResult()[0]['pdf'];

					if (file_exists($file)) {
					    header('Content-Description: File Transfer');
					    header('Content-Type: application/octet-stream');
					    header("Content-Type: application/force-download");
					    header('Content-Disposition: attachment; filename=' . urlencode(basename($file)));
					    // header('Content-Transfer-Encoding: binary');
					    header('Expires: 0');
					    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					    header('Pragma: public');
					    header('Content-Length: ' . filesize($file));
					    ob_clean();
					    flush();
					    readfile($file);
					    exit;
					}
				}else{
					Header("Location: ".HOME."404");
				}
			}
		}
	}

	$getPagina = new Read();
	require_once(DIR."includes/topo_paginas.php");
	require_once(DIR."includes/paginas/".str_replace("-", "_", $r_DIR['page']).".php");
	
endif;

?>

<?php require_once('includes/footer.php'); ?>