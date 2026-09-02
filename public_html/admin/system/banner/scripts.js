<!-- page script -->
<script>

//OnLoad
$(function () {
      
  $('.selectSite').select2();
          
  //Delega event ao select para quando for excluir itemss
  $('.selectSite').on("select2:unselect", function(e){
            
    //trago as propriedades do obj
    var args = JSON.stringify(e.params, function (key, value) {
      if (value && value.nodeName) return "[DOM node]";
      if (value instanceof $.Event) return "[$.Event]";
        return value;
    });
            
    //json to array
    var obj = $.parseJSON(args);
            
    if ($('#modo').text() != 'crear'){
      //Delete do banco de dados
      console.log( obj['data']['id'] , obj['data']['text'] );  
    }
  });
          
  $( 'h1' ).appendTo( $( '.content-header' ) );

  switch ($('#modo').text()){
    case "consulta":
       getListBanner();
    break;
    case "editar":
      GetDetalhesBanner();
    break;
  }

  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
  });

});
    

function PreviewImg(input){
			
  if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#previewimg').attr('src', e.target.result);
		};			
		reader.readAsDataURL(input.files[0]);
	}
}
  
function getListBanner(){

  $.ajax({
    url: 'webservices/banner/servico.php',
    type: 'POST',
    data: {acao:'getListBanner'},
    success: function(resultado){
      
      console.log(resultado);

      var myarray = $.parseJSON(resultado);

      for (i=0; i <= myarray.length - 1; i++) {

        $('#data-list > tbody:last-child').append('<tr>'+
          '<td><img src="../'+myarray[i]['img']+'" alt="" width="300"></td>'+
          '<td>'+myarray[i]['titulo']+'</td>'+ 
          '<td>'+(myarray[i]['status'] == '1' ? '<span class="label label-success">Habilitado</span>' : '<span class="label label-danger">Desabilitado</span>')+'</td>'+
          '<td>'+
            "<a href='painel.php?exe=banner/update&id_banner="+myarray[i]['id_banner']+"' class='btn btn-block btn-flat btn-primary btn-xs'>Editar</a>" +
            "<a class='btn btn-block btn-flat btn-danger btn-xs' onCLick='DeleteBanner("+myarray[i]['id_banner']+",this)'>Excluir</a>" +
          '</td></tr>');

      }
            
      $('#data-list').removeClass( 'display' ).addClass('table table-striped table-bordered');

      $('#data-list').DataTable( {
        "sDom":'fptip',
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

function CreateBanner() {
     
  var formData = new FormData(document.getElementById("newbanner"));
     
  $.when($.ajax({
    url: 'webservices/banner/servico.php',
    type: 'POST',
    data: formData,
    async: true,
    cache: false,
    contentType: false,
    processData: false,
    success: function(dataresult) {
        
      if (!isNaN(dataresult)){

        mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
          '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
          'Banner incluido com sucesso'+
          '</div>';

        $("#newparceiro :input").prop("disabled", true);

        window.setTimeout(function(){
          window.location.href = "painel.php?exe=banner/update&id_banner=" + dataresult;
        }, 5000);

      }else{
        mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
          '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
          'Não foi possível adionar o Banner! detalhes:'+ dataresult +  
          '</div>';
      }

      $('#mensagem_evento').append(mensagem);
    }
  })).done(function(dataresult) {
    console.log("View data before callback: " + dataresult);   
  });
};
   
   
  function DeleteBanner(idparceiro, elemento){

    $.ajax({
      url: 'webservices/banner/servico.php',
      type: 'POST',
      data: {acao:'DeleteBanner',IdBanner:idparceiro},
      success: function(resultado){
        console.log(resultado);

        if (resultado == 'ok'){

          mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
            '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
            'Banner excluido com sucesso'+
            '</div>';

          $(elemento).closest('tr').remove();

        }else{
          
          mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
            '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
            'Não foi possível excluir o banner, detalhes do error: ' + resultado +
            '</div>';
        }

        $('#mensagem_evento').append(mensagem);

      }

    });
  }


  function GetDetalhesBanner(){

    $.ajax({
      url: 'webservices/banner/servico.php',
      type: 'POST',
      data: {acao:'GetBanner',IdBanner:GetURLParameter('id_banner')},
      success: function(resultado){

        console.log(resultado);
               
        var myarray = $.parseJSON(resultado);

        $('input[name="id_banner"]').val(myarray[0]['id_banner']);
        $('input[name="titulo"]').val(myarray[0]['titulo']);
        $('input[name="link"]').val(myarray[0]['link']);
        $('input[name="img"]').val(myarray[0]['img']);
		    $('#previewimg').attr('src', '../'+myarray[0]['img']);
          
        $(function () { 
          $(".selectSite").val(myarray[0]['sites']).trigger("change"); 
        });
          
        myarray[0]['status'] == '1' ? $('#status').attr('checked', true) : $('#status').attr('checked', false);

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

      }

    });

  }
      
      
  function UpdateBanner(){
      
    var formData = new FormData(document.getElementById("newbanner"));

    $.when($.ajax({
      url: 'webservices/banner/servico.php',
      type: 'POST',
      data: formData,
      async: true,
      cache: false,
      contentType: false,
      processData: false,
      success: function(dataresult) {
        
        if (!isNaN(dataresult)){

          mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
            '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
            'Banner atualizado com sucesso'+
            '</div>';
                       
            GetDetalhesBanner();
               
        }else{
          
          mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
            '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
            'Não foi possível atualizar o banner! detalhes:'+ dataresult +  
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
<script src='../resources/plugins/datatables/jquery.dataTables.min.js'></script>
<script src='../resources/plugins/datatables/dataTables.bootstrap.min.js'></script>
<!-- Select2 -->
<script src='../resources/plugins/select2/select2.full.min.js'></script>