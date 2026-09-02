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

        <!-- Modal - Criar galeria -->
        <div class="modal fade modal-form" id="create-galeria_sobre" tabindex="-1" role="dialog" aria-labelledby="create-galeria_sobre">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-galeria_sobre-Label"> Incluir Convênio </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-galeria_sobre'>

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='galeria_sobre-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="galeria_sobre_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("galeria_sobre", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar galeria -->
        <div class="modal fade modal-form" id="edite-galeria_sobre" tabindex="-1" role="dialog" aria-labelledby="edite-galeria_sobre">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-galeria_sobre-Label"> Editar Convênio </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-galeria_sobre'>

                  <input type='hidden' name="id">

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='galeria_sobre-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="galeria_sobre_image" onchange="PreviewImg(this)">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("galeria_sobre", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar provedor -->
        <div class="modal fade modal-form" id="create-provedor" tabindex="-1" role="dialog" aria-labelledby="create-provedor">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-provedor-Label"> Incluir Convênio </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-provedor'>

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='provedor-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="provedor_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="nome" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'></div>

                  <div class="form-group col-md-2">
                    <label>De</label>
                    <input name="data1" class='col-md-12' maxlength="4" required="">
                  </div>

                  <div class="form-group col-md-2">
                    <label>Até</label>
                    <input name="data2" class='col-md-12' maxlength="4" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("provedor", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar provedor -->
        <div class="modal fade modal-form" id="edite-provedor" tabindex="-1" role="dialog" aria-labelledby="edite-provedor">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-provedor-Label"> Editar Convênio </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-provedor'>

                  <input type='hidden' name="id">

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem em destaque</label>
                    <img class='provedor-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="provedor_image" onchange="PreviewImg(this)">
                  </div>

                  <div class="form-group col-md-6">
                    <label>Nome</label>
                    <input name="nome" class='col-md-12' rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'></div>

                  <div class="form-group col-md-2">
                    <label>De</label>
                    <input name="data1" class='col-md-12' maxlength="4" required="">
                  </div>

                  <div class="form-group col-md-2">
                    <label>Até</label>
                    <input name="data2" class='col-md-12' maxlength="4" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("provedor", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar balanço -->
        <div class="modal fade modal-form" id="create-balanco" tabindex="-1" role="dialog" aria-labelledby="create-balanco">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-balanco-Label"> Incluir balanço </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-balanco'>

                  <div class='form-group col-md-6'>
                    <label for="img">Balanço em PDF</label>
                    <img class='balanco-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="balanco_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-2">
                    <label>Ano</label>
                    <input name="ano" class='col-md-12' maxlength="4" rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("balanco", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar balanço -->
        <div class="modal fade modal-form" id="edite-balanco" tabindex="-1" role="dialog" aria-labelledby="edite-balanco">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-balanco-Label"> Editar balanço </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-balanco'>

                  <input type='hidden' name="id">

                  <div class='form-group col-md-6'>
                    <label for="img">Balanço em PDF</label>
                    <img class='balanco-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="balanco_image" onchange="PreviewImg(this)">
                  </div>

                  <div class="form-group col-md-2">
                    <label>Ano</label>
                    <input name="ano" class='col-md-12' maxlength="4" rows='6' style="width: 100%;" required="">
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("balanco", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        
        <!-- Modal - Criar galeria ações -->
        <div class="modal fade modal-form" id="create-galeria_acao" tabindex="-1" role="dialog" aria-labelledby="create-galeria_acao">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-galeria_acao-Label"> Incluir Foto </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-galeria_acao'>

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem</label>
                    <img class='galeria_acao-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="galeria_acao_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea name="descricao" class='col-md-12'  rows='6' style="width: 100%;" required=""></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("galeria_acao", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar galeria ações -->
        <div class="modal fade modal-form" id="edite-galeria_acao" tabindex="-1" role="dialog" aria-labelledby="edite-galeria_acao">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-galeria_acao-Label"> Editar Foto </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-galeria_acao'>

                  <input type='hidden' name="id">

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem</label>
                    <img class='galeria_acao-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="galeria_acao_image" onchange="PreviewImg(this)">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea name="descricao" class='col-md-12'  rows='6' style="width: 100%;" required=""></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("galeria_acao", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Criar galeria humanização -->
        <div class="modal fade modal-form" id="create-galeria_humanizacao" tabindex="-1" role="dialog" aria-labelledby="create-galeria_humanizacao">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-galeria_humanizacao-Label"> Incluir Foto </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-create-galeria_humanizacao'>

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem</label>
                    <img class='galeria_humanizacao-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="galeria_humanizacao_image" onchange="PreviewImg(this)" required="">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea name="descricao" class='col-md-12'  rows='6' style="width: 100%;" required=""></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='createConteudo("galeria_humanizacao", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal - Editar galeria humanização -->
        <div class="modal fade modal-form" id="edite-galeria_humanizacao" tabindex="-1" role="dialog" aria-labelledby="edite-galeria_humanizacao">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edite-galeria_humanizacao-Label"> Editar Foto </h4>
              </div>

              <div class="modal-body col-md-12">
                <form method="post" id='form-galeria_humanizacao'>

                  <input type='hidden' name="id">

                  <div class='form-group col-md-6'>
                    <label for="img">Imagem</label>
                    <img class='galeria_humanizacao-images previewimg' style="max-width: 100%;">
                    <br><br>         
                    <input type="file" name="galeria_humanizacao_image" onchange="PreviewImg(this)">
                  </div>

                  <div class="form-group col-md-12">
                    <label>Descrição</label>
                    <textarea name="descricao" class='col-md-12'  rows='6' style="width: 100%;" required=""></textarea>
                  </div>

                  <div class='col-md-12'>
                    <label class="msg"></label>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick='updateConteudo("galeria_humanizacao", null)'>Gravar alterações</button>
              </div>

            </div>
          </div>
        </div>

        <!-- form start -->
        <form role="form" id="paginasForm" name="paginasForm">

          <div class="nav-tabs-custom" style="margin:10px;">

            <ul class="nav nav-tabs">
              <li><a href='#sobre-santa-casa-lorena' data-toggle='tab'>Sobre</a></li>
              <li><a href='#humanizacao_' data-toggle='tab'>Humanização</a></li>
              <li><a href='#acoes-sociais-ambientais' data-toggle='tab'>Ações Sociais Ambientais</a></li>
              <li><a href='#programa-nacional-seguranca' data-toggle='tab'>Programa Nacional de Segurança</a></li>
              <li><a href='#portal-transparencia' data-toggle='tab'>Portal Transparência</a></li>
            </ul>

            <div class="tab-content col-md-12">

              <div class="tab-pane" id="sobre-santa-casa-lorena">

                <div class='col-md-12'>

                  <div class='col-md-3'>
                    <div class="form-group col-md-12">
                      <label for="img">Imagem em destaque</label>
                      <img class='sobre-santa-casa-lorena-image previewimg' style="max-width: 100%;">
                      <br><br>         
                      <input type="file" name="sobre_santa_casa_lorena_image" onchange="PreviewImg(this)">
                      <p class="help-block">Escolha a imagem .</p>
                    </div>
                  </div>

                  <div class='col-md-9'>
                    <div class="form-group col-md-6">
                      <label>Título</label>
                      <textarea id="sobre_santa_casa_lorena" name="sobre_santa_casa_lorena" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-6">
                      <label>Sub título</label>
                      <textarea id="sobre_santa_casa_lorena_sub_titulo" name="sobre_santa_casa_lorena_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Descrição</label>
                      <textarea id="sobre_santa_casa_lorena_descricao" name="sobre_santa_casa_lorena_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>SEO <p>(max 155 caracteres)</p> </label>
                      <textarea id="sobre_santa_casa_lorena_seo" name="sobre_santa_casa_lorena_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>
                  </div>

                  <div class='col-md-12'><hr></div>

                  <div class='col-md-6'>

                    <div class="form-group col-md-12">
                      <label>Bloco 1</label>
                      <textarea name="sobre_santa_casa_lorena-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Bloco 2</label>
                      <textarea name="sobre_santa_casa_lorena-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Bloco 3</label>
                      <textarea name="sobre_santa_casa_lorena-texto3" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>

                  </div>

                  <div class='col-md-6'>

                    <div class="form-group col-md-12">
                      <label>Galeria</label>
                      <textarea name="sobre_santa_casa_lorena-texto4" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>
                  </div>

                  <div class='col-md-12'><hr></div>

                  <div class='col-md-6'>

                    <div class='form-group col-md-12'>
                      <label>Galeria</label> 

                      <div class="pull-right">
                        <a class="btn btn-success btn-sm" onClick='createConteudoModal("galeria_sobre")'>
                          <i class="fa fa-plus"></i>
                        </a>
                      </div>
                    </div>

                    <table id="lista-galeria_sobre" class="table table-bordered table-striped">
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

                  <div class='col-md-6'>

                    <div class="form-group col-md-12">
                      <label>Missão</label>
                      <textarea name="sobre_santa_casa_lorena-missao" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Visão</label>
                      <textarea name="sobre_santa_casa_lorena-visao" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>
                  </div>

                  <div class='col-md-6'>

                    <div class="form-group col-md-12">
                      <label>Valor</label>
                      <textarea name="sobre_santa_casa_lorena-valor" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>
                  </div>

                  <div class='col-md-12'><hr></div>

                  <div class='col-md-6'>

                    <div class="form-group col-md-12">
                      <label>Provedores</label>
                      <textarea name="sobre_santa_casa_lorena-provedor" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>
                  </div>

                  <div class='col-md-6'>
                    <div class='form-group col-md-12'>
                      <label>Provedores</label> 

                      <div class="pull-right">
                        <a class="btn btn-success btn-sm" onClick='createConteudoModal("provedor")'>
                          <i class="fa fa-plus"></i>
                        </a>
                      </div>
                    </div>

                    <table id="lista-provedor" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Nome</th>
                          <th>Ação</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

              <div class="tab-pane" id="humanizacao_">

                <div class='col-md-12'>

                  <div class='col-md-3'>
                    <div class="form-group col-md-12">
                      <label for="img">Imagem em destaque</label>
                      <img class='humanizacao-image previewimg' style="max-width: 100%;">
                      <br><br>         
                      <input type="file" name="humanizacao_image" onchange="PreviewImg(this)">
                      <p class="help-block">Escolha a imagem .</p>
                    </div>
                  </div>

                  <div class='col-md-9'>
                    <div class="form-group col-md-6">
                      <label>Título</label>
                      <textarea id="humanizacao" name="humanizacao" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-6">
                      <label>Sub título</label>
                      <textarea id="humanizacao_sub_titulo" name="humanizacao_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Descrição</label>
                      <textarea id="humanizacao_descricao" name="humanizacao_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>SEO <p>(max 155 caracteres)</p> </label>
                      <textarea id="humanizacao_seo" name="humanizacao_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>
                  </div>
                </div>

                <div class="col-md-12"><hr></div>

                <div class="col-md-6">
                  
                  <div class="form-group col-md-12">
                    <label>Bloco 1</label>
                    <textarea name="humanizacao-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Bloco 2</label>
                    <textarea name="humanizacao-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Bloco 3</label>
                    <textarea name="humanizacao-texto3" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Bloco 4</label>
                    <textarea name="humanizacao-texto4" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                  </div>
                </div>

                <div class="col-md-6">

                  <div class='form-group col-md-12'>
                    <label>Galeria Humanização</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("galeria_humanizacao")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-galeria_humanizacao" class="table table-bordered table-striped">
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

              <div class="tab-pane" id="acoes-sociais-ambientais">

                <div class='col-md-12'>

                  <div class='col-md-3'>
                    <div class="form-group col-md-12">
                      <label for="img">Imagem em destaque</label>
                      <img class='acoes-sociais-ambientais-image previewimg'  style="max-width: 100%;">
                      <br><br>         
                      <input type="file" name="acoes_sociais_ambientais_image" onchange="PreviewImg(this)">
                      <p class="help-block">Escolha a imagem .</p>
                    </div>
                  </div>

                  <div class='col-md-9'>
                    <div class="form-group col-md-6">
                      <label>Título</label>
                      <textarea id="acoes_sociais_ambientais" name="acoes_sociais_ambientais" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-6">
                      <label>Sub título</label>
                      <textarea id="acoes_sociais_ambientais_sub_titulo" name="acoes_sociais_ambientais_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Descrição</label>
                      <textarea id="acoes_sociais_ambientais_descricao" name="acoes_sociais_ambientais_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>SEO <p>(max 155 caracteres)</p> </label>
                      <textarea id="acoes_sociais_ambientais_seo" name="acoes_sociais_ambientais_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>
                  </div>

                  <div class='col-md-9'>

                    <div class="form-group col-md-12">
                      <label>Bloco 1</label>
                      <textarea name="acoes_sociais_ambientais-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>
                  </div>

                  <div class='col-md-3'>
                    <div class="form-group col-md-9">
                      <label for="img">Imagem 1(Imagem grande)</label>
                      <img class='acoes_sociais_ambientais-image1 previewimg' style="max-width: 100%">
                      <br><br>         
                      <input type="file" name="acoes_sociais_ambientais_image1" onchange="PreviewImg(this)">
                      <p class="help-block">Escolha a imagem .</p>
                    </div>
                  </div>

                  <div class='col-md-12'></div>

                  <div class='col-md-6'>

                    <div class="form-group col-md-12">
                      <label>Bloco 2</label>
                      <textarea name="acoes_sociais_ambientais-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Bloco 3</label>
                      <textarea name="acoes_sociais_ambientais-texto3" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Bloco 4</label>
                      <textarea name="acoes_sociais_ambientais-texto4" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>
                  </div>

                  <div class='col-md-6'>

                    <div class='form-group col-md-12'>
                      <label>Galeria de Ações sociais</label> 

                      <div class="pull-right">
                        <a class="btn btn-success btn-sm" onClick='createConteudoModal("galeria_acao")'>
                          <i class="fa fa-plus"></i>
                        </a>
                      </div>
                    </div>

                    <table id="lista-galeria_acao" class="table table-bordered table-striped">
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

              <div class="tab-pane" id="programa-nacional-seguranca">

                <div class='col-md-12'>

                  <div class='col-md-3'>
                    <div class="form-group col-md-12">
                      <label for="img">Imagem em destaque</label>
                      <img class='programa-nacional-seguranca-image previewimg' style="max-width: 100%;">
                      <br><br>         
                      <input type="file" name="programa_nacional_seguranca_image" onchange="PreviewImg(this)">
                      <p class="help-block">Escolha a imagem .</p>
                    </div>
                  </div>

                  <div class='col-md-9'>
                    <div class="form-group col-md-6">
                      <label>Título</label>
                      <textarea id="programa_nacional_seguranca" name="programa_nacional_seguranca" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-6">
                      <label>Sub título</label>
                      <textarea id="programa_nacional_seguranca_sub_titulo" name="programa_nacional_seguranca_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Descrição</label>
                      <textarea id="programa_nacional_seguranca_descricao" name="programa_nacional_seguranca_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>SEO <p>(max 155 caracteres)</p> </label>
                      <textarea id="programa_nacional_seguranca_seo" name="programa_nacional_seguranca_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>
                  </div>

                  <div class='col-md-12'><hr></div>

                  <div class='col-md-3'>
                    <div class="form-group col-md-9">
                      <label for="img">Imagem em destaque</label>
                      <img class='programa_nacional_seguranca-image1 previewimg' style="max-width: 100%">
                      <br><br>         
                      <input type="file" name="programa_nacional_seguranca_image1" onchange="PreviewImg(this)">
                      <p class="help-block">Escolha a imagem .</p>
                    </div>
                  </div>

                  <div class='col-md-9'>

                    <div class="form-group col-md-12">
                      <label>Bloco 1</label>
                      <textarea name="programa_nacional_seguranca-texto1" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Bloco 2</label>
                      <textarea name="programa_nacional_seguranca-texto2" class='col-md-12' rows='6' style="width: 100%;"></textarea>
                    </div>
                  </div>

                </div>
              </div>

              <div class="tab-pane" id="portal-transparencia">

                <div class='col-md-12'>

                  <div class='col-md-3'>
                    <div class="form-group col-md-12">
                      <label for="img">Imagem em destaque</label>
                      <img class='portal-transparencia-image previewimg' style="max-width: 100%;">
                      <br><br>         
                      <input type="file" name="portal_transparencia_image" onchange="PreviewImg(this)">
                      <p class="help-block">Escolha a imagem .</p>
                    </div>
                  </div>

                  <div class='col-md-9'>
                    <div class="form-group col-md-6">
                      <label>Título</label>
                      <textarea id="portal_transparencia" name="portal_transparencia" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-6">
                      <label>Sub título</label>
                      <textarea id="portal_transparencia_sub_titulo" name="portal_transparencia_sub_titulo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>Descrição</label>
                      <textarea id="portal_transparencia_descricao" name="portal_transparencia_descricao" class='col-md-12' rows='4' style="width: 100%;"></textarea>
                    </div>

                    <div class="form-group col-md-12">
                      <label>SEO <p>(max 155 caracteres)</p> </label>
                      <textarea id="portal_transparencia_seo" name="portal_transparencia_seo" class='col-md-12' rows='2' style="width: 100%;"></textarea>
                    </div>
                  </div>
                </div>

                <div class='col-md-12'><hr></div>

                <div class='col-md-6'>

                  <div class='form-group col-md-12'>
                    <label>Balanços</label> 

                    <div class="pull-right">
                      <a class="btn btn-success btn-sm" onClick='createConteudoModal("balanco")'>
                        <i class="fa fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  <table id="lista-balanco" class="table table-bordered table-striped">
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
  $js2 = 'system/paginas/institucional.js';
  ?>