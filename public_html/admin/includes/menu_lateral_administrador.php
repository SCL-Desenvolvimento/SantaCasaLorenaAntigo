<?php if(!defined('SCL_ADMIN_PANEL')){http_response_code(403);exit;} ?>
<a class="nav-overview" href="painel.php">Visão geral</a><ul class="sidebar-menu"><li class="header">Conteúdo e atendimento</li>
<?php $navigation=[
 'Notícias'=>[['Todas as notícias','noticias/index'],['Nova notícia','noticias/create']],
 'Banners'=>[['Todos os banners','banner/index'],['Novo banner','banner/create']],
 'Galerias'=>[['Todas as galerias','galeria/index'],['Nova galeria','galeria/create']],
 'Institucional'=>[['Sobre a Santa Casa','paginas/institucional#sobre-santa-casa-lorena'],['Humanização','paginas/institucional#humanizacao_'],['Ações sociais e ambientais','paginas/institucional#acoes-sociais-ambientais'],['Segurança do paciente','paginas/institucional#programa-nacional-seguranca'],['Transparência','paginas/institucional#portal-transparencia']],
 'Instalações'=>[['Pronto atendimento','paginas/instalacoes#pronto-atendimento'],['Hotelaria','paginas/instalacoes#hotelaria_'],['Clínica Emília','paginas/instalacoes#clinica-emilia'],['Diagnóstico por imagem','paginas/instalacoes#centro-diagnostico-por-imagem'],['Internação','paginas/instalacoes#unidade-internacao']],
 'Serviços'=>[['Convênios','paginas/servicos#convenios_'],['Especialidades','paginas/servicos#especialidades_'],['Capacidade e produção','paginas/servicos#capacidade-instalacao-producao'],['Manual do paciente','paginas/servicos#manual-paciente-visitante']],
 'Atendimento'=>[['Recebimentos','atendimento'],['Textos e localização','paginas/fale-conosco#localizacao_'],['Relatórios','relatorios']],
 'Usuários'=>[['Todos os usuários','usuario/index'],['Novo usuário','usuario/create'],['Meu perfil','usuario/update&id_usuario='.(int)$usuarioLogin['id_usuario']]]
 ];foreach($navigation as $label=>$links): ?><li class="treeview"><a href="#"><span><?= admin_escape($label) ?></span></a><ul class="treeview-menu"><?php foreach($links as [$label,$target]): ?><li><a href="painel.php?exe=<?= admin_escape($target) ?>"><?= admin_escape($label) ?></a></li><?php endforeach ?></ul></li><?php endforeach ?></ul>
