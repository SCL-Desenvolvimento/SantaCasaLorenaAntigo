<?php

class Check{
	private static $Data;
	private static $Format;
	
	public static function Email($Email){
		self::$Data = (string) $Email;
		self::$Format = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';
		
		if(preg_match(self::$Format, self::$Data)):
			return true;
		else:
			return false;		
		endif;
	}

	/*
		$cnpj = "11222333000199";
		echo mask($cnpj,'##.###.###/####-##');
	*/
	
	public static function isInteger($input){
   		return(ctype_digit(strval($input)));
	}

	public static function setLink($Link){

		if($Link == ""){
			return false;
		}elseif(preg_match("/http/", $Link) || preg_match("/https/", $Link)){
			return "window.open('{$Link}')";
		}else{
			return "location.href='{$Link}'";
		}

	}
	
	public static function insertImg($Img, $Pasta){

		$Upload = new Upload("arquivos");
		$Upload->Image($Img, null, null, "/{$Pasta}");
		if (!$Upload->getResult()) scl_deny(422);
		return $Upload->getResult();
	}

	public static function insertFile($File, $Pasta){

		$Upload = new Upload("arquivos");
		$Upload->File($File, null, "/{$Pasta}", null);
		if (!$Upload->getResult()) scl_deny(422);
		return $Upload->getResult();
	}
	
	public static function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';
		$retorno = '';
		$caracteres = '';
		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;
		$len = strlen($caracteres);
		for ($n = 1; $n <= $tamanho; $n++) {
			$rand = random_int(1, $len);
			$retorno .= $caracteres[$rand-1];
		}
		return $retorno;
	}

	public static function registraImpressao($Anuncio, $pagina, $erro){

		$navegador = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_DEFAULT);

		if(!preg_match("/bot/",$navegador)):
		
			
			if(strpos($navegador, 'Chrome')):
				$navegador = "Chrome";
			elseif(strpos($navegador, 'Firefox')):
				$navegador = "FireFox";
			elseif(strpos($navegador, 'Opera')):
				$navegador = "Opera";
			elseif(strpos($navegador, 'MSIE') || strpos($navegador, 'Trident/')):
				$navegador = "IE";
			elseif(strpos($navegador, 'Safari')):
				$navegador = "Safari";
			else:
				$navegador = "Outros";
			endif;

			//foreach($Anuncios AS $dados):

				$infoImp = array();
				$infoImp['id_anuncio'] = $Anuncio;
				$infoImp['pagina'] = $pagina;
				$infoImp['erro'] = $erro;
				$infoImp['navegador'] = $navegador;
				$infoImp['ip'] = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
				$infoImp['data'] = date("Y-m-d H:i:s");
				$newImp = new Create();
				$newImp->ExeCreate(PREFIX."anuncio_impressao", $infoImp);

			//endforeach;
		endif;
	}

	public static function getCategoriaNav($Categoria, $Tipo = null){

		$NAV = array();
		$NAV['link'] = $Categoria['url_amigavel'];
		$NAV['breadCrumb'] = "";

		$readTaxonomias1 = new Read();
        $readTaxonomias1->fullRead("SELECT T.id_taxonomia, T.taxonomia_pai, T.url_amigavel, TI.nome
                                  	FROM ".PREFIX."taxonomia AS T
                                  	INNER JOIN ".PREFIX."taxonomia_info AS TI ON (TI.id_taxonomia = T.id_taxonomia)
                                  	WHERE T.id_taxonomia = {$Categoria['taxonomia_pai']} AND TI.lang = '{$_SESSION['idioma']}' ORDER BY TI.nome ASC");

        if($readTaxonomias1->getResult()):
	        foreach ($readTaxonomias1->getResult() AS $taxonomia1) {
	            
	        	$NAV['link'] = $taxonomia1['url_amigavel']."/".$NAV['link'];

	            $readTaxonomias2 = new Read();
	            $readTaxonomias2->fullRead("SELECT T.id_taxonomia, T.taxonomia_pai, T.url_amigavel, TI.nome
	                                    	FROM ".PREFIX."taxonomia AS T
	                                    	INNER JOIN ".PREFIX."taxonomia_info AS TI ON (TI.id_taxonomia = T.id_taxonomia)
	                                    	WHERE T.id_taxonomia = {$taxonomia1['taxonomia_pai']} AND TI.lang = '{$_SESSION['idioma']}' ORDER BY TI.nome ASC");

		        if($readTaxonomias2->getResult()):
		            foreach ($readTaxonomias2->getResult() AS $taxonomia2) {

		            	$NAV['link'] = $taxonomia2['url_amigavel']."/".$NAV['link'];
		            	   
		            }
		        endif;
	        }
	    endif;

        return ($Tipo == null ? $NAV : ($Tipo == "link" ? (isset($Categoria['tipo']) && $Categoria['tipo'] == 'post' ? "blog/".$NAV['link'] : "produtos/".$NAV['link'] ) : $NAV['breadCrumb'] ));
	}

	public static function geraTitulo($Titulo){

		$palavras = explode(" ",$Titulo);
		$inverteCor =  ceil(count($palavras)/2);

		$Titulo = "";

		foreach ($palavras AS $key => $palavra) {
			if($key < $inverteCor){
				$Titulo .= "<span class='parte-vermelho'>{$palavra} </span>";
			}else{

				$Titulo .= "<span class='parte-azul'>{$palavra} </span>".(($key+1) == $inverteCor ? "<br>" : "" );
			}
			$Titulo .= (($key+1) == $inverteCor ? "<br>" : "" );
		}
		return $Titulo;
	}

	public static function getBreadcrump($Conteudo){

		/*
		$readTaxonomias = new Read();
		$readTaxonomias->fullRead("SELECT T.id_taxonomia
									FROM ".PREFIX."taxonomia AS T
									INNER JOIN ".PREFIX."conteudo_taxonomia AS CT ON (T.id_taxonomia = CT.id_taxonomia)
									WHERE CT.id_conteudo = {$Conteudo['id_conteudo']} GROUP BY T.id_taxonomia");
														
		$taxonomiasE = array();
		$i = 0;						
		foreach ($readTaxonomias->getResult() AS $taxonomiaE) {
			$taxonomiasE[$i] = $taxonomiaE['id_taxonomia'];
			$i++;
		}

		$t1 = array();
		$t2 = array();
		$t3 = array();
		$taxonomias = array();

		$readTaxonomias1 = new Read();
		$readTaxonomias1->fullRead("SELECT T.id_taxonomia, TI.nome, T.taxonomia_pai
									FROM ".PREFIX."taxonomia AS T
									INNER JOIN ".PREFIX."taxonomia_info AS TI ON (TI.id_taxonomia = T.id_taxonomia)
									WHERE T.taxonomia_pai = 0 AND T.taxonomia = 'categoria' AND TI.lang = 'br' ORDER BY TI.nome ASC");

		foreach ($readTaxonomias1->getResult() AS $taxonomia1) {
					
			if(in_array($taxonomia1['id_taxonomia'], $taxonomiasE)): $t1[] = $taxonomia1; endif;

			$readTaxonomias2 = new Read();
			$readTaxonomias2->fullRead("SELECT T.id_taxonomia, TI.nome, T.taxonomia_pai
										FROM ".PREFIX."taxonomia AS T
										INNER JOIN ".PREFIX."taxonomia_info AS TI ON (TI.id_taxonomia = T.id_taxonomia)
										WHERE taxonomia_pai = {$taxonomia1['id_taxonomia']} AND taxonomia = 'categoria' AND TI.lang = 'br'  ORDER BY TI.nome ASC");

			foreach ($readTaxonomias2->getResult() AS $taxonomia2) {
						
				if(in_array($taxonomia2['id_taxonomia'], $taxonomiasE)): $t2[] = $taxonomia2; endif;

				$readTaxonomias3 = new Read();
				$readTaxonomias3->fullRead("SELECT T.id_taxonomia, TI.nome, T.taxonomia_pai
											FROM ".PREFIX."taxonomia AS T
											INNER JOIN ".PREFIX."taxonomia_info AS TI ON (TI.id_taxonomia = T.id_taxonomia)
											WHERE taxonomia_pai = {$taxonomia2['id_taxonomia']} AND taxonomia = 'categoria'  AND TI.lang = 'br'  ORDER BY TI.nome ASC");

				foreach ($readTaxonomias3->getResult() AS $taxonomia3) {
					if(in_array($taxonomia3['id_taxonomia'], $taxonomiasE)): $t3[] = $taxonomia3; endif;
				}
			}
		}

		var_dump($t1);
		var_dump($t2);
		var_dump($t3);

		if(!empty($t3)){
			$i = 0;
			while (!isset($taxonomias[2]) && (($i + 1) <= count($t2))) {
				if($t3['taxonomia_pai'] == $t2[$i]['id_taxonomia']): $taxonomias[2] = $t2[$i]; endif;
				$i++;
				if($i == 10):
					exit;
				endif;
			}
		}

		
		if(!empty($t2)){
			$i = 0;
			while (!isset($taxonomias[1]) && (($i + 1) <= count($t1)) ) {
				if($taxonomias[2] == $t1[$i]['id_taxonomia']): $taxonomias[1] = $t1[$i]; endif;
				$i++;
				if($i == 10):
					exit;
				endif;
			}
		}
		

		/*
		if(!empty($t3)){
			$i = 0;
			while (!isset($taxonomias[2])) {
				if($t3['taxonomia_pai'] == $t2[$i]['id_taxonomia']): echo "tem"; endif;
			}
		}
		*/

		//var_dump($taxonomias);	

	}

	public static function checkLinkExterno($Link){
		return (preg_match("/http/", $Link) || preg_match("/https/", $Link) ? "href='{$Link}' target='_blank' " : "href='".HOME.$_SESSION['idioma']."/{$Link}'" );
	}

	public static function getTitulo($Titulos){

		$Titulos = json_decode($Titulos, true);
		foreach ($Titulos AS $key => $titulo) {
			if($key == $_SESSION['idioma'] && $titulo != ""){
				return $titulo;
			}
		}

		return $Titulos['br'];

	}

	public static function ajusteLink($Link){
		$Link = str_replace(array("produtos//", "blog//"), array("produtos/", "blog/"), $Link);
		return str_replace($_SESSION['idioma']."//", $_SESSION['idioma']."/", $Link);
	}

	public static function checkBrowser() {
		$navegador = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_DEFAULT);
			
		if(strpos($navegador, 'Chrome')):
			$navegador = "Chrome";
		elseif(strpos($navegador, 'Firefox')):
			$navegador = "FireFox";
		elseif(strpos($navegador, 'Opera')):
			$navegador = "Opera";
		elseif(strpos($navegador, 'MSIE') || strpos($navegador, 'Trident/')):
			$navegador = "IE";
		elseif(strpos($navegador, 'Safari')):
			$navegador = "Safari";
		else:
			$navegador = "Outros";
		endif;

		return $navegador;
	}

	public static function datasReverse($dataMin){
   		$date = explode("-", $dataMin);
   		$ano = $date[0];
   		$mes = ($date[1] != 10 ? str_replace('0', '', $date[1]) : $date[1] );

   		$datas = array();

   		for($i = $ano; $i <= date('Y'); $i++):
   			if($i == date('Y')):
	   			for($y = 1; $y <= (date('m') != 10 ? str_replace('0', '', date('m')) : date('m') ); $y++):
	   				array_push($datas, $i."-".$y);
	   			endfor;
	   		elseif($i == $ano):
	   			for($y = $mes; $y <= 12; $y++):
	   				array_push($datas, $i."-".$y);
	   			endfor;
	   		else:
	   			for($y = 1; $y <= 12; $y++):
	   				array_push($datas, $i."-".$y);
	   			endfor;
	   		endif;
   		endfor;

   		return array_reverse($datas);
	}

	public static function mask($val, $mask){
		 $maskared = '';
		 $k = 0;
		 for($i = 0; $i<=strlen($mask)-1; $i++)
		 {
		 if($mask[$i] == '#')
		 {
		 if(isset($val[$k]))
		 $maskared .= $val[$k++];
		 }
		 else
		 {
		 if(isset($mask[$i]))
		 $maskared .= $mask[$i];
		 }
		 }
		 return $maskared;
	}

	public static function validarCPF( $cpf = '') { 

		$cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
		// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
		if ( strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
			return FALSE;
		} else { 
	                // Calcula os números para verificar se o CPF é verdadeiro
			for ($t = 9; $t < 11; $t++) {
				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf[$c] * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($cpf[$c] != $d) {
					return FALSE;
				}
			}
			return TRUE;
		}
	}
	
	public static function validarCNPJ($cnpj){

		$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
		// Valida tamanho
		if (strlen($cnpj) != 14)
			return false;
		// Valida primeiro dígito verificador
		for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
		{
			$soma += $cnpj[$i] * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}
		$resto = $soma % 11;
		if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
			return false;
		// Valida segundo dígito verificador
		for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
		{
			$soma += $cnpj[$i] * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}
		$resto = $soma % 11;
		return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
	}

	public static function getSortAds($resultado, $limite){

		$resultadoSorteados = array();
		$i = 1;

		while ($i <= $limite) {

			$agrega = true;
			$x = array_rand($resultado, 1);

			foreach ($resultadoSorteados AS $key => $ad) {

				if(isset($ad['id_a']) && $ad['id_a'] == $resultado[$x]['id_a']){
				
					$agrega = false;
				}
			}

			if($agrega){

				array_push($resultadoSorteados, $resultado[$x]);
				unset($resultado[$x]);
				$i++;
			}
		}

		return $resultadoSorteados;
	}

	/*
		Número de meses, desde de a data de ajuizamento
		$d1 = "2011-01-01";
		$d2 = "2011-01-10";
	*/
	public function diffDate($d1, $d2, $type='', $sep='-')	{
		 $d1 = explode($sep, $d1);
		 $d2 = explode($sep, $d2);
		 switch ($type)
		 {
			 case 'A':
			 $X = 31536000;
			 break;
			 case 'M':
			 $X = 2592000;
			 break;
			 case 'D':
			 $X = 86400;
			 break;
			 case 'H':
			 $X = 3600;
			 break;
			 case 'MI':
			 $X = 60;
			 break;
			 default:
			 $X = 1;
		 }
		 return floor(((mktime(0, 0, 0, $d2[1], $d2[2], $d2[0]) - mktime(0, 0, 0, $d1[1], $d1[2], $d1[0] ) ) / $X ) );
	}

	public static function dataExtenso($Data){

		$meses = array(
	    	'01' => 'Janeiro',
	        '02' => 'Fevereiro',
	        '03' => 'Março',
	        '04' => 'Abril',
	        '05' => 'Maio',
	        '06' => 'Junho',
	        '07' => 'Julho',
	        '08' => 'Agosto',
	        '09' => 'Setembro',
	        '10' => 'Outubro',
	        '11' => 'Novembro',
	        '12' => 'Dezembro'
	    );

		$DataTemp = explode(" ", $Data);
		$DataNova = explode("-", $DataTemp[0]);
		return $DataNova[2]." de ".$meses[$DataNova[1]]." de ".$DataNova[0];

	}

	public static function dataExtensoMesAno($Data){

		$meses = array(
	    	'01' => 'Janeiro',
	        '02' => 'Fevereiro',
	        '03' => 'Março',
	        '04' => 'Abril',
	        '05' => 'Maio',
	        '06' => 'Junho',
	        '07' => 'Julho',
	        '08' => 'Agosto',
	        '09' => 'Setembro',
	        '10' => 'Outubro',
	        '11' => 'Novembro',
	        '12' => 'Dezembro'
	    );

		$DataTemp = explode(" ", $Data);
		$DataNova = explode("-", $DataTemp[0]);
		$DataNova[1] = ($DataNova[1] == 10 ? str_replace('010', '10', $DataNova[1]) : ($DataNova[1] < 10 ? str_replace('00', '0', $DataNova[1]) : str_replace('0', '', $DataNova[1]) ) );
		return $meses[$DataNova[1]]." de ".$DataNova[0];

	}


	public static function somaHora($hora1,$hora2){
	 	$h1 = explode(":",$hora1);
	 	$h2 = explode(":",$hora2);
	 	//echo $hora1;
	 	//var_dump($h1);

	 	//echo $hora2;
	 	//var_dump($h2);

	 	$segundo = $h1[2] + $h2[2] ;
	 	$minuto  = $h1[1] + $h2[1] ;
	 	$horas   = $h1[0] + $h2[0] ;
	 	$dia   	= 0 ;
	 	
	 	if($segundo > 59){
	 	
	 		$segundodif = $segundo - 60;
	 		$segundo = $segundodif;
	 		$minuto = $minuto + 1;
	 	}
	 	
	 	if($minuto > 59){
	 		
	 		$minutodif = $minuto - 60;
	 		$minuto = $minutodif;
	 		$horas = $horas + 1;
	 	}
	 	
	 	/*if($horas > 24){
	 		
	 		$num = 0;
	 		
	 	 	(int)$num = $horas / 24;
	 	  	$horaAtual = (int)$num * 24;
			$horasDif = $horas - $horaAtual;
	 		
	 		$horas = $horasDif;				
	 		
	 		for($i = 1; $i <= (int)$num; $i++){
	 		 			
	 			$dia +=  1 ;
	 		}
		 	 		
		}*/
			
		if(strlen($horas) == 1){
		
			$horas = "0".$horas;
		}
		
		if(strlen($minuto) == 1){
		
			$minuto = "0".$minuto;
		}
		
		if(strlen($segundo) == 1){
		
			$segundo = "0".$segundo;
	 	}
	 	
	 	//return  $dia.":".$horas.":".$minuto.":".$segundo;
	 	return  $horas.":".$minuto.":".$segundo;
	 
	}
	
	public static function Moeda($Data){
	if(strpos($Data,'.')!='')
	{
		   self::$Format=explode('.',$Data);
		   if(strlen(self::$Format[0])==4)
		   {
		     $parte1=substr(self::$Format[0],0,1);
		     $parte2=substr(self::$Format[0],1,3);
		     if(strlen(self::$Format[1])<2)
		     {
		     	self::$Data=$parte1.'.'.$parte2.','.self::$Format[1].'0';
		     }else
		     {
		     	self::$Data=$parte1.'.'.$parte2.','.self::$Format[1];
		     }
		   }
		   elseif(strlen(self::$Format[0])==5)
		   {
		     $parte1=substr(self::$Format[0],0,2);
		     $parte2=substr(self::$Format[0],2,3);
		     if(strlen(self::$Format[1])<2)
		     {
		     	self::$Data=$parte1.'.'.$parte2.','.self::$Format[1].'0';
		     }
		     else
		     {
		     	self::$Data=$parte1.'.'.$parte2.','.self::$Format[1];
		     }
		   }
		   elseif(strlen(self::$Format[0])==6)
		   {
		     $parte1=substr(self::$Format[0],0,3);
		     $parte2=substr(self::$Format[0],3,3);
		     if(strlen(self::$Format[1])<2)
		     {
		     	self::$Data=$parte1.'.'.$parte2.','.self::$Format[1].'0';
		     }
		     else
		     {
		     	self::$Data=$parte1.'.'.$parte2.','.self::$Format[1];
		     }
		   }
		   elseif(strlen(self::$Format[0])==7)
		   {
		     $parte1=substr(self::$Format[0],0,1);
		     $parte2=substr(self::$Format[0],1,3);
		     $parte3=substr(self::$Format[0],4,3);
		     if(strlen(self::$Format[1])<2)
		     {
		     	self::$Data=$parte1.'.'.$parte2.'.'.$parte3.','.self::$Format[1].'0';
		     }
		     else
		     {
		    self::$Data=$parte1.'.'.$parte2.'.'.$parte3.','.self::$Format[1];
		     }
		   }
		   elseif(strlen(self::$Format[0])==8)
		   {
		     $parte1=substr(self::$Format[0],0,2);
		     $parte2=substr(self::$Format[0],2,3);
		     $parte3=substr(self::$Format[0],5,3);
		     if(strlen(self::$Format[1])<2){
		    self::$Data=$parte1.'.'.$parte2.'.'.$parte3.','.self::$Format[1].'0';
		     }else{
		    self::$Data=$parte1.'.'.$parte2.'.'.$parte3.','.self::$Format[1];
		     }
		   }
		   elseif(strlen(self::$Format[0])==9)
		   {
		     $parte1=substr(self::$Format[0],0,3);
		     $parte2=substr(self::$Format[0],3,3);
		     $parte3=substr(self::$Format[0],6,3);
		     if(strlen(self::$Format[1])<2)
		     {
		     	self::$Data=$parte1.'.'.$parte2.'.'.$parte3.','.self::$Format[1].'0';
		     }
		     else
		     {
		     	self::$Data=$parte1.'.'.$parte2.'.'.$parte3.','.self::$Format[1];
		     }
		   }
		   elseif(strlen(self::$Format[0])==10)
		   {
		     $parte1=substr(self::$Format[0],0,1);
		     $parte2=substr(self::$Format[0],1,3);
		     $parte3=substr(self::$Format[0],4,3);
		     $parte4=substr(self::$Format[0],7,3);
		     if(strlen(self::$Format[1])<2)
		     {
		     	self::$Data=$parte1.'.'.$parte2.'.'.$parte3.'.'.$parte4.','.self::$Format[1].'0';
		     }
		     else
		     {
		     	self::$Data=$parte1.'.'.$parte2.'.'.$parte3.'.'.$parte4.','.self::$Format[1];
		     }
		   }
		   else
		   {
		     if(strlen(self::$Format[1])<2)
		     {
		    	self::$Data=self::$Format[0].','.self::$Format[1].'0';
		     }
		     else
		     {
		    	self::$Data=self::$Format[0].','.self::$Format[1];
		     }
		   }
	  }
	  else
	  {
	     self::$Format=$Data;
	   if(strlen(self::$Format)==4)
	   {
	     $parte1=substr(self::$Format,0,1);
	     $parte2=substr(self::$Format,1,3);
	    self::$Data=$parte1.'.'.$parte2.','.'00';
	   }
	   elseif(strlen(self::$Format)==5)
	   {
	     $parte1=substr(self::$Format,0,2);
	     $parte2=substr(self::$Format,2,3);
	    self::$Data=$parte1.'.'.$parte2.','.'00';
	   }
	   elseif(strlen(self::$Format)==6)
	   {
	     $parte1=substr(self::$Format,0,3);
	     $parte2=substr(self::$Format,3,3);
	    self::$Data=$parte1.'.'.$parte2.','.'00';
	   }
	   elseif(strlen(self::$Format)==7)
	   {
	     $parte1=substr(self::$Format,0,1);
	     $parte2=substr(self::$Format,1,3);
	     $parte3=substr(self::$Format,4,3);
	    self::$Data=$parte1.'.'.$parte2.'.'.$parte3.','.'00';
	   }
	   elseif(strlen(self::$Format)==8)
	   {
	     $parte1=substr(self::$Format,0,2);
	     $parte2=substr(self::$Format,2,3);
	     $parte3=substr(self::$Format,5,3);
	    self::$Data=$parte1.'.'.$parte2.'.'.$parte3.','.'00';
	   }
	   elseif(strlen(self::$Format)==9)
	   {
	     $parte1=substr(self::$Format,0,3);
	     $parte2=substr(self::$Format,3,3);
	     $parte3=substr(self::$Format,6,3);
	    self::$Data=$parte1.'.'.$parte2.'.'.$parte3.','.'00';
	   }
	   elseif(strlen(self::$Format)==10)
	   {
	     $parte1=substr(self::$Format,0,1);
	     $parte2=substr(self::$Format,1,3);
	     $parte3=substr(self::$Format,4,3);
	     $parte4=substr(self::$Format,7,3);
	    self::$Data=$parte1.'.'.$parte2.'.'.$parte3.'.'.$parte4.','.'00';
	   }
	   else
	   {
	    self::$Data=self::$Format.','.'00';
	   }
	}
	  return "R$ ".self::$Data;
}
  

	

	public static function urlAmigavel($Name){
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', strip_tags((string) $Name));
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $text ?: ''), '-'));
    }

	public static function getUrlAmigavel($Url, $Site = null){
		return HOME.(isset($Site) ? "".$Site['nome']."/".$Url : "".$Url);
	}
	
	public static function Data($Data){
		
		self::$Format = explode(' ', $Data);
		self::$Data = explode('/',self::$Format['0']);

		if(empty(self::$Format['1'])):
			self::$Format['1'] = date('H:i:s');
		endif;
		
		self::$Data = self::$Data['2'].'-'.self::$Data['1'].'-'.self::$Data['0'].' '.self::$Format['1'];
		return str_replace('--', '', self::$Data);
	}

	public static function HumanData($Data){
		
		self::$Format = explode(' ', $Data);
		self::$Data = explode('-',self::$Format['0']);

		if(empty(self::$Format['1'])):
			self::$Format['1'] = date('H:i:s');
		endif;
		
		self::$Data = self::$Data['2'].'/'.self::$Data['1'].'/'.self::$Data['0'];
		return str_replace('--', '', self::$Data);
	}
	
	public static function limitarCaracteres($String, $Limite, $Pointer = Null){
		self::$Data = strip_tags(trim($String));
		self::$Format = (int) $Limite;
		
		$ArrWords = explode(' ', self::$Data);
		$ArrNum = count($ArrWords);
		$NewWord = implode(' ', array_slice($ArrWords, 0, self::$Format));

		$Pointer = (empty($Pointer)?  '...' : ' '.$Pointer);
		$Result = (self::$Format < $ArrNum ? $NewWord.$Pointer : self::$Data);
		return $Result;
	}
	
	public static function CatByName($CatName){
		$Read = new Read();
		$Read->ExeRead("System_categories", "WHERE category_name = :name", "name={$CatName}");
		
		if($Read->getRowCount()):
			return $Read->getResult()[0]['category_id'].'<br>';
		else:
			echo "A categoria {$CatName} não foi encontrada";
			die;
		endif;
	}
	public static function Registro($Tabela, $Campo, $Data){

		$Read = new Read();
		$Read->ExeRead($Tabela, "WHERE {$Campo} = :{$Campo}" , "{$Campo}={$Data}");
		
		if($Read->getRowCount() > 0):
			return true;
		endif;
	}


	public static function AtualizaRegistro($Tabela, $Campo, $Data){
		$Read = new Read();
		$Read->ExeRead($Tabela, "WHERE {$Campo} = :{$Campo}" , "{$Campo}={$Data}");
		
		if($Read->getRowCount() > 0):
			$id = explode("_", $Tabela);
			$id = "id_".$id[1];
			return $Read->getResult()[0][$id];
		endif;
	}
	
	public static function UserOnline(){
		$Now = date('Y-m-d H:i:s');
		$DeleteUserOnline = new Delete();
		$DeleteUserOnline->ExeDelete('System_sitevieSystem_online', 'WHERE online_endview < :now', "now={$Now}");
		
		
		$ReadUserOnline = new Read();
		$ReadUserOnline->ExeRead("System_sitevieSystem_online");
		return $ReadUserOnline->getRowCount();
	}
	
	public static function ManipularImagem($ImageURL, $ImageDesc, $ImageW = null, $ImageH = null){
		self::$Data = 'uploads/'.$ImageURL;
		
		if (file_exists(self::$Data) && !is_dir(self::$Data)):
			$Pacth = HOME;
			$Imagem = self::$Data;
			return "<img src=\"{$Pacth}tim.php?src={$Pacth}/{$Imagem}&h={$ImageH}&w={$ImageW}\" alt=\"{$ImageDesc}\" title=\"{$ImageDesc}\">";
		else:
			echo "Caminho ou Imagem especificado errado";
		endif;
	}
    
    public static function GetAtributo($atributoName){
        $Read = new Read();
        $Read->ExeRead("".PREFIX."atributos", "WHERE atributo = :attb" , "attb={$atributoName}");
        return $Read->getResult()[0]["valor"];
    }

    public static function UpdateAtributos($ArrayAtributos){
        try{
            $Update = new Update();
            foreach ($ArrayAtributos as $k => $v) {
                $att['atributo'] = $k;
                $att['valor'] = $v;
                $Update->ExeUpdate(PREFIX."atributos", $att, "WHERE atributo = :id", "id={$k}");    
            }   
         }catch(Exception $e) {
                    echo 'Excepción na clase Check atualizando os atributos: ',  $e->getMessage(), "\n";
         }   
    }
 
}

?>
