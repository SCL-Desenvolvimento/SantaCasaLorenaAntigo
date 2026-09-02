<?php 

	//echo EMKT_HEADER.EMKT_FOOTER;

	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_doacao ORDER BY data DESC LIMIT 1");
	$info = $getPagina->getResult()[0];
?>

<section class='bloco-conteudo'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo'>
		<div class="titulos">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<h2><?php echo nl2br($info['bloco1']); ?></h2>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
		<div class="textos">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<p class="fonte2"><?php echo nl2br($info['bloco2']); ?></p>
			</div>
			<div class="clearBoth"></div>
		</div>
	</div>
</section>
<div class="clearBoth"></div>

<div class='bloco-conteudo'>
	<div class='conteudo-paginas-padding bg-cinza form-contato'>

		<div class="col-md-4 col-sm-12 titulo">
			<h2>seja um doador</h2>
			<div class="barra">
				<div class="parte1"></div>
				<div class="parte2"></div>
			</div>
		</div>

		<?php 

		if(isset($dados['g-recaptcha-response'])){

			// verifique a chave secreta
			$reCaptcha = new ReCaptcha($secret);

			$response = $reCaptcha->verifyResponse(
		        $_SERVER["REMOTE_ADDR"],
		        $_POST["g-recaptcha-response"]
		    );

			if($response != null && $response->success) {

				$createContato = new Create;
				unset($dados['g-recaptcha-response']);
				$dados['data_cadastro'] = date("Y-m-d H:i:s");

				if(isset($dados['form']) && $dados['form'] == "deposito"){
					unset($dados['form']);

					$dados['tipo'] = "deposito";

					$createContato->ExeCreate(PREFIX."doacoes", $dados);

					if($createContato->getResult()){

						$Message = EMKT_HEADER."<table width='700' border='0' cellspacing='30' cellpadding='0' class='conteudo white' align='center' bgcolor='#FFFFFF'>
							<tr>
								<td align='center'>
									<img width='200' src='http://www.santacasalorena.org.br/novo/resources/img/logo.jpg'>
								</td>
							</tr>
							<tr>
								<td align='center'>
									<h2>Um novo contato de doação via DEPÓSITO BANCÁRIO foi realizado através do site</h2>
								</td>
							</tr>
							<tr>
								<td class='linha'></td>
							</tr>
							<tr>
								<td>
									<h2>NOME</h2>
									<p>{$dados['nome']}</p>
								</td>
							</tr>
							<tr>
								<td>
									<h2>E-MAIL</h2>
									<p>{$dados['email']}</p>
								</td>
							</tr>
							<tr>
								<td>
									<h2>CIDADE</h2>
									<p>{$dados['cidade']}</p>
								</td>
							</tr>
							<tr>
								<td>
									<h2>ASSUNTO</h2>
									<p>{$dados['assunto']}</p>
								</td>
							</tr>
							<tr>
								<td>
									<h2>MENSAGEM</h2>
									<p>{$dados['mensagem']}</p>
								</td>
							</tr>
						</table>".EMKT_FOOTER;
				       
				        $body = utf8_decode($Message);
				        $mail = new PHPMailer();
				        $nomeRemetente = 'Santa Casa de Lorena';
				        $mail->Subject =  utf8_decode("Depósito Bancário - Nova doação através site");

				        if(HOME == 'http://172.16.0.123/SCL/'):

				          $usuario = 'pentaxialdev@gmail.com';
				          $To = $_POST['email'];

				          $mail->SetFrom($usuario, utf8_decode($nomeRemetente));
				          $mail->IsSMTP();
				          //$mail->SMTPDebug = 6;
				          $mail->SMTPSecure = 'tls'; 
				          $mail->Port = 25; //Indica a porta de conexão para a saída de e-mails
				          $mail->Host = 'smtp.gmail.com'; //smtp.dominio.com.br
				          $mail->SMTPAuth = true; //define se haverá ou não autenticação no SMTP
				          $mail->Username = $usuario;
				          $mail->Password = 'PX705co*10';
				      
				        else:

				          	$usuario = 'webmaster@santacasalorena.org.br';
				        	//$To = $localizacao['email'];
				        	$To = "secretaria@santacasalorena.org.br";

				        endif;

				        $mail->SetFrom($usuario, utf8_decode($nomeRemetente));

				        $mail->MsgHTML($body);
				        $mail->AddAddress($To, "");

				        if($mail->Send()){
				        	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro' style='color:#00a65a !important;'>E-mail enviado com sucesso</label></div>";
				        }else{
				        	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro'>Erro ao enviar e-mail</label></div>";
				        }
				    }else{
				    	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro'>Erro ao enviar e-mail</label></div>";
				    }

				}elseif(isset($dados['form']) && $dados['form'] == "boleto"){
					unset($dados['form']);

					$dados['tipo'] = "boleto";

					$createContato->ExeCreate(PREFIX."doacoes", $dados);

					if($createContato->getResult()){

						$Message = EMKT_HEADER."<table width='700' border='0' cellspacing='30' cellpadding='0' class='conteudo white' align='center' bgcolor='#FFFFFF'>
							<tr>
								<td align='center'>
									<img width='200' src='http://www.santacasalorena.org.br/novo/resources/img/logo.jpg'>
								</td>
							</tr>
							<tr>
								<td align='center'>
									<h2>Um novo contato de doação via BOLETO BANCÁRIO foi realizado através do site</h2>
								</td>
							</tr>
							<tr>
								<td class='linha'></td>
							</tr>
							<tr>
								<td>
									<h2>NOME</h2>
									<p>{$dados['nome']}</p>
								</td>
							</tr>
							<tr>
								<td>
									<h2>E-MAIL</h2>
									<p>{$dados['email']}</p>
								</td>
							</tr>
							<tr>
								<td>
									<h2>CIDADE</h2>
									<p>{$dados['cidade']}</p>
								</td>
							</tr>
							<tr>
								<td>
									<h2>ASSUNTO</h2>
									<p>{$dados['assunto']}</p>
								</td>
							</tr>
							<tr>
								<td>
									<h2>MENSAGEM</h2>
									<p>{$dados['mensagem']}</p>
								</td>
							</tr>
						</table>".EMKT_FOOTER;
				       
				        $body = utf8_decode($Message);
				        $mail = new PHPMailer();
				        $nomeRemetente = 'Santa Casa de Lorena';
				        $mail->Subject =  utf8_decode("Boleto Bancário - Nova doação através site");

				        if(HOME == 'http://172.16.0.123/SCL/'):

				          $usuario = 'pentaxialdev@gmail.com';
				          $To = $_POST['email'];

				          $mail->SetFrom($usuario, utf8_decode($nomeRemetente));
				          $mail->IsSMTP();
				          //$mail->SMTPDebug = 6;
				          $mail->SMTPSecure = 'tls'; 
				          $mail->Port = 25; //Indica a porta de conexão para a saída de e-mails
				          $mail->Host = 'smtp.gmail.com'; //smtp.dominio.com.br
				          $mail->SMTPAuth = true; //define se haverá ou não autenticação no SMTP
				          $mail->Username = $usuario;
				          $mail->Password = 'PX705co*10';
				      
				        else:

				          	$usuario = 'webmaster@santacasalorena.org.br';
				        	//$To = $localizacao['email'];
				        	$To = "secretaria@santacasalorena.org.br";

				        endif;

				        $mail->SetFrom($usuario, utf8_decode($nomeRemetente));

				        $mail->MsgHTML($body);
				        $mail->AddAddress($To, "");

				        if($mail->Send()){
				        	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro' style='color:#00a65a !important;'>E-mail enviado com sucesso</label></div>";
				        }else{
				        	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro'>Erro ao enviar e-mail</label></div>";
				        }
				    }else{
				    	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro'>Erro ao enviar e-mail</label></div>";
				    }
				}
			}else{
				echo "<div class='col-md-12 col-sm-12'><label class='msg-erro'>PREENCHA O CAPTCHA CORRETAMENTE</label></div>";
			}
		}

		?>

		<div class="col-md-12 col-sm-12 fonte2" id="bloco_deposito_texto"><?php echo nl2br($info['bloco3']); ?></div>

		<div class="col-md-12 col-sm-12">

			<div class='btn-fale_conosco contato-ativo' id="deposito" style="min-width: 270px;">
				<div class='icon deposito'></div>
				<p>Depósito Bancário</p>
				<span class="seta-baixo"></span>
			</div>

			<div class='btn-fale_conosco' id="boleto">
				<div class='icon boleto'></div>
				<p>Boleto Bancário</p>
				<span class="seta-baixo"></span>
			</div>

		</div>

		<div class="col-md-12 col-sm-12" id="bloco_deposito" style="padding-left: 0; padding-right: 0; ">
			<form method="post" id="form_contato" action="<?php echo (isset($_SERVER['QUERY_STRING']) ? HOME.str_replace("qs=", "", $_SERVER['QUERY_STRING']) : "" ); ?>">
				
				<input type="hidden" name="form" value="deposito">
				<input type="hidden" id="deposito-recaptcha" name="g-recaptcha-response">

				<div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="nome" id='nome-d' placeholder="nome*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="email" id="email-d" placeholder="email*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="cidade" id='cidade-d' placeholder="cidade*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12">
	                <input class="form-control" name="assunto" id='assunto-d' placeholder="assunto*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12">
	                <textarea class="form-control" name="mensagem" id='mensagem-d' placeholder="mensagem*" style="height: 250px;" ></textarea>
	                <label class="msg-erro"></label>
	            </div>

            	<input type="submit" class='btn-fale_conosco' value="enviar">
            </form>
		</div>

		<div class="col-md-12 col-sm-12" id="bloco_boleto" style="padding-left: 0; padding-right: 0; display: none;">
			<form method="post" id="form_boleto" action="<?php echo (isset($_SERVER['QUERY_STRING']) ? HOME.str_replace("qs=", "", $_SERVER['QUERY_STRING']) : "" ); ?>">
				
				<input type="hidden" name="form" value="boleto">
				<input type="hidden" id="boleto-recaptcha" name="g-recaptcha-response">

				<div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="nome" id='nome-b' placeholder="nome*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="email" id="email-b" placeholder="email*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="cidade" id='cidade-b' placeholder="cidade*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12">
	                <input class="form-control" name="assunto" id='assunto-b' placeholder="assunto*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12">
	                <textarea class="form-control" name="mensagem" id='mensagem-b' placeholder="mensagem*" style="height: 250px;" ></textarea>
	                <label class="msg-erro"></label>
	            </div>

	            <input type="submit" class='btn-fale_conosco' value="enviar">
	        </form>
		</div>

	    <div style="padding:0 15px;"><div class="g-recaptcha" data-sitekey="6LfM4TkUAAAAABw-GkATRquqIuSgp_KSKYN_XWjO"></div></div>
	    <div class="clear"></div>

	</div>

</div>

<script type="text/javascript">
	
	$(document).ready(function(){

		$( "#deposito" ).on( "click", function() {

			$("#boleto").removeClass("contato-ativo");
			$("#bloco_boleto").fadeOut();

			$(this).addClass("contato-ativo");
			$("#bloco_deposito").fadeIn();
		});

		$( "#boleto" ).on( "click", function() {

			$("#deposito").removeClass("contato-ativo");
			$("#bloco_deposito").fadeOut();

			$(this).addClass("contato-ativo");
			$("#bloco_boleto").fadeIn();
		});
	});

	$("#form_contato").on('submit', function (e) {

		$("#deposito-recaptcha").val($("#g-recaptcha-response").val());

	    e.preventDefault();

		$(".form-group").removeClass("has-warning").find(".msg-erro").attr("style","").text("");

	    if($('#nome-d').val() == ""){

	        $('#nome-d').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um nome válido");
	    }else if($('#email-d').val() == "" || !validaEmail($('#email-d').val())){

	        $('#email-d').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um e-mail válido");
	    }else if($('#cidade-d').val() == ""){

	        $('#cidade-d').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um cidade válida");
	    }else if($('#assunto-d').val() == ""){

	        $('#assunto-d').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um assunto válido");
	    }else if($('#mensagem-d').val() == ""){

	        $('#mensagem-d').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira uma mensagem válida");
	    }else{
	                
	        $(this).off('submit').submit();  
	    }
	});

	$("#form_boleto").on('submit', function (e) {

		$("#boleto-recaptcha").val($("#g-recaptcha-response").val());

	    e.preventDefault();

		$(".form-group").removeClass("has-warning").find(".msg-erro").attr("style","").text("");

	    if($('#nome-b').val() == ""){

	        $('#nome-b').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um nome válido");
	    }else if($('#email-b').val() == "" || !validaEmail($('#email-b').val())){

	        $('#email-b').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um e-mail válido");
	    }else if($('#cidade-b').val() == ""){

	        $('#cidade-b').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um cidade válida");
	    }else if($('#assunto-b').val() == ""){

	        $('#assunto-b').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um assunto válido");
	    }else if($('#mensagem-b').val() == ""){

	        $('#mensagem-b').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira uma mensagem válida");
	    }else{
	                
	        $(this).off('submit').submit();  
	    }
	});

</script>