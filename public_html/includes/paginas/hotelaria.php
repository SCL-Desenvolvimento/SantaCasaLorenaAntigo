<?php 
	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_hotelaria ORDER BY data DESC LIMIT 1");
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
	</div>
</section>
<div class="clearBoth"></div>

<div class="galeria-hotelaria control-nav">

<?php
	
	$getHotelaria = new Read(); 
	$getHotelaria->fullRead("SELECT * FROM ".PREFIX."hotelaria ORDER BY data_criacao ASC"); 
	if($getHotelaria->getResult()){
		foreach ($getHotelaria->getResult() AS $key => $item) {
			echo "<div class='item'>";
			echo "<img src='../{$item['img']}' alt='{$item['titulo']}'>";
			echo "</div>";
		}
	}
?>
</div>
<div class="clearBoth"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.galeria-hotelaria').owlCarousel({
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