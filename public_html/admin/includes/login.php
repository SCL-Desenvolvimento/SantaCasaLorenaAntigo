<form action="<?php echo HOME."admin/"?>" method="post">
  <div class="form-group has-feedback">
    <input type="user" name="usuario" class="form-control" placeholder="Usuário">
    <span class="glyphicon glyphicon-user form-control-feedback"></span>
  </div>
  <div class="form-group has-feedback">
    <input type="password" name="senha" class="form-control" placeholder="Senha">
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <button type="submit" name="AdminLogin" value="Entrar" class="btn btn-login">Entrar</button>
    </div>
    <!-- /.col -->
  </div>
</form>

<a href="#" style="padding: 0 !important;" data-toggle="modal" data-target="#recuperar-senha">Esqueci Minha Senha</a><br>
