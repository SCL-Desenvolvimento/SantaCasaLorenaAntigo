<section class='bloco-conteudo bg-topo-paginas'>

	<div class='bloco-conteudo-padding bloco-paginas-padding'>

		<?php 

			if(!isset($r_DIR['noticia'])){
		?>

			<div class="col-md-5 col-sm-12 titulo-pagina">
				<a href='<?php echo HOME; ?>'>Home <i class="fa fa-fw fa-angle-right"></i> </a>
				<?php echo (isset($r_DIR['info']['sessao']) && $r_DIR['info']['sessao'] != "" ? "<a>".$r_DIR['info']['sessao']."<i class='fa fa-fw fa-angle-right'></i> </a>"  : "" ); ?> 
				<h1><?php echo $r_DIR['info']['titulo']; ?></h1>
				<h2><?php echo $r_DIR['info']['sub_titulo']; ?></h2>
				<div class="barra">
					<div class="parte1"></div>
					<div class="parte2"></div>
				</div>

				<?php 

					if(isset($r_DIR['info']['imagem']) && $r_DIR['info']['imagem'] != "" ){
						echo "<p class='contra-titulo tleft'>
							{$r_DIR['info']['descricao_pagina']}
						</p>";
					}

				?>

			</div>

			<?php 

				if(isset($r_DIR['info']['imagem']) && $r_DIR['info']['imagem'] != "" ){
					echo "<div class='col-md-7 col-sm-12 contra-image' style='background-image: url(".ROOT.$r_DIR['info']['imagem'].");'></div>";
					$clear = 0;
				}else{
					echo "<p class='col-md-6 col-sm-12 contra-titulo contra-titulo-paginas pull-right'>
						".(isset($r_DIR['info']['descricao_pagina']) ? $r_DIR['info']['descricao_pagina'] : "" )."
					</p>";
					$clear = 50;
				}

			}else{
		?>

			<div class="col-md-9 col-sm-12 titulo-pagina titulo-pagina-noticia">
				<a href='#'>DE <span>#<?php echo $r_DIR['noticia']['tag']; ?></span></a>
				<h1><?php echo $r_DIR['noticia']['titulo']; ?></h1>
				<div class="barra">
					<div class="parte1"></div>
					<div class="parte2"></div>
				</div>

			</div>

			<p class='col-md-3 col-sm-12 contra-titulo contra-titulo-paginas contra-titulo-noticia pull-right'>
				postado <?php echo date("d/m/Y", strtotime($r_DIR['noticia']['data_criacao'])); ?>
				<br>
				por <?php echo $r_DIR['noticia']['criador']; ?>
			</p>

		<?php
			}
		?>
		
	</div>
	<div class="clear" <?php echo (isset($clear) ? "style='height:{$clear}px;'" : "") ?>></div>
</section>
<div class="clear"></div>