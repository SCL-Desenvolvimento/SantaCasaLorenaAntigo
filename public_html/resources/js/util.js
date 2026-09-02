
$(document).on({
	ajaxStart: function() {  $("#espere").addClass("show"); },
	ajaxStop: function() { $("#espere").removeClass("show"); }    
});

function GetCEP(cep){
		
	$.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+cep, function(){
		if(resultadoCEP["resultado"] == "1"){
				
			//console.log(resultadoCEP);
				
			$('input[name="rua"]').val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));
			$('input[name="bairro"]').val(unescape(resultadoCEP["bairro"]));
			$('input[name="cidade"]').val(unescape(resultadoCEP["cidade"]));
			$('#estados').val(unescape(resultadoCEP["uf"])).change();					
		}
	});	
}


function GetCEP(cep){

	$.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+cep, function(){
		if(resultadoCEP["resultado"] == "1"){

			//console.log(resultadoCEP);

			$('input[name="rua"]').val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));
			$('input[name="bairro"]').val(unescape(resultadoCEP["bairro"]));
			$('input[name="cidade"]').val(unescape(resultadoCEP["cidade"]));
			$('#estados').val(unescape(resultadoCEP["uf"])).change();
		}
	});
}


	function ValidarCPF(element) { 
		//console.log($(element).val());
		if ($(element).val() == ""){
			//RemoveWarning();
	        //console.log("erro1");
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
	        console.log("erro2");	
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
	       // console.log("erro3");	
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
	            //onsole.log("erro4");	
				return false; 	
			}

		//RemoveWarning();
		return true; 
	}

	function validarCNPJ(cnpj) {
	 
	    cnpj = cnpj.replace(/[^\d]+/g,'');
	    
	    //console.log(cnpj);

	    if(cnpj == '') return false;
	     
	    if (cnpj.length != 14)
	        return false;
	 
	    // Elimina CNPJs invalidos conhecidos
	    if (cnpj == "00000000000000" || 
	        cnpj == "11111111111111" || 
	        cnpj == "22222222222222" || 
	        cnpj == "33333333333333" || 
	        cnpj == "44444444444444" || 
	        cnpj == "55555555555555" || 
	        cnpj == "66666666666666" || 
	        cnpj == "77777777777777" || 
	        cnpj == "88888888888888" || 
	        cnpj == "99999999999999")
	        return false;
	         
	    // Valida DVs
	    tamanho = cnpj.length - 2
	    numeros = cnpj.substring(0,tamanho);
	    digitos = cnpj.substring(tamanho);
	    soma = 0;
	    pos = tamanho - 7;
	    for (i = tamanho; i >= 1; i--) {
	      soma += numeros.charAt(tamanho - i) * pos--;
	      if (pos < 2)
	            pos = 9;
	    }
	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	    if (resultado != digitos.charAt(0))
	        return false;
	         
	    tamanho = tamanho + 1;
	    numeros = cnpj.substring(0,tamanho);
	    soma = 0;
	    pos = tamanho - 7;
	    for (i = tamanho; i >= 1; i--) {
	      soma += numeros.charAt(tamanho - i) * pos--;
	      if (pos < 2)
	            pos = 9;
	    }
	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	    if (resultado != digitos.charAt(1))
	          return false;
	           
	    return true;
	    
	}

	function numberToMoeda(valor, moeda) {
	    return moeda + " " + valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	}

	function dataFormatada(d) {
	    var nomeMeses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez']
	    var data = new Date(d);
	    var dia = data.getDate();
	    var mes = data.getMonth();
	    mes = nomeMeses[mes];
	    var ano = data.getFullYear();
	    //return [dia, mes, ano].join(' ');
	    return [dia, mes, ano].join('/');
	}

	 //ValidaÃ§Ã£o de e-mail
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

	function Mascara(o,f){
        v_obj=o
        v_fun=f
        setTimeout("execmascara()",1)
    }
    /*FunÃ§Ã£o que Executa os objetos*/
    function execmascara(){
        v_obj.value=v_fun(v_obj.value)
    }
    function leech(v){
        v=v.replace(/o/gi,"0")
        v=v.replace(/i/gi,"1")
        v=v.replace(/z/gi,"2")
        v=v.replace(/e/gi,"3")
        v=v.replace(/a/gi,"4")
        v=v.replace(/s/gi,"5")
        v=v.replace(/t/gi,"7")
        return v
    }
    function Integer(v){
        return v.replace(/\D/g,"")
    }
    /*FunÃ§Ã£o que padroniza telefone (11) 4184-1241*/
    function Telefone(v){
        v=v.replace(/\D/g,"")                 
        v=v.replace(/^(\d\d)(\d)/g,"($1) $2") 
        v=v.replace(/(\d{4})(\d)/,"$1-$2")    
        return v
    }
    /*FunÃ§Ã£o que padroniza telefone (11) 41841241*/
    function TelefoneCall(v){
        v=v.replace(/\D/g,"")                 
        v=v.replace(/^(\d\d)(\d)/g,"($1) $2")    
        return v
    }
    /*FunÃ§Ã£o que padroniza CPF*/
    function Cpf(v){
        v=v.replace(/\D/g,"")                    
        v=v.replace(/(\d{3})(\d)/,"$1.$2")       
        v=v.replace(/(\d{3})(\d)/,"$1.$2")       
        v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") 
        return v
    }
    /*FunÃ§Ã£o que padroniza CEP*/
    function Cep(v){
        v=v.replace(/D/g,"")                
        v=v.replace(/^(\d{5})(\d)/,"$1-$2") 
        return v
    }
    /*FunÃ§Ã£o que padroniza CNPJ*/
    function Cnpj(v){
        v=v.replace(/\D/g,"")                   
        v=v.replace(/^(\d{2})(\d)/,"$1.$2")     
        v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3") 
        v=v.replace(/\.(\d{3})(\d)/,".$1/$2")           
        v=v.replace(/(\d{4})(\d)/,"$1-$2")              
        return v
    }
    /*FunÃ§Ã£o que permite apenas numeros Romanos*/
    function Romanos(v){
        v=v.toUpperCase()             
        v=v.replace(/[^IVXLCDM]/g,"") 
        while(v.replace(/^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/,"")!="")
            v=v.replace(/.$/,"")
        return v
    }
    /*FunÃ§Ã£o que padroniza o Site*/
    function Site(v){
        v=v.replace(/^http:\/\/?/,"")
        dominio=v
        caminho=""
        if(v.indexOf("/")>-1)
            dominio=v.split("/")[0]
            caminho=v.replace(/[^\/]*/,"")
            dominio=dominio.replace(/[^\w\.\+-:@]/g,"")
            caminho=caminho.replace(/[^\w\d\+-@:\?&=%\(\)\.]/g,"")
            caminho=caminho.replace(/([\?&])=/,"$1")
        if(caminho!="")dominio=dominio.replace(/\.+$/,"")
            v="http://"+dominio+caminho
        return v
    }
    /*FunÃ§Ã£o que padroniza DATA*/
    function Data(v){
        v=v.replace(/\D/g,"") 
        v=v.replace(/(\d{2})(\d)/,"$1/$2") 
        v=v.replace(/(\d{2})(\d)/,"$1/$2") 
        return v
    }
    /*FunÃ§Ã£o que padroniza DATA*/
    function Hora(v){
        v=v.replace(/\D/g,"") 
        v=v.replace(/(\d{2})(\d)/,"$1:$2")  
        return v
    }
    /*FunÃ§Ã£o que padroniza valor monÃ©tario*/
    function Valor(v){
        v=v.replace(/\D/g,"") //Remove tudo o que nÃ£o Ã© dÃ­gito
        v=v.replace(/^([0-9]{3}\.?){3}-[0-9]{2}$/,"$1.$2");
        //v=v.replace(/(\d{3})(\d)/g,"$1,$2")
        v=v.replace(/(\d)(\d{2})$/,"$1.$2") //Coloca ponto antes dos 2 Ãºltimos digitos
        return v
    }
    /*FunÃ§Ã£o que padroniza Area*/
    function Area(v){
        v=v.replace(/\D/g,"") 
        v=v.replace(/(\d)(\d{2})$/,"$1.$2") 
        return v
    }

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