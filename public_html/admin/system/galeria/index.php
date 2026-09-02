<!-- DataTables -->
<link rel="stylesheet" href="../resources/plugins/datatables/dataTables.bootstrap.css">
<?php

	$login = new Login(3);
    if(!$login->CheckLogin()):
         header('Location: painel.php');
        die;
    endif;

		$readGaleria = new Read;
		$readGaleria->fullRead("SELECT G.id_galeria, G.nome, AN.url
								FROM ".PREFIX."galeria AS G
								LEFT JOIN ".PREFIX."galeria_anexo AS GAN ON (GAN.id_galeria = G.id_galeria)
								LEFT JOIN ".PREFIX."anexo AS AN ON (AN.id_anexo = GAN.id_anexo)
								GROUP BY G.nome ORDER BY G.id_galeria DESC
		");
		
		$INFOGALERIA = $readGaleria->getResult();

?>
<div id="mensagem_evento">

</div>

<div class="row">
    <div class="col-xs-12">
       	<div class="box box-primary">

          <div class="box-header">
               <h1 class="pagina-titulo">
                Galeria
                <small>listagem</small>
              	</h1>
              	<div class="pull-right">
	               <a href="painel.php?exe=galeria/index" class="btn btn-primary btn-sm">
	                <i class="fa fa-list"></i>
	              </a>

	              <a href="painel.php?exe=galeria/create" class="btn btn-success btn-sm">
	                <i class="fa fa-plus"></i>
	              </a>

	        	</div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
							<table id="data-list" class="table table-bordered table-striped">
							<thead>
							<tr>
								<th>ID</th>
								<th>Galeria</th>
								<th>Foto</th>
							</tr>
							</thead>
							<tbody>
								<?php
									foreach($INFOGALERIA as $galeria) {
										$idGaleria = $galeria['id_galeria'];
										echo "<tr class='baixotitle'>";
											echo "<td>".$galeria['id_galeria']."</td>";
											echo "<td>".$galeria['nome'].
											"<div class='baixotitle'>
											<p class='editarpost'>
											<a href='painel.php?exe=galeria/update&id_galeria=".$idGaleria."'>Editar</a></p>
											<p class='editarpost'></p>
											</div></td>";
											echo "<td><img src='".HOME."".$galeria['url']."' width='150' height='100'></td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
            </div>
            <!-- /.box-body -->
      	</div>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<?php include("scripts.php") ?>
