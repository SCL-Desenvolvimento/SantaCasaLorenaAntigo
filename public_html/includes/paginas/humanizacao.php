<?php 
	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_humanizacao ORDER BY data DESC LIMIT 1");
	$info = $getPagina->getResult()[0];
?>

<section class='bloco-conteudo humanizacao'>
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

<!--Carousel-->
<section class="bloco-conteudo">
	<div class='bloco-conteudo-padding bg-blue control-nav-alt'>
		<?php 
			$controle_humanizacao = 0;
			$getHumanizacao = new Read(); 
			$getHumanizacao->fullRead("SELECT * FROM ".PREFIX."galeria_humanizacao ORDER BY data_criacao ASC"); 
			if($getHumanizacao->getResult()){
				echo "<div id='galeria-humanizacao'>";

				foreach ($getHumanizacao->getResult() AS $key => $item) {
					//var_dump($item);
					if($controle_humanizacao == 0){
						echo "<div class='item-humanizacao '>";
					}
					echo "<div class='no-text mozaico-item mozaico$controle_humanizacao  "; 

					switch ($controle_humanizacao) {
						case 0: 
							echo "col-md-6 h-100";
							break;
						case 1: 
							echo "col-md-3 h-50";
							break;
						case 2: 
							echo "col-md-3 h-50";
							break;
						case 3: 
							echo "col-md-6 h-50";
							break;
					}
					echo "'>";
						echo "<div class='mozaico-content' style='background-image: url(../{$item['img']}); background-size: cover; background-position:center center;'></div>";
						echo "<div class='clearBoth'></div>";
					echo "</div>";
					if($controle_humanizacao == 3 || (($key +1) == $getHumanizacao->getRowCount())){
						echo "</div>";
						$controle_humanizacao = 0;
					}
					else{
						$controle_humanizacao += 1;
					}
				}
				echo "</div>";


				echo "<div id='galeria-humanizacao-mobile'>";

				foreach ($getHumanizacao->getResult() AS $key => $item) {
					echo "<div class='item-humanizacao-mobile'>";
						echo "<img src='../{$item['img']}' alt='{$item['descricao']}'>";
					echo "</div>";
				}
				echo "</div>";
			}

		?>
	</div>
</section>
<div class="clearBoth"></div>
<!--/Carousel-->

<section class="bloco-conteudo">
	<div class='bloco-conteudo-padding'>
		<div class="titulos">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="tleft col-md-6">
					<h2>Sempre Evoluindo</h2>
					<div class="barra">
						<div class="parte1"></div>
						<div class="parte2"></div>
					</div>
				</div>
				<div class="tright col-md-6 fonte2-small">
					A partir de 2010 várias melhorias foram criadas pela instituição objetivando a humanização:
				</div>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
		<div class="textos">
			<div class="col-md-1"></div>
			<div class="col-md-10 fonte2">
				<?php echo nl2br($info['bloco4']); ?>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
	</div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#galeria-humanizacao').owlCarousel({
			loop:true,
			items:1
		});
		$('#galeria-humanizacao-mobile').owlCarousel({
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