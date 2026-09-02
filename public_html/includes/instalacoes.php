<section class='bloco-conteudo'>

	<div class='bloco-conteudo-padding'>
		<div class="col-md-8 col-sm-12 titulo">
			<h1>Atendimento de qualidade e um serviço de excelência! <span class="tag">#150AnosporVoce</span></h1>
			<div class="barra">
				<div class="parte1"></div>
				<div class="parte2"></div>
			</div>
		</div>

		<p class="col-md-4 col-sm-12 contra-titulo">
			Estrutura de ponta e profissionais qualificados.<br/>
			De portas e braços abertos pra você!
		</p>
	</div>

	<div class="clear"></div>

	<div class='bloco-instalacoes'>

		<?php 

			$getServicos = new Read();
			$getServicos->fullRead("SELECT * FROM ".PREFIX."paginas WHERE url_amigavel = 'clinica_emilia' OR url_amigavel = 'hotelaria' OR url_amigavel = 'pronto_atendimento' OR url_amigavel = 'unidade_internacao' OR url_amigavel = 'centro_diagnostico_por_imagem'");
			if($getServicos->getResult()){
			
				echo "<div class='carousel-instalacoes'>";
				$contador = 1;
				foreach($getServicos->getResult() AS $instalacao){

					//($instalacao['url_amigavel'] ? "???" : (mb_strlen(strip_tags($instalacao['sub_titulo'], '<(.*?)>')) > 70 ? mb_substr(strip_tags($instalacao['sub_titulo'], '<(.*?)>') ,0,70)."..." : strip_tags($instalacao['sub_titulo'], '<(.*?)>')))

					echo "<div style='padding-bottom:5px'>
						<div class='instalacoes'>
							<div class='img' style=\"background-image: url('".ROOT."resources/img/carouselInstalacoes/img0".$contador.".jpg');\" alt='{$instalacao['nome_pagina']}'></div>
							<div class='textos'>
								<h2 class='titulo'>
									".Check::geraTitulo($instalacao['nome_pagina'])."
								</h2>
								<div class='barra barra-half'>
									<div class='parte1'></div>
									<div class='parte2'></div>
								</div>
								<p>
								";
								// var_dump($instalacao);
							
								switch ($instalacao['url_amigavel']) {
									case 'clinica_emilia':
										echo "Equipe médica especializada de alto nível técnico garantindo assim o atendimento com qualidade e satisfação.";
										break;
									case 'hotelaria':
										echo "Satisfazendo todas as necessidades dos pacientes bem como a integridade física.";
										break;
									case 'pronto_atendimento':
										echo "Mais de 25 mil casos por ano, situações de urgência e emergência.";
										break;
									case 'centro_diagnostico_por_imagem':
										echo "Planejadas para oferecer aos nossos pacientes todo acolhimento e segurança necessários.";
										break;
									case 'unidade_internacao':
										echo "Ambiente climatizado, moderno e médicos altamente capacitados, prontos para um serviço de excelência.";
										break;
								}

								echo "
								</p>
								<a href='instalacoes/{$instalacao['url_amigavel']}' class='saiba_mais'>saiba mais <i class='fa fa-fw fa-angle-right'></i> </a>
							</div>
							<div class='clear'></div>
						</div>

					</div>";

					$contador++;
				}

				echo "</div>";

			}

		?>

	</div>
</section>
<div class="clear"></div>