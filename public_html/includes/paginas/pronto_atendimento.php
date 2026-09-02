<?php 
	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_pronto_atendimento ORDER BY data DESC LIMIT 1");
	$info = $getPagina->getResult()[0];
?>

<section class='bloco-conteudo'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo'>
		<div class="titulos">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<h2><?php echo nl2br($info['bloco1']); ?></h2>
				<hr>
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
		<div class="clearBoth"></div>
		<div class="textos">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<h3><?php echo nl2br($info['bloco3']); ?></h3>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
	</div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>

<section class='bloco-conteudo'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo bg-gray'>
		<div class="titulos">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<h2 class="upper">Classificação dos atendimentos</h2>
				<div class="barra barra-half">
					<div class="parte1"></div>
					<div class="parte2"></div>
				</div>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
		<div class="textos">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<p class="fonte2"><?php echo nl2br($info['bloco4']); ?></p>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
		<div class="blocos-atendimento">
			<div class="col-md-1"></div>
			<div class="col-md-10">


				<div class="bloco-atendimento bg-white">
					<div class="img-atedimento col-md-1 col-sm-2 col-xs-12">
						<img src="<?php echo ROOT;?>resources/img/emergencia-vermelho.png" alt="Emergência">
					</div>
					<div class="texto-atendimento col-md-11 col-sm-10 col-xs-12">
						<h2 class="upper">Emergência</h2>
						<p class="fonte2">
						VERMELHO = EMERGÊNCIA (ALTO RISCO E VIDA)<br>
						ATENDIMENTO IMEDIATO</p>
						<!--<p class="fonte2"><?php echo $info['emergencia']; ?></p>-->
					</div>
					<div class="clearBoth"></div>
				</div>
				<div class="clearBoth"></div>


				<div class="bloco-atendimento bg-white">
					<div class="img-atedimento col-md-1 col-sm-2 col-xs-12">
						<img src="<?php echo ROOT;?>resources/img/emergencia-amarelo.png" alt="Urgência">
					</div>
					<div class="texto-atendimento col-md-11 col-sm-10 col-xs-12">
						<h2 class="upper">Urgência</h2>
						<p class="fonte2">
						AMARELO = URGÊNCIA (RISCO MODERADO)<br>
						ATENDIMENTO EM ATÉ 60 MINUTOS
						</p>
						<!--<p class="fonte2"><?php echo $info['urgencia']; ?></p>-->
					</div>
					<div class="clearBoth"></div>
				</div>
				<div class="clearBoth"></div>


				<div class="bloco-atendimento bg-white">
					<div class="img-atedimento col-md-1 col-sm-2 col-xs-12">
						<img src="http://www.santacasalorena.org.br/arquivos/2020/emergencia-verde.png" alt="Urgência relativa">
					</div>
					<div class="texto-atendimento col-md-11 col-sm-10 col-xs-12">
						<h2 class="upper">Pouco Urgência</h2>
						<p class="fonte2">
						VERDE = POUCA URGÊNCIA (RISCO BAIXO)<br>
						ATENDIMENTO EM ATÉ 2 HORAS
						</p>
						<!---<p class="fonte2"><?php echo $info['urgencia_relativa']; ?></p>-->
					</div>
					<div class="clearBoth"></div>
				</div>
				<div class="clearBoth"></div>
				
				
				<div class="bloco-atendimento bg-white">
					<div class="img-atedimento col-md-1 col-sm-2 col-xs-12">
						<img src="http://www.santacasalorena.org.br/arquivos/2020/emergencia-azul.png" alt="Urgência relativa">
					</div>
					<div class="texto-atendimento col-md-11 col-sm-10 col-xs-12">
						<h2 class="upper">Não Urgência</h2>
						<p class="fonte2">
						AZUL = NÃO URGÊNCIA (SITUAÇAO NÃO AGUDA)<br>
						ATENDIMENTO EM ATÉ 4 HORAS
						</p>
						<!--<p class="fonte2"><?php echo $info['urgencia_relativa']; ?></p>-->
					</div>
					<div class="clearBoth"></div>
				</div>
				<div class="clearBoth"></div>

				<div class="col-md-12">
					<br>
					<p class="fonte2"><?php echo nl2br($info['bloco5']); ?></p>
				</div>


			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
	</div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>

<div class="galeria-pronto_atendimento control-nav">

<?php
	
	$getProntoAtendimento = new Read(); 
	$getProntoAtendimento->fullRead("SELECT * FROM ".PREFIX."pronto_atendimento ORDER BY data_criacao ASC"); 
	if($getProntoAtendimento->getResult()){
		foreach ($getProntoAtendimento->getResult() AS $key => $item) {
			echo "<div class='item'>";
			echo "<img src='../{$item['img']}' alt='{$item['titulo']}' alt='Pronto atendimento'>";
			echo "</div>";
		}
	}
?>
</div>
<div class="clearBoth"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.galeria-pronto_atendimento').owlCarousel({
		    loop:true,
		    margin:30,
		    responsive:{
		        0:{
		            items:1
		        },
		        740:{
		            items:2
		        },
		        900:{
		            items:3
		        }
		    }
		});
	});
</script>