
<script>
  
  $(function(){

    switch ($('#modo').text()){

      case "consulta":
        listUsers();
      break;

      case "update":
        getUser();
      break;

      case "create":
      break;

	  }

    $('#nivel').select2({
      placeholder: "Selecione um nível de usuário"
      //allowClear: true
    });

    $('#id_loja').select2({
      placeholder: "Selecione a loja"
      //allowClear: true
    });

    var $getNivel = $("#nivel");
    $getNivel.on("change", function (e) {

      if($(this).val() == 1){
        $(".loja").fadeIn();
      }else{
        $(".loja").fadeOut();
      }

    });


  });

  function listUsers(){

    var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST', dataType : "json", data: {acao:'listUsers',nivel:GetURLParameter('nivel')}});
    request.done(function(response){

      //console.log(response);

      (Array.isArray(response) ? response : []).forEach(function (item) {
        var id = Number(item.id_usuario), row = $('<tr>');
        $('<td>').text(item.nome || '').appendTo(row);
        $('<td>').text(item.email || '').appendTo(row);
        var status = $('<span>').addClass('label ' + (item.status == 1 ? 'label-success' : 'label-danger')).text(item.status == 1 ? 'Habilitado' : 'Desabilitado').css('cursor', 'pointer');
        status.on('click', function () { alteraStatus(this, id); });
        $('<td>').append(status).appendTo(row);
        var actions = $('<td>').appendTo(row);
        $('<a>').addClass('btn btn-block btn-flat btn-primary btn-xs').attr('href', 'painel.php?exe=usuario/update&id_usuario=' + id).text('Editar').appendTo(actions);
        $('<button>').addClass('btn btn-block btn-flat btn-danger btn-xs').text('Excluir').on('click', function () { excluiAdministrador(this, id, sclAdminEscape(item.nome)); }).appendTo(actions);
        $('#data-list tbody').append(row);
      });

      $('#data-list').DataTable( {
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

  function alteraStatus(elemento, id_usuario){
    var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST', async: true,  data: { acao:"alteraStatus", id_usuario : id_usuario}});
    request.done(function (response){
      if(response == 1){
        $(elemento).removeClass('label-danger').addClass('label-success').html('habilitado');
      }else{
        $(elemento).removeClass('label-success').addClass('label-danger').html('desabilitado');
      }
    });
  }

  function excluiAdministrador(elemento, id_usuario, nome){

    bootbox.confirm({
      message: "Realmente deseja excluir o "+nome+"?",
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
          var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST', async: true,  data: { acao:"excluiUser", id_usuario : id_usuario}});
          request.done(function (response){
            if(response == 1){
              $(elemento).closest('tr').fadeOut();
              mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
                  '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                  'Usuário excluido com sucesso'+
                '</div>';

                $('#mensagem_evento').html(mensagem);
            }
          });
        }
      }
    });
  }

  function CreateUsuario() {

    //Mensagfem de erro padrão para UPDATE
    msg_erro = '<div class=\"alert alert-warning alert-dismissible\">'+
      '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
      'Preencha corretamente todos os campos'+
      '</div>';

    //Válida se formulário esta OK
    if ($('#valido').val() == 'true'){

      //Pega formulario
      f = document.getElementById("usuarioForm");

      if(f.id_loja != undefined && f.id_loja.value == "" && $(".loja").is(":visible")){
          $(f.id_loja).closest('.form-group').addClass('has-warning');
          $(f.id_loja).closest('.form-group').find(".msg-erro").html('Selecionar uma loja é obrigatório para cadastro de lojista');
          f.id_loja.focus();
        //Valida o nome
      }else{
        $('.form-group').removeClass('has-warning').removeClass('has-error').find('.msg-erro').html('');
        $("#valido").val(true);   

        if(f.nome.value == ""){
          $(f.nome).closest('.form-group').addClass('has-warning');
          $(f.nome).next().html('Insira um nome válido');
          f.nome.focus();
        }else{
          $('.form-group').removeClass('has-warning').removeClass('has-error').find('.msg-erro').html('');
          $("#valido").val(true);   

          //Valida o email
          if(f.email.value == "" || !validaEmail(f.email.value)){   
            $(f.email).closest('.form-group').addClass('has-warning').find('.msg-erro').html('Insira um e-mail válido');
            f.email.focus();
          }else{
            //Valida existência do email
            var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST',  data: { acao:"ValidarEmail", email:f.email.value}}); 
            request.done(function (response){

              if(response != 1){

                $("#"+f.email.name).closest('div').addClass("has-error");
                $(f.email).closest('.form-group').find('.msg-erro').html('Este dado não pode se repetir');
                $("#valido").val(false);

              }else{
                $('.form-group').removeClass('has-warning').removeClass('has-error').find('.msg-erro').html('');
                $("#valido").val(true);   

                //Valida o login
                if(f.usuario.value == ""){
                  $(f.usuario).closest('.form-group').addClass('has-warning').find('.msg-erro').html('Insira um login válido');
                  f.usuario.focus();
                }else{

                  //Valida existência do login
                  var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST',  data: { acao:"ValidarLogin", login:f.usuario.value}}); 
                  request.done(function (response){

                    if(response != 1){

                      $("#"+f.usuario.name).closest('div').addClass("has-error");
                      $(f.usuario).closest('.form-group').find('.msg-erro').html('Este dado não pode se repetir');
                      $("#valido").val(false);

                    }else{
                      

                      //Valida o nome
                      if(f.senha.value == "" || (f.senha.value.length < 12 || f.senha.value.length > 72)){
                        $(f.senha).closest('.form-group').addClass('has-warning');
                        $(f.senha).next().html('Insira uma senha válida de 12 a 72 caracteres');
                        f.senha.focus();
                      }else{

                        $('.form-group').removeClass('has-warning').removeClass('has-error').find('.msg-erro').html('');
                        $("#btnCreateUsuario").hide(); 

                        var formData = new FormData(document.getElementById("usuarioForm"));
                        formData.append("acao", "createUser");

                        var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST',  data: formData, async: true, cache: false, contentType: false, processData: false});
                        request.done(function ( response ) {
                          //console.info(response);
                          var mensagem = "";
                          if (response != '0'){
                            mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
                            '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                            'Usuario incluido com sucesso'+
                            '</div>';

                            $("#usuarioForm :input").prop("disabled", true);
                            window.setTimeout(function(){
                              window.location.href = "painel.php?exe=usuario/update&id_usuario=" + response;
                            }, 1000);

                          } else {
                            mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
                            '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                            'Não foi possível criar o usuario! :'+ response +
                            '</div>';
                          }

                          $('#mensagem_evento').html(mensagem);
                          window.location.href = "#";
                        });

                      //Fecha valida senha
                      }

                    //Fecha valida login
                    }
                  });
                }

              //Fecha valida email
              }
            });
          }

        //Fecha valida nome
        }

      //Fecha valida loja
      }
    }else{
      $('#mensagem_evento').html(msg_erro);
    }

  }

  function getUser(){

    if (GetURLParameter('id_usuario') == undefined) return;
    var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST', dataType : "json",  data: { acao:"getUser", id_usuario : GetURLParameter('id_usuario')} });

    request.done(function (res){

      var response = res;

      $('#foto-perfil').attr("src","../"+response['img']);
      $('input[name="nome"]').val(response['nome']);
      $('input[name="email"]').val(response['email']);
      $('input[name="usuario"]').val(response['usuario']);
      $('input[name="status"]').attr("checked", response['status'] == 1 ? true : false);
      $(iCheck);

    });

  }

  function UpdateUsuario() {

    //Mensagfem de erro padrão para UPDATE
    msg_erro = '<div class=\"alert alert-warning alert-dismissible\">'+
      '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
      'Preencha corretamente todos os campos'+
      '</div>';

    //Válida se formulário esta OK
    if ($('#valido').val() == 'true'){

      //Pega formulario
      f = document.getElementById("usuarioForm");

      if(f.id_loja != undefined && f.id_loja.value == "" && $(".loja").is(":visible")){
        $(f.id_loja).closest('.form-group').addClass('has-warning');
        $(f.id_loja).closest('.form-group').find(".msg-erro").html('Selecionar uma loja é obrigatório para cadastro de lojista');
        f.id_loja.focus();
        //Valida o nome
      }else{

        //Valida o nome
        if(f.nome.value == ""){
          $(f.nome).closest('.form-group').addClass('has-warning');
          $(f.nome).next().html('Insira um nome válido');
          f.nome.focus();
        }else{
          $('.form-group').removeClass('has-warning').removeClass('has-error').find('.msg-erro').html('');
          $("#valido").val(true);   

          //Valida o email
          if(f.email.value == "" || !validaEmail(f.email.value)){   
            $(f.email).closest('.form-group').addClass('has-warning').find('.msg-erro').html('Insira um e-mail válido');
            f.email.focus();
          }else{
            //Valida existência do email
            var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST',  data: { acao:"ValidarEmail", email:f.email.value, atual:GetURLParameter("id_usuario") }}); 
            request.done(function (response){

              if(response != 1){

                $("#"+f.email.name).closest('div').addClass("has-error");
                $(f.email).closest('.form-group').find('.msg-erro').html('Este dado não pode se repetir');
                $("#valido").val(false);

              }else{
                $('.form-group').removeClass('has-warning').removeClass('has-error').find('.msg-erro').html('');
                $("#valido").val(true);   

                //Valida o login
                if(f.usuario.value == ""){
                  $(f.usuario).closest('.form-group').addClass('has-warning').find('.msg-erro').html('Insira um login válido');
                  f.usuario.focus();
                }else{

                  //Valida existência do login
                  var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST',  data: { acao:"ValidarLogin", login:f.usuario.value, atual:GetURLParameter("id_usuario") }}); 
                  request.done(function (response){

                    if(response != 1){

                      $("#"+f.usuario.name).closest('div').addClass("has-error");
                      $(f.usuario).closest('.form-group').find('.msg-erro').html('Este dado não pode se repetir');
                      $("#valido").val(false);

                    }else{
                      
                      //Valida o nome
                      if(f.senha.value != "" && (f.senha.value.length < 12 || f.senha.value.length > 72)){
                        $(f.senha).closest('.form-group').addClass('has-warning');
                        $(f.senha).next().html('Insira uma senha válida de 12 a 72 caracteres');
                        f.senha.focus();
                      }else{

                        $('.form-group').removeClass('has-warning').removeClass('has-error').find('.msg-erro').html('');
                        $("#valido").val(true); 

                        if($("#nivel_atual").val() != $("#nivel").val() && $("#nivel").val() != undefined){

                          bootbox.confirm({
                            message: "Realmente deseja alterar o nível de acesso deste usuário?",
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
                                
                                var formData = new FormData(document.getElementById("usuarioForm"));
                                formData.append("acao", "updateUser");
                                formData.append("id_usuario", GetURLParameter('id_usuario'));

                                var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST',  data: formData, async: true, cache: false, contentType: false, processData: false});
                                request.done(function ( response ) {
                                  
                                  //console.log(response);

                                  var mensagem = "";
                                  if (response == 1){
                                    mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
                                      '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                                      'Usuário atualizado com sucesso'+
                                      '</div>';

                                    /*
                                    window.setTimeout(function(){
                                      window.location.href = "";
                                    }, 1000);
                                    */

                                  }else{
                                    mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
                                      '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                                      'Não foi possível atualizar o usuário! :'+ response +
                                      '</div>';
                                  }

                                  $('#mensagem_evento').html(mensagem);
                                  window.location.href = "#";

                                });

                              }else{

                              }

                            }
                          });

                        }else{

                          var formData = new FormData(document.getElementById("usuarioForm"));
                          formData.append("acao", "updateUser");
                          formData.append("id_usuario", GetURLParameter('id_usuario'));

                          var request = $.ajax({ url: 'webservices/usuario/servico.php', type: 'POST',  data: formData, async: true, cache: false, contentType: false, processData: false});
                          request.done(function ( response ) {
                            
                            //console.log(response);

                            var mensagem = "";
                            if (response == 1){
                              mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
                                '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                                'Usuário atualizado com sucesso'+
                                '</div>';
                              /*
                              window.setTimeout(function(){
                                window.location.href = "";
                              }, 1000);
                              */

                            }else{
                              mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
                                '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
                                'Não foi possível atualizar o usuário! :'+ response +
                                '</div>';
                            }

                            $('#mensagem_evento').html(mensagem);
                            window.location.href = "#";

                          });

                        }

                      ////Fecha valida senha
                      }

                    //Fecha valida login
                    }
                  });
                }

              //Fecha valida email
              }
            });
          }

        //Fecha valida nome
        }

      //Fecha valida loja
      }

    }else{
      $('#mensagem_evento').html(msg_erro);
    }

  }

  
  function ValidateLogin(input, atual){
    ConsumeServiceValidate(input, { acao:"ValidarLogin", login:input.value, atual:atual }, "usuario");
  }

  function ValidateEmail(input, atual){
    ConsumeServiceValidate(input, { acao:"ValidarEmail", email:input.value, atual:atual }, "usuario");
  }

  function ValidateRG(input, atual){
    ConsumeServiceValidate(input, { acao:"ValidarRG", rg:input.value, atual:atual }, "usuario");
  }
    
  function ValidateCPF(input, atual){
    ConsumeServiceValidate(input, { acao:"ValidarCPF", cpf:input.value, atual:atual }, "usuario");
  }

  function verificaSenha(input){
    var length = new TextEncoder().encode(input.value).length;
    var valid = length >= 12 && length <= 72;
    $(input).closest('.form-group').toggleClass('has-warning', !valid).toggleClass('has-success', valid).find('.msg-erro').text(valid ? '' : 'Use uma senha de 12 a 72 bytes (acentos ocupam mais de um byte).');
  }

  function limpaMSGSenha(input){
    if($(input).val().length >= 12){
      $(input).closest('.form-group').find('.msg-erro').html('');
    }
  }
    
    
</script>
<script src='../resources/js/util.js'></script>
<script src='../resources/plugins/input-mask/jquery.inputmask.js'></script>
<script src='../resources/plugins/input-mask/jquery.inputmask.date.extensions.js'></script>