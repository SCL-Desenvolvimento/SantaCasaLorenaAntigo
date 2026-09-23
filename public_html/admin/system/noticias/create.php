<?php if (!defined('SCL_ADMIN_PANEL')) { http_response_code(403); exit; } ?>
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

<div class="row">

  <span id="modo" style="display:none">crear</span>

              
  <!-- left column -->
  <div class="col-md-12">
        
       <div class="box box-primary">
             <div class="box-header">
               <h1 class="pagina-titulo">
                Noticia
                <small>Criar</small>
              </h1>
                <div class="pull-right">
                 <a href="painel.php?exe=noticias/index" class="btn btn-primary btn-sm">
                  <i class="fa fa-list"></i>
                </a>

            </div>
          </div>
          <!-- /.box-header -->

            <!-- form start -->
            <form id="newnoticia" name="newnoticia" role="form" enctype="multipart/form-data">

                <div class="box-body">

                  <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                      <img id="previewimg" width="300px">
                      <br>
                      <label for="img">Imagem Principal</label>          
                      <input type="file" name="img" id="newimagem" onchange="PreviewImg(this)">
                      <p class="help-block">Escolha a imagem .</p>
                    </div>
                  </div>
                
                  <div class="col-md-6 col-sm-12"> 
                    <div class="form-group">
                      <label for="exampleInputEmail1">Título do noticia</label>
                      <input type="text" class="form-control" name = "titulo" placeholder="entre com título">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Subtítulo</label>
                      <textarea class="form-control" name="subtitulo" placeholder="entre com subtítulo (max 250 caracteres)" maxlength="250"></textarea>
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleInputEmail1">URL Amigável</label>
                      <input type="text" class="form-control" name="link" placeholder="entre com a url amigavel">
                    </div>

                    <div class="form-group tag" id="tag">
                      <label>Categorias</label>
                      <select id="id_tag" class="tagsSelect form-control" multiple="multiple" data-placeholder="Selecione as categorias" name="id_tag[]" style="width: 100%;">
                      </select>
                    </div>
                     
                    <div class="checkbox" >
                      <label>
                        <input type="checkbox" name ="status" class="minimal" value ="1"> Habilitar noticia
                      </label>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                    <label>Descrição</label>
                      <textarea id="descricao" name="descricao" rows="50"></textarea>
                    </div>
                  </div>
                
                <input type="hidden" name="acao" value="NewNoticia">
                
                <div class="box-footer">
                <button type="button" class="btn btn-primary col-md-4" onclick="CreateNoticia();">Salvar</button>
              </div>

          </div>
          <!-- /.box -->
          
          </form>
          <div class="espere"></div>
  </div>
</div>

<?php include("scripts.php") ?>            