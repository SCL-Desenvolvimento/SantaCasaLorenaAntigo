<!-- Notificações -->
<li class="dropdown messages-menu">
  <!--<a href="#" class="dropdown-toggle"> <!-- data-toggle="dropdown" --
    <i class="fa fa-envelope-o"></i>
    <span class="label label-success">4</span>
  </a>
  
  <ul class="dropdown-menu">
    <li class="header">Você tem 4 mensagens</li>
    
    <li>
      <!-- inner menu: contains the actual data --
      <ul class="menu">

        <!-- start message -->
        <!--<li>
          <a href="#">
            <div class="pull-left">
              <img src="../dist/img/user1-128x128.jpg" class="img-circle" alt="User Image">
            </div>          
            <h4>
              João
              <small><i class="fa fa-clock-o"></i> 5 min</small>
            </h4>
            <p>Olá, testando</p>
          </a>
        </li>-->
        <!-- end message -->

        <!-- start message -->
        <!--<li>
          <a href="#">
            <div class="pull-left">
              <img src="../dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
            </div>          
            <h4>
              João
              <small><i class="fa fa-clock-o"></i> 5 min</small>
            </h4>
            <p>Olá, testando</p>
          </a>
        </li>-->
        <!-- end message -->

        <!-- start message -->
        <!--<li>
          <a href="#">
            <div class="pull-left">
              <img src="../dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
            </div>          
            <h4>
              João
              <small><i class="fa fa-clock-o"></i> 5 min</small>
            </h4>
            <p>Olá, testando</p>
          </a>
        </li>-->
        <!-- end message -->

        <!-- start message -->
        <!--<li>
          <a href="#">
            <div class="pull-left">
              <img src="../dist/img/user5-128x128.jpg" class="img-circle" alt="User Image">
            </div>          
            <h4>
              João
              <small><i class="fa fa-clock-o"></i> 5 min</small>
            </h4>
            <p>Olá, testando</p>
          </a>
        </li>-->
        <!-- end message -->

        <!-- start message -->
        <!--<li>
          <a href="#">
            <div class="pull-left">
              <img src="../dist/img/user6-128x128.jpg" class="img-circle" alt="User Image">
            </div>          
            <h4>
              João
              <small><i class="fa fa-clock-o"></i> 5 min</small>
            </h4>
            <p>Olá, testando</p>
          </a>
        </li>-->
        <!-- end message --
      
      </ul>
    </li>
    <li class="footer"><a href="#">Ver todas as mensagens</a></li>
  </ul>
</li>
<!-- /.Notificações -->

<!-- Notificações: style em dropdown.less -->
<!--<li class="dropdown notifications-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-bell-o"></i>
    <span class="label label-warning">10</span>
  </a>
            
  <ul class="dropdown-menu">
    <li class="header">Você tem 10 noficações</li>
    <li>
      <!-- inner menu: contains the actual data --
      <ul class="menu">
        <li>
          <a href="#">
            <i class="fa fa-users text-aqua"></i> 5 new members joined today
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
              page and may cause design problems
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-users text-red"></i> 5 new members joined
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-shopping-cart text-green"></i> 25 sales made
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-user text-red"></i> You changed your username
          </a>
        </li>
      </ul>
    </li>
    <li class="footer"><a href="#">Ver todos</a></li>
  </ul>
</li>
<!-- /.Notificações -->

<!-- User Account: style can be found in dropdown.less -->
<li class="dropdown user user-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <img src="<?php echo ($_SESSION['UsuarioLogin']['img'] != "" ? "includes/tim.php?src={$_SESSION['UsuarioLogin']['img']}&h=25&w=25" : "includes/tim.php?src=/img/user.png&h=25&w=25")?>" class="user-image" alt="User Image">
    <span class="hidden-xs">
      <?php 
  			echo $_SESSION['UsuarioLogin']['nome'];
  		?>
    </span>
  </a>
  
  <ul class="dropdown-menu">
  <!-- User image -->
    <li class="user-header">
      <img src="<?php echo ($_SESSION['UsuarioLogin']['img'] != "" ? "includes/tim.php?src={$_SESSION['UsuarioLogin']['img']}&h=90&w=90" : "includes/tim.php?src=/img/user.png&h=90&w=90")?>" class="img-circle" alt="User Image">
      <p>
      <?php 
			 echo $_SESSION['UsuarioLogin']['nome']." - ".$_SESSION['UsuarioLogin']['usuario'];
			?> 
        <small>Membro desde <?php echo date("d/m/Y", strtotime($_SESSION['UsuarioLogin']['cadastro']))?></small>
      </p>
    </li>
              
    <!-- Menu Body -->
    <!--<li class="user-body">
      <div class="row">
        <div class="col-xs-4 text-center">
          <a href="">Publicações</a>
        </div>
      </div>
    <!-- /.row -->
    <!--</li>-->

              
  <!-- Menu Footer-->
    <li class="user-footer">
      <div class="pull-left">
        <a href="painel.php?exe=usuario/update&id_usuario=<?php echo $_SESSION['UsuarioLogin']['id_usuario']; ?>" class="btn btn-default btn-flat">Perfil</a>
      </div>
      <div class="pull-right">
        <a href="?LogOff=true" class="btn btn-default btn-flat">Sair</a>
      </div>
    </li>
  </ul>
</li>