<!-- DataTables -->
<link rel="stylesheet" href="../resources/plugins/datatables/dataTables.bootstrap.css">
<?php 
$login = new Login(3);

if(!$login->CheckLogin()):
  unset($_SESSION['UsuarioLogin']);
  header("Location: index.php?exe=Restrito");
else:
  $usuarioLogin = $_SESSION['UsuarioLogin'];
endif;

?>
<div id="mensagem_evento">
	
</div>

<span id="modo" style="display:none">consulta</span>

<div class="row">

  <div class="col-xs-12">
    <div class="box box-primary">

      <div class="box-header">

        <!-- <i class="fa fa-refresh fa-spin"></i> -->

        <h1 class="pagina-titulo">
          Notícias
          <small>listagem</small>
        </h1>
        <div class="pull-right">
          
         <a href="painel.php?exe=noticias/create" class="btn btn-success btn-sm">
           <i class="fa fa-plus"></i>
         </a>

       </div>
     </div>
     <!-- /.box-header -->

     <div class="box-body">
       <table id="data-list" class="display" cellspacing="0">
        <thead>
          <tr>
            <th>Imagem</th>
            <th>Título</th>
            <th>Status</th>
            <th>Ação</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        
      </table>
    </div>
    <!-- /.box-body -->
    <div class="espere"></div>
  </div>
</div>
<!-- /.col -->
</div>
<!-- /.row -->
<?php include("scripts.php") ?>  