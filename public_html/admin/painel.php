<?php
require __DIR__ . '/../_app/Config.inc.php';
scl_admin_require();
define('SCL_ADMIN_PANEL', true);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['LogOff'])) {
    scl_logout(); header('Location: index.php?exe=LogOff', true, 303); exit;
}
$getExe = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT) ?: 'home';
$route = trim((string) $getExe, '/');
$routes = ['atendimento','relatorios','home','usuario','usuario/index','usuario/create','usuario/update','noticias','noticias/index','noticias/create','noticias/update','banner','banner/index','banner/create','banner/update','galeria','galeria/index','galeria/create','galeria/update','paginas/institucional','paginas/servicos','paginas/instalacoes','paginas/fale-conosco'];
if (!in_array($route,$routes,true)) scl_deny(404);
if (in_array($route,['usuario','noticias','banner','galeria'],true)) $route.='/index';
$linkTo=explode('/',$route);
$usuarioLogin=$_SESSION['UsuarioLogin'];
$login=new Login(3); // Existing templates reuse the authenticated login object.
function admin_escape($text){return htmlspecialchars((string)$text,ENT_QUOTES,'UTF-8');}
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="<?= admin_escape(scl_csrf_token()) ?>"><title>Administração | Santa Casa de Lorena</title><link rel="icon" href="../favicon.ico">
<?php foreach(['bootstrap/bootstrap.min.css','datatables/dataTables.bootstrap5.min.css','tom-select/tom-select.bootstrap5.min.css'] as $asset): ?><link rel="stylesheet" href="../resources/vendor/<?= $asset ?>"><?php endforeach ?>
<link rel="stylesheet" href="../resources/css/admin-modern.css"></head><body class="admin-app"><a class="admin-skip" href="#admin-content">Pular para o conteúdo</a>
<header class="admin-header"><button class="btn btn-outline-secondary" type="button" data-sidebar-toggle aria-label="Abrir menu" aria-expanded="false">☰</button><a class="brand" href="painel.php">Santa Casa de Lorena<small>ADMINISTRAÇÃO DO SITE</small></a><div class="account"><span><?= admin_escape($usuarioLogin['nome']) ?></span><a class="btn btn-default btn-sm" href="painel.php?exe=usuario/update&amp;id_usuario=<?= (int)$usuarioLogin['id_usuario'] ?>">Meu perfil</a><form method="post"><input type="hidden" name="_csrf" value="<?= admin_escape(scl_csrf_token()) ?>"><button class="btn btn-outline-secondary btn-sm" name="LogOff" value="1">Sair</button></form></div></header>
<div class="admin-layout"><aside class="admin-sidebar" aria-label="Menu administrativo"><?php require __DIR__.'/includes/menu_lateral_administrador.php'; ?><a href="diagnostico.php" target="_blank" rel="noopener">Diagnóstico do servidor ↗</a><a href="../" target="_blank" rel="noopener">Visitar o site ↗</a></aside><main class="admin-main" id="admin-content" tabindex="-1"><div class="content-header"></div><section class="content"><?php require __DIR__.'/system/'.$route.'.php'; ?></section><footer class="admin-footer">Santa Casa de Lorena · Administração</footer></main></div><div class="espere" role="status">Carregando…</div>
<?php require __DIR__.'/includes/scripts.php'; if(isset($js))require $js; if(isset($js2))require $js2; ?>
</body></html>
