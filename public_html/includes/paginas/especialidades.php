<?php 
	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_especialidades ORDER BY data DESC LIMIT 1");
	$info = $getPagina->getResult()[0];
?>

<section class='bloco-conteudo'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo'>
		<div class="titulos">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<h2><?php echo nl2br($info['bloco1']); ?></h2>
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
			<div class="col-md-10 especialidades">
				
			<?php 

				$getEspecialidade = new Read(); 
				$getEspecialidade->fullRead("SELECT * FROM ".PREFIX."especialidade ORDER BY data_criacao ASC"); 

				if($getEspecialidade->getResult()){
					foreach ($getEspecialidade->getResult() AS $key => $especialidade) {
						
						echo "<div class='especialidade'>
							<img src='".ROOT."resources/img/icon-logo.png' alt='{$especialidade['nome']}'>
							{$especialidade['nome']}
						</div>";
					}
				}

			?>

			</div>
			<div class="clearBoth"></div>
		</div>
		<div class="clearBoth"></div>
	</div>
	<div class="clearBoth"></div>
</section>
<div class="clearBoth"></div>