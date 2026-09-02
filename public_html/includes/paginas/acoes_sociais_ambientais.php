<?php 
	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_acoes_sociais_ambientais ORDER BY data DESC LIMIT 1");
	$info = $getPagina->getResult()[0];
?>
<section class='bloco-conteudo acoes-sociais'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo'>
		<div class="titulos">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<h2 class="upper">Ministro da eucaristia e demais religiões</h2>
				<div class="barra barra-half">
					<div class="parte1"></div>
					<div class="parte2"></div>
				</div>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
		<div class="textos texto-padding">
			<div class="col-md-1"></div>
			<div class="col-md-10 noPadding">
				<p class="fonte2"><?php echo nl2br($info['bloco1']); ?></p>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
	</div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>

<section class='bloco-conteudo acoes-sociais acoes-sociais-bg'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo' style="background: url(<?php echo ROOT.$info['img1']; ?>) center 100% no-repeat; height: 400px; background-size: cover;"></div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>

<section class='bloco-conteudo acoes-sociais'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo voluntariado'>
		<div class="row bg-white">
			<div class="col-md-1"></div>
			<div class="col-md-10 titulos">
				<h2 class="upper">Voluntariado</h2>
				<div class="barra barra-half">
					<div class="parte1"></div>
					<div class="parte2"></div>
				</div>
			</div>
			<div class="col-md-1"></div>
		</div>
		<div class="clearBoth"></div>
		<div class="row bg-white">
			<div class="col-md-1"></div>
			<div class="col-md-10 textos texto-padding">
				<p class="fonte2"><?php echo nl2br($info['bloco2']); ?></p>
			</div>
			<div class="col-md-1"></div>
		</div>
		<div class="clear"></div>
		<div class="row bg-white">	
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 voluntariado-imagem control-nav">
				
				<?php

					$getGaleria = new Read(); 
					$getGaleria->fullRead("SELECT * FROM ".PREFIX."galeria_acao ORDER BY data_criacao ASC"); 
					if($getGaleria->getResult()){
						foreach ($getGaleria->getResult() AS $key => $item) {
							echo "<div class='item'>";
							echo "<img src='../{$item['img']}' alt='{$item['descricao']}' class='img-responsive'>";
							echo "</div>";
						}
					}

				?>

			</div>
			<div class="col-md-5 col-sm-6 textos texto-padding textos-pad-top">
				<p class="fonte2"><?php echo nl2br($info['bloco3']); ?></p>
			</div>
		</div>
		<div class="clearBoth"></div>
		<div class="row bg-white">
			<div class="col-md-1"></div>
			<div class="col-md-10 textos texto-padding">
				<p class="fonte2"><?php echo nl2br($info['bloco4']); ?></p>
			</div>
			<div class="col-md-1"></div>
		</div>
		<div class="clearBoth"></div>
	</div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.voluntariado-imagem').owlCarousel({
			loop: true,
			autoplay: true,
			items: 1,
		});
	});
</script>

<?php
	require_once('includes/noticias.php');
?>