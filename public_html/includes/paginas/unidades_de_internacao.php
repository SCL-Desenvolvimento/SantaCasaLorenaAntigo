<?php 
	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_unidade_internacao ORDER BY data DESC LIMIT 1");
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


<section class='bloco-conteudo bg-noticias'>
	<div class='bloco-conteudo-padding'>
		<?php
			
			$getUnidadeInternacao = new Read(); 
			$getUnidadeInternacao->fullRead("SELECT * FROM ".PREFIX."unidade_internacao ORDER BY data_criacao ASC"); 
			if($getUnidadeInternacao->getResult()){

				$galeria_slide = "";

				foreach ($getUnidadeInternacao->getResult() AS $x => $galeria) {

					echo "<div class='col-md-12 col-sm-12 titulo titulo-barra'>
						<h3>{$galeria['titulo']}</h3>
						<div class='barra'>
							<div class='parte1'></div>
							<div class='parte2'></div>
						</div>
					</div>
					<div class='galeria-{$x}  control-nav col-md-offset-1 col-md-5 col-md-offset-0 col-sm-12 album album-internacao'>";

					$getUnidadeInternacaoImagem = new Read(); 
					$getUnidadeInternacaoImagem->fullRead("SELECT * FROM ".PREFIX."unidade_internacao_imagem WHERE id_unidade_internacao = {$galeria['id_unidade_internacao']} ORDER BY data_criacao DESC"); 
					
					if($getUnidadeInternacaoImagem->getRowCount() > 1)
						$galeria_slide .= ",.galeria-{$x}";

					if($getUnidadeInternacaoImagem->getResult()){
						foreach ($getUnidadeInternacaoImagem->getResult() AS $y => $item) {
							
							echo "<div class='item'>";
							echo "<img src='../{$item['img']}' alt='{$galeria['titulo']} - {$y}'>";
							echo "</div>";
						}
					}
					echo "</div>

					<div class='col-md-6 col-sm-12 album-desc fonte2'>
						<p>{$galeria['descricao']}</p>
					</div>

					<div class='clearBoth'></div>";
				}
			}
		?>
	</div>
</section>

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