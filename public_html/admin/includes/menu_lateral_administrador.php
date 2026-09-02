<ul class="sidebar-menu">

  <li class="header">Menu Principal</li>

  <li class="treeview">
    <a href="#">
      <i class="fa fa-users"></i>
      <span>Usuários</span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
      <li><a href="painel.php?exe=usuario/index"><i class="fa fa-circle-o"></i> Todos os usuários</a></li>
      <li><a href="painel.php?exe=usuario/create"><i class="fa fa-circle-o"></i> Adicionar Novo</a></li>
      <li><a href="painel.php?exe=usuario/update&id_usuario=<?php echo $_SESSION['UsuarioLogin']['id_usuario']; ?>"><i class="fa fa-circle-o"></i> Seu Perfil</a></li>
    </ul>
  </li>

  <li class="treeview">
    <a href="#">
      <i class="fa fa-image"></i>
      <span>Banners</span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
      <li><a href="painel.php?exe=banner/index"><i class="fa fa-circle-o"></i> Todos os banners</a></li>
      <li><a href="painel.php?exe=banner/create"><i class="fa fa-circle-o"></i> Adicionar Novo</a></li>
    </ul>
  </li>

  <li class="treeview ">
    <a href="#">
      <i class="fa fa-th"></i>
      <span>Páginas</span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu <?php echo (isset($linkTo[0]) && $linkTo[0] == "paginas" ? "menu-open" : "" ); ?>" <?php echo (isset($linkTo[0]) && $linkTo[0] == "paginas" ? "style=\"display:block;\"" : "" ); ?>>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-share"></i>
          <span>Institucional</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu <?php echo (isset($linkTo[1]) && $linkTo[1] == "institucional" ? "menu-open" : "" ); ?>">
          <li><a href="painel.php?exe=paginas/institucional#sobre-santa-casa-lorena"><i class="fa fa-circle-o"></i> Sobre a santa casa</a></li>
          <li><a href="painel.php?exe=paginas/institucional#humanizacao_"><i class="fa fa-circle-o"></i> Humanização</a></li>
          <li><a href="painel.php?exe=paginas/institucional#acoes-sociais-ambientais"><i class="fa fa-circle-o"></i> Ações sociais e ambientais</a></li>
          <li><a href="painel.php?exe=paginas/institucional#programa-nacional-seguranca"><i class="fa fa-circle-o"></i> Programa nacional de segurança do paciente</a></li>
          <li><a href="painel.php?exe=paginas/institucional#portal-transparencia"><i class="fa fa-circle-o"></i> Portal da transparência</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-share"></i>
          <span>Instalações</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu <?php echo (isset($linkTo[1]) && $linkTo[1] == "instalacoes" ? "menu-open" : "" ); ?>" <?php echo (isset($linkTo[1]) && $linkTo[1] == "instalacoes" ? "style=\"display:block;\"" : "" ); ?>>
          <li><a href="painel.php?exe=paginas/instalacoes#pronto-atendimento"><i class="fa fa-circle-o"></i> Pronto atendimento</a></li>
          <li><a href="painel.php?exe=paginas/instalacoes#hotelaria_"><i class="fa fa-circle-o"></i> Hotelaria</a></li>
          <li><a href="painel.php?exe=paginas/instalacoes#clinica-emilia"><i class="fa fa-circle-o"></i> Clínica Emília</a></li>
          <li><a href="painel.php?exe=paginas/instalacoes#centro-diagnostico-por-imagem"><i class="fa fa-circle-o"></i> Centro de diagnóstico por imagem</a></li>
          <li><a href="painel.php?exe=paginas/instalacoes#unidade-internacao"><i class="fa fa-circle-o"></i> Unidade de internação</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-share"></i>
          <span>Serviços</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu <?php echo (isset($linkTo[1]) && $linkTo[1] == "servicos" ? "menu-open" : "" ); ?>" <?php echo (isset($linkTo[1]) && $linkTo[1] == "servicos" ? "style=\"display:block;\"" : "" ); ?>>
          <li><a href="painel.php?exe=paginas/servicos#convenios_"><i class="fa fa-circle-o"></i> Convenios</a></li>
          <li><a href="painel.php?exe=paginas/servicos#especialidades_"><i class="fa fa-circle-o"></i> Especialidades</a></li>
          <li><a href="painel.php?exe=paginas/servicos#capacidade-instalacao-producao"><i class="fa fa-circle-o"></i> Capacidade de instalação e produção</a></li>
          <li><a href="painel.php?exe=paginas/servicos#manual-paciente-visitante"><i class="fa fa-circle-o"></i> Manual do paciente e visitante</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-share"></i>
          <span>Fale Conosco</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu <?php echo (isset($linkTo[1]) && $linkTo[1] == "fale-conosco" ? "menu-open" : "" ); ?>" <?php echo (isset($linkTo[1]) && $linkTo[1] == "fale-conosco" ? "style=\"display:block;\"" : "" ); ?>>
          <li><a href="painel.php?exe=paginas/fale-conosco#ouvidoria_"><i class="fa fa-circle-o"></i> Ouvidoria</a></li>
          <li><a href="painel.php?exe=paginas/fale-conosco#trabalhe-conosco"><i class="fa fa-circle-o"></i> Trabalhe conosco</a></li>
          <li><a href="painel.php?exe=paginas/fale-conosco#doacoes_"><i class="fa fa-circle-o"></i> Doações</a></li>
          <li><a href="painel.php?exe=paginas/fale-conosco#localizacao_"><i class="fa fa-circle-o"></i> Localização</a></li>
        </ul>
      </li>

    </ul>
  </li>

  <li class="treeview">
    <a href="#">
      <i class="fa fa-file-text"></i>
      <span>Notícias</span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu <?php echo (isset($linkTo[0]) && $linkTo[0] == "noticias" ? "menu-open" : "" ); ?>" <?php echo (isset($linkTo[0]) && $linkTo[0] == "noticias" ? "style=\"display:block;\"" : "" ); ?>>
      <li><a href="painel.php?exe=noticias/index"><i class="fa fa-circle-o"></i> Todas as notícias</a></li>
      <li><a href="painel.php?exe=noticias/create"><i class="fa fa-circle-o"></i> Adicionar notícia</a></li>
      <li><a href="painel.php?exe=galeria/index"><i class="fa fa-circle-o"></i> Galerias</a></li>
    </ul>
  </li>

</ul>