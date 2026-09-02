<script>

/* JS - Contato início */

  function listContatos(){

    var request = $.ajax({ url: 'webservices/paginas/fale-conosco/servico.php', type: 'POST', dataType: 'Json', data: {acao:'listContatos'}});
    request.done(function(response){

      //console.log(response);

      for(i=0; i <= response.length - 1; i++) {
        $('#lista-contatos tbody').append(`<tr>
          <td>${response[i]['nome']}</td>
          <td>${response[i]['email']}</td>
          <td style='font-size:1px !important; color:transparent;'>${response[i]['data_cadastro']}<span style='font-size:14px !important; color:#000;'>${response[i]['data_formatada']}</span></td>
          <td></td>
        </tr>`);
      }
      //"<a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiContato(this, "+response[i]['id_contato']+")'>Excluir</a>"+

      $('#lista-contatos').DataTable({
        "columnDefs": [
          {
            "targets": [ 3 ],
            "searchable": false,
            "orderable": false,
          },
        ]
      });
    });
  }

  function listOuvidoria(){

    var request = $.ajax({ url: 'webservices/paginas/fale-conosco/servico.php', type: 'POST', dataType: 'Json', data: {acao:'listOuvidoria'}});
    request.done(function(response){

      //console.log(response);

      for(i=0; i <= response.length - 1; i++) {
        $('#lista-ouvidoria tbody').append(`<tr>
          <td>${response[i]['nome']}</td>
          <td>${response[i]['email']}</td>
          <td style='font-size:1px !important; color:transparent;'>${response[i]['data_cadastro']}<span style='font-size:14px !important; color:#000;'>${response[i]['data_formatada']}</span></td>
          <td></td>
        </tr>`);
      }
      //"<a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiContato(this, "+response[i]['id_contato']+")'>Excluir</a>"+

      $('#lista-ouvidoria').DataTable({
        "columnDefs": [
          {
            "targets": [ 3 ],
            "searchable": false,
            "orderable": false,
          },
        ]
      });
    });
  }

  function listTrabalheConosco(){

    var request = $.ajax({ url: 'webservices/paginas/fale-conosco/servico.php', type: 'POST', dataType: 'Json', data: {acao:'listTrabalheConosco'}});
    request.done(function(response){

      //console.log(response);

      for(i=0; i <= response.length - 1; i++) {
        $('#lista-trabalhe_conosco tbody').append(`<tr>
          <td>${response[i]['nome']}</td>
          <td>${response[i]['email']}</td>
          <td><a class='btn btn-block btn-flat btn-info btn-xs' href='../${response[i]['curriculum']}' target='_blank'>ver curriculum</a></td>
          <td style='font-size:1px !important; color:transparent;'>${response[i]['data_cadastro']}<span style='font-size:14px !important; color:#000;'>${response[i]['data_formatada']}</span></td>
          <td></td>
        </tr>`);
      }
      //"<a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiContato(this, "+response[i]['id_contato']+")'>Excluir</a>"+

      $('#lista-trabalhe_conosco').DataTable({
        "columnDefs": [
          {
            "targets": [ 3 ],
            "searchable": false,
            "orderable": false,
          },
        ]
      });
    });
  }

  /*
  function listTrabalheConosco(){

    var request = $.ajax({ url: 'webservices/paginas/fale-conosco/servico.php', type: 'POST', dataType: 'Json', data: {acao:'listTrabalheConosco'}});
    request.done(function(response){

      //console.log(response);

      for(i=0; i <= response.length - 1; i++) {
        $('#lista-trabalhe_conosco tbody').append(`<tr>
          <td>${response[i]['nome']}</td>
          <td>${response[i]['email']}</td>
          <td><a class='btn btn-block btn-flat btn-info btn-xs' href='../${response[i]['curriculum']}' target='_blank'>ver curriculum</a></td>
          <td style='font-size:1px !important; color:transparent;'>${response[i]['data_cadastro']}<span style='font-size:14px !important; color:#000;'>${response[i]['data_formatada']}</span></td>
          <td></td>
        </tr>`);
      }
      //"<a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiContato(this, "+response[i]['id_contato']+")'>Excluir</a>"+

      $('#lista-trabalhe_conosco').DataTable({
        "columnDefs": [
          {
            "targets": [ 3 ],
            "searchable": false,
            "orderable": false,
          },
        ]
      });
    });
  }
  */

  /*
  function excluiContato(elemento, id_contato){

    bootbox.confirm({
      message: "Realmente deseja excluir o contato ?",
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
          var request = $.ajax({ url: 'webservices/paginas/fale-conosco/servico.php', type: 'POST', async: true,  data: { acao:"excluiContato", id_contato : id_contato}});
          request.done(function (response){
            if(response == 1){
              $(elemento).closest('tr').fadeOut();
              mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
                  '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                  'Contato excluido com sucesso'+
                '</div>';

                $('#mensagem_evento').html(mensagem);
            }
          });
        }
      }
    });
  }
  */

  function listDoacoes(){

    var request = $.ajax({ url: 'webservices/paginas/fale-conosco/servico.php', type: 'POST', dataType: 'Json', data: {acao:'listDoacoes'}});
    request.done(function(response){

      console.log(response);

      for(i=0; i <= response.length - 1; i++) {
        $('#lista-doacoes tbody').append(`<tr>
          <td>${response[i]['nome']}</td>
          <td>${response[i]['email']}</td>
          <td>${response[i]['cidade']}</td>
          <td style='font-size:1px !important; color:transparent;'>${response[i]['data_cadastro']}<span style='font-size:14px !important; color:#000;'>${response[i]['data_formatada']}</span></td>
          <td></td>
        </tr>`);
      }
      //"<a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='excluiContato(this, "+response[i]['id_contato']+")'>Excluir</a>"+

      $('#lista-doacoes').DataTable({
        "columnDefs": [
          {
            "targets": [ 4 ],
            "searchable": false,
            "orderable": false,
          },
        ]
      });
    });
  }
/* JS - Contato fim */

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
  getLista("balanco", 1, get_balanco); 
}

function getLocalizacao(){

  var request = $.ajax({ url: 'webservices/paginas/fale-conosco/servico.php', type: 'POST', dataType:'json', data: {acao:"getLocalizacao"}});
  request.done(function (response){  
    //console.log(response);

    $("input[name='localizacao-telefone']").val(response['telefone']);
    $("input[name='localizacao-email']").val(response['email']);
    $("input[name='localizacao-localizacao']").val(response['localizacao']);
  });
}


function getDoacaoTextos(){

  var request = $.ajax({ url: 'webservices/paginas/fale-conosco/servico.php', type: 'POST', dataType:'json', data: {acao:"getDoacaoTextos"}});
  request.done(function (response){  

    //console.log(response);

    $("textarea[name='doacoes-texto1']").text(response['bloco1']);
    $("textarea[name='doacoes-texto2']").text(response['bloco2']);
    $("textarea[name='doacoes-texto3']").text(response['bloco3']);
  });
}


getlistas();
getLocalizacao();

listContatos();
listOuvidoria();
listTrabalheConosco();
getDoacaoTextos();
listDoacoes();

</script>