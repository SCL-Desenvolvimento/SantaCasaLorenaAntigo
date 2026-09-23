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

<span id="modo" style="display:none">editar</span>
<div class="row">
  <!-- left column -->
  <div class="col-md-12">
    <div class="box box-primary">
            
          <div class="box-header">
              <h1 class="pagina-titulo">
                Noticia
                <small>atualização</small>
              </h1>
                <div class="pull-right">
                 <a href="painel.php?exe=noticias/index" class="btn btn-primary btn-sm">
                  <i class="fa fa-list"></i>
                </a>

                <a href="painel.php?exe=noticias/create" class="btn btn-success btn-sm">
                  <i class="fa fa-plus"></i>
                </a>

            </div>
          </div>
          <!-- /.box-header -->
<!-- form start -->
            <form id="newnoticia" name="newnoticia" role="form">

                <div class="box-body">

                  <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                      <img id="previewimg" width="300px">
                      <br>
                      <label for="img">Imagem Principal</label>          
                      <input type="hidden" name="img" id="img">
                      <input type="file" name="newimagem" id="newimagem" onchange="PreviewImg(this)">
                      <p class="help-block">Escolha a imagem .</p>
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-12"> 
                    <div class="form-group">
                      <label for="titulo">Título do noticia</label>
                      <input type="text" class="form-control" name = "titulo" id = "titulo" placeholder="entre com título">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Subtítulo</label>
                      <textarea class="form-control" name="subtitulo" placeholder="entre com subtítulo (max 250 caracteres)" maxlength="250"></textarea>
                    </div>
                    
                    <div class="form-group">
                      <label for="link">URL Amigável</label>
                      <input type="text" class="form-control" name = "link" id = "link" placeholder="entre com a url amigavel">
                    </div>

                    <div class="form-group tag" id="tag">
                      <label>Categorias</label>
                      <select id="id_tag" class="tagsSelect form-control" multiple="multiple" data-placeholder="Selecione as categorias" name="id_tag[]" style="width: 100%;">
                      <?php 
                        $readTag = new Read;
                        //$readTag->ExeRead(PREFIX.'tag'); 
                        $readTag->fullRead("SELECT t.* FROM ".PREFIX."tag AS t
                                              INNER JOIN ".PREFIX."tag_noticia AS tp ON (tp.id_tag = t.id_tag) 
                                              WHERE tp.id_noticia = :id_noticia", "id_noticia={$_GET['id_noticia']}"); 
                        
                        if($readTag->getRowCount()): 
                          foreach ($readTag->getResult() as $tag):
                            echo "<option value='{$tag['id_tag']}'>{$tag['nome']}</option>" ;
                          endforeach;
                        endif;
                      ?>  
                      </select>
                    </div>

                    <div class="checkbox" >
                      <label>
                        <input type="checkbox" name ="status" id ="status" class="minimal" value ="1"> Habilitar noticia
                      </label>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                    <label>Descrição</label>
                      <textarea id="descricao" name="descricao" rows="50"></textarea>
                    </div>
                  </div>
                
                <input type="hidden" name="acao" value="UpdateNoticia">
                
                <input type="hidden" name="id_noticia" id="id_noticia" >
                
                <div class="box-footer">
                <button type="button" class="btn btn-primary col-md-4"  onclick="UpdateNoticia();">Salvar</button>
              </div>

          </div>
          <!-- /.box -->
          
          </form>
           <div class="espere"></div>
          </div>
          <!-- /.box -->
  </div>
</div>

<?php include("scripts.php") ?> 