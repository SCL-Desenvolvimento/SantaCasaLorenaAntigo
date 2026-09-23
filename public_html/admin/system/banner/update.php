<?php if (!defined('SCL_ADMIN_PANEL')) { http_response_code(403); exit; } ?>
<?php 
	if(!$login->CheckLogin(3)):
    header('Location: painel.php');
    die;
  endif;
?>
<div id="mensagem_evento">

</div>

<span id="modo" style="display:none">editar</span>
<div class="row">
  <!-- left column -->
  <div class="col-md-12">
    <div class="box box-primary">
            
          <div class="box-header">
              <h1 class="pagina-titulo">
                Banner
                <small>atualização</small>
              </h1>
                <div class="pull-right">
                 <a href="painel.php?exe=banner/index" class="btn btn-primary btn-sm">
                  <i class="fa fa-list"></i>
                </a>

                <a href="painel.php?exe=banner/create" class="btn btn-success btn-sm">
                  <i class="fa fa-plus"></i>
                </a>

            </div>
          </div>
          <!-- /.box-header -->
<!-- form start -->
            <form id="newbanner" name="newbanner" role="form">

                <div class="box-body">
                  
                <div class="form-group">
                  <label for="titulo">Titulo</label>
                  <input type="text" class="form-control" name = "titulo" id = "titulo" placeholder="entre com titulo">
                </div>
                
                <div class="form-group">
                  <label for="link">Url</label>
                  <input type="text" class="form-control" name = "link" id = "link" placeholder="entre com o link">
                </div>
                
                
                <div class="form-group">
						<img id="previewimg" width="300px"><br>
					  <label for="img">Imagem</label>					 
					  <input type="hidden" name="img" id="img">
					  <input type="file" name="newimagem" id="newimagem" onchange="PreviewImg(this)">
					  <p class="help-block">Escolha a imagem .</p>
                </div>
                
                <div class="checkbox" >
                  <label>
                    <input type="checkbox" name ="status" id ="status" class="minimal" value ="1"> Habilitar banner
                  </label>
                </div>
                
                <input type="hidden" name="acao" value="UpdateBanner">
                
                <input type="hidden" name="id_banner" id="id_banner" >
                
                <div class="box-footer">
                <button type="button" class="btn btn-primary col-md-4"  onclick="UpdateBanner();">Salvar</button>
              </div>

          </div>
          <!-- /.box -->
          
          </form>
        <div class='espere'></div>
      </div>
      <!-- /.box -->
  </div>
</div>

<?php include("scripts.php") ?> 