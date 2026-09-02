<?php 
	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_capacidade_instalacao_producao ORDER BY data DESC LIMIT 1");
	$info = $getPagina->getResult()[0];
?>
<section class='bloco-conteudo'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo'>
		<div class='titulos'>
			<div class='col-md-1'></div>
			<div class='col-md-10'>
				<h2><?php echo nl2br($info['bloco1']); ?></h2>
				<hr>
				<p class='fonte2'><?php echo nl2br($info['bloco2']); ?></p>
			</div>
			<div class='clearBoth'></div>
		</div>
	</div>
	<div class='clearBoth'></div>
</section>
<div class='clearBoth'></div>


<section class='bloco-conteudo'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo bg-gray'>
		<div class='col-md-1'></div>
		<div class='col-md-10'>

			<!--peguei a imagem da internet-->

			
			<?php
			
				$getCapacidade = new Read(); 
				$getCapacidade->fullRead("SELECT * FROM ".PREFIX."capacidade ORDER BY data_criacao ASC"); 
				if($getCapacidade->getResult()){

					$galeria_slide = "";

					foreach ($getCapacidade->getResult() AS $x => $galeria) {

						echo "<div class='item-capacidade bg-white'>
							<div id='carousel-capacidade-{$x}' class='carousel-capacidade'>";

						$getCapacidadeImagem = new Read(); 
						$getCapacidadeImagem->fullRead("SELECT * FROM ".PREFIX."capacidade_imagem WHERE id_capacidade = {$galeria['id_capacidade']} ORDER BY data_criacao DESC"); 
						
						if($getCapacidadeImagem->getRowCount() > 1)
							$galeria_slide .= ",#carousel-capacidade-{$x}";

						if($getCapacidadeImagem->getResult()){
							foreach ($getCapacidadeImagem->getResult() AS $y => $item) {
								
								echo "<div class='item'>
									<img src='".ROOT."{$item['img']}' alt='{$galeria['titulo']}'>
								</div>";
							}
						}
						echo "</div>

							<div class='area-conteudo'>
								<div class='conteudo'>
									<h2 class='upper title-capacidade'>{$galeria['titulo']}</h2>
									<div class='barra'>
										<div class='parte1'></div>
										<div class='parte2'></div>
									</div>
									<p class='fonte2'>{$galeria['descricao']}</p>	
								</div>
								<div class='clearBoth'></div>
							</div>
							<div class='clearBoth'></div>
						</div>";
					}
				}
			?>

			<!--
			<div class='item-capacidade bg-white'>
				<img class='img-capacidade' src='https://my.clevelandclinic.org/-/scassets/images/org/locations/lutheran-hospital/lutheran-rich-text-panel.ashx' alt='Lorem ipsum'>
				<div class='area-conteudo'>
					<div class='conteudo'>
						<h2 class='upper'>Título</h2>
						<div class='barra'>
							<div class='parte1'></div>
							<div class='parte2'></div>
						</div>
						<p class="fonte2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. </p>
					</div>
				</div>
				<div class='clearBoth'></div>
			</div>
			-->

		</div>
		<div class='clearBoth'></div>
	</div>
	<div class='clearBoth'></div>
</section>
<div class='clearBoth'></div>

<script type="text/javascript">
	
	$(document).ready(function(){
		$('<?php echo substr($galeria_slide, 1); ?>').owlCarousel({
		    loop:true,
		    margin:30,
		    responsive:{
		        0:{
		            items:1
		        }
		    }
		});
	});

</script>