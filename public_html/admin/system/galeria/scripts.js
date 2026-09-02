<!-- page script -->
  <script>
  $( 'h1' ).appendTo( $( '.content-header' ) );

  $(function () {
    // if(($('#conteudo').length > 0)){
    //   CKEDITOR.replace('conteudo');
    //   console.log("teste");
    // }

    //Initialize Select2 Elements
    $('.select2').select2();

    $('#data-list').DataTable( {
      'columnDefs': [
          {
              'targets': [ 2 ],
              //'visible': false,
              'searchable': false,
              'orderable': false,
          },
      ]
    } );

    $('[data-mask]').inputmask();

    //iCheck for checkbox and radio inputs
    $("input[type='checkbox'].minimal, input[type='radio'].minimal").iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
  });

    function PreviewImg(input){
      if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#previewimg')
        .attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
      }
    }

    function CreateGaleria() {

          // $('#descricao').val(CKEDITOR.instances['descricao'].getData());
        var formData = new FormData(document.getElementById("newGaleria"));
        formData.append("acao", "CreateGaleria");

        var request = $.ajax({ url: 'webservices/galeria/servico.php', type: 'POST', data: formData, async: true, contentType: false, cache: false, processData: false});
        request.done(function(dataresult){
        console.log(dataresult);

         if (!isNaN(dataresult)){

                      mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
                       '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                       'Galeria cadastrada com sucesso'+
                       '</div>';

                      //  window.setTimeout(function(){
                      //
                      //     window.location.href = "painel.php?exe=empreeendimento/update&id_produto=" + dataresult;
                      //
                      // }, 5000);

                     }
                     else
                     {
                       mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
                          '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                          'Não foi possível adicionar à Galeria! detalhes:'+ dataresult +
                          '</div>';
                     }

                    $('#mensagem_evento').append(mensagem);
         });
    };


function CreateAnexo() {

      // $('#descricao').val(CKEDITOR.instances['descricao'].getData());
    var formData = new FormData(document.getElementById("newGaleria"));
    formData.append("acao", "CreateAnexo");

    var request = $.ajax({ url: 'webservices/galeria/servico.php', type: 'POST', data: formData, async: true, contentType: false, cache: false, processData: false});
    request.done(function(dataresult){
    console.log(dataresult);

    $('#nome').val(null);
    $('#newimagem').val(null);
    $('#previewimg').attr('src', null);

    var myarray = $.parseJSON(dataresult);

     var html='';

     html +="<div class='col-sm-6 seguraimg' style='display:table;'>";
     html +="<div class='col-sm-4' style='display:table;'>";
     html += "<img style='background: url(<?php echo HOME;?>"+myarray['img']+") no-repeat; background-size: cover; background-position: center; width: 300px; height: 100px; min-width: 100%;margin-bottom:10px'/>";
     html +="</div>";
     html +="<div class='col-sm-1' id='segurabotao'>";
     html +='<input class="btn btn-danger btn-sm" onclick="deleteAnexo(this, '+"'"+myarray['id_anexo']+"'"+')" type="button" value="Excluir">';
     html +="</div>";
     html +="<input name='id_anexo[]' type='hidden' value='"+myarray['id_anexo']+"'/>";
     html +="</div>";

    $('#thumbAnexo').append(html);

    //  if (!isNaN(dataresult)){
     //
    //               mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
    //                '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
    //                'Galeria cadastrada com sucesso'+
    //                '</div>';
     //
    //               //  window.setTimeout(function(){
    //               //
    //               //     window.location.href = "painel.php?exe=empreeendimento/update&id_produto=" + dataresult;
    //               //
    //               // }, 5000);
     //
    //              }
    //              else
    //              {
    //                mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
    //                   '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
    //                   'Não foi possível adicionar à Galeria! detalhes:'+ dataresult +
    //                   '</div>';
    //              }
     //
    //             $('#mensagem_evento').append(mensagem);
     });
};

function UpdateGaleria() {

      // $('#descricao').val(CKEDITOR.instances['descricao'].getData());
    var formData = new FormData(document.getElementById("newGaleria"));
    formData.append("acao", "UpdateGaleria");

    var request = $.ajax({ url: 'webservices/galeria/servico.php', type: 'POST', data: formData, async: true, contentType: false, cache: false, processData: false});
    request.done(function(dataresult){
    console.log(dataresult);

     if (!isNaN(dataresult)){

                  mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
                   '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                   'Galeria atualizada com sucesso'+
                   '</div>';

                   window.setTimeout(function(){

                      window.location.href = "painel.php?exe=galeria/update&id_galeria=" + dataresult;

                  }, 2000);

                 }
                 else
                 {
                   mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
                      '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                      'Não foi possível adicionar à Galeria! detalhes:'+ dataresult +
                      '</div>';
                 }

                $('#mensagem_evento').append(mensagem);
     });
};

function deleteAnexo(elemento, id){
  $(elemento).closest(".seguraimg").remove();
     console.log('id delete',id)
     var request = $.ajax({ url: 'webservices/galeria/servico.php', type: 'POST',data: {acao:'deleteAnexo',id:id}});
     request.done(function(resultado){
      //  var myarray = $.parseJSON(resultado);
     alert('Deletado com sucesso!', resultado);
    });

}

function deleteAnexoUpdate(elemento, id){
  $(elemento).closest(".seguraimg").remove();
     console.log('id delete',id)
     var request = $.ajax({ url: 'webservices/galeria/servico.php', type: 'POST',data: {acao:'deleteAnexoUpdate',id:id}});
     request.done(function(resultado){
      //  var myarray = $.parseJSON(resultado);
     alert('Deletado com sucesso!', resultado);
    });

}

</script>
