<?php if (!defined('SCL_ADMIN_PANEL')) { http_response_code(403); exit; } ?>
<?php
	$login = new Login(3);
  if(!$login->CheckLogin()):
    header('Location: painel.php');
    die;
  endif;

	$idGaleria = $_GET['id_galeria'];

	$readGaleria = new Read;
	$readGaleria->fullRead("SELECT G.id_galeria, G.nome, AN.url
								FROM ".PREFIX."galeria AS G
								LEFT JOIN ".PREFIX."galeria_anexo AS GAN ON (GAN.id_galeria = G.id_galeria)
								LEFT JOIN ".PREFIX."anexo AS AN ON (AN.id_anexo = GAN.id_anexo)
								WHERE G.id_galeria='$idGaleria' GROUP BY G.nome ORDER BY G.id_galeria DESC");

	$INFOGALERIA = $readGaleria->getResult();

	foreach($INFOGALERIA as $galeria){
		//var_dump($INFOCONTEUDO);exit;
	}

	$readIMGS = new Read;
	$readIMGS->fullRead("SELECT AN.url, AN.id_anexo
							FROM ".PREFIX."galeria AS G
							LEFT JOIN ".PREFIX."galeria_anexo AS GAN ON (GAN.id_galeria = G.id_galeria)
							LEFT JOIN ".PREFIX."anexo AS AN ON (AN.id_anexo = GAN.id_anexo)
							WHERE G.id_galeria='$idGaleria' GROUP BY AN.url");

	$INFOIMGS = $readIMGS->getResult()

?>
<div id="mensagem_evento"></div>

<div class="row">
  <!-- left column -->
  <div class="col-md-12">
    <div class="box box-primary">

          <div class="box-header">
              <h1 class="pagina-titulo">
                Galeria
                <small>atualização</small>
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

            <!-- form start -->
            <form id="newGaleria" name="newGaleria" role="form" enctype="multipart/form-data">
							<div class="box-body">

								<div class="col-md-12">

								<div class="form-group">
									<label for="">Nome da Galeria</label>
									<input type="text" class="form-control" placeholder="Entre com o nome" name="nomegaleria" id="nomegaleria" value='<?php echo $galeria['nome']; ?>'>
								</div>

							<?php foreach ($INFOIMGS as $imgs) { if(empty($imgs['url'])){}else{ ?>

							<div class='col-sm-6 seguraimg' style='display:table;'>
							<div class='col-sm-4' style='display:table;'>
							 <img style='background: url(<?php echo HOME."".$imgs['url']; ?>) no-repeat; background-size: cover; background-position: center; width: 300px; height: 100px; min-width: 100%;margin-bottom:10px'/>
							</div>
							<div class='col-sm-1' id='segurabotao'>
							<input class="btn btn-danger btn-sm" onclick="deleteAnexoUpdate(this, <?php echo $imgs['id_anexo'];?>)" type="button" value="Excluir">
							</div>
							<input name='id_anexo[]2' type='hidden' value='<?php echo $imgs['id_anexo'];?>'/>
							</div>

							<?php	}} ?>

							</div>

							<div class="col-md-12">
								<hr>
							</div>

								<div class="col-md-6">

									<div class="form-group">
										 <img id="previewimg" width="300px">
										 <br>
										 <label for="img">Imagem Principal</label>
										 <input type="file" name="anexo" id="newimagem" onchange="PreviewImg(this)">
										 <p class="help-block">Escolha a imagem .</p>
							 		</div>

							  </div>

		            <div class="col-md-6">

		                <div class="form-group">
		                  <label for="">Titulo</label>
		                  <input type="text" class="form-control" placeholder="Entre com o nome" name="nome" id="nome">
		                </div>

		            </div>

							</div>
							<div class="box-footer">

								<div id="thumbAnexo" style="display:table"></div>

								<div id="hidden"></div>

								<input name='idGaleria' type='hidden' value='<?php echo $galeria['id_galeria'];?>'/>

								<button type="button" class="btn btn-primary col-md-4" name="CategoriaCreate" onclick="CreateAnexo();">Inserir Foto</button>
                <button type="button" class="btn btn-success col-md-4" name="CategoriaCreate" onclick="UpdateGaleria();" style="margin-left:5px;">Salvar</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
  </div>
</div>

<?php include("scripts.php") ?>
