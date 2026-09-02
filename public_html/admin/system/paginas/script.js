<script>

$(function(){

  switch ($('#modo').text()){

    case "update":
      getPaginas();
    break;
  }
});

function getPaginas(){

  //console.log("teste");

  //if (GetURLParameter('id_pagina') == undefined) return;
  var request = $.ajax({ url: 'webservices/paginas/servico.php', type: 'POST', dataType:'json', data: {acao:"getPaginas"}});
  request.done(function (response){
    
    response = response;
    $.each(response, function(i, p){

      //console.log("."+p['url_amigavel'].replace(/[\_]/g, "-")+"-image");
      //console.log(p['url_amigavel']+"_sub_titulo");

      $("#"+p['url_amigavel']).val(p['titulo']);
      $("#"+p['url_amigavel']+"_seo").val(p['seo']);
      $("#"+p['url_amigavel']+"_sub_titulo").val(p['sub_titulo']);
      $("#"+p['url_amigavel']+"_descricao").val(p['descricao']);
      $("."+p['url_amigavel'].replace(/[\_]/g, "-")+"-image").attr("src", "../"+p['img_principal']);
      //console.log(p['url_amigavel'].replace(/[\_]/g, "-")+"-image");
      //getLista(p['url_amigavel'].replace(/[\_]/g, "-"), p['lista'], "get_"+p['url_amigavel'].replace(/[\_]/g, "-"));
    });

    //$('textarea[name=""]').text(response['']);
    //CKEDITOR.replace('');

    /*
    if(response[''] == null || response[''] == 0)
      $("#selecao-unidade").remove()
    */

    /*
    if(response[''] != 0)
      $(".").html("");
    else
      $(".").html("");
    */
    
    /*
      $('# option[value="'+response['']+'"]').prop("selected", "selected");
      $('#').select2({
        placeholder: "Selecione"
      });
    */

    $(iCheck);
  });
}

function updatePaginas(){

  //Mensagem de erro padrão para UPDATE
  /*
  msg_erro = '<div class=\"alert alert-warning alert-dismissible\">'+
  '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
  'Preencha corretamente todos os campos'+
  '</div>';
  */

  //Pega formulario
  f = document.getElementById("paginasForm");

  $('.form-group').removeClass('has-warning').removeClass('has-error').find('.msg-erro').html('');  

  //$('#').val(CKEDITOR.instances[''].getData());

  var formData = new FormData(document.getElementById("paginasForm"));
  formData.append("acao", "updatePaginas");

  var request = $.ajax({ url: 'webservices/paginas/servico.php', type: 'POST',  data: formData, async: true, cache: false, contentType: false, processData: false});
  request.done(function ( response ) {

    //console.log(response);

    var mensagem = "";
    if (response == 1){

      mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
      '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
      'Páginas atualizadas com sucesso'+
      '</div>';
    }else{

      mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
      '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
      'Não foi possível atualizar as páginas! :'+ response +
      '</div>';
    }

    $('#mensagem_evento').html(mensagem);
    window.location.href = "#";
  });
}

function createConteudoModal(dominio){

  //console.log((`#create-${dominio}`));
  $(`#create-${dominio}`).modal("show");
  $(`#create-${dominio}`).find('input').val("");
  //$(`#create-${dominio}`).find(".modal-body");

  if(CKEDITOR.instances['ck-manual_paciente']) {
    CKEDITOR.instances['ck-manual_paciente'].destroy();
    $('#create-manual_paciente textarea[name="descricao"]').val("");
    CKEDITOR.replace('ck-manual_paciente');
  }else{
    CKEDITOR.replace('ck-manual_paciente');
  }

  
}

function getLista(dominio, lista, FUNC){
  if(lista == 1){
    var request = $.ajax({ url: 'webservices/paginas/servico.php', type: 'POST', dataType:'json', data: {acao:"getLista", dominio:dominio}});
    request.done(function (response){  
      FUNC(response);
    }); 
  }
}

function getConteudo(dominio, id, FUNC){
  var request = $.ajax({url: 'webservices/paginas/servico.php', type: 'POST', dataType:'json', data: {acao:"getConteudo", dominio:dominio, id:id}});
  request.done(function (response){  
    //console.log(response);
    FUNC(response);
  }); 
}

function updateConteudo(dominio, id){

  var formData = new FormData(document.getElementById(`form-${dominio}`));
  formData.append("acao", "updateConteudo");
  formData.append("dominio", dominio);

  if(dominio == 'manual_paciente')
    formData.append("descricao", CKEDITOR.instances['update-manual_paciente'].getData());

  formData.append("id", (id == null ? $(`#form-${dominio} input[name="id"]`).val() : id ));

  var request = $.ajax({ url: 'webservices/paginas/servico.php', type: 'POST',  data: formData, async: true, cache: false, contentType: false, processData: false});
  request.done(function ( response ) {

    console.log(response);

    if(response == 1){

      setTimeout(function(){

        $(`#form-${dominio}`).find(".msg").html(`${dominio} alterado com sucesso`);
        setTimeout(getlistas(), 3000);
      }, 1000);
    }else{

      $(`#form-${dominio}`).find(".msg").html(`erro ao salvar alterações`);
    }
  });
}

function createConteudo(dominio){

  var formData = new FormData(document.getElementById(`form-create-${dominio}`));
  formData.append("acao", "createConteudo");
  formData.append("dominio", dominio);

  if(dominio == 'manual_paciente')
    formData.append("descricao", CKEDITOR.instances['ck-manual_paciente'].getData());

  var request = $.ajax({ url: 'webservices/paginas/servico.php', type: 'POST',  data: formData, async: true, cache: false, contentType: false, processData: false});
  request.done(function ( response ) {

    console.log(response);

    if(response > 0){

      setTimeout(function(){

        $(`#form-create-${dominio}`).find(".msg").html(`${dominio} cadastrado com sucesso`);
        setTimeout(getlistas(), 3000);
      }, 1000);
    }else{

      $(`#form-create-${dominio}`).find(".msg").html(`erro ao salvar alterações`);
    }
  });
}

function excluiConteudo(elemento, id, dominio){

  bootbox.confirm({
    message: "Realmente deseja excluir este conteúdo",
    buttons: {
      'cancel': {
        label: 'Não',
        className: 'btn-danger'
      },
      'confirm': {
        label: 'Sim',
        className: 'btn-success'
      }
    },
    
    callback: function(result) {

      if(result == 1){

        //console.log(elemento+" "+id+" "+dominio);

        var request = $.ajax({ url: `webservices/paginas/servico.php`, type: 'POST', async: true,  data: { acao:"excluiConteudo", id:id, dominio:dominio}});
        request.done(function (response){
          console.log(response);
          if(response == 1){
            $(elemento).closest('tr').fadeOut();
            mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
              '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
              'Conteúdo excluido com sucesso'+
              '</div>';

            $('#mensagem_evento').html(mensagem);
          }
        });
      }
    }
  });
}

</script>
<script src='../resources/js/util.js'></script>
<script src='../resources/plugins/input-mask/jquery.inputmask.js'></script>
<script src='../resources/plugins/input-mask/jquery.inputmask.date.extensions.js'></script>
<script src='../resources/plugins/ckeditor/ckeditor.js'></script>