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

        <!-- Modal - Criar pronto atendimento -->
        <div class="modal fade modal-form" id="create-pronto_atendimento" tabindex="-1" role="dialog" aria-labelledby="create-pronto_atendimento">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-pronto_atendimento-Label"> Inserir galeria pronto atendimento </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-pronto_atendimento'>

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='pronto_atendimento-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="pronto_atendimento_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("pronto_atendimento", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar pronto atendimento -->
        <div class="modal fade modal-form" id="edite-pronto_atendimento" tabindex="-1" role="dialog" aria-labelledby="edite-pronto_atendimento">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-pronto_atendimento-Label"> Editar galeria pronto atendimento </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-pronto_atendimento'>

                  <input type='hidden' name="id">

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='pronto_atendimento-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="pronto_atendimento_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("pronto_atendimento", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar hotelaria -->
        <div class="modal fade modal-form" id="create-hotelaria" tabindex="-1" role="dialog" aria-labelledby="create-hotelaria">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-hotelaria-Label"> Inserir galeria hotelaria </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-hotelaria'>

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='hotelaria-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="hotelaria_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("hotelaria", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar hotelaria -->
        <div class="modal fade modal-form" id="edite-hotelaria" tabindex="-1" role="dialog" aria-labelledby="edite-hotelaria">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-hotelaria-Label"> Editar galeria hotelaria </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-hotelaria'>

                  <input type='hidden' name="id">

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='hotelaria-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="hotelaria_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("hotelaria", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar clinica emilia -->
        <div class="modal fade modal-form" id="create-clinica_emilia" tabindex="-1" role="dialog" aria-labelledby="create-clinica_emilia">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-clinica_emilia-Label"> Inserir galeria pronto atendimento </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-clinica_emilia'>

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='clinica_emilia-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="clinica_emilia_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("clinica_emilia", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar clinica emilia -->
        <div class="modal fade modal-form" id="edite-clinica_emilia" tabindex="-1" role="dialog" aria-labelledby="edite-clinica_emilia">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-clinica_emilia-Label"> Editar galeria pronto atendimento </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-clinica_emilia'>

                  <input type='hidden' name="id">

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='clinica_emilia-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="clinica_emilia_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("clinica_emilia", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar centro de diagnostico por imagem -->
        <div class="modal fade modal-form" id="create-centro_diagnostico_por_imagem" tabindex="-1" role="dialog" aria-labelledby="create-centro_diagnostico_por_imagem">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-centro_diagnostico_por_imagem-Label"> Inserir imagem na galeria </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-centro_diagnostico_por_imagem'>

                   <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='centro_diagnostico_por_imagem-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="centro_diagnostico_por_imagem_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("centro_diagnostico_por_imagem", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar centro de diagnostico por imagem -->
        <div class="modal fade modal-form" id="edite-centro_diagnostico_por_imagem" tabindex="-1" role="dialog" aria-labelledby="edite-centro_diagnostico_por_imagem">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-centro_diagnostico_por_imagem-Label"> Editar imagem da galeria </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-centro_diagnostico_por_imagem'>

                  <input type='hidden' name="id">

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='centro_diagnostico_por_imagem-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="centro_diagnostico_por_imagem_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="titulo" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("centro_diagnostico_por_imagem", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar unidade internação -->
        <div class="modal fade modal-form" id="create-unidade_internacao" tabindex="-1" role="dialog" aria-labelledby="create-unidade_internacao">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-unidade_internacao-Label"> Inserir unidade internação </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-unidade_internacao'>

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
                <button type="button" class="btn btn-primary" onClick='createConteudo("unidade_internacao", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar unidade internação -->
        <div class="modal fade modal-form" id="edite-unidade_internacao" tabindex="-1" role="dialog" aria-labelledby="edite-unidade_internacao">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-unidade_internacao-Label"> Editar unidade internação </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-unidade_internacao'>

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
                              <input type="file" name="arquivo[]" class="arquivo-oferta upload input-image" onChange="makeFileListUnidadeInternacao(this)" accept="image/x-png, image/gif, image/jpeg">
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

                  <div id="unidade_internacao-imagens" class="col-md-12"></div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("unidade_internacao", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- form start -->
        <form role="form" id="paginasForm" name="paginasForm">

          <div class="nav-tabs-custom" style="margin:10px;">

            <ul class="nav nav-tabs">
              <li><a href='#pronto-atendimento' data-toggle='tab'>Pronto Atendimento</a></li>
              <li><a href='#hotelaria_' data-toggle='tab'>Hotelaria</a></li>
              <li><a href='#clinica-emilia' data-toggle='tab'>Clínica Emília</a></li>
              <li><a href='#centro-diagnostico-por-imagem' data-toggle='tab'>Centro de Diagnóstico por Imagem</a></li>
              <li><a href='#unidade-internacao' data-toggle='tab'>Unidade de Internação</a></li>
            </ul>

            <div class="tab-content col-md-12">

              <div class="tab-pane" id="pronto-atendimento">

                <div class='col-md-3'>
                  <div class="form-group col-md-12">
                    <label for="img">Imagem em destaque</label>
                    <img class='pronto-atendimento-image previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="pronto_atendimento_image" onchange="PreviewImg(this)" required="">
                  </div>
                </div>

                <div class='col-md-9'>

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <textarea id="pronto_atendimento" name="pronto_atendimento" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="pronto_atendimento_sub_titulo" name="pronto_atendimento_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id="pronto_atendimento_descricao" name="pronto_atendimento_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>SEO <p>(max 155 caracteres)</p> </label>
                    <textarea id="pronto_atendimento_seo" name="pronto_atendimento_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-6'>

                  <div class="form-group col-md-12">
                    <label>Bloco 1</label>
                    <textarea name="pronto_atendimento-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Bloco 2</label>
                    <textarea name="pronto_atendimento-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-6'>
                  <div class="form-group col-md-12">
                    <label>Bloco 3</label>
                    <textarea name="pronto_atendimento-texto3" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Bloco 4</label>
                    <textarea name="pronto_atendimento-texto4" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-6'>

                  <div class="form-group col-md-12">
                    <label>Texto emergência(EM VERMELHO)</label>
                    <textarea name="pronto_atendimento-emergencia" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Texto urgência(EM AMARELO)</label>
                    <textarea name="pronto_atendimento-urgencia" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-6'>

                  <div class="form-group col-md-12">
                    <label>Texto urgência relativa(EM VERDE OU AZUL)</label>
                    <textarea name="pronto_atendimento-urgencia_relativa" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Bloco 5</label>
                    <textarea name="pronto_atendimento-texto5" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class='form-group col-md-12'>
                    <label>Áreas</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("pronto_atendimento")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-pronto_atendimento" class="table table-bordered table-striped">
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

              <div class="tab-pane" id="hotelaria_">

                <div class='col-md-3'>
                  <div class="form-group col-md-12">
                    <label for="img">Imagem em destaque</label>
                    <img class='hotelaria-image previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="hotelaria_image" onchange="PreviewImg(this)" required="">
                  </div>
                </div>

                <div class='col-md-9'>

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <textarea id="hotelaria" name="hotelaria" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="hotelaria_sub_titulo" name="hotelaria_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id="hotelaria_descricao" name="hotelaria_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>SEO <p>(max 155 caracteres)</p> </label>
                    <textarea id="hotelaria_seo" name="hotelaria_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class="form-group col-md-6">
                    <label>Bloco 1</label>
                    <textarea name="hotelaria-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Bloco 2</label>
                    <textarea name="hotelaria-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class='form-group col-md-12'>
                    <label>Áreas</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("hotelaria")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-hotelaria" class="table table-bordered table-striped">
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

              <div class="tab-pane" id="clinica-emilia">

                <div class='col-md-3'>
                  <div class="form-group col-md-12">
                    <label for="img">Imagem em destaque</label>
                    <img class='clinica-emilia-image previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="clinica_emilia_image" onchange="PreviewImg(this)" required="">
                  </div>
                </div>

                <div class='col-md-9'>

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <textarea id="clinica_emilia" name="clinica_emilia" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="clinica_emilia_sub_titulo" name="clinica_emilia_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id="clinica_emilia_descricao" name="clinica_emilia_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>SEO <p>(max 155 caracteres)</p> </label>
                    <textarea id="clinica_emilia_seo" name="clinica_emilia_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class="form-group col-md-6">
                    <label>Bloco 1</label>
                    <textarea name="clinica_emilia-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Bloco 2</label>
                    <textarea name="clinica_emilia-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class='form-group col-md-12'>
                    <label>Áreas</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("clinica_emilia")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-clinica_emilia" class="table table-bordered table-striped">
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

              <div class="tab-pane" id="centro-diagnostico-por-imagem">

                <div class='col-md-3'>
                  <div class="form-group col-md-12">
                    <label for="img">Imagem em destaque</label>
                    <img class='centro-diagnostico-por-imagem-image previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="centro_diagnostico_por_imagem_image" onchange="PreviewImg(this)" required="">
                  </div>
                </div>

                <div class='col-md-9'>

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <textarea id="centro_diagnostico_por_imagem" name="centro_diagnostico_por_imagem" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="centro_diagnostico_por_imagem_sub_titulo" name="centro_diagnostico_por_imagem_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id="centro_diagnostico_por_imagem_descricao" name="centro_diagnostico_por_imagem_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>SEO <p>(max 155 caracteres)</p> </label>
                    <textarea id="centro_diagnostico_por_imagem_seo" name="centro_diagnostico_por_imagem_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class="form-group col-md-6">
                    <label>Bloco 1</label>
                    <textarea name="centro_diagnostico_por_imagem-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Bloco 2</label>
                    <textarea name="centro_diagnostico_por_imagem-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class='form-group col-md-12'>
                    <label>Áreas</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("centro_diagnostico_por_imagem")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-centro_diagnostico_por_imagem" class="table table-bordered table-striped">
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

              <div class="tab-pane" id="unidade-internacao">

                <div class='col-md-3'>
                  <div class="form-group col-md-9">
                    <label for="img">Imagem em destaque</label>
                    <img class='unidade-internacao-image previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="unidade_internacao_image" onchange="PreviewImg(this)" required="">
                  </div>
                </div>

                <div class='col-md-9'>

                  <div class="form-group col-md-6">
                    <label>Título</label>
                    <textarea id="unidade_internacao" name="unidade_internacao" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Sub título</label>
                    <textarea id="unidade_internacao_sub_titulo" name="unidade_internacao_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea id="unidade_internacao_descricao" name="unidade_internacao_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>SEO <p>(max 155 caracteres)</p> </label>
                    <textarea id="unidade_internacao_seo" name="unidade_internacao_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class="form-group col-md-6">
                    <label>Bloco 1</label>
                    <textarea name="unidade_internacao-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Bloco 2</label>
                    <textarea name="unidade_internacao-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                    <label>Bloco 3</label>
                    <textarea name="unidade_internacao-texto3" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class='col-md-12'>
                  <hr>
                </div>

                <div class='col-md-12'>

                  <div class='form-group col-md-12'>
                    <label>Áreas</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("unidade_internacao")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-unidade_internacao" class="table table-bordered table-striped">
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
  $js = 'system/paginas/script.js';
  $js2 = 'system/paginas/instalacoes.js';
  ?>