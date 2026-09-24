<?php if (!defined('SCL_ADMIN_PANEL')) { http_response_code(403); exit; } ?>
<!-- DataTables -->


<?php

$login = new Login(1);
if (!$login->CheckLogin() || (isset($access) && $access['info'] != 1)):
  header('Location: painel.php');
die;
endif;

?>
<div id="mensagem_evento"></div>
<div id="modo">update</div>
<?php if($_SESSION['UsuarioLogin']['nivel'] == 1){ ?><div id="id_user" style="display: none;"><?php echo $_SESSION['UsuarioLogin']['id_usuario']; ?></div><?php } ?>
<div class="row">
  <!-- left column -->
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        
        <h1 class="pagina-titulo">
          Atendimento e localização
            <small>Textos, imagens e informações publicadas no site</small>
        </h1>

        <div class="pull-right">
          <a href="painel.php" class="btn btn-primary btn-sm">
            <i class="fa fa-list"></i>
          </a>
        </div>

      </div>
      <!-- /.box-header -->

      <!-- form start -->
      <form role="form" id="paginasForm" name="paginasForm">

        <div class="nav-tabs-custom" style="margin:10px;">

          <ul class="nav nav-tabs">
            <li><a href='#ouvidoria_' data-bs-toggle='tab'>Ouvidoria</a></li>
            <li><a href='#trabalhe-conosco' data-bs-toggle='tab'>Trabalhe Conosco</a></li>
            <li><a href='#doacoes_' data-bs-toggle='tab'>Doações</a></li>
            <li><a href='#pesquisa_atendimento_' data-bs-toggle='tab'>Pesquisa de Atendimento</a></li>
            <li><a href='#localizacao_' data-bs-toggle='tab'>Localização</a></li>
          </ul>

          <div class="tab-content col-md-12">

            

            <div class="tab-pane" id="ouvidoria_">

              <div class='col-md-3'>
                <div class="form-group col-md-12">
                  <label for="img">Imagem em destaque</label>
                  <img class='ouvidoria-image previewimg' style="max-width: 100%;">
                  <br><br>         
                  <input type="file" name="ouvidoria_image" onchange="PreviewImg(this)">
                  <p class="help-block">Escolha a imagem .</p>
                </div>
              </div>

              <div class='col-md-9'>

                <div class="form-group col-md-6">
                  <label>Título</label>
                    <textarea id="ouvidoria" name="ouvidoria" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>

                 <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="ouvidoria_sub_titulo" name="ouvidoria_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                <div class="form-group col-md-12">
                  <label>Descrição</label>
                  <textarea id="ouvidoria_descricao" name="ouvidoria_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                </div>

                <div class="form-group col-md-12">
                  <label>SEO <p>(max 155 caracteres)</p> </label>
                  <textarea id="ouvidoria_seo" name="ouvidoria_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>
              </div>

              <div class='col-md-12'><hr></div>
              <div class='col-md-12'>

                <a class="btn btn-default mb-3" href="painel.php?exe=atendimento&amp;canal=ouvidoria">Consultar recebimentos e relatórios →</a>
              </div>

            </div>

            <div class="tab-pane" id="trabalhe-conosco">

              <div class='col-md-3'>
                <div class="form-group col-md-12">
                  <label for="img">Imagem em destaque</label>
                  <img class='trabalhe-conosco-image previewimg' style="max-width: 100%;">
                  <br><br>         
                  <input type="file" name="trabalhe_conosco_image" onchange="PreviewImg(this)">
                  <p class="help-block">Escolha a imagem .</p>
                </div>
              </div>

              <div class='col-md-9'>

                <div class="form-group col-md-6">
                  <label>Título</label>
                    <textarea id="trabalhe_conosco" name="trabalhe_conosco" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>

                 <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="trabalhe_conosco_sub_titulo" name="trabalhe_conosco_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                <div class="form-group col-md-12">
                  <label>Descrição</label>
                  <textarea id="trabalhe_conosco_descricao" name="trabalhe_conosco_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                </div>

                <div class="form-group col-md-12">
                  <label>SEO <p>(max 155 caracteres)</p> </label>
                  <textarea id="trabalhe_conosco_seo" name="trabalhe_conosco_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>
              </div>

              <div class='col-md-12'><hr></div>
              <div class='col-md-12'>

                <a class="btn btn-default mb-3" href="painel.php?exe=atendimento&amp;canal=curriculos">Consultar recebimentos e relatórios →</a>
              </div>

            </div>

            <div class="tab-pane" id="doacoes_">

              <div class='col-md-3'>
                <div class="form-group col-md-12">
                  <label for="img">Imagem em destaque</label>
                  <img class='doacoes-image previewimg' style="max-width: 100%;">
                  <br><br>         
                  <input type="file" name="doacoes_image" onchange="PreviewImg(this)">
                  <p class="help-block">Escolha a imagem .</p>
                </div>
              </div>

              <div class='col-md-9'>

                <div class="form-group col-md-6">
                  <label>Título</label>
                    <textarea id="doacoes" name="doacoes" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>

                 <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="doacoes_sub_titulo" name="doacoes_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                <div class="form-group col-md-12">
                  <label>Descrição</label>
                  <textarea id="doacoes_descricao" name="doacoes_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                </div>

                <div class="form-group col-md-12">
                  <label>SEO <p>(max 155 caracteres)</p> </label>
                  <textarea id="doacoes_seo" name="doacoes_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>
              </div>

              <div class='col-md-12'>
                <hr>
              </div>

              <div class='col-md-6'>

                <div class="form-group col-md-12">
                  <label>Bloco 1</label>
                  <textarea name="doacoes-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                </div>

                <div class="form-group col-md-12">
                  <label>Bloco 2</label>
                  <textarea name="doacoes-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                </div>
              </div>

              <div class='col-md-6'>

                <div class="form-group col-md-12">
                  <label>Bloco 3</label>
                  <textarea name="doacoes-texto3" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                </div>
              </div>

              <div class='col-md-12'><hr></div>
              <div class='col-md-12'>

                <a class="btn btn-default mb-3" href="painel.php?exe=atendimento&amp;canal=doacoes">Consultar recebimentos e relatórios →</a>
              </div>

            </div>

            <div class="tab-pane" id="pesquisa_atendimento_">

              <div class='col-md-6'>

                <div class="form-group col-md-12">
                  <label>Título</label>
                  <textarea id="pesquisa_atendimento" name="pesquisa_atendimento" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>

                <div class="form-group col-md-12">
                  <label>Sub título</label>
                  <textarea id="pesquisa_atendimento_sub_titulo" name="pesquisa_atendimento_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>

                <div class="form-group col-md-12">
                  <label>Descrição</label>
                  <textarea id="pesquisa_atendimento_descricao" name="pesquisa_atendimento_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                </div>
              </div>

              <div class='col-md-6'>

                <div class="form-group col-md-12">
                  <a href="relatorio-pesquisa_atendimento.php" class="btn btn-success btn-sm pull-right" target="_blank" data-bs-toggle="tooltip" title="Gerar relatório de pesquisa de atendimento">
                    <i class="fa fa-file-excel-o"></i>
                  </a>
                </div>

                <div class="form-group col-md-12">
                  <label>SEO <p>(max 155 caracteres)</p> </label>
                  <textarea id="pesquisa_atendimento_seo" name="pesquisa_atendimento_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>

              </div>

              

            </div>

            <div class="tab-pane" id="localizacao_">

              <div class="col-md-6">

                <div class="form-group col-md-6">
                  <label>Telefone</label>
                  <input name="localizacao-telefone" class='col-md-12' rows='6' style="width: 100%;" required="">
                </div>

                <div class="form-group col-md-6">
                  <label>E-mail</label>
                  <input name="localizacao-email" class='col-md-12' rows='6' style="width: 100%;" required="">
                </div>

                <div class="form-group col-md-12">
                  <label>Localização</label>
                  <input name="localizacao-localizacao" class='col-md-12' rows='6' style="width: 100%;" required="">
                </div>

              </div>

              <div class='col-md-6'>

                <div class="form-group col-md-6">
                  <label>Título</label>
                    <textarea id="localizacao" name="localizacao" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>

                 <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="localizacao_sub_titulo" name="localizacao_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                <div class="form-group col-md-12">
                  <label>Descrição</label>
                  <textarea id="localizacao_descricao" name="localizacao_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                </div>

                <div class="form-group col-md-12">
                  <label>SEO <p>(max 155 caracteres)</p> </label>
                  <textarea id="localizacao_seo" name="localizacao_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>
              </div>
            </div>

          </div>

          <div class="box-footer">
            <button type="button" class="btn btn-primary col-md-4" id='btnUpdatePagina' onclick="updatePaginas();">Atualizar páginas</button>
          </div>

        </div>

      </form>

      <div class="espere"></div>
    </div>
    <!-- /.box -->
  </div>
</div>

<?php
  $js = 'system/paginas/script.js';
  $js2 = 'system/paginas/fale-conosco.js';
?>