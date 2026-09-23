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

        <!-- Modal - Criar convenio -->
        <div class="modal fade modal-form" id="create-convenios" tabindex="-1" role="dialog" aria-labelledby="create-convenios">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-convenios-Label"> Incluir Convênio </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-convenios'>

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque (158px/41px)</label>
                    <img class='convenios-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="convenios_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="nome" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea name="descricao" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("convenios", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar convenio -->
        <div class="modal fade modal-form" id="edite-convenios" tabindex="-1" role="dialog" aria-labelledby="edite-convenios">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-convenios-Label"> Editar Convênio </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-convenios'>

                  <input type='hidden' name="id">

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque (158px/41px)</label>
                    <img class='convenios-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="convenios_image" onchange="PreviewImg(this)">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="nome" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea name="descricao" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("convenios", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar especialidades -->
        <div class="modal fade modal-form" id="create-especialidades" tabindex="-1" role="dialog" aria-labelledby="create-especialidades">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-especialidades-Label"> Incluir especialidades </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-especialidades'>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="nome" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea name="descricao" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("especialidades", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar especialidade -->
        <div class="modal fade modal-form" id="edite-especialidades" tabindex="-1" role="dialog" aria-labelledby="edite-especialidades">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-especialidades-Label"> Editar especialidades </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-especialidades'>

                  <input type='hidden' name="id">

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="nome" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea name="descricao" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("especialidades", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar capacidade -->
        <div class="modal fade modal-form" id="create-capacidade" tabindex="-1" role="dialog" aria-labelledby="create-capacidade">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-capacidade-Label"> Inserir capacidade </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-capacidade'>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea name="descricao" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("capacidade", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar capacidade -->
        <div class="modal fade modal-form" id="edite-capacidade" tabindex="-1" role="dialog" aria-labelledby="edite-capacidade">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-capacidade-Label"> Editar capacidade </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-capacidade'>

                  <input type='hidden' name="id">

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class="form-group col-md-6">
                    
                    <div class="overflow-x-scroll">
                      <div class="size">
                        <div class="bxFile fileUpload">
                          <center>
                            <div class="bgFile" id="input">
                              <input type="file" name="arquivo[]" class="arquivo-oferta upload input-image" onChange="makeFileListCapacidade(this)" accept="image/x-png, image/gif, image/jpeg">
                              <label class="msg-error"></label>
                            </div>
                          </center>
                        </div>
                        <div class="clearBoth"></div>
                      </div>
                    </div>

                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea name="descricao" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div id="capacidade-imagens" class="col-md-12"></div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("capacidade", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar manual -->
        <div class="modal fade modal-form" id="create-manual_paciente" role="dialog" aria-labelledby="create-manual_paciente">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-manual_paciente-Label"> Incluir instrução </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-manual_paciente'>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id='ck-manual_paciente' name="descricao" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("manual_paciente", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar manual -->
        <div class="modal fade modal-form" id="edite-manual_paciente" role="dialog" aria-labelledby="edite-manual_paciente">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-manual_paciente-Label"> Editar instrução </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-manual_paciente'>

                  <input type='hidden' name="id">

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id='update-manual_paciente' name="descricao" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("manual_paciente", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar download_manual_paciente -->
        <div class="modal fade modal-form" id="create-download_manual_paciente" tabindex="-1" role="dialog" aria-labelledby="create-download_manual_paciente">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-download_manual_paciente-Label"> Incluir arquivo </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-download_manual_paciente'>

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <input name="titulo" class='col-md-12' style="width: 100%;" required="">
                  </div>

                  <div class='form-group col-md-12'>
                    <label for="img">Arquivo</label>
                    <img class='download_manual_paciente-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="download_manual_paciente_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("download_manual_paciente", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar download_manual_paciente -->
        <div class="modal fade modal-form" id="edite-download_manual_paciente" tabindex="-1" role="dialog" aria-labelledby="edite-download_manual_paciente">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-download_manual_paciente-Label"> Editar arquivo </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-download_manual_paciente'>

                  <input type='hidden' name="id">

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <input name="titulo" class='col-md-12' style="width: 100%;" required="">
                  </div>

                  <div class='form-group col-md-12'>
                    <label for="img">Arquivo</label>
                    <img class='download_manual_paciente-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="download_manual_paciente_image" onchange="PreviewImg(this)">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("download_manual_paciente", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- form start -->
        <form role="form" id="paginasForm" name="paginasForm">

          <div class="nav-tabs-custom" style="margin:10px;">

            <ul class="nav nav-tabs">
              <li><a href='#convenios_' data-toggle='tab'>Convenios</a></li>
              <li><a href='#especialidades_' data-toggle='tab'>Especialidades</a></li>
              <li><a href='#capacidade-instalacao-producao' data-toggle='tab'>Capacidade de instalação e produção</a></li>
              <li><a href='#manual-paciente-visitante' data-toggle='tab'>Manual do paciente e visitante</a></li>
            </ul>

            <div class="tab-content col-md-12">

              <div class="tab-pane" id="convenios_">

                <div class='col-md-3'>
                  <div class="form-group col-md-12">
                    <label for="img">Imagem em destaque</label>
                    <img class='convenios-image previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="convenios_image" onchange="PreviewImg(this)">
                    <p class="help-block">Escolha a imagem .</p>
                  </div>
                </div>

                <div class='col-md-9'>

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <textarea id="convenios" name="convenios" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="convenios_sub_titulo" name="convenios_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id="convenios_descricao" name="convenios_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>SEO <p>(max 155 caracteres)</p> </label>
                    <textarea id="convenios_seo" name="convenios_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class='form-group col-md-12'>
                    <label>Convênios</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("convenios")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-convenios" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th colspan="2"></th>
                        <th>Ação</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="tab-pane" id="especialidades_">

                <div class='col-md-3'>
                  <div class="form-group col-md-12">
                    <label for="img">Imagem em destaque</label>
                    <img class='especialidades-image previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="especialidades_image" onchange="PreviewImg(this)">
                    <p class="help-block">Escolha a imagem .</p>
                  </div>
                </div>

                <div class='col-md-9'>

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <textarea id="especialidades" name="especialidades" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                   <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="especialidades_sub_titulo" name="especialidades_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id="especialidades_descricao" name="especialidades_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>SEO <p>(max 155 caracteres)</p> </label>
                    <textarea id="especialidades_seo" name="especialidades_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class="form-group col-md-6">
                    <label>Bloco 1</label>
                    <textarea name="especialidades-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Bloco 2</label>
                    <textarea name="especialidades-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class='form-group col-md-12'>
                    <label>Especialidades</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("especialidades")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-especialidades" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th colspan="2"></th>
                        <th>Ação</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="tab-pane" id="capacidade-instalacao-producao">

                <div class='col-md-3'>
                  <div class="form-group col-md-12">
                    <label for="img">Imagem em destaque</label>
                    <img class='capacidade-instalacao-producao-image previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="capacidade_instalacao_producao_image" onchange="PreviewImg(this)">
                    <p class="help-block">Escolha a imagem .</p>
                  </div>
                </div>

                <div class='col-md-9'>

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <textarea id="capacidade_instalacao_producao" name="capacidade_instalacao_producao" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                   <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="capacidade_instalacao_producao_sub_titulo" name="capacidade_instalacao_producao_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id="capacidade_instalacao_producao_descricao" name="capacidade_instalacao_producao_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>SEO <p>(max 155 caracteres)</p> </label>
                    <textarea id="capacidade_instalacao_producao_seo" name="capacidade_instalacao_producao_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class="form-group col-md-6">
                    <label>Bloco 1</label>
                    <textarea name="capacidade_instalacao_producao-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Bloco 2</label>
                    <textarea name="capacidade_instalacao_producao-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class='form-group col-md-12'>
                    <label>Capacidades</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("capacidade")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-capacidade" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th colspan="2"></th>
                        <th>Ação</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>

              </div>

              <div class="tab-pane" id="manual-paciente-visitante">

                <div class='col-md-3'>
                  <div class="form-group col-md-12">
                    <label for="img">Imagem em destaque</label>
                    <img class='manual-paciente-visitante-image previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="manual_paciente_visitante_image" onchange="PreviewImg(this)">
                    <p class="help-block">Escolha a imagem .</p>
                  </div>
                </div>

                <div class='col-md-9'>

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <textarea id="manual_paciente_visitante" name="manual_paciente_visitante" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                   <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="manual_paciente_visitante_sub_titulo" name="manual_paciente_visitante_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id="manual_paciente_visitante_descricao" name="manual_paciente_visitante_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>SEO <p>(max 155 caracteres)</p> </label>
                    <textarea id="manual_paciente_visitante_seo" name="manual_paciente_visitante_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class="form-group col-md-6">
                    <label>Bloco 1</label>
                    <textarea name="manual_paciente_visitante-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Bloco 2</label>
                    <textarea name="manual_paciente_visitante-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-6'>

                  <div class='form-group col-md-12'>
                    <label>Instruções do manual</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("manual_paciente")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-manual_paciente" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Ação</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>

                <div class='col-md-6'>

                  <div class='form-group col-md-12'>
                    <label>Arquivos para download</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("download_manual_paciente")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-download_manual_paciente" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th colspan="2"></th>
                        <th>Ação</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
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
  //$js = 'system/paginas/institucional.js';
  $js = 'system/paginas/script.js';
  $js2 = 'system/paginas/servicos.js';
  ?>