<!-- CK Editor -->


<script>

//data-bs-toggle="modal" data-bs-target="#myModal"

function get_convenio(convenios){

  //console.log(convenios);
  $("#lista-convenios").html("<thead><tr><th></th><th></th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(convenios, function( i, c ) {
    $("#lista-convenios tbody").append(`<tr>
      <td><img src="../${c['img']}" style='max-width:200px;'></td>
      <td>${c['nome']}</td>
      <td>
      	<a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"convenios\", ${c['id_convenio']}, editeConvenio)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${c['id_convenio']}, \"convenios\")'>Excluir</a>
      </td>
    </tr>`);
  });

  /*
  $('#lista-convenios').DataTable( {
    "columnDefs": [
      {
         "targets": [ 2 ],
          "searchable": false,
          "orderable": false,
      },
    ]
  });
  */
}

function get_especialidades(especialidades){

  //console.log(especialidades);
  $("#lista-especialidades").html("<thead><tr><th></th><th></th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(especialidades, function( i, c ) {
    $("#lista-especialidades tbody").append(`<tr>
      <td><img src="../${c['img']}" style='max-width:200px;'></td>
      <td>${c['nome']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"especialidades\", ${c['id_especialidade']}, editeEspecialidade)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${c['id_especialidade']}, \"especialidades\")'>Excluir</a>
      </td>
    </tr>`);
  });

  /*
  $('#lista-especialidades').DataTable( {
    "columnDefs": [
      {
         "targets": [ 2 ],
          "searchable": false,
          "orderable": false,
      },
    ]
  });
  */
}

function get_manual_paciente(manual_paciente){

  //console.log(especialidades);
  $("#lista-manual_paciente").html("<thead><tr><th></th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(manual_paciente, function( i, c ) {
    $("#lista-manual_paciente tbody").append(`<tr>
      <td>${c['titulo']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"manual_paciente\", ${c['id_manual_paciente']}, editeManualPaciente)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${c['id_manual_paciente']}, \"manual_paciente\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

function get_capacidade(capacidade){

  //console.log(especialidades);
  $("#lista-capacidade").html("<thead><tr><th></th><th></th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(capacidade, function( i, c ) {
    $("#lista-capacidade tbody").append(`<tr>
      <td><img src="../${c['img']}" style='max-width:200px;'></td>
      <td>${c['titulo']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"capacidade\", ${c['id_capacidade']}, editeCapacidade)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${c['id_capacidade']}, \"capacidade\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

/* Início - Balanço */
//data-bs-toggle="modal" data-bs-target="#myModal"
function get_download_manual_paciente(download_manual_paciente){

  //console.log(galeria_sobre);
  $("#lista-download_manual_paciente").html("<thead><tr><th>Titulo</th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(download_manual_paciente, function( i, g ) {
    $("#lista-download_manual_paciente tbody").append(`<tr>
      <td>${g['titulo']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"download_manual_paciente\", ${g['id_download_manual_paciente']}, editeDownloadManualPaciente)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${g['id_download_manual_paciente']}, \"download_manual_paciente\")'>Excluir</a>
      </td>
    </tr>`);
  });

}

function editeDownloadManualPaciente(download_manual_paciente){

  //console.log(convenio);

  $("#edite-download_manual_paciente").sclModal("show");
  $("#edite-download_manual_paciente").find(".modal-body");

  $('#edite-download_manual_paciente input[name="id"]').val(download_manual_paciente['id_download_manual_paciente']);
  $('#edite-download_manual_paciente input[name="titulo"]').val(download_manual_paciente['titulo']);
}
/* Fim - Balanço */

function getlistas(){
  $('.modal-form').sclModal('hide');
  getLista("convenios", 1, get_convenio);
  getLista("especialidades", 1, get_especialidades);
  getLista("manual_paciente", 1, get_manual_paciente);
  getLista("capacidade", 1, get_capacidade);
  getLista("download_manual_paciente", 1, get_download_manual_paciente); 
}

function editeConvenio(convenio){

  //console.log(convenio);

  $("#edite-convenios").sclModal("show");
  $("#edite-convenios").find(".modal-body");

  $('#edite-convenios input[name="id"]').val(convenio['id_convenio']);
  $('#edite-convenios img').attr("src", "../"+convenio['img']);
  $('#edite-convenios input[name="nome"]').val(convenio['nome']);
  $('#edite-convenios textarea[name="descricao"]').val(convenio['descricao']);
  $('#edite-convenios').find(".msg").html("");
}

function editeCapacidade(capacidade){

  //console.log(convenio);

  $("#edite-capacidade").sclModal("show");
  $("#edite-capacidade").find(".modal-body");

  $('#edite-capacidade input[name="id"]').val(capacidade['id_capacidade']);
  $('#edite-capacidade input[name="titulo"]').val(capacidade['titulo']);
  $('#edite-capacidade textarea[name="descricao"]').val(capacidade['descricao']);
  $('#edite-capacidade').find(".msg").html("");

  getImagesCapacidade(capacidade['id_capacidade']);
}

function getImagesCapacidade(id_capacidade){

  $("#capacidade-imagens").html("");

  var request = $.ajax({ url: 'webservices/paginas/servicos/servico.php', type: 'POST', dataType:'json', data: {acao:"getImagesCapacidade", id_capacidade:id_capacidade}});
  request.done(function (response){  
    //console.log(response);
    if(response != ""){

      response.forEach(image => {
        
        $("#capacidade-imagens").append(`<div class='form-group col-md-6 capacidade-imagem'>
          <center><img src='../${image['img']}' class='img-responsive'></center>
          <br>
          <div class='form-group col-md-6'>
            <center>
              <input type='checkbox' class='status' ${(image['status'] != null && image['status'] != 0 ? 'checked' : '' )} onChange='alteraStatusImagemCapacidade(this, ${image['id_capacidade_imagem']})'>
            </center>
          </div>
          <div class='form-group col-md-6'>
            <center>
              <a class='label label-danger' onClick='excluiImagemCapacidade(this, ${image['id_capacidade_imagem']})'>excluir</a>
            </center>
          </div>
        </div>`)
      });
    }
  });
}

function makeFileListCapacidade(input, acao) {

  if(input.files[0] != null){

    var formData = new FormData();

    formData.append("acao", "insereImagemCapacidade");
    formData.append("img", $("#form-capacidade input[type=file]")[0].files[0]);
    formData.append("id", $('#form-capacidade input[name="id"]').val());

    var request = $.ajax({ url: 'webservices/paginas/servicos/servico.php', type: 'POST',  data: formData, async: true, cache: false, contentType: false, processData: false});
    request.done(function (response){
      
      if(response != ""){

        var image = JSON.parse(response);

        $("#capacidade-imagens").append(`<div class='form-group col-md-6 capacidade-imagem'>
          <center><img src='../${image['img']}' class='img-responsive'></center>
          <br>
          <div class='form-group col-md-6'>
            <center>
              <input type='checkbox' class='status' ${(image['status'] != null && image['status'] != 0 ? 'checked' : '' )} onChange='alteraStatusImagemCapacidade(this, ${image['id']})'>
            </center>
          </div>
          <div class='form-group col-md-6'>
            <center>
              <a class='label label-danger' onClick='excluiImagemCapacidade(this, ${image['id']})'>excluir</a>
            </center>
          </div>
        </div>`);
      }
    });
  }
}

function alteraStatusImagemCapacidade(elemento, id_imagem){

  status = ($(elemento).is(':checked') ? 1 : 0 );

  console.log(status);

  var request = $.ajax({ url: 'webservices/paginas/servicos/servico.php', type: 'POST', data: {acao:"alteraStatusImagemCapacidade", id_imagem:id_imagem, status:status}});
  request.done(function (response){
    //console.log(response);
  });
}

function excluiImagemCapacidade(elemento, id_imagem){

  var request = $.ajax({ url: 'webservices/paginas/servicos/servico.php', type: 'POST', data: {acao:"excluiImagemCapacidade", id_imagem:id_imagem}});
  request.done(function (response){
    if(response == 1){
      $(elemento).closest(".capacidade-imagem").remove();
    }
  });
}

function editeEspecialidade(especialidade){
  //console.log(especialidade);
  //console.log(convenio);

  $("#edite-especialidades").sclModal("show");
  $("#edite-especialidades").find(".modal-body");

  $('#edite-especialidades input[name="id"]').val(especialidade['id_especialidade']);
  $('#edite-especialidades input[name="nome"]').val(especialidade['nome']);
  $('#edite-especialidades textarea[name="descricao"]').val(especialidade['descricao']);
  $('#edite-especialidades').find(".msg").html("");
}

function editeManualPaciente(manual_paciente){
  //console.log(especialidade);
  //console.log(convenio);

  $("#edite-manual_paciente").sclModal("show");
  $("#edite-manual_paciente").find(".modal-body");

  $('#edite-manual_paciente input[name="id"]').val(manual_paciente['id_manual_paciente']);
  $('#edite-manual_paciente input[name="titulo"]').val(manual_paciente['titulo']);
  $('#edite-manual_paciente textarea[name="descricao"]').val(manual_paciente['descricao']);

  if(SCLEditor.instances['update-manual_paciente']) {
    SCLEditor.instances['update-manual_paciente'].destroy();
    $('#edite-manual_paciente textarea[name="descricao"]').val(manual_paciente['descricao']);
  }


  SCLEditor.replace('update-manual_paciente');

  $('#edite-manual_paciente').find(".msg").html("");
}

function getEspecialidadesTextos(){

  var request = $.ajax({ url: 'webservices/paginas/servicos/servico.php', type: 'POST', dataType:'json', data: {acao:"getEspecialidadesTextos"}});
  request.done(function (response){  
    //console.log(response);

    $("textarea[name='especialidades-texto1']").val(response['bloco1']);
    $("textarea[name='especialidades-texto2']").val(response['bloco2']);
  });
}

function getCapacidadeTextos(){

  var request = $.ajax({ url: 'webservices/paginas/servicos/servico.php', type: 'POST', dataType:'json', data: {acao:"getCapacidadeTextos"}});
  request.done(function (response){  
    //console.log(response);

    $("textarea[name='capacidade_instalacao_producao-texto1']").val(response['bloco1']);
    $("textarea[name='capacidade_instalacao_producao-texto2']").val(response['bloco2']);
  });
}

function getManualPacienteTextos(){

  var request = $.ajax({ url: 'webservices/paginas/servicos/servico.php', type: 'POST', dataType:'json', data: {acao:"getManualPacienteTextos"}});
  request.done(function (response){  
    //console.log(response);

    $("textarea[name='manual_paciente_visitante-texto1']").val(response['bloco1']);
    $("textarea[name='manual_paciente_visitante-texto2']").val(response['bloco2']);
  });
}

getlistas();
getEspecialidadesTextos();
getCapacidadeTextos();
getManualPacienteTextos();
</script>