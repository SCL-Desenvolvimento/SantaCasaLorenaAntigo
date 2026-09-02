<style type="text/css">

	@media screen and (max-width: 1680px) {
		.conteudo-paginas-padding{
			padding-left: 90px;
			padding-right: 90px;
		}
	}
	@media screen and (max-width: 1200px) {
		.conteudo-paginas-padding{
			padding-left: 15px;
			padding-right: 15px;
		}
	}
	@media screen and (max-width: 420px) {
		#pesquisa{
			max-width: 220px !important;
		}
		#pesquisa p{
			margin-top: 5px;
			margin-bottom: 0;
			max-width: 120px !important;
		}
	}
	

</style>

<?php 
	
	if(isset($_SESSION['tb'])){
		unset($_SESSION['tb']);
		$tb = true;
	}

	if(isset($_SESSION['pa'])){
		unset($_SESSION['pa']);
		$pa = true;
	}

	$getOuvidoria = new Read();
	$getOuvidoria->fullRead("SELECT * FROM ".PREFIX."paginas WHERE url_amigavel = 'ouvidoria'");
	$ouvidoria = $getOuvidoria->getResult()[0]['descricao'];

	$getTrabalheConosco = new Read();
	$getTrabalheConosco->fullRead("SELECT * FROM ".PREFIX."paginas WHERE url_amigavel = 'trabalhe_conosco'");
	$trabalhe_conosco = $getTrabalheConosco->getResult()[0]['descricao'];

	$getPesquisaAtendimento = new Read();
	$getPesquisaAtendimento->fullRead("SELECT * FROM ".PREFIX."paginas WHERE url_amigavel = 'pesquisa_atendimento'");
	$pesquisa_atendimento = $getPesquisaAtendimento->getResult()[0]['descricao'];
?>

<div class='bloco-conteudo localizacao'>

	<div class='conteudo-paginas-padding' style="padding-top: 40px;">

		<div class="col-md-4 col-sm-12">
			<center>
				<img src="<?php echo ROOT; ?>resources/img/localizacao02.svg" alt="Localização - Santa Casa de Lorena" class="img-responsive" alt='Localização - Santa Casa de Lorena' style='max-width: 53px;'>
				<h2>Localização</h2>
				<h3 class="fonte2"><?php echo $localizacao['localizacao']; ?></h3>
			</center>
		</div>

		<div class="col-md-4 col-sm-12">
			<center>
				<img src="<?php echo ROOT; ?>resources/img/telefone02.svg" alt="Localização - Santa Casa de Lorena" class="img-responsive" alt='Telefone - Santa Casa de Lorena' style='max-width: 53px;'>
				<h2>Telefone</h2>
				<h3 class="fonte2" style="margin-bottom: 0px;"><?php echo $localizacao['telefone']; ?></h3>
				<h3 class="fonte2">(12) 3159-3344</h3>
			</center>
		</div>

		<div class="col-md-4 col-sm-12">
			<center>
				<img src="<?php echo ROOT; ?>resources/img/email02.svg" alt="Email - Santa Casa de Lorena" class="img-responsive" alt='E-mail - Santa Casa de Lorena' style='max-width: 53px;'>
				<h2>E-mail</h2>
				<h3 class="fonte2"><?php echo $localizacao['email']; ?></h3>
			</center>
		</div>

	</div>

	<div class="clear"></div>
</div>

<div class='bloco-conteudo'>
	<div class='conteudo-paginas-padding bg-cinza form-contato'>

		<div class="col-md-4 col-sm-12 titulo">
			<h2>entre em contato</h2>
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

			if(($response != null && $response->success)) {

				$createContato = new Create;
				unset($dados['g-recaptcha-response']);
				$dados['data_cadastro'] = date("Y-m-d H:i:s");

				if(isset($dados['form']) && $dados['form'] == "contato"){
					unset($dados['form']);

					$createContato->ExeCreate(PREFIX."ouvidoria", $dados);

					if($createContato->getResult()){

						$Message = EMKT_HEADER."<table width='700' border='0' cellspacing='30' cellpadding='0' class='conteudo white' align='center' bgcolor='#FFFFFF'>
							<tr>
								<td align='center'>
									<img width='200' src='http://www.santacasalorena.org.br/novo/resources/img/logo.jpg'>
								</td>
							</tr>
							<tr>
								<td align='center'>
									<h2>Um novo contato foi realizado através do site</h2>
								</td>
							</tr>
							<tr>
								<td class=''><hr></td>
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
									<h2>RAZÃO</h2>
									<p>{$dados['razao']}</p>
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
				        $mail->Subject =  utf8_decode("Ouvidoria - Novo contato através do site");

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
				        	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro msg-envio' style='color:#00a65a !important;'>E-mail enviado com sucesso</label></div>";
				        }else{
				        	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro msg-erro-envio'>Erro ao enviar e-mail</label></div>";
				        }
				    }else{
				    	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro msg-erro-envio'>Erro ao enviar e-mail</label></div>";
				    }

				}elseif(isset($dados['form']) && $dados['form'] == "trabalhe_conosco"){
					unset($dados['form']);

					$getCurriculum= new Upload("arquivos");
					$getCurriculum->File($_FILES['curriculum-tc'], Check::urlAmigavel($dados['nome']."_".date("dmY")), "/curriculuns", null);

					if($getCurriculum->getResult()){

						$dados['curriculum'] = $getCurriculum->getResult();
						$createContato->ExeCreate(PREFIX."trabalhe_conosco", $dados);

						if($createContato->getResult()){

							$Message = EMKT_HEADER."<table width='700' border='0' cellspacing='30' cellpadding='0' class='conteudo white' align='center' bgcolor='#FFFFFF'>
								<tr>
									<td align='center'>
										<img width='200' src='http://www.santacasalorena.org.br/novo/resources/img/logo.jpg'>
									</td>
								</tr>
								<tr>
									<td align='center'>
										<h2>Um novo contato de TRABALHO CONOSCO foi realizado através do site</h2>
									</td>
								</tr>
								<tr>
									<td class=''><hr></td>
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
							</table>".EMKT_FOOTER;
					       
					        $body = utf8_decode($Message);
					        $mail = new PHPMailer();
					        $nomeRemetente = 'Santa Casa de Lorena';
					        $mail->Subject =  utf8_decode("Trabalhe Conosco - Novo contato através do site");

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
					        	$To = "rh.sl@santacasalorena.org.br";

					        endif;

					        $mail->SetFrom($usuario, utf8_decode($nomeRemetente));

					        $mail->MsgHTML($body);
					        $mail->AddAddress($To, "");
					        $mail->AddAttachment($getCurriculum->getResult());

					        if($mail->Send()){
					        	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro msg-envio' style='color:#00a65a !important;'>E-mail enviado com sucesso</label></div>";
					        }else{
					        	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro msg-erro-envio'>Erro ao enviar e-mail</label></div>";
					        }
					    }else{
					    	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro msg-erro-envio'>Erro ao enviar e-mail</label></div>";
					    }
				    }else{
				    	echo "falha no upload do arquivo ou arquivo é válido";
				    }
				}elseif(isset($dados['form']) && $dados['form'] == "pesquisa"){
					unset($dados['form']);

					$createContato->ExeCreate(PREFIX."pesquisa_atendimento", $dados);

					if($createContato->getResult()){

						$Message = EMKT_HEADER."<table width='700' border='0' cellspacing='30' cellpadding='0' class='conteudo white' align='center' bgcolor='#FFFFFF'>
							<tr>
								<td align='center'>
									<img width='200' src='http://www.santacasalorena.org.br/novo/resources/img/logo.jpg'>
								</td>
							</tr>
							<tr>
								<td align='center'>
									<h2>Um novo contato foi realizado através do site</h2>
								</td>
							</tr>
							<tr>
								<td class=''><hr></td>
							</tr>

							<tr>
								<td>
									<h2>1.) Quanto tempo você teve que esperar para o primeiro atendimento na Santa Casa de Lorena?</h2>
									<p>{$dados['tempo_espera']}</p>
								</td>
							</tr>

							<tr>
								<td>
									<h2>2.) Avalie o nosso atendimento de 0 (muito ruim) a 5 (excelente):</h2>
									<p>{$dados['nota_atendimento']}</p>
								</td>
							</tr>

							<tr>
								<td>
									<h2>3.) Com que rapidez a equipe de atendimento resolveu o problema?</h2>
									<p>{$dados['resolucao_problema']}</p>
								</td>
							</tr>

							<tr>
								<td>
									<h2>4.) Quão preparada estava nossa equipe de atendimento?</h2>
									<p>{$dados['preparo_atendimento']}</p>
								</td>
							</tr>

							<tr>
								<td>
									<h2>5.) As informações passadas foram claras?</h2>
									<p>{$dados['informacoes_passadas']}</p>
								</td>
							</tr>

							<tr>
								<td>
									<h2>6.) Quantas de suas perguntas foram respondidas pela equipe de atendimento?</h2>
									<p>{$dados['perguntas_respondidas']}</p>
								</td>
							</tr>

							<tr>
								<td>
									<h2>7.) Sua experiência com o nosso atendimento foi melhor ou pior do que você esperava?</h2>
									<p>{$dados['experiencia']}</p>
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
				        $mail->Subject =  utf8_decode("Pesquisa de atendimento - Novo contato através do site");

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
				        	//$To = "contato@pentaxial.com.br";

				        endif;

				        $mail->SetFrom($usuario, utf8_decode($nomeRemetente));

				        $mail->MsgHTML($body);
				        $mail->AddAddress($To, "");

				        if($mail->Send()){
				        	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro msg-envio' style='color:#00a65a !important;'>E-mail de pesquisa de atendimento enviado com sucesso</label></div>";
				        }else{
				        	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro msg-erro-envio'>Erro ao enviar e-mail</label></div>";
				        }
				    }else{
				    	echo "<div class='col-md-12 col-sm-12'><label class='msg-erro msg-erro-envio'>Erro ao enviar e-mail</label></div>";
				    }

				}
			}else{
				echo "<div class='col-md-12 col-sm-12'><label class='msg-erro msg-erro-envio'>PREENCHA O CAPTCHA CORRETAMENTE</label></div>";
			}
		}

		?>

		<div class="col-md-12 col-sm-12 fonte2" id="bloco_ouvidoria_texto"><?php echo $ouvidoria; ?></div>
		<div class="col-md-12 col-sm-12 fonte2" id="bloco_trabalhe-conosco_texto" style="display: none;"><?php echo $trabalhe_conosco; ?></div>
		
		<div class="col-md-12 col-sm-12">

			<div class='btn-fale_conosco <?php echo (isset($tb) || isset($pa) ? "" : "contato-ativo" ); ?>' id="ouvidoria" style="max-width: 190px;">
				<div class='icon ouvidoria'></div>
				<p>Ouvidoria</p>
				<span class="seta-baixo"></span>
			</div>

			<div class='btn-fale_conosco <?php echo (isset($tb) ? "contato-ativo" : "" ); ?>' id="trabalhe-conosco">
				<div class='icon trabalhe-conosco'></div>
				<p>Trabalhe conosco</p>
				<span class="seta-baixo"></span>
			</div>

			
			<div class='btn-fale_conosco <?php echo (isset($pa) ? "contato-ativo" : "" ); ?>' id="pesquisa" style="width: 340px !important;">
				<div class='icon pesquisa'></div>
				<p>Pesquisa de Atendimento</p>
				<span class="seta-baixo"></span>
			</div>
			

		</div>

		<div class="col-md-12 col-sm-12 fonte2" id="bloco_pesquisa_atendimento_texto" style="<?php echo (isset($pa) ? "" : "display: none;" ); ?> font-family: 'Montserrat', sans-serif;">
			<b><?php echo $pesquisa_atendimento; ?></b>
			<br><br>
		</div>

		<div class="col-md-12 col-sm-12" id="bloco_ouvidoria" style="padding-left: 0; padding-right: 0; <?php echo (isset($tb) || isset($pa) ? "display: none;" : "" ); ?>">
			<form method="post" id="form_contato" action="<?php echo (isset($_SERVER['QUERY_STRING']) ? HOME.str_replace("qs=", "", $_SERVER['QUERY_STRING']) : "" ); ?>">
				
				<input type="hidden" name="form" value="contato">
				<input type="hidden" id="ouvidoria-recaptcha" name="g-recaptcha-response">

				<div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="nome" id='nome-o' placeholder="nome*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="email" id="email-o" placeholder="email*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="cidade" id='cidade-o' placeholder="cidade*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-6 col-sm-12">
	                <input class="form-control" name="assunto" id='assunto-o' placeholder="assunto*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-6 col-sm-12">
	                <input class="form-control" name="razao" id='razao-o' placeholder="razao para contato*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12">
	                <textarea class="form-control" name="mensagem" id='mensagem-o' placeholder="mensagem*" style="height: 250px;" ></textarea>
	                <label class="msg-erro"></label>
	            </div>

            	<input type="submit" class='btn-fale_conosco' value="enviar">
            </form>
		</div>

		<div class="col-md-12 col-sm-12" id="bloco_trabalhe-conosco" style="padding-left: 0; padding-right: 0; <?php echo (isset($tb) ? "" : "display: none;" ); ?>">
			<form method="post" id="form_trabalhe-conosco" action="<?php echo (isset($_SERVER['QUERY_STRING']) ? HOME.str_replace("qs=", "", $_SERVER['QUERY_STRING']) : "" ); ?>" enctype="multipart/form-data">
				
				<input type="hidden" name="form" value="trabalhe_conosco">
				<input type="hidden" id="trabalhe-conosco-recaptcha" name="g-recaptcha-response">

				<div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="nome" id='nome-tc' placeholder="nome*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="email" id='email-tc' id="email" placeholder="email*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-4 col-sm-12">
	                <input class="form-control" name="cidade" id='cidade-tc' placeholder="cidade*">
	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12" style="min-height: 40px;">
                  <label for="exampleInputFile">Curriculum (PDF)</label>
                  <input type="file" name='curriculum-tc' id='curriculum-tc' style="height: 20px;">
                  <label class="msg-erro"></label>
                </div>

	            <input type="submit" class='btn-fale_conosco' value="enviar">
	        </form>
		</div>

		<div class="col-md-12 col-sm-12" id="bloco_pesquisa" style="padding-left: 0; padding-right: 0; <?php echo (isset($pa) ? "" : "display: none;" ); ?>">
			<form method="post" id="form_pesquisa" action="<?php echo (isset($_SERVER['QUERY_STRING']) ? HOME.str_replace("qs=", "", $_SERVER['QUERY_STRING']) : "" ); ?>">
				
				<input type="hidden" name="form" value="pesquisa">
				<input type="hidden" id="pesquisa-recaptcha" name="g-recaptcha-response">

				<div class="form-group col-md-12 col-sm-12 questao">
	                <p>1.) Quanto tempo você teve que esperar para o primeiro atendimento na Santa Casa de Lorena?</p>

	                <label><input type='radio' name='tempo_espera' value="Minutos"><p>Minutos</p></label>
	                <label><input type='radio' name='tempo_espera' value="Horas"><p>Horas</p></label>

	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12 questao">
	                <p>2.) Avalie o nosso atendimento de 0 (muito ruim) a 5 (excelente):</p>

	                <label><input type='radio' name='nota_atendimento' value="1"><p>1</p></label>
	                <label><input type='radio' name='nota_atendimento' value="2"><p>2</p></label>
	                <label><input type='radio' name='nota_atendimento' value="3"><p>3</p></label>
	                <label><input type='radio' name='nota_atendimento' value="4"><p>4</p></label>
	                <label><input type='radio' name='nota_atendimento' value="5"><p>5</p></label>

	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12 questao">
	                <p>3.) Com que rapidez a equipe de atendimento resolveu o problema?</p>

	                <label><input type='radio' name='resolucao_problema' value="Imediatamente"><p>Imediatamente</p></label>
	                <label><input type='radio' name='resolucao_problema' value="Em poucas horas"><p>Em poucas horas</p></label>
	                <label><input type='radio' name='resolucao_problema' value="Em poucos minutos"><p>Em poucos minutos</p></label>
	                <label><input type='radio' name='resolucao_problema' value="Não foi resolvido"><p>Não foi resolvido</p></label>

	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12 questao">
	                <p>4.) Quão preparada estava nossa equipe de atendimento?</p>

	                <label><input type='radio' name='preparo_atendimento' value="Extremamente preparados"><p>Extremamente preparados</p></label>
	                <label><input type='radio' name='preparo_atendimento' value="Muito preparados"><p>Muito preparados</p></label>
	                <label><input type='radio' name='preparo_atendimento' value="Preparados"><p>Preparados</p></label>
	                <label><input type='radio' name='preparo_atendimento' value="Pouco preparados"><p>Pouco preparados</p></label>
	                <label><input type='radio' name='preparo_atendimento' value="Nada preparados"><p>Nada preparados</p></label>

	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12 questao">
	                <p>5.) As informações passadas foram claras?</p>

	                <label><input type='radio' name='informacoes_passadas' value="Sim"><p>Sim</p></label>
	                <label><input type='radio' name='informacoes_passadas' value="Moderadamente"><p>Moderadamente</p></label>
	                <label><input type='radio' name='informacoes_passadas' value="Não"><p>Não</p></label>

	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12 questao">
	                <p>6.) Quantas de suas perguntas foram respondidas pela equipe de atendimento?</p>

	                <label><input type='radio' name='perguntas_respondidas' value="Todas"><p>Todas</p></label>
	                <label><input type='radio' name='perguntas_respondidas' value="A maioria"><p>A maioria</p></label>
	                <label><input type='radio' name='perguntas_respondidas' value="Nenhuma"><p>Nenhuma</p></label>

	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12 questao">
	                <p>7.) Sua experiência com o nosso atendimento foi melhor ou pior do que você esperava?</p>

	                <label><input type='radio' name='experiencia' value="Muito melhor"><p>Muito melhor</p></label>
	                <label><input type='radio' name='experiencia' value="Mais ou menos o que esperava"><p>Mais ou menos o que esperava</p></label>
	                <label><input type='radio' name='experiencia' value="Muito pior"><p>Muito pior</p></label>

	                <label class="msg-erro"></label>
	            </div>

	            <div class="form-group col-md-12 col-sm-12">
	            	<p>Tem alguma observação? Escreva-a abaixo:</p>
	                <textarea class="form-control" name="mensagem" id='mensagem-o' placeholder="mensagem*" style="height: 250px;" ></textarea>
	                <label class="msg-erro"></label>
	            </div>

            	<input type="submit" class='btn-fale_conosco' value="enviar">
            </form>
		</div>

	    <div style="padding:0 15px;"><div class="g-recaptcha" data-sitekey="6LfM4TkUAAAAABw-GkATRquqIuSgp_KSKYN_XWjO"></div></div>
	    <div class="clear"></div>

	</div>

	<div>

		<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDlhyOX-3idOmnw4PDc052pZUL24iWKhBA"></script>
		<script>

			var geocoder;
			var map;
			function initialize() {
			  geocoder = new google.maps.Geocoder();
			  var latlng = new google.maps.LatLng(14.2392976, -53.1805017);
			  var mapOptions = {
			    zoom: 18,
			    center: latlng
			  }
			  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			  codeAddress();
			}

			function codeAddress() {
			  var address = document.getElementById('address').value;
			  geocoder.geocode( { 'address': address}, function(results, status) {
			    if (status == google.maps.GeocoderStatus.OK) {
			      map.setCenter(results[0].geometry.location);
			      var marker = new google.maps.Marker({
			          map: map,
			          position: results[0].geometry.location
			      });
			    } else {
			      alert('Os dados de geocalização não conferem: ' + status);
			    }
			  });
			}

			google.maps.event.addDomListener(window, 'load', initialize);

    	</script>

        <input type="hidden" id="address" value="<?php echo $localizacao['localizacao']; ?>">
        <div id="map-canvas"></div>

	</div>

</div>

<script type="text/javascript">
	
	$(document).ready(function(){

		$( "#ouvidoria" ).on( "click", function() {

			$("#trabalhe-conosco").removeClass("contato-ativo");
			$("#bloco_trabalhe-conosco_texto").hide();

			$("#pesquisa").removeClass("contato-ativo");
			$("#bloco_pesquisa_atendimento_texto").hide();
			

			$("#bloco_pesquisa").hide();
			$("#bloco_trabalhe-conosco").hide();

			$(this).addClass("contato-ativo");
			$("#bloco_ouvidoria_texto").show();
			$("#bloco_ouvidoria").fadeIn();
		});

		$( "#trabalhe-conosco" ).on( "click", function() {

			$("#ouvidoria").removeClass("contato-ativo");
			$("#bloco_ouvidoria_texto").hide();

			$("#pesquisa").removeClass("contato-ativo");
			$("#bloco_pesquisa_atendimento_texto").hide();


			$("#bloco_pesquisa").hide();
			$("#bloco_ouvidoria").hide();

			$(this).addClass("contato-ativo");
			$("#bloco_trabalhe-conosco_texto").show();
			$("#bloco_trabalhe-conosco").fadeIn();
		});

		$( "#pesquisa" ).on( "click", function() {

			$("#trabalhe-conosco").removeClass("contato-ativo");
			$("#bloco_trabalhe-conosco_texto").hide();

			$("#ouvidoria").removeClass("contato-ativo");
			$("#bloco_ouvidoria_texto").hide();

			
			$("#bloco_ouvidoria").hide();
			$("#bloco_trabalhe-conosco").hide();

			$(this).addClass("contato-ativo");
			$("#bloco_pesquisa_atendimento_texto").show();
			$("#bloco_pesquisa").fadeIn();
		});

	});

	$("#form_contato").on('submit', function (e) {

		$("#ouvidoria-recaptcha").val($("#g-recaptcha-response").val());

	    e.preventDefault();

		$(".form-group").removeClass("has-warning").find(".msg-erro").attr("style","").text("");

	    if($('#nome-o').val() == ""){

	        $('#nome-o').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um nome válido");
	    }else if($('#email-o').val() == "" || !validaEmail($('#email-o').val())){

	        $('#email-o').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um e-mail válido");
	    }else if($('#cidade-o').val() == ""){

	        $('#cidade-o').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um cidade válida");
	    }else if($('#assunto-o').val() == ""){

	        $('#assunto-o').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um assunto válido");
	    }else if($('#razao-o').val() == ""){

	        $('#razao-o').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira uma razão válida");
	    }else if($('#mensagem-o').val() == ""){

	        $('#mensagem-o').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira uma mensagem válida");
	    }else{
	                
	        $(this).off('submit').submit();  
	    }
	});

	$("#form_trabalhe-conosco").on('submit', function (e) {

		$("#trabalhe-conosco-recaptcha").val($("#g-recaptcha-response").val());

	    e.preventDefault();

		$(".form-group").removeClass("has-warning").find(".msg-erro").attr("style","").text("");

	    if($('#nome-tc').val() == ""){

	        $('#nome-tc').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um nome válido");
	    }else if($('#email-tc').val() == "" || !validaEmail($('#email-tc').val())){

	        $('#email-tc').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um e-mail válido");
	    }else if($('#cidade-tc').val() == ""){

	        $('#cidade-tc').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira uma cidade válida");
	    }else if(!fileValidation(document.getElementById('curriculum-tc'))){

	    	$('#curriculum-tc').focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira um arquivo válido(PDF)");
	    }else{
	                
	        $(this).off('submit').submit();  
	    }
	});

	$("#form_pesquisa").on('submit', function (e) {

		p = document.getElementById('form_pesquisa');

		$("#pesquisa-recaptcha").val($("#g-recaptcha-response").val());

	    e.preventDefault();
	    
		$(".form-group").removeClass("has-warning").find(".msg-erro").attr("style","").text("");

	    if(p.tempo_espera.value == ""){
	        $(p.tempo_espera).focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Selecione uma opção");
	    }else if(p.nota_atendimento.value == ""){

	    	$(p.nota_atendimento).focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Selecione uma opção");
	    }else if(p.resolucao_problema.value == ""){

	    	$(p.resolucao_problema).focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Selecione uma opção");
	    }else if(p.preparo_atendimento.value == ""){

	    	$(p.preparo_atendimento).focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Selecione uma opção");
	    }else if(p.informacoes_passadas.value == ""){

	    	$(p.informacoes_passadas).focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Selecione uma opção");
	    }else if(p.perguntas_respondidas.value == ""){

	    	$(p.perguntas_respondidas).focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Selecione uma opção");
	    }else if(p.experiencia.value == ""){

	    	$(p.experiencia).focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Selecione uma opção");
	    }else if(p.mensagem.value == ""){

	    	$(p.mensagem).focus().closest(".form-group").addClass("has-warning").find(".msg-erro").fadeIn().text("Insira uma mensagem válida");
	    }else{
	                
	        $(this).off('submit').submit();  
	    }
	});

	/*
	function fileValidation(){
	    var fileInput = document.getElementById('curriculum-tc');
	    var filePath = fileInput.value;
	    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
	    if(!allowedExtensions.exec(filePath)){
	        alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
	        fileInput.value = '';
	        return false;
	    }else{
	        //Image preview
	        if (fileInput.files && fileInput.files[0]) {
	            var reader = new FileReader();
	            reader.onload = function(e) {
	                document.getElementById('imagePreview').innerHTML = '<img src="'+e.target.result+'"/>';
	            };
	            reader.readAsDataURL(fileInput.files[0]);
	        }
	    }
	}
	*/

	function fileValidation(elemento){
	    var fileInput = elemento;
	    var filePath = fileInput.value;
	    var allowedExtensions = /(\.pdf)$/i;
	    if(!allowedExtensions.exec(filePath)){
	        fileInput.value = '';
	        return false;
	    }else{
	        return true;
	    }
	}

</script>