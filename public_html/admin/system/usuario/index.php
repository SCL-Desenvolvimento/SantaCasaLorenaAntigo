<!-- DataTables -->
<link rel="stylesheet" href="../resources/plugins/datatables/dataTables.bootstrap.css">
<?php 

	$login = new Login(1);
  if (!$login->CheckLogin() || (isset($access['admin']) && $access['admin'] != 1)):
    header('Location: painel.php');
    die;
  endif;
		
?>

<div id="mensagem_evento"></div>
<span id="modo" style="display:none">consulta</span>

<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header">

		    <h1 class="pagina-titulo">
          Usuários
          <small>listagem</small>
        </h1>

		    <div class="pull-right">
	        <a href="painel.php?exe=usuario/create" class="btn btn-success btn-sm" data-toggle="tooltip" title="" data-original-title="Adicionar usuário">
		        <i class="fa fa-plus"></i>
		      </a>
        </div>

	    </div>
	    <!-- /.box-header --> 
      <div class="box-body">
        <table id="data-list" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Status</th>
              <th>Ação</th>
            </tr>
          </thead>
          <tbody>
        </table>
      </div>
    <!-- /.box-body -->
    </div>
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
<?php include("scripts.php") ?>  