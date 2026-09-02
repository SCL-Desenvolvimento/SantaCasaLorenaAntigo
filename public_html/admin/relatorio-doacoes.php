<?php
	require('../_app/Config.inc.php');
	
	$login = new Login(3);

	if(!$login->CheckLogin()):
		unset($_SESSION['UsuarioLogin']);
		header("Location: index.php?exe=Restrito");
	else:
		$usuarioLogin = $_SESSION['UsuarioLogin'];
	endif;

	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if((isset($_GET['data_inicio']) && $_GET['data_inicio'] != "") && (isset($_GET['data_fim']) && $_GET['data_fim'] != "")):

        $d1 = explode("/", $_GET['data_inicio']);
        $data_inicio = $d1[2]."-".$d1[1]."-".$d1[0]." 00:01:01";

        $d2 = explode("/", $_GET['data_fim']);
        $data_fim = $d2[2]."-".$d2[1]."-".$d2[0]." 23:59:59";

        $data = " (C.data BETWEEN '{$data_inicio}' AND '{$data_fim}') AND ";
        $periodo = "Entre ".$_GET['data_inicio']." e ".$_GET['data_fim'];
    else:
        if(isset($_GET['data_inicio']) && $_GET['data_inicio'] != ""):

            $d1 = explode("/", $_GET['data_inicio']);
            $data_inicio = $d1[2]."-".$d1[1]."-".$d1[0]." 00:01:01";
            $data = " (C.data > '{$data_inicio}') AND ";
            $periodo = "A partir de ".$_GET['data_inicio'];
        elseif(isset($_GET['data_fim']) && $_GET['data_fim'] != ""):

            $d2 = explode("/", $_GET['data_fim']);
            $data_fim = $d2[2]."-".$d2[1]."-".$d2[0]." 23:59:59";
            $data = " (C.data < '{$data_fim}') AND ";
            $periodo = "Até ".$_GET['data_fim'];
        endif;
    endif;

    $getDoacoes = new Read();
    $getDoacoes->fullRead("SELECT D.* FROM ".PREFIX."doacoes AS D
                            WHERE ".(isset($data) && $data != "" ? $data : "")." D.id_doacao > 0 ORDER BY D.data_cadastro DESC");
	
	$nome_arquivo = "relatorio-doacoes_".date("dmy");
	header("Content-type: application/vnd.ms-excel");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
	header("Pragma: no-cache"); 
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<table width="400" border="0">
	<tr height="45">
    	<td bgcolor="#012F3A" colspan="2" align="center" style="font-weight:bold; vertical-align:middle; color:#FFFFFF; font-size:12pt; border-bottom:1px solid #FFFFFF;">Doações - Santa Casa de Lorena</td>
    </tr>  
</table>
<table width="900" border="0" cellspacing="0" cellpadding="0">
	<tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr height="25">
        <td bgcolor="#999999"><b>&nbsp;Nome</b></td>
        <td bgcolor="#999999">&nbsp;</td>
        <td bgcolor="#999999"><b>&nbsp;E-mail</b></td>
        <td bgcolor="#999999">&nbsp;</td>
        <td bgcolor="#999999"><b>&nbsp;Cidade</b></td>
        <td bgcolor="#999999">&nbsp;</td>
        <td bgcolor="#999999"><b>&nbsp;Assunto</b></td>
        <td bgcolor="#999999">&nbsp;</td>
        <td bgcolor="#999999"><b>&nbsp;Data</b></td>
        <td bgcolor="#999999">&nbsp;</td>
        <td bgcolor="#999999"><b>&nbsp;Mensagem</b></td>
        <td bgcolor="#999999">&nbsp;</td>
    </tr>
	<?php				
		foreach ($getDoacoes->getResult() AS $doacao){

			$i=0;
			$bg = ($i % 2 == 0 ? "#EEEEEE" : "#CCCCCC");
			$i++;
	?>
    <tr bgcolor="<?php echo $bg; ?>">
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo $doacao['nome']; ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo $doacao['email']; ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo $doacao['cidade']; ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo $doacao['assunto']; ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo date("d/m/Y - H:i:s", strtotime($doacao['data_cadastro'])); ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo $doacao['mensagem']; ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
    </tr>
	<?php
		}
	?>             
    <tr height="20">
        <td colspan="26"></td>
    </tr>
</table>