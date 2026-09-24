
<!-- page script -->
<script>
    
  //OnLoad
  $(function () {

    $('input[type="checkbox"].minimal, input[type="radio"].minimal').addClass('form-check-input');

    $('.selectSite').sclSelect();

    $( 'h1' ).appendTo( $( '.content-header' ) );

    switch ($('#modo').text()){
      case "crear":

        $(select2Cats);
        $(iCheck);
        SCLEditor.replace('descricao');
      break;
      
      case "consulta": 
            
        getListNoticia();
        break;
      
      case "editar":
        
        GetDetalhesNoticia();

        $('#id_tag').on('change',function(){
          // Synchronize removed persisted categories with the existing endpoint.
          const current=new Set($(this).val()||[]);
          for(const old of this.sclSavedTags||[])if(!current.has(old))DeleteTag(old);
          this.sclSavedTags=[...current];
        });
      break;
    }

    $('input[type="checkbox"].minimal, input[type="radio"].minimal').addClass('form-check-input');
  });
    
  function getTags(elemento,idNoticia){

    if(GetURLParameter('id_noticia') != null){
      
      var request = $.ajax({ url: 'webservices/noticias/servico.php', type: 'POST', data: {acao:'getTagsNoticia', id_noticia:idNoticia}, async: false});
      request.done(function(response){
          
        $(JSON.parse(response)).each(function(){
            
          tag  = this;
          $("#"+elemento+" option").each(function(){
            ($(this).val() == tag['id_tag'] ? $(this).attr('selected', true) : false);
          });
        });

        $(select2Cats);
        document.getElementById(elemento).sclSavedTags=$('#'+elemento).val()||[];

      });
    }
  }

  function PreviewImg(input){
     
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#previewimg').attr('src', e.target.result);
      };			
      reader.readAsDataURL(input.files[0]);
    }
  }

  function getListNoticia(){

    $.ajax({
      url: 'webservices/noticias/servico.php',
      type: 'POST',
      data: {acao:'getListNoticia'},

      success: function(resultado){

        var myarray = JSON.parse(resultado);

        for (i=0; i <= myarray.length - 1; i++) {

          $('#data-list > tbody:last-child').append('<tr>'+
            '<td><img src="../'+myarray[i]['img']+'" alt="" width="100"></td>'+
            '<td>'+myarray[i]['titulo']+'</td>'+
            "<td>"+(myarray[i]['status'] == '1' ? "<span class='label label-success' onCLick='alteraStatus(this, "+myarray[i]['id_noticia']+")' style='cursor:pointer;'>habilitado</span>" : "<span class='label label-danger' onCLick='alteraStatus(this, "+myarray[i]['id_noticia']+")' style='cursor:pointer;'>desabilitado</span>")+"</td>"+ 
            '<td>'+
            "<a href='painel.php?exe=noticias/update&id_noticia="+myarray[i]['id_noticia']+"' class='btn btn-block btn-flat btn-primary btn-xs'>Editar</a>" +
            "<a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='DeleteNoticia("+myarray[i]['id_noticia']+",this, \""+myarray[i]['titulo']+"\")'>Excluir</a>" +
            '</a></td></tr>' );
        }

        $('#data-list')
        .removeClass( 'display' )
        .addClass('table table-striped table-bordered');

        $('#data-list').DataTable({
          "columnDefs": [
            {
              "targets": [ 3 ],
              //"visible": false,
              "searchable": false,
              "orderable": false,
            },
          ]
        });
      }
    });
  }

  function alteraStatus(elemento, id_noticia){

    var request = $.ajax({ url: 'webservices/noticias/servico.php', type: 'POST', async: true,  data: { acao:"alteraStatus", id_noticia : id_noticia}});
    request.done(function (response){

      if(response == 1){
        $(elemento).removeClass('label-danger').addClass('label-success').html('habilitado');
      }else{
        $(elemento).removeClass('label-success').addClass('label-danger').html('desabilitado');
      }
    });
  }

  function CreateNoticia() {
    $('#descricao').val(SCLEditor.instances['descricao'].getData());
    var formData = new FormData(document.getElementById("newnoticia"));

    $.when($.ajax({
      url: 'webservices/noticias/servico.php',
      type: 'POST',
      data: formData,
      async: true,
      cache: false,
      contentType: false,
      processData: false,
      success: function(dataresult) {

        if (!isNaN(dataresult)){

          mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
          '<button type=\"button\" class=\"close\" data-bs-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
          'Noticia incluida com sucesso'+
          '</div>';

          $("#newparceiro :input").prop("disabled", true);

          window.setTimeout(function(){
            window.location.href = "painel.php?exe=noticias/update&id_noticia=" + dataresult;
          }, 5000);

        }else{
          mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
          '<button type=\"button\" class=\"close\" data-bs-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
          'Não foi possível adicionar o Noticia! detalhes:'+ dataresult +  
          '</div>';
        }

        $('#mensagem_evento').append(mensagem);
      }
    })).done(function(dataresult) {

      console.log("View data before callback: " + dataresult);
    });
  }


  function DeleteNoticia(id_noticia, elemento, nome){
    sclConfirm({
      message: "Realmente deseja excluir esta notícia: "+nome+"?",
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
          $.ajax({
            url: 'webservices/noticias/servico.php',
            type: 'POST',
            data: {acao:'DeleteNoticia',IdNoticia:id_noticia},
            success: function(resultado){
              if (resultado == 'ok'){

                mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
                '<button type=\"button\" class=\"close\" data-bs-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                'Notícia excluida com sucesso'+
                '</div>';

                $(elemento).closest('tr').remove();

              }else{
                mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
                '<button type=\"button\" class=\"close\" data-bs-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                'Não foi possível excluir a notícia, detalhes do error: ' + resultado +
                '</div>';
             }

             $('#mensagem_evento').append(mensagem);

           }

         });
        };
      }
    });
  }


  function GetDetalhesNoticia(){

    $.ajax({
      url: 'webservices/noticias/servico.php',
      type: 'POST',
      data: {acao:'GetNoticia',IdNoticia:GetURLParameter('id_noticia')},

      success: function(resultado){

        //console.log(resultado);

        var myarray = JSON.parse(resultado);

        $('input[name="id_noticia"]').val(myarray[0]['id_noticia']);

        $('input[name="titulo"]').val(myarray[0]['titulo']);

        $('textarea[name="subtitulo"]').text(myarray[0]['subtitulo']);

        $('#descricao').val(myarray[0]['descricao']);
        SCLEditor.replace('descricao');
          
        $('input[name="link"]').val(myarray[0]['link']);
          
        $('input[name="img"]').val(myarray[0]['img']);
          
        $('#previewimg').attr('src', "../"+myarray[0]['img']);
          
        myarray[0]['status'] == '1' ? $('#status').attr('checked', true) : $('#status').attr('checked', false);

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').addClass('form-check-input');
        getTags('id_tag', myarray[0]['id_noticia']);
      }
    });
  }

  function DeleteTag(id_tag){
    //console.log(id_tag);
    $.ajax({ url: 'webservices/noticias/servico.php', type: 'POST', data: {acao:'excluirTag', id_noticia:GetURLParameter('id_noticia'), id_tag:id_tag,}}).done(function(resultado){
      if(resultado == 1){
        mensagem = '<div class="alert alert-success alert-dismissible">'+
        '<button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">&times;</button>'+
        'Categoria excluida com sucesso'+
        '</div>';
        $('#mensagem_evento').html(mensagem);
      }
    });
  }



  function UpdateNoticia(){

    $('#descricao').val(SCLEditor.instances['descricao'].getData());
    var formData = new FormData(document.getElementById("newnoticia"));

    $.when($.ajax({
      url: 'webservices/noticias/servico.php',
      type: 'POST',
      data: formData,
      async: true,
      cache: false,
      contentType: false,
      processData: false,
      success: function(dataresult) {

        if (!isNaN(dataresult)){

          mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
          '<button type=\"button\" class=\"close\" data-bs-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
          'Notícia atualizada com sucesso'+
          '</div>';

          //GetDetalhesNoticia();
        }else{
                       
          mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
          '<button type=\"button\" class=\"close\" data-bs-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
          'Não foi possível atualizar a notícia! detalhes:'+ dataresult +  
          '</div>';
        }

        $('#mensagem_evento').append(mensagem);
      }
    })).done(function(dataresult) {

      console.log("View data before callback: " + dataresult);
    });
  }

</script>

<!-- DataTables -->



<!-- Select2 -->


<!-- CK Editor -->
