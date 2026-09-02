<script>

function get_unidade_internacao(unidade_internacao){

  //console.log(especialidades);
  $("#lista-unidade_internacao").html("<thead><tr><th></th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(unidade_internacao, function( i, c ) {
    $("#lista-unidade_internacao tbody").append(`<tr>
      <td>${c['titulo']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"unidade_internacao\", ${c['id_unidade_internacao']}, editeUnidadeInternacao)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${c['id_unidade_internacao']}, \"unidade_internacao\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

function editeUnidadeInternacao(unidade_internacao){

  //console.log(convenio);

  $("#edite-unidade_internacao").modal("show");
  $("#edite-unidade_internacao").find(".modal-body");

  $('#edite-unidade_internacao input[name="id"]').val(unidade_internacao['id_unidade_internacao']);
  $('#edite-unidade_internacao input[name="titulo"]').val(unidade_internacao['titulo']);
  $('#edite-unidade_internacao textarea[name="descricao"]').text(unidade_internacao['descricao']);
  $('#edite-unidade_internacao').find(".msg").html("");

  getImagesUnidadeInternacao(unidade_internacao['id_unidade_internacao']);
}

function get_pronto_atendimento(pronto_atendimento){

  //console.log(convenios);
  $("#lista-pronto_atendimento").html("<thead><tr><th></th><th></th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(pronto_atendimento, function( i, c ) {
    $("#lista-pronto_atendimento tbody").append(`<tr>
      <td><img src="../${c['img']}" style='max-width:200px;'></td>
      <td>${c['titulo']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"pronto_atendimento\", ${c['id_pronto_atendimento']}, editeProntoAtendimento)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${c['id_pronto_atendimento']}, \"pronto_atendimento\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

function editeProntoAtendimento(pronto_atendimento){

  //console.log(convenio);

  $("#edite-pronto_atendimento").modal("show");
  $("#edite-pronto_atendimento").find(".modal-body");

  $('#edite-pronto_atendimento input[name="id"]').val(pronto_atendimento['id_pronto_atendimento']);
  $('#edite-pronto_atendimento input[name="titulo"]').val(pronto_atendimento['titulo']);
  $('#edite-pronto_atendimento img').attr("src", "../"+pronto_atendimento['img']);
  $('#edite-pronto_atendimento').find(".msg").html("");
}

function get_hotelaria(hotelaria){

  //console.log(convenios);
  $("#lista-hotelaria").html("<thead><tr><th></th><th></th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(hotelaria, function( i, c ) {
    $("#lista-hotelaria tbody").append(`<tr>
      <td><img src="../${c['img']}" style='max-width:200px;'></td>
      <td>${c['titulo']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"hotelaria\", ${c['id_hotelaria']}, editeHotelaria)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${c['id_hotelaria']}, \"hotelaria\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

function editeHotelaria(hotelaria){

  //console.log(convenio);
  $("#edite-hotelaria").modal("show");
  $("#edite-hotelaria").find(".modal-body");

  $('#edite-hotelaria input[name="id"]').val(hotelaria['id_hotelaria']);
  $('#edite-hotelaria input[name="titulo"]').val(hotelaria['titulo']);
  $('#edite-hotelaria img').attr("src", "../"+hotelaria['img']);
  $('#edite-hotelaria').find(".msg").html("");
}

function get_clinica_emilia(clinica_emilia){

  //console.log(clinica_emilia);
  $("#lista-clinica_emilia").html("<thead><tr><th></th><th></th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(clinica_emilia, function( i, c ) {
    $("#lista-clinica_emilia tbody").append(`<tr>
      <td><img src="../${c['img']}" style='max-width:200px;'></td>
      <td>${c['titulo']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"clinica_emilia\", ${c['id_clinica_emilia']}, editeClinicaEmilia)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${c['id_clinica_emilia']}, \"clinica_emilia\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

function editeClinicaEmilia(clinica_emilia){

  //console.log(convenio);
  $("#edite-clinica_emilia").modal("show");
  $("#edite-clinica_emilia").find(".modal-body");

  $('#edite-clinica_emilia input[name="id"]').val(clinica_emilia['id_clinica_emilia']);
  $('#edite-clinica_emilia input[name="titulo"]').val(clinica_emilia['titulo']);
  $('#edite-clinica_emilia img').attr("src", "../"+clinica_emilia['img']);
  $('#edite-clinica_emilia').find(".msg").html("");
}

function get_centro_diagnostico_por_imagem(centro_diagnostico_por_imagem){

  //console.log(clinica_emilia);
  $("#lista-centro_diagnostico_por_imagem").html("<thead><tr><th></th><th></th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(centro_diagnostico_por_imagem, function( i, c ) {
    $("#lista-centro_diagnostico_por_imagem tbody").append(`<tr>
      <td><img src="../${c['img']}" style='max-width:200px;'></td>
      <td>${c['titulo']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"centro_diagnostico_por_imagem\", ${c['id_centro_diagnostico_por_imagem']}, editeCentroDiagnosticoImagem)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${c['id_centro_diagnostico_por_imagem']}, \"centro_diagnostico_por_imagem\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

function editeCentroDiagnosticoImagem(centro_diagnostico_por_imagem){

  //console.log(convenio);
  $("#edite-centro_diagnostico_por_imagem").modal("show");
  $("#edite-centro_diagnostico_por_imagem").find(".modal-body");

  $('#edite-centro_diagnostico_por_imagem input[name="id"]').val(centro_diagnostico_por_imagem['id_centro_diagnostico_por_imagem']);
  $('#edite-centro_diagnostico_por_imagem input[name="titulo"]').val(centro_diagnostico_por_imagem['titulo']);
  $('#edite-centro_diagnostico_por_imagem img').attr("src", "../"+centro_diagnostico_por_imagem['img']);
  $('#edite-centro_diagnostico_por_imagem').find(".msg").html("");
}

function getlistas(){
  $('.modal-form').modal('hide');
  getLista("unidade_internacao", 1, get_unidade_internacao);
  getLista("pronto_atendimento", 1, get_pronto_atendimento);
  getLista("hotelaria", 1, get_hotelaria);
  getLista("clinica_emilia", 1, get_clinica_emilia);
  getLista("centro_diagnostico_por_imagem", 1, get_centro_diagnostico_por_imagem);
}

function getImagesUnidadeInternacao(id_unidade_internacao){

  $("#unidade_internacao-imagens").html("");

  var request = $.ajax({ url: 'webservices/paginas/instalacoes/servico.php', type: 'POST', dataType:'json', data: {acao:"getImagesUnidadeInternacao", id_unidade_internacao:id_unidade_internacao}});
  request.done(function (response){  
    //console.log(response);
    if(response != ""){

      response.forEach(image => {
        
        $("#unidade_internacao-imagens").append(`<div class='form-group col-md-6 unidade_internacao-imagem'>
          <center><img src='../${image['img']}' class='img-responsive'></center>
          <br>
          <div class='form-group col-md-6'>
            <center>
              <input type='checkbox' class='status' ${(image['status'] != null && image['status'] != 0 ? 'checked' : '' )} onChange='alteraStatusUnidadeInternacao(this, ${image['id_unidade_internacao_imagem']})'>
            </center>
          </div>
          <div class='form-group col-md-6'>
            <center>
              <a class='label label-danger' onClick='excluiImagemUnidadeInternacao(this, ${image['id_unidade_internacao_imagem']})'>excluir</a>
            </center>
          </div>
        </div>`)
      });
    }
  });
}

function makeFileListUnidadeInternacao(input, acao) {

  if(input.files[0] != null){

    var formData = new FormData();

    formData.append("acao", "insereImagemUnidadeInternacao");
    formData.append("img", $("#form-unidade_internacao input[type=file]")[0].files[0]);
    formData.append("id", $('#form-unidade_internacao input[name="id"]').val());

    var request = $.ajax({ url: 'webservices/paginas/instalacoes/servico.php', type: 'POST',  data: formData, async: true, cache: false, contentType: false, processData: false});
    request.done(function (response){
      
      if(response != ""){

        var image = $.parseJSON(response);

        $("#unidade_internacao-imagens").append(`<div class='form-group col-md-6 unidade_internacao-imagem'>
          <center><img src='../${image['img']}' class='img-responsive'></center>
          <br>
          <div class='form-group col-md-6'>
            <center>
              <input type='checkbox' class='status' ${(image['status'] != null && image['status'] != 0 ? 'checked' : '' )} onChange='alteraStatusUnidadeInternacao(this, ${image['id']})'>
            </center>
          </div>
          <div class='form-group col-md-6'>
            <center>
              <a class='label label-danger' onClick='excluiImagemUnidadeInternacao(this, ${image['id']})'>excluir</a>
            </center>
          </div>
        </div>`);
      }
    });
  }
}

function alteraStatusUnidadeInternacao(elemento, id_imagem){

  status = ($(elemento).is(':checked') ? 1 : 0 );

  console.log(status);

  var request = $.ajax({ url: 'webservices/paginas/instalacoes/servico.php', type: 'POST', data: {acao:"alteraStatusUnidadeInternacao", id_imagem:id_imagem, status:status}});
  request.done(function (response){
    //console.log(response);
  });
}

function excluiImagemUnidadeInternacao(elemento, id_imagem){

  var request = $.ajax({ url: 'webservices/paginas/instalacoes/servico.php', type: 'POST', data: {acao:"excluiImagemUnidadeInternacao", id_imagem:id_imagem}});
  request.done(function (response){
    if(response == 1){
      $(elemento).closest(".unidade_internacao-imagem").remove();
    }
  });
}

function getUnidadeInternacaoTextos(){

  var request = $.ajax({ url: 'webservices/paginas/instalacoes/servico.php', type: 'POST', dataType:'json', data: {acao:"getUnidadeInternacaoTextos"}});
  request.done(function (response){  

    $("textarea[name='unidade_internacao-texto1']").text(response['bloco1']);
    $("textarea[name='unidade_internacao-texto2']").text(response['bloco2']);
    $("textarea[name='unidade_internacao-texto3']").text(response['bloco3']);
  });
}

function getProntoAtendimentoTextos(){

  var request = $.ajax({ url: 'webservices/paginas/instalacoes/servico.php', type: 'POST', dataType:'json', data: {acao:"getProntoAtendimentoTextos"}});
  request.done(function (response){  

    $("textarea[name='pronto_atendimento-texto1']").text(response['bloco1']);
    $("textarea[name='pronto_atendimento-texto2']").text(response['bloco2']);
    $("textarea[name='pronto_atendimento-texto3']").text(response['bloco3']);
    $("textarea[name='pronto_atendimento-texto4']").text(response['bloco4']);

    $("textarea[name='pronto_atendimento-emergencia']").text(response['emergencia']);
    $("textarea[name='pronto_atendimento-urgencia']").text(response['urgencia']);
    $("textarea[name='pronto_atendimento-urgencia_relativa']").text(response['urgencia_relativa']);

    $("textarea[name='pronto_atendimento-texto5']").text(response['bloco5']);
  });
}

function getHotelariaTextos(){

  var request = $.ajax({ url: 'webservices/paginas/instalacoes/servico.php', type: 'POST', dataType:'json', data: {acao:"getHotelariaTextos"}});
  request.done(function (response){  

    $("textarea[name='hotelaria-texto1']").text(response['bloco1']);
    $("textarea[name='hotelaria-texto2']").text(response['bloco2']);
  });
}

function getClinicaEmiliaTextos(){

  var request = $.ajax({ url: 'webservices/paginas/instalacoes/servico.php', type: 'POST', dataType:'json', data: {acao:"getClinicaEmiliaTextos"}});
  request.done(function (response){  

    $("textarea[name='clinica_emilia-texto1']").text(response['bloco1']);
    $("textarea[name='clinica_emilia-texto2']").text(response['bloco2']);
  });
}

function getCentroDiagnosticoImagem(){

  var request = $.ajax({ url: 'webservices/paginas/instalacoes/servico.php', type: 'POST', dataType:'json', data: {acao:"getCentroDiagnosticoImagem"}});
  request.done(function (response){  

    $("textarea[name='centro_diagnostico_por_imagem-texto1']").text(response['bloco1']);
    $("textarea[name='centro_diagnostico_por_imagem-texto2']").text(response['bloco2']);
  });
}

getlistas();
getUnidadeInternacaoTextos();
getProntoAtendimentoTextos();
getHotelariaTextos();
getClinicaEmiliaTextos();
getCentroDiagnosticoImagem();

</script>