<?php if (!defined('SCL_ADMIN_PANEL')) { http_response_code(403); exit; } ?>
<!-- DataTables -->
<link rel="stylesheet" href="../resources/plugins/datatables/dataTables.bootstrap.css">

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
          Páginas
          <small>atualização</small>
        </h1>

        <div class="pull-right">
          <a href="painel.php?exe=paginas/index" class="btn btn-primary btn-sm">
            <i class="fa fa-list"></i>
          </a>
        </div>

      </div>
      <!-- /.box-header -->

      <!-- form start -->
      <form role="form" id="paginasForm" name="paginasForm">

        <div class="nav-tabs-custom" style="margin:10px;">

          <ul class="nav nav-tabs">
            <li><a href='#ouvidoria_' data-toggle='tab'>Ouvidoria</a></li>
            <li><a href='#trabalhe-conosco' data-toggle='tab'>Trabalhe Conosco</a></li>
            <li><a href='#doacoes_' data-toggle='tab'>Doações</a></li>
            <li><a href='#pesquisa_atendimento_' data-toggle='tab'>Pesquisa de Atendimento</a></li>
            <li><a href='#localizacao_' data-toggle='tab'>Localização</a></li>
          </ul>

          <div class="tab-content col-md-12">

            <!--
            <div class="tab-pane" id="fale-conosco">

              <div class='col-md-3'>
                <div class="form-group col-md-9">
                  <label for="img">Imagem em destaque</label>
                  <img class='fale-conosco-image previewimg' style="max-width: 100%">
                  <br><br>         
                  <input type="file" name="fale_conosco_image" onchange="PreviewImg(this)">
                  <p class="help-block">Escolha a imagem .</p>
                </div>
              </div>

              <div class='col-md-9'>

                <div class="form-group col-md-6">
                  <label>Título</label>
                    <textarea id="fale_conosco" name="fale_conosco" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>

                <div class="form-group col-md-6">
                  <label>Sub título</label>
                  <textarea id="fale_conosco_sub_titulo" name="fale_conosco_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>

                <div class="form-group col-md-12">
                  <label>Descrição</label>
                  <textarea id="fale_conosco_descricao" name="fale_conosco_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                </div>

                <div class="form-group col-md-12">
                  <label>SEO <p>(max 155 caracteres)</p> </label>
                  <textarea id="fale_conosco_seo" name="fale_conosco_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>
              </div>

              <div class='col-md-12'><hr></div>
              <div class='col-md-12'>

                <table id="lista-contatos" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>E-mail</th>
                      <th>Data</th>
                      <th>
                        <a href="relatorio-contatos.php" class="btn btn-success btn-sm" target="_blank" data-toggle="tooltip" title="Gerar relatório de contatos">
                          <i class="fa fa-file-excel-o"></i>
                        </a>
                      </th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>

            </div>
            -->

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

                <table id="lista-ouvidoria" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>E-mail</th>
                      <th>Data</th>
                      <th>
                        <a href="relatorio-ouvidoria.php" class="btn btn-success btn-sm" target="_blank" data-toggle="tooltip" title="Gerar relatório de ouvidoria">
                          <i class="fa fa-file-excel-o"></i>
                        </a>
                      </th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
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

                <table id="lista-trabalhe_conosco" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>E-mail</th>
                      <th>Curriculum</th>
                      <th>Data</th>
                      <th>
                        <a href="relatorio-trabalhe_conosco.php" class="btn btn-success btn-sm" target="_blank" data-toggle="tooltip" title="Gerar relatório de trabalhe conosco">
                          <i class="fa fa-file-excel-o"></i>
                        </a>
                      </th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
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

                <table id="lista-doacoes" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>E-mail</th>
                      <th>Cidade</th>
                      <th>Data</th>
                      <th>
                        <a href="relatorio-doacoes.php" class="btn btn-success btn-sm" target="_blank" data-toggle="tooltip" title="Gerar relatório de doações">
                          <i class="fa fa-file-excel-o"></i>
                        </a>
                      </th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
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
                  <a href="relatorio-pesquisa_atendimento.php" class="btn btn-success btn-sm pull-right" target="_blank" data-toggle="tooltip" title="Gerar relatório de pesquisa de atendimento">
                    <i class="fa fa-file-excel-o"></i>
                  </a>
                </div>

                <div class="form-group col-md-12">
                  <label>SEO <p>(max 155 caracteres)</p> </label>
                  <textarea id="pesquisa_atendimento_seo" name="pesquisa_atendimento_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                </div>

              </div>

              <!-- 

              <div class='col-md-12'><hr></div>

              <div class='col-md-12'>

                <table id="lista-pesquisa_atendimento" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>E-mail</th>
                      <th>Data</th>
                      <th>
                        <a href="relatorio-pesquisa_atendimento.php" class="btn btn-success btn-sm" target="_blank" data-toggle="tooltip" title="Gerar relatório de pesquisa de atendimento">
                          <i class="fa fa-file-excel-o"></i>
                        </a>
                      </th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
              -->

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