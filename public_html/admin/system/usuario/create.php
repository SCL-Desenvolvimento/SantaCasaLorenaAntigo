<?php

$login = new Login(1);

if (!$login->CheckLogin()):
	header('Location: painel.php');
	die;
endif;
?>
<div id="mensagem_evento"></div>

<div id="modo">create</div>
<div class="row">
  <!-- left column -->
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">

        <h1 class="pagina-titulo">
          Usuários
          <small>Criação</small>
        </h1>

        <div class="pull-right">
          <a href="painel.php?exe=usuario/index&nivel=<?php echo $_SESSION['UsuarioLogin']['nivel']; ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-list"></i>
          </a>

          <a href="painel.php?exe=usuario/create" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i>
          </a>
        </div>

      </div>
      <!-- /.box-header -->

      <!-- form start -->
      <form role="form" id="usuarioForm" name="usuarioForm">
        <div class="nav-tabs-custom" style="margin:10px;">

          <div class="tab-content col-md-12">
            <div class="tab-pane active" id="tab_1">

              <div class='col-md-6'>

                <div class="panel panel-default">
                  <div class="panel-body">
                    <center>
                      <img class='previewimg' id='foto-perfil' width='150px' src=''>
                    </center>
                    <br>
                    <input type="file" name="img" onchange="PreviewImg(this)">
                    <p class="help-block">Foto de perfil</p>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">Nome</label>
                  <input type="text" class="form-control" placeholder="Entre com o nome" name="nome">
                  <label class='msg-erro'></label>
                </div>

                <div class="form-group">
                  <label for="">Email</label>
                  <input type="email" class="form-control" id="email" placeholder="Entre com o e-mail" name="email" value="" onBlur="ValidateEmail(this)">
                  <label class='msg-erro'></label>
                </div>

                <div class="form-group">
                  <label for="">Login</label>
                  <input type="text" class="form-control" id="usuario" placeholder="Entre com o login" name="usuario" value="" onBlur="ValidateLogin(this)">
                  <label class='msg-erro'></label>
                </div>

                <div class="form-group">
                  <label for="">Senha</label>
                  <input type="password" class="form-control" placeholder="Entre com a senha" id="senha" name="senha" value="" onKeyUp='verificaSenha(this)' onBlur="limpaMSGSenha(this)" maxlength="8">
                  <label class='msg-erro'></label>
                </div>

                <div class="form-group">
                  <label>
                    <input type="checkbox" class="minimal" name="status" value="1">
                    Habiltar usuário
                  </label>
                </div>

              </div>

            </div>

          </div>

          <div class="box-footer">
            <input type="hidden" id="valido" value="true">
            <button type="button" class="btn btn-primary col-md-4" id='btnCreateUsuario' onclick="CreateUsuario();" >Criar usuário</button>
          </div>

        </div>

      </form>

      <div class="espere"></div>
    </div>
    <!-- /.box -->
  </div>
</div>

<?php include "scripts.php"?>