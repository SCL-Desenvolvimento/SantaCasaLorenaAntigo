<?php
	$login = new Login(3);
  if(!$login->CheckLogin()):
    header('Location: painel.php');
    die;
  endif;

  $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

  if(!empty($dados['CategoriaCreate'])):
    unset($dados['CategoriaCreate']);
    require('_models/Categoria.class.php');

    $cadastra = new Categoria();
    $cadastra->ExeCreate($dados);
    if(!$cadastra->GetResultado()):
      SystemErro($cadastra->GetErro()[0], $cadastra->GetErro()[1], $cadastra->GetErro()[2]);
    else:
      header("Location: painel.php?exe=categoria/update&sucesso={$cadastra->GetResultado()}");
    endif;
  endif;
?>
<div id="mensagem_evento"></div>

<div class="row">
  <!-- left column -->
  <div class="col-md-12">

       <div class="box box-primary">
             <div class="box-header">
               <h1 class="pagina-titulo">
                Galeria
                <small>cadastro</small>
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
									<input type="text" class="form-control" placeholder="Entre com o nome" name="nomegaleria" id="nomegaleria">
								</div>

							</div>

								<div class="col-md-6">

								 <img id="previewimg" width="300px">
								 <br>
								 <label for="img">Imagem Principal</label>
								 <input type="file" name="anexo" id="newimagem" onchange="PreviewImg(this)">
								 <p class="help-block">Escolha a imagem .</p>

								 <!-- <div id="teste"></div>
								 <div id="teste2"></div> -->

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

								<button type="button" class="btn btn-primary col-md-4" name="CategoriaCreate" onclick="CreateAnexo();">Inserir Foto</button>
                <button type="button" class="btn btn-success col-md-4" name="CategoriaCreate" onclick="CreateGaleria();" style="margin-left:5px;">Salvar</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
  </div>
</div>

<?php include("scripts.php") ?>
