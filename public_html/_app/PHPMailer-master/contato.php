<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Anclas</title>
<link rel="stylesheet" href="css/style.css">
<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

<!-- Contato -->
<script src="scripts/contato.js" type="text/javascript"></script>
<script src="scripts/mascaras.js" type="text/javascript"></script>
<!-- Fim de contato -->

</head>

<body>

<?php
/*apenas dispara o envio da mensagem caso houver/existir $_POST['enviar']*/
if(isset($_POST['enviar'])){
	
	echo $_POST['enviar'];

$destinatarios = 'andre@pentaxialroot.com.br';

$nomeDestinatario = 'André Lucas Franco';

$usuario = 'teste@anclas.com.br';

$senha = "anclas2184";

$assunto = "Teste de Email";

/*abaixo as veriaveis principais, que devem conter em seu formulario*/
$nomeRemetente = $_POST['nomeRemetente'];
$assunto = $_POST['assunto'];
$_POST['mensagem'] = nl2br('E-mail: '. $_POST['email'] ."

". $_POST['mensagem']);


/*********************************** A PARTIR DAQUI NAO ALTERAR ************************************/
include_once("PHPMailer-master/class.smtp.php");
include_once("PHPMailer-master/class.phpmailer.php");

$To = $destinatarios;
$Subject = $assunto;
$Message = $_POST['mensagem'];


$Host = 'smtp.'.substr(strstr($usuario, '@'), 1);
$Username = $usuario;
$Password = $senha;
$Port = "587";

$mail = new PHPMailer();
$body = $Message;
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = $Host; // SMTP server
$mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
// 1 = errors and messages
// 2 = messages only
$mail->SMTPAuth = true; // enable SMTP authentication
$mail->Port = $Port; // set the SMTP port for the service server
$mail->Username = $Username; // account username
$mail->Password = $Password; // account password

$mail->SetFrom($usuario, $nomeDestinatario);
$mail->Subject = $Subject;
$mail->MsgHTML($body);
$mail->AddAddress($To, "");

if(!$mail->Send()) {
$mensagemRetorno = 'Erro ao enviar e-mail: '. print($mail->ErrorInfo);
} else {
$mensagemRetorno = 'E-mail enviado com sucesso!';
}


echo $mensagemRetorno;
}
?>

<div id="fb-root"></div>

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
      $('html, body').animate({scrollTop: $('#ancora').offset().top }, 1000);
});
</script>

<div class="tudo">
    
    <?php include"includes/topo.php" ?>
    
        <!-- CONTEÚDO -->
        <div class="bg_conteudo2">
            <div class="tudo2">
            	
                <div class="banner_contato">
                	<div class="titulo_banners">Contato</div>
                </div>
                
            	<div class="contd_esq" id="ancora">
                    Para maiores informações, entre em contato com nossa equipe de atendimento:<br>
                    <br>
                    <strong>Telefones: </strong>(12) 3629-3496 / 3629-3491<br>
                    <strong>E-mail: </strong>comercial@anclas.com.br<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;contato@anclas.com.br<br>
                    <br>
                    <strong>Horário de funcionamento:<br> </strong>Seg. à Quinta das 8:00 às 18:00<br>
                    Sexta-feira das 8:00 ás 17:00<br>
                    <br>
                    <!-- Tabela de contato -->
                    <div style="width:700px; height:auto; float:left;">
                        <form method="post" name="form_contato" id="form_contato" action="">
                            <input type="text" id="nome_contato" name="nomeremetente" class="class_input" placeholder="Insira seu nome..." style="margin-bottom:5px; font-family:'Arial';">
                            <br>
                            <input type="text" id="telefone_contato" name="telefone" placeholder="Insira seu telefone..." class="class_input" style="font-family:'Arial'; margin-left:0px; margin-bottom:5px; width:200px;" maxlength="15" onKeyDown="Mascara(this,Telefone);" onKeyPress="Mascara(this,Telefone);" onKeyUp="Mascara(this,Telefone);" />
                            <br>
                            <input type="text" id="email_contato" name="emailremetente" class="class_input" placeholder="Insira seu e-mail..." style="margin-bottom:5px; font-family:'Arial';">
                            <textarea style="font-family:'Arial'; font-size:13px;" id="mensagem_contato" name="mensagem" placeholder="Insira sua mensagem..." class="input_contato_text class_input" onKeyDown="textCounter(this.form.mensagem_contato,this.form.remLen,1000);" onKeyUp="textCounter(this.form.mensagem_contato,this.form.remLen,1000);"></textarea>
                       
                        <input style="font-family:'Montserrat', sans-serif; border-radius:10px; width:150px; height:50px; margin-top:20px; margin-left:470px; cursor:pointer; background:#a6ce38; color:#FFF; border:#FFF 2px solid;" type="submit" id="button" name="enviar" value="Enviar" class="btn_enviar" onClick="contato()" />
                    </div>
                    <!-- Fim tabela contato -->
                    </div>
                    <div class="clear"></div>
                     </form>
                </div>
                
                <div style="width:250px; height:auto; margin:30px 0px 30px 0px; float:left;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.3406338133695!2d-45.549338936181556!3d-23.01126194975885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ccf9a46dc30cf7%3A0xabfaa68279e429c8!2sAv.+Amador+Bueno+da+Veiga%2C+294+-+Jardim+Jaragua%2C+Taubat%C3%A9+-+SP%2C+Brasil!5e0!3m2!1spt-BR!2sbr!4v1430829675388" width="250" height="650" frameborder="0" scrolling="no" style="border:0"></iframe>
                    <div align="right">
                        <a href="https://www.google.com/maps/place/Av.+Amador+Bueno+da+Veiga,+294+-+Jardim+Jaragua,+Taubat%C3%A9+-+SP,+Brasil/@-23.0112619,-45.5493389,17z/data=!4m2!3m1!1s0x94ccf9a46dc30cf7:0xabfaa68279e429c8?hl=pt-BR" target="_blank" class="decor">
                            <strong>Veja em tamanho maior</strong>
                        </a>
                    </div>
                </div>
            
            </div>
        </div>
        <!-- Fim CONTEÚDO -->
        
    <div class="clear"></div>    
	<?php include"includes/nossos_clientes.php" ?>
    
    <?php include"includes/news.php" ?>
    
    <?php include"includes/rodape.php" ?>
    
</div>

<script type="text/javascript">

	var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
	var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email");
	var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {minChars:2, maxChars:2});
	var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");	
	var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");

</script>
</body>
</html>