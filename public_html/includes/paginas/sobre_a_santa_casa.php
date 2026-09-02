<?php 
	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_sobre ORDER BY data DESC LIMIT 1");
	$info = $getPagina->getResult()[0];
?>
<section class='bloco-conteudo sobre-a-santa-casa'>
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
			<div class="col-md-5">
				<p class="fonte2"><?php echo nl2br($info['bloco2']); ?></p>
			</div>
			<div class="col-md-5">
				<p class="fonte2"><?php echo nl2br($info['bloco3']); ?></p>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
	</div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>

<section class='bloco-conteudo sobre-a-santa-casa'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo bg-blue'>


		<div class="col-md-6 imagem">
			<!--Carousel-->
			<div id="galeria-sobre">
			<?php
				$getSobre = new Read(); 
				$getSobre->fullRead("SELECT * FROM ".PREFIX."galeria_sobre ORDER BY data_criacao ASC"); 
				if($getSobre->getResult()){
					foreach ($getSobre->getResult() AS $key => $item) {
						echo "<div class='item'>";
						echo "<img src='../{$item['img']}' alt='{$item['descricao']}'>";
						echo "</div>";
					}
				}
			?>
			</div>
			<!--/Carousel-->
		</div>


		<div class="col-md-6 textos texto-padding fonte2 texto-white">
			<p><?php echo nl2br($info['bloco4']); ?></p>
		</div>
		<div class="clearBoth"></div>
	</div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>

<section class='bloco-conteudo sobre-bloco-missao'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo'>
		<div class="row textos texto-padding-rl">
			<div class="col-md-1"></div>
			<div class="col-md-1 col-sm-3 col-xs-2 icon-sobre"><img src="<?php echo ROOT?>resources/img/missao.svg" alt="Missão"></div>
			<div class="col-md-4 col-sm-9 col-xs-10 padd15">
				<h3>Missão</h3>
				<p class="fonte2 "><?php echo $info['missao']; ?></p>
			</div>
			<div class="clearResp"></div>
			<div class="col-md-1 col-sm-3 col-xs-2 icon-sobre"><img src="<?php echo ROOT?>resources/img/visao.svg" alt="Visão"></div>
			<div class="col-md-4 col-sm-9 col-xs-10 padd15">
				<h3>Visão</h3>
				<p class="fonte2 "><?php echo $info['visao']; ?></p>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="row textos texto-padding">
			<div class="col-md-1"></div>
			<div class="col-md-1 col-sm-3 col-xs-2 icon-sobre"><img src="<?php echo ROOT?>resources/img/valores.svg" alt="Valores"></div>
			<div class="col-md-9 col-sm-9 col-xs-10 ">
				<h3>Valores</h3>
				<p class="fonte2 "><?php echo $info['valor']; ?></p>
			</div>
		</div>
		<div class="clearBoth"></div>
	</div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>

<section class='bloco-conteudo sobre-a-santa-casa'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo bg-gray'>
		<div class="col-md-1"></div>
		<div class="col-md-10">
			<div class="col-md-6 titulos titulo-no-margin">
				<h2>Provedores</h2>
				<div class="barra">
					<div class="parte1"></div>
					<div class="parte2"></div>
				</div>
			</div>
			<div class="clearBoth"></div>
			<div class="col-md-12 textos fonte2">
				<?php echo $info['provedor']; ?> 
			</div>
		</div>
		<div class="clearBoth"></div>
		<div class="col-md-12 provedores">
			<?php
				$getProvedore = new Read(); 
				$getProvedore->fullRead("SELECT * FROM ".PREFIX."provedor ORDER BY data2 ASC"); 
				if($getProvedore->getResult()){
					foreach ($getProvedore->getResult() AS $key => $item) {
						echo "<div class='provedor'>";
						echo "<img src='../{$item['img']}' alt='{$item['nome']}'>";
						echo "<div class='textos'>";
						echo "<h3 class='fonte-gray'>{$item['data1']} - {$item['data2']}</h3>";
						echo "<h4 class='fonte2'>{$item['nome']}</h4>";
						echo "</div>";
						echo "</div>";
					}
				}
			?>
		</div>
		<div class="clearBoth"></div>
	</div>
</section>
<div class="clearBoth"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#galeria-sobre').owlCarousel({
			loop: true,
			autoplay: true,
			items: 1,
		});
		$('#galeria-sobre-mobile').owlCarousel({
		    stagePadding: 180,
		    loop:true,
		    margin:30,
		    responsive:{
		        0:{
		            items:1,
		            stagePadding: 0
		        },
		        480:{
		            items:2,
		            stagePadding: 0
		        },
		        740:{
		            items:2,
		            stagePadding: 60
		        },
		        840:{
		            items:2,
		            stagePadding: 160
		        },
		        900:{
		            items:3,
		            stagePadding: 50
		        },
		        1170:{
		            items:3
		        }
		    }
		});
	});
</script>