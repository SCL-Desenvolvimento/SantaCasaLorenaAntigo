<?php if (!defined('SCL_ADMIN_PANEL')) { http_response_code(403); exit; } ?>
<?php 
	if(!$login->CheckLogin(3)):
    header('Location: painel.php');
    die;
  endif;
 ?>
<div id="mensagem_evento">
  
</div>   

<div class="row">

  <span id="modo" style="display:none">crear</span>

              
  <!-- left column -->
  <div class="col-md-12">
        
       <div class="box box-primary">
             <div class="box-header">
               <h1 class="pagina-titulo">
                Banner
                <small>Criar</small>
              </h1>
                <div class="pull-right">
                 <a href="painel.php?exe=banner/index" class="btn btn-primary btn-sm">
                  <i class="fa fa-list"></i>
                </a>

            </div>
          </div>
          <!-- /.box-header -->

            <!-- form start -->
            <form id="newbanner" name="newbanner" role="form" enctype="multipart/form-data">

                <div class="box-body">
                  
                <div class="form-group">
                  <label for="exampleInputEmail1">Titulo do banner</label>
                  <input type="text" class="form-control" name = "titulo" placeholder="entre com titulo">
                </div>
                
                <div class="form-group">
                  <label for="exampleInputEmail1">Url</label>
                  <input type="text" class="form-control" name = "link" placeholder="entre com o link">
                </div>

                <div class="form-group">
                  <label for="img">Imagem</label>
                  <input type="file" name="img" id="img">
                  <p class="help-block">Escolha o banner.</p>
                </div>
                
                <div class="checkbox" >
                  <label>
                    <input type="checkbox" name ="status" class="minimal" value ="1"> Habilitar banner
                  </label>
                </div>
                
                <input type="hidden" name="acao" value="NewBanner">
                
                <div class="box-footer">
                <button type="button" class="btn btn-primary col-md-4" onclick="CreateBanner();">Salvar</button>
              </div>

          </div>
          <!-- /.box -->
          
          </form>
          <div class='espere'></div>
  </div>
</div>

<?php include("scripts.php") ?>            