<script>
  // Navbar
  function openNav() {
    document.getElementById("mySidenav").style.width = "100%";
    document.getElementById("mySidenav2").style.width = "515px";
  }

  function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("mySidenav2").style.width = "0";
  }
  // End | Navbar

  function debounce(fn, milissegundos) {
 
    let timer = 0;
   
    return () => {
       
      clearTimeout(timer);
      timer = setTimeout(fn, milissegundos);
    }
  }

  $(document).on("click", ".video-youtube", function () {

  	if($(this).data('video') != undefined) 
      video = $(this).data('video');

    console.log(video);
    $("#video-youtube-url").prop("src", video);
  });

  $("#tipo_anuncio").change(function() {
    getFabricante();
  });

  function getFabricante(){

    var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {tipo_anuncio:$('#tipo_anuncio option:selected').val(), acao:'getFabricantes'}});
    request.done(function(response){

     $("#fabricante").html("<option value=''>Selecione o fabricante</option>");
     $("#modelo").html("<option value=''>Selecione um fabricante primeiramente</option>");
     $(JSON.parse(response)).each(function(i, f){
  	        //(i == 0 ? $("#fabricante").html("<option>Selecione o fabricante</option>") : false);
  	        $("#fabricante").append("<option value='"+f.codigo_marca+"'>"+f.marca+"</option>");
         });
   });
  }

  $("#fabricante").change(function() {
    getModelo();
  });

  function getModelo(){

  	var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {fabricante:$('#fabricante option:selected').val(), acao:'getModelos'}});
    request.done(function(response){

      $("#modelo").html("<option value=''>Selecione o modelo</option>");
      $(JSON.parse(response)).each(function(i, m){
            //(i == 0 ? $("#modelo").html("<option>Selecione o modelo</option>") : false);
            $("#modelo").append("<option value='"+m.id_grupo+"'>"+m.grupo+"</option>");
          });
    });
  }

  if(GetURLParameter("tipo") != "" && GetURLParameter("tipo") != undefined){

    if(GetURLParameter("fabricante") != "")
      getFabricanteURL();
    else
      getFabricante();
  }

  function getFabricanteURL(){

    var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {tipo_anuncio:$('#tipo_anuncio option:selected').val(), acao:'getFabricantes'}});
    request.done(function(response){

      $("#fabricante").html("<option value=''>Selecione o fabricante</option>");
      $("#modelo").html("<option value=''>Selecione um fabricante primeiramente</option>");
      $(JSON.parse(response)).each(function(i, f){
            //(i == 0 ? $("#fabricante").html("<option>Selecione o fabricante</option>") : false);
            $("#fabricante").append("<option value='"+f.codigo_marca+"' "+(GetURLParameter("fabricante") == f.codigo_marca ? "selected" : "" )+">"+f.marca+"</option>");
          });

      if(GetURLParameter("fabricante") != ""){

        if(GetURLParameter("modelo") != "")
          getModeloURL();
        else
          getModelo();
      }

    });
  }

  function getModeloURL(){

    var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {fabricante:$('#fabricante option:selected').val(), acao:'getModelos'}});
    request.done(function(response){

      $("#modelo").html("<option value=''>Selecione o modelo</option>");
      $(JSON.parse(response)).each(function(i, m){
        //(i == 0 ? $("#modelo").html("<option>Selecione o modelo</option>") : false);
        $("#modelo").append("<option value='"+m.id_grupo+"' "+(GetURLParameter("modelo") == m.id_grupo ? "selected" : "" )+">"+m.grupo+"</option>");
      });
    });
  }

  function getEnquete(enquete){

    $(".enquete-atual").hide();

    var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {enquete:enquete, acao:'getEnquete'}});
    request.done(function(response){

      if(response != 0){
        $("#enquete-opcoes").html(response);
        $(".enquete-atual").fadeIn();
      }
    });
  }

  function votar(enquete){

    if($('input[name=opcao]:checked', '#enquete-form').val() != undefined){
      var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {enquete:enquete, opcao:$('input[name=opcao]:checked', '#enquete-form').val(), acao:'votar'}});
      request.done(function(response){
        $("#enquete-opcoes").html(response);
      });
    }else{

    }

  }

  function getTelefone(elemento){

    $(elemento).find(".texto").hide();
    $(elemento).find(".telefone_celular").show();
  }

  $('#enviar_amigo_btn').click(function(e){

    //e.preventDefault();
    $('a[href="#enviar_amigo"]').closest('ul').find('li').removeClass('active');
    $('a[href="#enviar_amigo"]').closest('li').addClass('active');

    $('#veiculo-detalhes-tabs').find('.tab-pane').removeClass('active');
    $('#enviar_amigo').addClass('active');
  })

  $('#financiamento_btn').click(function(e){

    //e.preventDefault();
    $('a[href="#financiamento"]').closest('ul').find('li').removeClass('active');
    $('a[href="#financiamento"]').closest('li').addClass('active');

    $('#veiculo-detalhes-tabs').find('.tab-pane').removeClass('active');
    $('#financiamento').addClass('active');
  })

  $("#form_news").on('submit', function (e) {

    e.preventDefault();

    $(".form-group").removeClass("has-warning").find(".msg-erro").fadeOut().text("");

    if($('#newsletter').val() == "" || !validaEmail($('#newsletter').val())){

      console.log("teste");
      $('#newsletter').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um e-mail válido");
    }else{

      $(this).off('submit').submit();  
    }
  });

  function informaErro(){

    if($("#erro_anuncio_mensagem").val() != "" && $("#erro_anuncio_mensagem").val().length > 20){

      $("#erro_anuncio_mensagem").closest(".modal-body").find("label").html("");

      console.log(window.location);

      var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {erro:$("#erro_anuncio_mensagem").val(), anuncio: <?php echo (isset($r_DIR['anuncio']['id_anuncio']) ? $r_DIR['anuncio']['id_anuncio'] : "0"); ?>, acao:'informaErro'}});
      request.done(function(response){

        console.log(response);
        
        if(response > 0){
          $("#erro_anuncio_mensagem").closest(".modal-body").find("label").html("Erro informado com sucesso").css("color", "#2e9421");
          setTimeout(function(){
            $("#erro_anuncio_mensagem").closest(".modal-body").find("label").html("");
            $("#informaErro").modal('hide');
          }, 2000);
        }else{
          $("#erro_anuncio_mensagem").closest(".modal-body").find("label").html("Não foi possível informar o erro no anúncio").css("color", "#e31412");
        }

      });

    }else{
      $("#erro_anuncio_mensagem").closest(".modal-body").find("label").html("Nos comunique um erro válido").css("color", "#e31412");
    }
  }

  $(document).on('hide.bs.modal','#informaErro', function () {
    $("#erro_anuncio_mensagem").closest(".modal-body").find("label").html("");
    $("#erro_anuncio_mensagem").val("");
  });

  function addFavoritos(elemento, anuncio){
    var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {anuncio: anuncio, acao:'addFavoritos'}});
    request.done(function(response){

      console.log(response);

      if(response > 0){
        $(`<a class='iconsNome' style='cursor: pointer' onClick='removeFavoritos(this, ${anuncio})'><i class='glyphicon glyphicon-star'></i>Remover favorito</a>`).replaceAll(elemento);
      }

      if(response == ""){
        $("#favorito-alert").text("Você precisa estar logado para adicionar o veiculo aos seus favoritos");
      }

    });
  }

  function removeFavoritos(elemento, anuncio){
    var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {anuncio: anuncio, acao:'removeFavoritos'}});
    request.done(function(response){

      console.log(response);
      
      if(response > 0){
        $(`<a class='iconsNome' style='cursor: pointer' onClick='addFavoritos(this, ${anuncio})'><i class='glyphicon glyphicon-bookmark'></i>Add aos favoritos</a>`).replaceAll(elemento);
        $("#favorito-alert").text("");
      }
    });
  }

  function removeFavorito(elemento, anuncio){
    var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {anuncio: anuncio, acao:'removeFavoritos'}});
    request.done(function(response){

      console.log(response);
      
      if(response > 0){
        $(elemento).closest("tr").remove();
      }
    });
  }

  function imprimir(){
    console.log("Impressão");
  }

  <?php if(isset($r_DIR['page']) && $r_DIR['page'] == 'quero-vender'){ ?>

    var $getUnidade = $('#unidade');
    $getUnidade.on("change", function (e) {
      getLojas();
    });

    var $getTipoAnuncio = $('#tipo_anuncio');
    $getTipoAnuncio.on("change", function (e) {

      limpaCampos();

      if($('#tipo_anuncio').val() == 'Veículo'){

        getVeiculoForm();
        getFabricante();
      }else if($('#tipo_anuncio').val() == 'Motocicleta'){

        getMotocicletaForm();
        getFabricante();
      }else if($('#tipo_anuncio').val() == 'Utilitário'){

        getUtilitarioForm();
        getFabricante();
      }

    }); 

    var $getTipoPessoa = $('#tipo_pessoa');
    $getTipoPessoa.on("change", function (e) {

      $('#pessoa_fisica').find("input").val("");
      $('#pessoa_juridica').find("input").val("");

      if($('#tipo_pessoa').val() == "cpf"){

        $('#pessoa_juridica').hide();
        $('#pessoa_fisica').fadeIn();
      }else{

        $('#pessoa_fisica').hide();
        $('#pessoa_juridica').fadeIn();
      }
    });

    function getLojas(){

      var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {unidade:$('#unidade').val(), acao:'getLojas'}});
      request.done(function(response){

        $("#id_loja").html("<option value=''></option>");
        $(JSON.parse(response)).each(function(i, l){
          (i == 0 ? $("#id_loja").html("<option>Selecione a loja</option>") : false);
          $("#id_loja").append("<option value='"+l.id_loja+"'>"+l.nome+"</option>");
        });
      });
    }

    function getFabricante(){

      var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {tipo_anuncio:$('#tipo_anuncio').val(), acao:'getFabricantes'}});
      request.done(function(response){

        $("#fabricante").html("<option value=''>Selecione o fabricante</option>");
        $(JSON.parse(response)).each(function(i, f){
            //(i == 0 ? $("#fabricante").html("<option>Selecione o fabricante</option>") : false);
            $("#fabricante").append("<option value='"+f.codigo_marca+"'>"+f.marca+"</option>");
          });
      });
    }

    var $getFabricante = $('#fabricante');
    $getFabricante.on("change", function (e) {

      $("#modelo").html("<option value=''>Selecione o modelo</option>");

      $("#ano_fabricacao").html("<option value=''>Selecione o modelo</option>");

      $("#ano_modelo").html("<option value=''>Selecione o ano do modelo</option>");

      $("#versao").html("<option value=''>Selecione a versão</option>");
      
      var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {fabricante:$('#fabricante').val(), acao:'getModelos'}});
      request.done(function(response){

        $("#modelo").html("<option value=''>Selecione o modelo</option>");
        $(JSON.parse(response)).each(function(i, m){
          //(i == 0 ? $("#modelo").html("<option>Selecione o modelo</option>") : false);
          $("#modelo").append("<option value='"+m.id_grupo+"'>"+m.grupo+"</option>");
        });
      });
      

    });

    var $getModelo = $('#modelo');
    $getModelo.on("change", function (e) {

      var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {modelo:$('#modelo').val(), acao:'getVersoes'}});
      request.done(function(response){

        //console.log(response);

        if(response == 0){

          $("#ano_modelo").html("<option value=''></option>");

        }else{

          $("#versao").html("<option value=''>Selecione o modelo</option>");
          $(JSON.parse(response)).each(function(i, v){
            //(i == 0 ? $("#modelo").html("<option>Selecione o modelo</option>") : false);
            $("#versao").append("<option value='"+v.codigo_fipe+"'>"+v.modelo+"</option>");
          });

          $("#ano_fabricacao").html("<option value=''>Selecione o modelo</option>");

          $("#ano_modelo").html("<option value=''>Selecione o ano do modelo</option>");
        }
      });

      
      var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {modelo:$('#modelo').val(), acao:'getAnoFabricacao'}});
      request.done(function(response){

        //console.log(response);

        $("#ano_fabricacao").html("<option value=''>Selecione o ano de fabricação</option>");
        $(JSON.parse(response)).each(function(i, af){
          //(i == 0 ? $("#ano_fabricacao").html("<option>Selecione o ano</option>") : false);
          $("#ano_fabricacao").append("<option value='"+af.id_ano+"'>"+(af.ano == 3200 ? "O km" : af.ano )+"</option>");
        });
      });
      
    });

    var $getAnoFabricacao = $('#ano_fabricacao');
    $getAnoFabricacao.on("change", function (e) {

      var request = $.ajax({ url: 'api/servico.php', type: 'POST', data: {ano:$('#ano_fabricacao').val(), acao:'getValorFipe'}});
      request.done(function(response){

        //console.log(response);
      });

      ano = new Date();
      ano = ano.getFullYear();

      $("#ano_modelo").html("<option value=''>Selecione o ano do modelo</option>");
      $("#ano_modelo").append(`<option value='${($("#ano_fabricacao option:selected").text() == 'O km' ? parseInt(ano) + 1 : parseInt($("#ano_fabricacao option:selected").text()) )}'>${($("#ano_fabricacao option:selected").text() == 'O km' ? parseInt(ano) + 1 : parseInt($("#ano_fabricacao option:selected").text()) )}</option>`);
      $("#ano_modelo").append(`<option value='${($("#ano_fabricacao option:selected").text() == 'O km' ? parseInt(ano) : parseInt($("#ano_fabricacao option:selected").text()) + 1)}'>${($("#ano_fabricacao option:selected").text() == 'O km' ? parseInt(ano) : parseInt($("#ano_fabricacao option:selected").text()) + 1)}</option>`);
    });

    function getVeiculoForm(){

      $("#formMotocicleta").hide();
      $("#formUtilitario").hide();
      $("#formVeiculo").fadeIn();
    }

    function getMotocicletaForm(){

      $("#formUtilitario").hide();
      $("#formVeiculo").hide();
      $("#formMotocicleta").fadeIn();
    }

    function getUtilitarioForm(){

      $("#formMotocicleta").hide();
      $("#formVeiculo").hide();
      $("#formUtilitario").fadeIn();
    }

    function limpaCampos(){

      $("#fabricante").html("<option value=''>Selecione o fabricante</option>");

      $("#modelo").html("<option value=''>Selecione o modelo</option>");

      $("#versao").html("<option value=''>Selecione a versão</option>");

      $("#ano_fabricacao").html("<option value=''>Selecione o modelo</option>");

    }

    function validaPessoa(){

      if($("#tipo_pessoa").val() == 'cpf'){
        if(ValidarCPF($("#cpf"))){
          return true;
        }
      }else{
        if(validarCNPJ($("#cnpj").val())){
          return true;
        }
      }

      return false;
    }

    function setError(elemento, tipo, text){

      $(elemento).closest('.form-group').addClass((tipo == 1 ? "has-warning" : "has-error" )).find('.msg-erro').text(text).closest('.form-group').find('input, select').focus();
    }

    function getInput(element){

      array = "{'"+$(element).attr('id')+"':'"+$(element).val()+"'}";
      return $.parseJSON('['+array+']');
    }

    function getProprietario(){
    //console.log("Pega proprietário");
  }

  function textoAleatorio(tamanho){
    var letras = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
    var aleatorio = '';
    for (var i = 0; i < tamanho; i++) {
      var rnum = Math.floor(Math.random() * letras.length);
      aleatorio += letras.substring(rnum, rnum + 1);
    }
    return aleatorio;
  }

  function formatBytes(bytes){
    var kb = 1024;
    var ndx = Math.floor( Math.log(bytes) / Math.log(kb) );
    var fileSizeTypes = ["bytes", "kb", "mb", "gb", "tb", "pb", "eb", "zb", "yb"];

    return {
      size: +(bytes / kb / kb).toFixed(2),
      type: fileSizeTypes[ndx]
    };
  }

  function makeFileList(input, acao) {

    if(input.files[0] != null){
      //console.log(input.files);
      var tr = document.createElement("tr");
      tr.className = "imagem-anuncio";
      var tamanho = formatBytes(input.files[0].size);

      fileName = textoAleatorio(5);

      var reader = new FileReader();
      reader.onload = function (e) {
        $('.image-'+fileName).attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);

      tr.innerHTML = "<td align='center'>"+
      "<input name='ordem[]' type='hidden' value='"+input.files[0].name+"'>"+
      "<div class='imagem'><img src='' style='width:150px' class='image-"+fileName+"'></div>"+
      "<br><div class='nome'>"+input.files[0].name+"</div>"+
      "<br><div class='tamanho'><b>tamanho: </b>"+tamanho['size']+" "+tamanho['type']+"</div>"+
      "</td>"+
      "<td><br><br><br><br><a class='btn btn-block btn-flat btn-danger btn-xs' onclick='removeArquivo(this)'>Remover</a></td>";
      $("#tabela-imagens tbody").append(tr);
      //tamanhoTotal += input.files[0].size;

      $(input).hide();
      $("#input").append("<input type='file' name='arquivo[]' class='arquivo-oferta upload input-image' onChange='makeFileList(this)' accept='image/x-png, image/gif, image/jpeg'/>");
    }

    $('.imagem-destaque').on('click',function(e){

      var option = $(this);
      $('.imagem-destaque').prop('checked','');
      option.prop('checked','checked'); 
    });

  }

  function removeArquivo(elemento){

    input = document.getElementsByClassName("arquivo-oferta");
    arquivo = $(elemento).closest(".imagem-anuncio").find('.nome').text();

    for (var i = 0; i < input.length; i++) {
      if(input[i].files[0] != null && input[i].files[0].name == arquivo){

        $(input[i]).remove();
        $(elemento).closest(".imagem-anuncio").remove();
      }
    }
  }

  function validaCampos(){

    if($("#tipo_pessoa").val() != ""){

      if(validaPessoa()){

        getProprietario();

        if($("#tipo_anuncio").val() != ""){

          if($("#estado").val() != ""){

            if($("#fabricante").val() != ""){

              if($("#modelo").val() != ""){

                if($("#ano_fabricacao").val() != ""){

                  if($("#ano_modelo").val() != ""){

                    return true;
                  }else{
                    setError($("#ano_modelo"), 1, `Insira o ano do modelo`);
                  }
                }else{
                  setError($("#ano_fabricacao"), 1, `Selecione o ano de fabricação`);
                }
              }else{
                setError($("#modelo"), 1, `Selecione um modelo`);
              }
            }else{
              setError($("#fabricante"), 1, `Selecione uma fabricante`);
            }
          }else{
            setError($("#estado"), 1, `Selecione o estado que o item do anúncio se encontra`);
          }
        }else{
          setError($("#tipo_anuncio"), 1, "Selecione um tipo de anúncio");
        }

      }else{

        $("#"+$("#tipo_pessoa").val()).closest('.form-group').addClass("has-error").fadeIn().find('.msg-erro').text(`Insira um ${$("#tipo_pessoa").val()} válido`).closest('.form-group').find('input').focus();
      }
    }else{
      setError($("#tipo_pessoa"), 1, "Selecione o tipo de pessoa");
    }

    return false;
  }


  //function createAnuncio(elemento){
  $("#proposta-venda").on('submit', function (e) {

    e.preventDefault();
    sendForm();
    /*
    debounce(sendForm, 1000);
    */
  });

  function sendForm(){

    $('.form-group').removeClass("has-error").removeClass("has-warning").find(".msg-erro").text("");

    if(validaCampos()){

      //$('#descricao_oferta').val(CKEDITOR.instances['descricao_oferta'].getData());
      var formData = new FormData(document.getElementById("proposta-venda"));
      formData.append("acao", "enviarProposta");
      formData.append("receptor", <?php echo (isset($r_DIR['site']['id_loja']) ? "L-".$r_DIR['site']['id_loja'] : (isset($r_DIR['site']['id_unidade']) ? "U-".$r_DIR['site']['id_unidade'] : 0)); ?>);

      var request = $.ajax({ url: 'api/servico.php', type: 'POST',  data: formData, async: true, cache: false, contentType: false, processData: false});
      request.done(function(response){

        if(response > 0){

          mensagem = '<div class=\"alert alert-success alert-dismissible\">'+
          '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
          'Proposta enviada com sucesso'+
          '</div>';

          window.setTimeout(function(){
            window.location.href = '<?php echo str_replace("ASC/ASC/", "ASC/", str_replace(":/", "://", str_replace("//", "/", ROOT.$_SERVER['REQUEST_URI']))); ?>#mensagem_evento';
          }, 1000);

          window.setTimeout(function(){
            window.location.href = '<?php echo ROOT."meus-anuncios"; ?>#mensagem_evento';
          }, 5000);

        }else{

          mensagem = '<div class=\"alert alert-warning alert-dismissible\">'+
          '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>'+
          'Erro ao enviar proposta'+
          '</div>';
        }
        $('#mensagem_evento').html(mensagem);
      });
    }
  }

  <?php } ?>

  function validaDados(){

    d = document.getElementById('contato-form');

    telefone = d.telefone.value;
    for(var i = 1; i <= d.telefone.value.length; i++){
      telefone = telefone.replace("_", "");
    }

    if(d.nome.value == ""){
      $(d.nome).closest('.form-group').addClass('has-warning');
      $(d.nome).next().html('Insira um nome válido');
      d.nome.focus();
    }else if(d.email.value == "" || !validaEmail(d.email.value)){
      $(d.email).closest('.form-group').addClass('has-warning');
      $(d.email).next().html('Insira um e-mail válido');
      d.email.focus();
    }else if(telefone == "" || telefone.length < 14){
      $(d.telefone).closest('.form-group').addClass('has-warning');
      $(d.telefone).next().html('Insira um telefone/celular válido');
      d.telefone.focus();
    }else if(d.mensagem.value == "" || d.mensagem.value.length < 30){
      $(d.mensagem).closest('.form-group').addClass('has-warning');
      $(d.mensagem).next().html('Insira uma mensagem válida');
      d.mensagem.focus();
    }else{
      d.submit();
    }
    
  }

  var $getTipoPessoa = $('#tipoPessoa');
  $getTipoPessoa.on("change", function (e) {

      $('#cnpj_user').val("");
      $('#cpf_user').val("");

      if($('#tipoPessoa').val() == "cpf"){

        $('#input-cnpj').hide();
        $('#input-cpf').show();
        if($("#input-senha:hidden")){
          $("#input-senha").show();
        }
      }else{
        $('#input-cpf').hide();
        $('#input-cnpj').show();
        if($("#input-senha:hidden")){
          $("#input-senha").show();
        }
      }

      $("#linkAS").hide();
      $("#user_recupera_senha").hide();

      $("#linkRS").fadeIn();
      $("#user_entrar").fadeIn();
    });

    $("#user_recupera_senha").fadeOut();
    $("#user_entrar").fadeOut();
    $("#linkAS").fadeOut();
    $("#linkRS").fadeOut();
    $("#msg_recupera_senha").fadeOut();

    function recuperaSenha(elemento){
      $(elemento).parent().hide();
      $("#input-senha").hide();
      $("#user_entrar").hide();
      $("#user_recupera_senha").fadeIn();
      $("#linkAS").fadeIn();
    }

    function btnLogin(elemento){
      $(elemento).parent().hide();
      $("#msg_recupera_senha").hide();
      $("#input-senha").fadeIn();
      $("#user_recupera_senha").hide();
      $("#user_entrar").fadeIn();
      $("#linkRS").fadeIn();
      $("#msg_recupera_senha").fadeOut();
    }

    function realizaLogin(){

      if(!ValidarCPF($("#cpf_user")) && $('#tipoPessoa').val() == 'cpf'){

        $("#cpf_user").focus();
          $("#cpf_user").closest(".form-group").addClass('has-error');
          $("#cpf_user").closest(".form-group").find('.msg-erro').html("CPF INVÁLIDO OU NÃO CONSTA EM NOSSO SISTEMA").fadeIn();

      }else if(!validarCNPJ($("#cnpj_user").val()) && $('#tipoPessoa').val() == 'cnpj'){

        $("#cpf_user").closest(".form-group").removeClass('has-error');
          $("#cpf_user").closest(".form-group").find('.msg-erro').html("").fadeOut();

        $("#cnpj_user").focus();
          $("#cnpj_user").closest(".form-group").addClass('has-error');
          $("#cnpj_user").closest(".form-group").find('.msg-erro').html("CNPJ INVÁLIDO OU NÃO CONSTA EM NOSSO SISTEMA").fadeIn();

      }else if($("#senha_user").val() == ""){

        $("#cnpj_user").closest(".form-group").removeClass('has-error');
          $("#cnpj_user").closest(".form-group").find('.msg-erro').html("").fadeOut();

          $("#cpf_user").closest(".form-group").removeClass('has-error');
          $("#cpf_user").closest(".form-group").find('.msg-erro').html("").fadeOut();

        $("#senha_user").focus();
          $("#senha_user").closest(".form-group").addClass('has-error');
          $("#senha_user").closest(".form-group").find('.msg-erro').html("INSIRA UMA SENHA VÁLIDA").fadeIn();

      }else{

        $("#cnpj_user").closest(".form-group").removeClass('has-error');
          $("#cnpj_user").closest(".form-group").find('.msg-erro').html("").fadeOut();

          $("#cpf_user").closest(".form-group").removeClass('has-error');
          $("#cpf_user").closest(".form-group").find('.msg-erro').html("").fadeOut();

          $("#senha_user").closest(".form-group").removeClass('has-error');
          $("#senha_user").closest(".form-group").find('.msg-erro').html("").fadeOut();

          if($("#cpf_user").val() == ""){
            user = $("#cnpj_user").val();
          }else{
            user = $("#cpf_user").val();
          }

          var request = $.ajax({ url: 'api/checkUser.php', type: 'POST', data: {user: user, password:$("#senha_user").val()}, async: true });
        request.done(function(r){
          if(r == 0){
            $("#senha_user").focus();
              $("#senha_user").closest(".form-group").addClass('has-error');
              $("#senha_user").closest(".form-group").find('.msg-erro').html("DADOS DE ACESSO ESTÃO INCORRETOS OU NÃO CONSTAM NO SISTEMA").fadeIn();
          }else{
             window.location.href = r;
          }
        });

      }
    }

    function realizaRecuperacaoSenha(){

      if(!ValidarCPF($("#cpf_user")) && $('#tipoPessoa').val() == 'cpf'){

        $("#cpf_user").focus();
          $("#cpf_user").closest(".form-group").addClass('has-error');
          $("#cpf_user").closest(".form-group").find('.msg-erro').html("CPF INVÁLIDO OU NÃO CONSTA EM NOSSO SISTEMA").fadeIn();

      }else if(!validarCNPJ($("#cnpj_user").val()) && $('#tipoPessoa').val() == 'cnpj'){

        $("#cpf_user").closest(".form-group").removeClass('has-error');
          $("#cpf_user").closest(".form-group").find('.msg-erro').html("").fadeOut();

        $("#cnpj_user").focus();
          $("#cnpj_user").closest(".form-group").addClass('has-error');
          $("#cnpj_user").closest(".form-group").find('.msg-erro').html("CNPJ INVÁLIDO OU NÃO CONSTA EM NOSSO SISTEMA").fadeIn();

      }else{

        $("#cnpj_user").closest(".form-group").removeClass('has-error');
          $("#cnpj_user").closest(".form-group").find('.msg-erro').html("").fadeOut();

          $("#cpf_user").closest(".form-group").removeClass('has-error');
          $("#cpf_user").closest(".form-group").find('.msg-erro').html("").fadeOut();

          if($("#cpf_user").val() == ""){
            user = $("#cnpj_user").val();
          }else{
            user = $("#cpf_user").val();
          }

          var request = $.ajax({ url: 'api/recuperaSenha.php', type: 'POST', data: {user: user, password:$("#senha_user").val()}, async: true });
        request.done(function(r){
          if(r == 0){

            $("#msg_recupera_senha").fadeIn();
            $("#msg_recupera_senha").focus();
            $("#msg_recupera_senha").closest(".form-group").addClass('has-error');
            $("#msg_recupera_senha").closest(".form-group").find('.msg-erro').html("DADOS ESTÃO INCORRETOS OU NÃO CONSTAM NO SISTEMA").fadeIn();
          
          }else{

            $("#msg_recupera_senha").fadeIn();
            $("#msg_recupera_senha").focus();
            $("#msg_recupera_senha").closest(".form-group").addClass('has-success');
            $("#msg_recupera_senha").closest(".form-group").find('.msg-erro').html("UMA SENHA FOI ENVIADO PARA O EMAIL: "+r).fadeIn();

          }
        });

      }
    }

  <?php 
    if(isset($_SESSION['ClienteLogin'])){
  ?>

  function ValidateEmail(input, atual){
    ConsumeServiceValidate(input, { acao:"ValidarEmail", email:input.value, atual:atual }, "usuario");
  }
    
  function ConsumeServiceValidate(input, dataSend, entidade){
    if (input.value.length > 0){

      var request = $.ajax({ url: 'api/servico.php', type: 'POST',  data: dataSend });
      request.done(function (response){

        console.log(response);

        if (response != 1) {

          $("#"+input.name).closest('div').addClass("has-error");
          $(input).closest('.form-group').find('.msg-erro').html('Este dado não pode se repetir');
          $("#valido").val(false);
          return false;

        } else {

          $("#"+input.name).closest('div').removeClass("has-error").removeClass('has-warning');
          $(input).closest('.form-group').find('.msg-erro').html('');
          $("#valido").val(true);
          return true;

        }

      });

    }else{

      $("#"+input.name).closest('div').removeClass("has-error");
      $(input).closest('.form-group').find('.msg-erro').html('');
      $("#valido").val(true);
      return false;

    }

  }

  <?php
    }
  ?>

  var $getTipoPessoaCadastro = $('#tipoPessoa2');
  $getTipoPessoaCadastro.on("change", function (e) {

    $('#cnpj_user2').val("");
    $('#cpf_user2').val("");

    if($('#tipoPessoa2').val() == "cpf"){

      $('#input-cnpj2').hide();
      $('#input-cpf2').show();
    }else{

      $('#input-cpf2').hide();
      $('#input-cnpj2').show();
    }

  });

  function validaDadosCadastro(){

    d = document.getElementById('cadastro-form');

    telefone = d.telefone.value;
    for(var i = 1; i <= d.telefone.value.length; i++){
      telefone = telefone.replace("_", "");
    }

    if(d.nome.value == ""){

      $(d.nome).closest('.form-group').addClass('has-warning');
      $(d.nome).next().html('Insira um nome válido');
      d.nome.focus();
    }else if(d.email.value == "" || !validaEmail(d.email.value)){

      $(d.email).closest('.form-group').addClass('has-warning');
      $(d.email).next().html('Insira um e-mail válido');
      d.email.focus();
    }else if(telefone == "" || telefone.length < 14){

      $(d.telefone).closest('.form-group').addClass('has-warning');
      $(d.telefone).next().html('Insira um telefone/celular válido');
      d.telefone.focus();
    }else if(!ValidarCPF($("#cpf_user2")) && $('#tipoPessoa2').val() == 'cpf'){

      $("#cpf_user2").focus();
      $("#cpf_user2").closest(".form-group").addClass('has-error');
      $("#cpf_user2").closest(".form-group").find('.msg-erro').html("CPF INVÁLIDO").fadeIn();
    }else if(!validarCNPJ($("#cnpj_user2").val()) && $('#tipoPessoa2').val() == 'cnpj'){

      $("#cpf_user2").closest(".form-group").removeClass('has-error');
      $("#cpf_user2").closest(".form-group").find('.msg-erro').html("").fadeOut();

      $("#cnpj_user2").focus();
      $("#cnpj_user2").closest(".form-group").addClass('has-error');
      $("#cnpj_user2").closest(".form-group").find('.msg-erro').html("CNPJ INVÁLIDO").fadeIn();
    }else{

      $("#cpf_user2").closest(".form-group").removeClass('has-error');
      $("#cpf_user2").closest(".form-group").find('.msg-erro').html("").fadeOut();

      $("#cnpj_user2").closest(".form-group").removeClass('has-error');
      $("#cnpj_user2").closest(".form-group").find('.msg-erro').html("").fadeOut();

      d.submit();
    }
    
  }

  <?php 
    //&& (isset($r_DIR['paginacao']['pag']) && $r_DIR['paginacao']['pag'] == 1)
    if(isset($r_DIR['enquetes']) ){
      echo "getEnquete({$r_DIR['enquetes'][0]['id_enquete']});";
    }
  ?>

  <?php 
    if($r_DIR["page"] == "cadastro" && isset($_SESSION['ClienteLogin']) && !isset($_SESSION['nextLink']['quero-vender'])){

      echo "window.location = '".ROOT."meus-dados';";
    }elseif($r_DIR["page"] == "cadastro" && isset($_SESSION['ClienteLogin']) && isset($_SESSION['nextLink']['quero-vender'])){
      
      echo "window.location = '{$_SESSION['nextLink']['quero-vender']}'";
    }
  ?>

</script>