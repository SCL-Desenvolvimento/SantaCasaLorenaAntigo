<?php
	ob_start();
	
	require(__DIR__ . '/../_app/Config.inc.php');
scl_admin_require();

	define('SCL_ADMIN_PANEL', true);
	$login = new Login(3);
	$logoff = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['LogOff']));
	$getExe = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);

	if(!$login->CheckLogin()):
		unset($_SESSION['UsuarioLogin']);
		header("Location: index.php?exe=Restrito");
    exit;
	else:
		$usuarioLogin = $_SESSION['UsuarioLogin'];
	endif;

	if($logoff):
		scl_logout();
		header("Location: index.php?exe=LogOff", true, 303);
		exit;
	endif;

  if(isset($getExe)):
    $linkTo = explode('/', $getExe);
  else:
    $linkTo = array();
  endif;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="<?= htmlspecialchars(scl_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Área do Administrador | Santa Casa de Lorena</title>
  <link rel="icon" href="../favicon.ico">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../resources/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Google Fonts -->
  <link href='https://fonts.googleapis.com/css?family=Titillium+Web:600,900,400,300 | Titillium+Web' rel='stylesheet' type='text/css'>
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../resources/dist/css/skins/skin-black.css">

  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../resources/plugins/iCheck/all.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="../resources/plugins/select2/select2.min.css">

	<!-- CSS do painel administrativo -->
  <link rel="stylesheet" href="../resources/css/admin.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="../resources/dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style type="text/css">
  .breadcrumb{
    display: none !important;
  }
  /*alteracao menu: texto saindo do menu - julio ferreira*/
  .sidebar-menu .treeview-menu>li>a{
    white-space: normal;
  }
  </style>
</head>
<body class="hold-transition skin-black sidebar-mini">
<!-- Inicio -->
<div class="wrapper">
  <!-- Header -->
  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo / 50x50 pixels -->
      <span class="logo-mini"><img src="../resources/img/logo_mini2.png" alt="logo" class="img-responsive" style="margin-top:10px;"></span>
      <!-- logo normal -->
      <span class="logo-lg">
        <img src="../resources/img/logo2.png" alt="logo" class="img-responsive" style="max-height:50px; display: inline;">
      </span>
    </a>
     <!-- /Logo -->

    <!-- Header Menu -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Toggle button - Bloco lateral esquerdo -->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
	  <!-- /Toggle button - Bloco lateral esquerdo -->

     <!-- Header Menu lateral -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
        <?php include("includes/menu_header.php"); ?>
        </ul>
      </div>
      <!-- /Header Menu lateral -->

    </nav>
  </header>
  <!-- /Header -->


  <!-- Bloco lateral esquerdo -->
  <aside class="main-sidebar">
    <section class="sidebar">
      <!-- Usuario Logado -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?= htmlspecialchars(scl_avatar_url(), ENT_QUOTES, 'UTF-8') ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p> <?php echo htmlspecialchars($_SESSION['UsuarioLogin']['nome'], ENT_QUOTES, 'UTF-8'); ?></p>
          <a></a>
        </div>
      </div>
	  <!-- /Usuario Logado -->

    <!-- Busca -->
      <!--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
    <!-- /.Busca -->

      <!-- Menu lateral -->
      <?php include("includes/menu_lateral.php") ?>
      <!-- /Menu lateral -->

    </section>
  </aside>
  <!-- /Bloco lateral esquerdo -->

  <!-- Conteúdo Principal -->
  <div class="content-wrapper">
    <!-- Conteúdo Principal - Topo -->
    <section class="content-header">


      <!-- Navegação -->
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>


    </section>


	<!-- /Conteúdo Principal - Topo -->


    <!-- Controller -->
    <section class="content">

    <?php
        $route = trim((string) $getExe, '/');
        $routes = ['home', 'usuario', 'usuario/index', 'usuario/create', 'usuario/update', 'noticias', 'noticias/index', 'noticias/create', 'noticias/update', 'banner', 'banner/index', 'banner/create', 'banner/update', 'galeria', 'galeria/index', 'galeria/create', 'galeria/update', 'paginas/institucional', 'paginas/servicos', 'paginas/instalacoes', 'paginas/fale-conosco'];
        if ($route === '') $route = 'home';
        if (!in_array($route, $routes, true)) scl_deny(404);
        if (in_array($route, ['usuario', 'noticias', 'banner', 'galeria'], true)) $route .= '/index';
        require __DIR__ . '/system/' . $route . '.php';
    ?>
    </section>
    <!-- /Controller -->
  </div>
  <!-- /Conteúdo Principal -->

  <!-- Rodapé -->
  <footer class="main-footer">
     <div class="pull-right hidden-xs">
      <b>Versão</b> 0.1
    </div>
    <strong>Copyright © <?php echo date("Y"); ?> <a href="#">Santa Casa de Lorena</a>.</strong> Todos os direitos reservados.
  </footer>
  <!-- /Rodapé -->

  <!-- Controles Sidebar -->
  <?php include("includes/aside.php") ?>
  <!-- /Controles Sidebar -->
  <div class="control-sidebar-bg"></div>

</div>
<!-- Fim -->

<!-- Scripts -->
<?php
  include("includes/scripts.php");
  (isset($js) ? require_once($js) : "");
  (isset($js2) ? require_once($js2) : "");
 ?>
<!-- /.Scripts -->
</body>
</html>
