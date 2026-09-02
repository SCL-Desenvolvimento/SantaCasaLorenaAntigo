<?php
	if(isset($r_DIR['page'])){
?>
	<style type="text/css">
		.t-convenio{
			display: none;
		}	
	</style>
<?php 
	}
?>

<section class='bloco-conteudo'>

	<div class='bloco-conteudo-padding'>
		<div class="col-md-6 col-sm-12 titulo t-convenio">
			<h1>CONVÊNIOS</h1>
			<div class="barra">
				<div class="parte1"></div>
				<div class="parte2"></div>
			</div>
		</div>

		<p class="col-md-6 col-sm-12 contra-titulo t-convenio">
			Uma série de convênios atendidos em nossa unidade, que levam aos <br>
			nossos pacientes toda a dedicação, qualidade e suporte.
		</p>
	

		<div class='clear'></div>

		<?php 

			$getConvenios = new Read();
			$getConvenios->fullRead("SELECT * FROM ".PREFIX."convenio ORDER BY data_criacao ASC");

			if($getConvenios->getResult()){
				
				foreach($getConvenios->getResult() AS $convenio){
					echo "<div class='convenios col-md-2 col-sm-3 col-xs-4' align='center' style='min-width:158px; height: 41px;'>
						<img src='".ROOT."{$convenio['img']}' alt='Convênio - {$convenio['nome']}'>
					</div>";
					
					
				}

echo "<div class='convenios col-md-2 col-sm-3 col-xs-4' align='center' style='min-width:158px; height: 41px;'>
						<img style='transform: scale(1.2); margin-top: -10px' src='/arquivos/convenio/cas.png' alt='Convênio - SINEEVALI'>
					</div>";
				echo "<div class='carousel-convenios'>";

				foreach($getConvenios->getResult() AS $convenio){
					echo "<div class='item'>
						<img src='".ROOT."{$convenio['img']}' alt='Convênio - {$convenio['nome']}'>
					</div>";
				}

				echo "</div>";
			}

		?>
	</div>
<div class="clear"></div>

</section>
<div class="clear"></div>