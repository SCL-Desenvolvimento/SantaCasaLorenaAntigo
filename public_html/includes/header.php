<!DOCTYPE html>
<html lang="pt-br">
<!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="" />
  <meta name="author" content="">
  <title><?php echo (isset($r_DIR['info']) ? $r_DIR['info']['titulo'] : ($r_DIR['page'] == "404" ? "Erro 404" : "Home" ))." | Santa Casa de Lorena"; ?></title>
  <meta property="og:url" content="<?php echo (isset($_SERVER['QUERY_STRING']) ? HOME.str_replace("qs=", "", $_SERVER['QUERY_STRING']) : "" ); ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="<?php echo (isset($r_DIR['info']) ? $r_DIR['info']['titulo'] : ($r_DIR['page'] == "404" ? "Erro 404" : "Home" ))." | Santa Casa de Lorena"; ?>" />
  <meta property="og:description" content="<?php echo (isset($r_DIR['info']) ? (isset($r_DIR['info']['seo']) && $r_DIR['info']['seo'] != "" ? $r_DIR['info']['seo'] : (isset($r_DIR['info']['descricao_pagina']) && $r_DIR['info']['descricao_pagina'] != "" ? $r_DIR['info']['descricao_pagina'] : "" ) ) : "" ); ?>" />
  <meta property="og:image" content="<?php echo (isset($r_DIR['info']['imagem']) ? HOME.$r_DIR['info']['imagem'] : "" ); ?>" />
  <meta name="keywords" content="">
  <meta name="description" content="<?php echo (isset($r_DIR['info']) ? (isset($r_DIR['info']['seo']) && $r_DIR['info']['seo'] != "" ? $r_DIR['info']['seo'] : (isset($r_DIR['info']['descricao_pagina']) && $r_DIR['info']['descricao_pagina'] != "" ? $r_DIR['info']['descricao_pagina'] : "" ) ) : "" ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="icon" href="<?php echo ROOT; ?>favicon.ico">
  <link rel="apple-touch-icon" href="<?php echo ROOT; ?>apple-touch-icon.png">
  <link rel="apple-touch-icon" href="<?php echo ROOT; ?>touch-icon-ipad.png">
  <link rel="apple-touch-icon" href="<?php echo ROOT; ?>touch-icon-iphone-retina.png">
  <link rel="apple-touch-icon" href="<?php echo ROOT; ?>touch-icon-ipad-retina.png">

	<!-- Font -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Domine:400,700" rel="stylesheet">
	<!-- End | Font -->
  
  <!-- jQuery 2.1.4 -->
  <script src="<?php echo ROOT; ?>resources/plugins/jQuery/jQuery-2.1.4.min.js"></script>


  <!-- Bootstrap 3.3.5 -->
  <script src="<?php echo ROOT; ?>resources/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="<?php echo ROOT; ?>resources/bootstrap/css/bootstrap.min.css">

  <!-- Owl carousel -->
  <link rel="stylesheet" href="<?php echo ROOT; ?>resources/plugins/owlcarousel/owl.carousel.min.css">
  <link rel="stylesheet" href="<?php echo ROOT; ?>resources/plugins/owlcarousel/owl.theme.default.min.css">
  <script src="<?php echo ROOT; ?>resources/plugins/owlcarousel/owl.carousel.min.js"></script>

	<!-- Swiper -->
  <!--
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>resources/plugins/swiper/swiper.min.css">
  -->
	<!-- End | Swiper -->

	<!-- CSS - All pages -->
	<link rel="stylesheet" href="<?php echo ROOT; ?>resources/css/style.css">
	<!-- End | CSS - All pages -->
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

</head>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-110376666-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-110376666-1');
</script>

<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.11';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
  
