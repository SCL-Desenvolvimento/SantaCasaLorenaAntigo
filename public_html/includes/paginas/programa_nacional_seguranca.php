<?php 
	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_programa_nacional_seguranca ORDER BY data DESC LIMIT 1");
	$info = $getPagina->getResult()[0];
?>
<section class='bloco-conteudo'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo'>
		<div class="titulos">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<h2 class="upper">Programa nacional de segurança do paciente</h2>
				<div class="barra">
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
				<p class="fonte2"><?php echo nl2br($info['bloco1']); ?></p>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
	</div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>


<section class='bloco-conteudo bloco-servicos bloco-seguranca'>

	<div class='img' style="background-image: url(<?php echo ROOT.$info['img1']; ?>);"></div>

	<div class='conteudo-texto titulos'>
		<h2 class="upper">Núcleo de segurança do paciente</h2>
		<div class="barra">
			<div class="parte1"></div>
			<div class="parte2"></div>
		</div>
		<p><?php echo nl2br($info['bloco2']); ?></p>
	</div>	

</section>
<div class="clearBoth"></div>