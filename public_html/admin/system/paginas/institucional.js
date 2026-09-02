<script>

/* Início - Galeria */
//data-toggle="modal" data-target="#myModal"
function get_galeria_sobre(galeria_sobre){

  //console.log(galeria_sobre);
  $("#lista-galeria_sobre").html("<thead><tr><th></th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(galeria_sobre, function( i, g ) {
    $("#lista-galeria_sobre tbody").append(`<tr>
      <td><img src="../${g['img']}" style='max-width:200px;'></td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"galeria_sobre\", ${g['id_galeria_sobre']}, editeGaleriaSobre)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${g['id_galeria_sobre']}, \"galeria_sobre\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

function editeGaleriaSobre(galeria){

  //console.log(convenio);

  $("#edite-galeria_sobre").modal("show");
  $("#edite-galeria_sobre").find(".modal-body");

  $('#edite-galeria_sobre input[name="id"]').val(galeria['id_galeria_sobre']);
  $('#edite-galeria_sobre img').attr("src", "../"+galeria['img']);
  $('#edite-galeria_sobre').find(".msg").html("");
}
/* Fim - Galeria */

/* Início - Provedor */
//data-toggle="modal" data-target="#myModal"
function get_provedor(provedor){

  //console.log(galeria_sobre);
  $("#lista-provedor").html("<thead><tr><th></th><th>Nome</th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(provedor, function( i, g ) {
    $("#lista-provedor tbody").append(`<tr>
      <td><img src="../${g['img']}" style='max-width:200px;'></td>
      <td>${g['nome']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"provedor\", ${g['id_provedor']}, editeProvedores)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${g['id_provedor']}, \"provedor\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

function editeProvedores(provedor){

  //console.log(convenio);

  $("#edite-provedor").modal("show");
  $("#edite-provedor").find(".modal-body");

  $('#edite-provedor input[name="id"]').val(provedor['id_provedor']);
  $('#edite-provedor input[name="nome"]').val(provedor['nome']);
  $('#edite-provedor input[name="data1"]').val(provedor['data1']);
  $('#edite-provedor input[name="data2"]').val(provedor['data2']);
  $('#edite-provedor img').attr("src", "../"+provedor['img']);
  $('#edite-provedor').find(".msg").html("");
}
/* Fim - Provedor */

/* Início - Ações */
//data-toggle="modal" data-target="#myModal"
function get_galeria_acao(galeria_acao){

  //console.log(galeria_sobre);
  $("#lista-galeria_acao").html("<thead><tr><th>Descrição</th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(galeria_acao, function( i, g ) {
    $("#lista-galeria_acao tbody").append(`<tr>
      <td><img src="../${g['img']}" style='max-width:200px;'></td>
      <td>${g['descricao']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"galeria_acao\", ${g['id_galeria_acao']}, editeAcoes)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${g['id_galeria_acao']}, \"galeria_acao\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

function editeAcoes(galeria_acao){

  //console.log(convenio);

  $("#edite-galeria_acao").modal("show");
  $("#edite-galeria_acao").find(".modal-body");

  $('#edite-galeria_acao input[name="id"]').val(galeria_acao['id_galeria_acao']);
  $('#edite-galeria_acao img').attr("src", "../"+galeria_acao['img']);
  $('#edite-galeria_acao textarea[name="descricao"]').val(galeria_acao['descricao']);
}
/* Fim - Ações */

/* Início - Galeria Humanização */
//data-toggle="modal" data-target="#myModal"
function get_galeria_humanizacao(galeria_humanizacao){

  //console.log(galeria_sobre);
  $("#lista-galeria_humanizacao").html("<thead><tr><th>Descrição</th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(galeria_humanizacao, function( i, g ) {
    $("#lista-galeria_humanizacao tbody").append(`<tr>
      <td><img src="../${g['img']}" style='max-width:200px;'></td>
      <td>${g['descricao']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"galeria_humanizacao\", ${g['id_galeria_humanizacao']}, editeGaleriaHumanizacao)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${g['id_galeria_humanizacao']}, \"galeria_humanizacao\")'>Excluir</a>
      </td>
    </tr>`);
  });
}

function editeGaleriaHumanizacao(galeria_humanizacao){

  //console.log(convenio);

  $("#edite-galeria_humanizacao").modal("show");
  $("#edite-galeria_humanizacao").find(".modal-body");

  $('#edite-galeria_humanizacao input[name="id"]').val(galeria_humanizacao['id_galeria_humanizacao']);
  $('#edite-galeria_humanizacao img').attr("src", "../"+galeria_humanizacao['img']);
  $('#edite-galeria_humanizacao textarea[name="descricao"]').val(galeria_humanizacao['descricao']);
}
/* Fim - Ações */

/* Início - Balanço */
//data-toggle="modal" data-target="#myModal"
function get_balanco(balanco){

  //console.log(galeria_sobre);
  $("#lista-balanco").html("<thead><tr><th>Ano</th><th>Ação</th></tr></thead><tbody></tbody>");

  $.each(balanco, function( i, g ) {
    $("#lista-balanco tbody").append(`<tr>
      <td>${g['ano']}</td>
      <td>
        <a class='btn btn-block btn-flat btn-primary btn-xs' onClick='getConteudo(\"balanco\", ${g['id_balanco']}, editeBalanco)'>Editar</a>
        <a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiConteudo(this, ${g['id_balanco']}, \"balanco\")'>Excluir</a>
      </td>
    </tr>`);
  });

}

function editeBalanco(balanco){

  //console.log(convenio);

  $("#edite-balanco").modal("show");
  $("#edite-balanco").find(".modal-body");

  $('#edite-balanco input[name="id"]').val(balanco['id_balanco']);
  $('#edite-balanco input[name="ano"]').val(balanco['ano']);
}
/* Fim - Balanço */

function getlistas(){
  $('.modal-form').modal('hide');
  getLista("galeria_sobre", 1, get_galeria_sobre); 
  getLista("provedor", 1, get_provedor);
  getLista("balanco", 1, get_balanco); 
  getLista("galeria_acao", 1, get_galeria_acao);
  getLista("galeria_humanizacao", 1, get_galeria_humanizacao)
}

function getSobre(){

  var request = $.ajax({ url: 'webservices/paginas/institucional/servico.php', type: 'POST', dataType:'json', data: {acao:"getSobre"}});
  request.done(function (response){  
    //console.log(response);

    $("textarea[name='sobre_santa_casa_lorena-texto1']").text(response['bloco1']);
    $("textarea[name='sobre_santa_casa_lorena-texto2']").text(response['bloco2']);
    $("textarea[name='sobre_santa_casa_lorena-texto3']").text(response['bloco3']);
    $("textarea[name='sobre_santa_casa_lorena-texto4']").text(response['bloco4']);
    $("textarea[name='sobre_santa_casa_lorena-missao']").text(response['missao']);
    $("textarea[name='sobre_santa_casa_lorena-visao']").text(response['visao']);
    $("textarea[name='sobre_santa_casa_lorena-valor']").text(response['valor']);
    $("textarea[name='sobre_santa_casa_lorena-provedor']").text(response['provedor']);
  });
}

function getProgramaNacionalSeguranca(){

  var request = $.ajax({ url: 'webservices/paginas/institucional/servico.php', type: 'POST', dataType:'json', data: {acao:"getProgramaNacionalSeguranca"}});
  request.done(function (response){  
    //console.log(response);

    $('.programa_nacional_seguranca-image1').attr("src", "../"+response['img1']);
    $("textarea[name='programa_nacional_seguranca-texto2']").text(response['bloco2']);
    $("textarea[name='programa_nacional_seguranca-texto1']").text(response['bloco1']);
  });
}

function getAcoesSociaisAmbientais(){

  var request = $.ajax({ url: 'webservices/paginas/institucional/servico.php', type: 'POST', dataType:'json', data: {acao:"getAcoesSociaisAmbientais"}});
  request.done(function (response){  
    //console.log(response);

    $('.acoes_sociais_ambientais-image1').attr("src", "../"+response['img1']);
    $('.acoes_sociais_ambientais-image2').attr("src", "../"+response['img2']);
    $("textarea[name='acoes_sociais_ambientais-texto1']").text(response['bloco1']);
    $("textarea[name='acoes_sociais_ambientais-texto2']").text(response['bloco2']);
    $("textarea[name='acoes_sociais_ambientais-texto3']").text(response['bloco3']);
    $("textarea[name='acoes_sociais_ambientais-texto4']").text(response['bloco4']);
  });
}

function getHumanizacao(){

  var request = $.ajax({ url: 'webservices/paginas/institucional/servico.php', type: 'POST', dataType:'json', data: {acao:"getHumanizacao"}});
  request.done(function (response){  
    //console.log(response);

    $("textarea[name='humanizacao-texto1']").text(response['bloco1']);
    $("textarea[name='humanizacao-texto2']").text(response['bloco2']);
    $("textarea[name='humanizacao-texto3']").text(response['bloco3']);
    $("textarea[name='humanizacao-texto4']").text(response['bloco4']);
  });
}

getlistas();
getSobre();
getProgramaNacionalSeguranca();
getAcoesSociaisAmbientais();
getHumanizacao();

</script>