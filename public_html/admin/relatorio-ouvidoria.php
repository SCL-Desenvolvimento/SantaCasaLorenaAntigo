<?php
	require(__DIR__ . '/../_app/Config.inc.php');
scl_admin_require();
    require_once __DIR__.'/../includes/ui.php';
    require_once __DIR__.'/../includes/ouvidoria_queries.php';
	
	$login = new Login(3);

	if(!$login->CheckLogin()):
		unset($_SESSION['UsuarioLogin']);
		header("Location: index.php?exe=Restrito");
        exit;
	else:
		$usuarioLogin = $_SESSION['UsuarioLogin'];
	endif;

	$dados = scl_admin_input();

    try {
        [$sql, $params] = scl_ouvidoria_query($_GET);
    } catch (InvalidArgumentException $error) {
        http_response_code(400);
        header('Content-Type: text/plain; charset=UTF-8');
        echo $error->getMessage();
        exit;
    }
    $getOuvidoria = new Read();
    $getOuvidoria->fullRead($sql, $params);

    $nome_arquivo = "relatorio-ouvidoria_".date("dmy");
	header("Content-type: application/vnd.ms-excel");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=$nome_arquivo.xls");
	header("Pragma: no-cache"); 
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<table width="400" border="0">
	<tr height="45">
    	<td bgcolor="#012F3A" colspan="2" align="center" style="font-weight:bold; vertical-align:middle; color:#FFFFFF; font-size:12pt; border-bottom:1px solid #FFFFFF;">Ouvidoria - Santa Casa de Lorena</td>
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
        <td bgcolor="#999999"><b>&nbsp;Razão</b></td>
        <td bgcolor="#999999">&nbsp;</td>
        <td bgcolor="#999999"><b>&nbsp;Data</b></td>
        <td bgcolor="#999999">&nbsp;</td>
        <td bgcolor="#999999"><b>&nbsp;Mensagem</b></td>
        <td bgcolor="#999999">&nbsp;</td>
    </tr>
	<?php				
		foreach (($getOuvidoria->getResult() ?: array()) AS $ouvidoria){

			$i=0;
			$bg = ($i % 2 == 0 ? "#EEEEEE" : "#CCCCCC");
			$i++;
	?>
    <tr bgcolor="<?php echo $bg; ?>">
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo scl_report_text($ouvidoria['nome'] ?? ''); ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo scl_report_text($ouvidoria['email'] ?? ''); ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo scl_report_text($ouvidoria['cidade'] ?? ''); ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo scl_report_text($ouvidoria['assunto'] ?? ''); ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo scl_report_text($ouvidoria['razao'] ?? ''); ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo date("d/m/Y - H:i:s", strtotime($ouvidoria['data_cadastro'])); ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;<b><?php echo scl_report_text($ouvidoria['mensagem'] ?? ''); ?></b></td>
        <td bgcolor="<?php echo $bg; ?>">&nbsp;</td>
    </tr>
	<?php
		}
	?>             
    <tr height="20">
        <td colspan="26"></td>
    </tr>
</table>