<?php
	ob_start();
	
	require('../_app/Config.inc.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
  <title>Login | Santa Casa de Lorena</title>
  <link rel="icon" href="../favicon.ico">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../resources/bootstrap/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="../resources/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../resources/dist/css/skins/_all-skins.css">

  <!-- Style -->
  <link rel="stylesheet" href="../resources/css/style.css">

</head>
<body class="hold-transition login-page">

<div class="modal fade" id="recuperar-senha" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick="limpaRecuperarSenhaModal()">&times;</button>
        <h4 class="modal-title">Recuperar de senha</h4>
      </div>

      <div class="modal-body">
        <form method="post" id="recuperarSenhaForm" action="<?php echo HOME."admin/index.php"; ?>">

          <div class="form-group">
            <label for="">E-mail cadastrado</label>
            <input type="email" id="rEmail" class="form-control" name="email" >
            <label class="msg-erro"></label>
          </div>

          <input type="hidden" name="recuperar_senha" value="recuperar_senha">

        </form>
      </div>
          
          
      <div class="modal-footer">
        <button type="button" onclick="getSenha();" class="btn btn-success btn-sm">Confirmar</button>
      </div>

    </div>

  </div>
</div>

<?php 

if(isset($_POST['email'])):

    $checkEmail = new Read();
    $checkEmail->fullRead("SELECT id_usuario, nome, usuario FROM ".PREFIX."usuario WHERE email = :email","email={$_POST['email']}");
    if($checkEmail->getResult()):
      
      $dadosUpdate = array(); 
      $senha = Check::geraSenha(8);
      $dadosUpdate['senha'] = md5($senha);
      $dadodUpdate['data_alteracao'] = date("Y-m-d H:i:s");
      $atualizaSenha = new Update();

      $atualizaSenha->ExeUpdate(PREFIX."usuario", $dadosUpdate, "WHERE id_usuario = :id_usuario", "id_usuario={$checkEmail->getResult()[0]['id_usuario']}");

      if($atualizaSenha->getResult()):

        $Message = "<br><br><img src='".HOME."resources/img/logo2.png'><br><br>Você solicitou uma nova senha através site Santa Casa de Lorena.<br><br>
            <b>Senha temporária:</b> $senha<br><br>
            Acesse o Santa Casa de Lorena com seu LOGIN e esta SENHA acima e altere ela o quanto antes, acessando o menu 'Perfil' na barra lateral direita.<br><br>
            Não responda este e-mail. Ele foi gerado automaticamente pelo sistema.";
       
        $body = utf8_decode($Message);
        $mail = new PHPMailer();
        $nomeRemetente = 'ASC';
        $mail->Subject =  utf8_decode("Alteração de senha");

        if(HOME == 'http://localhost/ASC/'):

          $usuario = 'pentaxialdev@gmail.com';
          $To = $_POST['email'];

          $mail->SetFrom($usuario, utf8_decode($nomeRemetente));
          $mail->IsSMTP();
          //$mail->SMTPDebug = 6;
          $mail->SMTPSecure = 'tls'; 
          $mail->Port = 25; //Indica a porta de conexão para a saída de e-mails
          $mail->Host = 'smtp.gmail.com'; //smtp.dominio.com.br
          $mail->SMTPAuth = true; //define se haverá ou não autenticação no SMTP
          $mail->Username = $usuario;
          $mail->Password = 'PX705co*10';
      
        else:

          $usuario = 'webmaster@autoshoppingcristal.com.br';
          $To = $_POST['email'];

        endif;

        $mail->SetFrom($usuario, utf8_decode($nomeRemetente));

        $mail->MsgHTML($body);
        $mail->AddAddress($To, "");

        $mail->Send();

        SystemErro("Recuperação de senha", "Uma nova senha foi enviada para o e-mail <b>{$_POST['email']}</b>.", System_ACCEPT);

      else:
        SystemErro("Recuperação de senha", "Não foi possível alterar a senha. Tente novamente mais tarde.", System_ACCEPT);
      endif;

    else:
      SystemErro("Recuperação de senha", "O e-mail informado não consta em nosso sistema.", System_INFOR);
    endif;
  endif;

?>

<?php

    $get = filter_input(INPUT_GET, 'exe',FILTER_DEFAULT);
    if(!empty($get)):
        if($get == "LogOff"):
            SystemErro("", "LogOff realizado com sucesso", System_INFOR);
        elseif($get == "Restrito"):
            SystemErro("", "Opss, área restrita!", System_ALERT);
        endif;
    endif;


    $login = new Login(0);
    if($login->CheckLogin()):
      if($_SESSION['UsuarioLogin']['nivel'] >= 1):
        header('Location: painel.php');
      endif;
    endif;

    $dadosLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($dadosLogin['AdminLogin'])):
        $login->ExeLogin($dadosLogin);
        if(!$login->GetResultado()):
            SystemErro("Acesso negado", $login->GetErro()[0], System_ALERT);
        elseif($_SESSION['UsuarioLogin']['nivel'] < $login->GetNivel()):
            SystemErro("", $login->GetErro()[0], System_ALERT);
        else:
          if($_SESSION['UsuarioLogin']['nivel'] >= 1):
            header('Location: painel.php');
          else:
            header("Location: ".HOME."admin");
          endif;
        endif;
    endif;
?>

<div class="login-box">
  <div class="login-logo">
    <a href="#">
        <img src="../resources/img/logo.svg" alt="logo" class="img-responsive" style="display: inline;">
    </a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Área reservada</p>

    <!-- Login -->
    <?php require_once('includes/login.php'); ?>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="../resources/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- Bootstrap 3.3.5 -->
<script src="../resources/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">
  
  //Validação de e-mail
  function validaEmail(email){
    var exclude=/[^@\-\.\w]|^[_@\.\-]|[\._\-]{2}|[@\.]{2}|(@)[^@]*\1/;
    var check=/@[\w\-]+\./;
    var checkend=/\.[a-zA-Z]{2,3}$/;
    if(((email.search(exclude) != -1)||(email.search(check)) == -1)||(email.search(checkend) == -1)){
      return false;}
    else{
      return true;
    }
  }


  function getSenha(){
    if(!validaEmail($("#rEmail").val())){
      $("#rEmail").focus();
      $("#rEmail").closest(".form-group").addClass('has-error');
      $("#rEmail").closest(".form-group").find('.msg-erro').html("Insira um email válido");
    }else{
      $("#rEmail").closest(".form-group").removeClass('has-error').find('.msg-erro').html("");
      $('#recuperarSenha').modal('hide');
      $("#recuperarSenhaForm").submit();
    }
  }

function limpaRecuperarSenhaModal(){
  $("#rEmail").val('').closest(".form-group").removeClass('has-error').find('.msg-erro').html("");
}

</script>

</body>
</html>
