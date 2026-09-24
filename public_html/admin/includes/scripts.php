<?php if (!defined('SCL_ADMIN_PANEL')) { http_response_code(403); exit; } ?>
<script src="../resources/vendor/jquery/jquery.min.js"></script>
<script src="../resources/vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="../resources/vendor/datatables/dataTables.min.js"></script>
<script src="../resources/vendor/datatables/dataTables.bootstrap5.min.js"></script>
<script src="../resources/vendor/tom-select/tom-select.complete.min.js"></script>
<script src="../resources/vendor/tinymce/tinymce.min.js"></script>
<script src="../resources/js/admin-security.js"></script>
<script src="../resources/js/admin-components.js"></script>
<script src="../resources/js/admin-editor.js"></script>
<script src="../resources/js/util.js"></script>
<script src="../resources/js/admin-workspace.js"></script>
<script type="text/javascript">

  // cache the id
  var navbox = $('.nav-tabs');

  // activate tab on click
  navbox.on('click', 'a', function (e) {
    var $this = $(this);
    // prevent the Default behavior
    e.preventDefault();
    // send the hash to the address bar
    window.location.hash = $this.attr('href');
    // activate the clicked tab
    $this.sclTab('show');
  });

  // will show tab based on hash
  function refreshHash() {
    navbox.find('a[href="'+window.location.hash+'"]').sclTab('show');
  }

  // show tab if hash changes in address bar
  $(window).on('hashchange', refreshHash);

  // read has from address bar and show it
  if(window.location.hash) {
    // show tab on load
    refreshHash();
  }

	$(document).on({
	  ajaxStart: function() {  $(".espere").addClass("show"); },
	  ajaxStop: function() { $(".espere").removeClass("show"); }
	});

  function debounce(fn, milissegundos) {

    let timer = 0;

    return () => {

      clearTimeout(timer);
      timer = setTimeout(fn, milissegundos);
    }
  }

  function setTextCK($textareas){
    $.each( $textareas, function( key, value ) {
      $.each( $("textarea[name^='"+value+"_']"), function( key, value ) {
        SCLEditor.replace($(value).prop("name"));
      });
    });
  }

  function getTextCK($textareas){
    $.each( $textareas, function( key, value ) {
      $.each( $("textarea[name^='"+value+"_']"), function( key, value ) {
        $("#"+$(value).prop("name")).val(SCLEditor.instances[$(value).prop("name")].getData());
      });
    });
  }

	$( 'h1' ).appendTo( $( '.content-header' ) );

  //Initialize Select2 Elements
  function select2(){
    $('.select2').sclSelect();
  };

  function select2Tags(elemento, entidade){

    $("#"+elemento).sclSelect({
      minimumInputLength: 1,
      //tags: true,
      ajax: {
        url: "webservices/"+entidade+"/servico.php",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          console.log(params);
          return {
            tag: params.term, // search term
            id_loja: (entidade == 'loja' ? GetURLParameter("id_loja") : null ),
            acao: elemento
          };
        },
        processResults: function (data) {
          console.log(data);
          return {
            results: $.map(data, function (item) {
              return {
                text: item.titulo,
                id: item.id
              }
            })
          };
        }
      }
    })
  };

  function select2Cats(){
    $(".tagsSelect").sclSelect({
         tags: true,
         ajax: {
        url: "webservices/noticias/servico.php",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            q: params.term // search term
          };
        },
        processResults: function (data) {
          // parse the results into the format expected by Select2.
          // since we are using custom formatting functions we do not need to
          // alter the remote JSON data
          //console.log(data)
          return {
            results: data
            
          };
        }
      }
      //minimumInputLength: 1
    })
  };

  function iCheck(){
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').addClass('form-check-input');
  };

  function GetURLParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++){
      var sParameterName = sURLVariables[i].split('=');
      if(sParameterName[0] == sParam){
        return sParameterName[1];
      }
    }
  }

  function toDate(dateStr) {
    var parts = dateStr.split("-");
    console.log(parts[2]+"/"+parts[1]+"/"+parts[0]);
    return parts[2]+"/"+parts[1]+"/"+parts[0];
  }

  function dataFormatada(d) {
    var nomeMeses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez']
    var data = new Date(d);
    var dia = data.getDate();
    var mes = data.getMonth();
    var hora = data.getHours()
    mes = nomeMeses[mes];
    var ano = data.getFullYear();
    //return [dia, mes, ano].join(' ');
    console.log(data);
    if(data.getHours() != '21' && data.getMinutes() != '0' && data.getSeconds() != '0'){
      return [dia, mes, ano].join('/')+" "+(data.getHours() < 10 ? "0"+data.getHours() : data.getHours())+":"+(data.getMinutes() < 10 ? "0"+data.getMinutes() : data.getMinutes())+":"+(data.getSeconds() < 10 ? "0"+data.getSeconds() : data.getSeconds());
    }else{
      return [dia, mes, ano].join('/');
    }
  }

  //iCheck();

  function PreviewImg(input){

    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $(input).closest('div').find('.previewimg').attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  //esquerda para direita
  String.prototype.left = function(){
    return this.substr(0,arguments[0]==undefined?1:parseInt(arguments[0]));
  }

  //direta para esquerda
  String.prototype.right = function(){
    return this.substr(this.length-(arguments[0]==undefined?1:parseInt(arguments[0])),this.length);
  }

  function ConsumeServiceValidate(input, dataSend, entidade){
    if (input.value.length > 0){

      var request = $.ajax({ url: 'webservices/'+entidade+'/servico.php', type: 'POST',  data: dataSend });
      request.done(function (response){

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

  function ValidarCPF(element) {
    //console.log($(element).val());
    if ($(element).val() == ""){
      //RemoveWarning();
      return;
    }

    /*function Warning(){
      $(element).closest('.form-group').addClass('has-warning');
      $(element).focus();
    }
    function RemoveWarning(){
      $(element).closest('.form-group').removeClass('has-warning');
    }*/

    var strCPF = $(element).val();
    strCPF = strCPF.replace(/[\.-]/g, "");

    var Soma; var Resto; Soma = 0;

    if (strCPF == "00000000000")
    {
      //Warning();
      return false;
    }

    for (i=1; i<=9; i++)
      Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);

    Resto = (Soma * 10) % 11;

    if ((Resto == 10) || (Resto == 11))
      Resto = 0;

    if (Resto != parseInt(strCPF.substring(9, 10)) )
    {
      //Warning();
      return false;
    }

     Soma = 0;

     for (i = 1; i <= 10; i++)
       Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i); Resto = (Soma * 10) % 11;

     if ((Resto == 10) || (Resto == 11))
       Resto = 0;

     if (Resto != parseInt(strCPF.substring(10, 11) ) )
      {
        //Warning();
        return false;
      }

    //RemoveWarning();
    return true;
  }

//Validação de e-mail
function validaEmail(email){
  var exclude=/[^@\-\.\w]|^[_@\.\-]|[\._\-]{2}|[@\.]{2}|(@)[^@]*\1/;
  var check=/@[\w\-]+\./;
  var checkend=/\.[a-zA-Z]{2,3}$/;
  if(((email.search(exclude) != -1)||(email.search(check)) == -1)||(email.search(checkend) == -1)){
    return false;}
  else{
    return true;
  }
}

function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
 }
 function execmascara(){
    v_obj.value=v_fun(v_obj.value)
 }

 //Mascara para Celular
 function mcel(v){
    v=v.replace(/\D/g,"");
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2");
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");
    return v;
 }

 //Mascara para CEP
 function mcep(v){
  v=v.replace(/\D/g,"")
  v=v.replace(/(\d{5})(\d)/,"$1-$2")
  return v
 }

 function id( el ){
    return document.getElementById( el );
 }

 function validar(dom,tipo){
     switch(tipo){
         case'num':var regex=/[A-Za-z]/g;break;
         case'text':var regex=/\d/g;break;
     }
     dom.value=dom.value.replace(regex,'');
 }

</script>