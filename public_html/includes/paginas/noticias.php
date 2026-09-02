<div class='bloco-conteudo conteudo-paginas pagina-noticias'>


	<div class="col-md-8 col-sm-12 borda-lateral">
	<?php 
		if(isset($r_DIR['noticias'])){
	?>

	<div class="col-md-4 col-sm-12 titulo">
		<h2>últimas notícias</h2>
		<div class="barra">
			<div class="parte1"></div>
			<div class="parte2"></div>
		</div>
	</div>

	<div class="clear"></div>

	<?php 
		};
	?>

	<?php 
		if(isset($r_DIR['noticias'])){

			$Paginacao = new Paginacao(ROOT."noticias/".($Url->getNoticiaTag() != null ? $Url->getNoticiaTag()."/" : "" ), "<i class='fa fa-fw fa-angle-left'></i> &nbsp;&nbsp; Página anterior", "Próxima página &nbsp;&nbsp;<i class='fa fa-fw fa-angle-right'></i>", "1");    
	    	$Paginacao->ExePaginacao(($r_DIR['paginacao']['pag'] != "" ? $r_DIR['paginacao']['pag'] : 1), ($r_DIR['limit'] != "" ? $r_DIR['limit'] : 9));

			foreach ($r_DIR['noticias'] AS $key => $noticia){
				
				echo "<div class='col-md-12 col-sm-12 noticia'>
					<div class='img col-md-6 col-sm-12' style='background-image: url(".ROOT."{$noticia['img']});'></div>

					<div class='col-md-6 col-sm-12'>
						<span>DE <a href='".ROOT."noticias/{$noticia['url_tag']}'>#{$noticia['tag']}</a></span>
						<h2>{$noticia['titulo']}</h2>
						<p>".(mb_strlen(strip_tags($noticia['subtitulo'], '<(.*?)>')) > 120 ? mb_substr(strip_tags($noticia['subtitulo'], '<(.*?)>') ,0,120)."..." : strip_tags($noticia['subtitulo'], '<(.*?)>'))."</p>
						<a href='".ROOT."noticias/{$noticia['link']}'>continue lendo <i class='fa fa-fw fa-arrow-right'></i></a>
					</div>

				</div>";

			}

		}else{
			echo "

				<div class='bloco-conteudo-padding'>
		
					<div class='''textos'>
						<div class='col-md-12'>
							<p><center style='color:#29b6f6; font-size: 40px; font-weight: 700;'>Página não encontrada</center></p>
						</div>
						<div class='clearBoth'></div>
					</div>

					<div class='clearBoth'></div>
				</div>

			";
		}

		if(isset($r_DIR['noticias'])){

			if(isset($r_DIR['termos']) && isset($r_DIR['places'])):

				$Termos = str_replace("LIMIT :limit OFFSET :offset", "", $r_DIR['termos']);
				$Places = explode("&", $r_DIR['places']);

				array_pop($Places);
				array_pop($Places);
				$Places = implode("&", $Places);
			endif;
			echo "<div class='paginacao'>";
			$pagina = $Paginacao->ExePaginator(null, $Termos, $Places);
			echo "<div class='n-paginas'>Página ".$r_DIR['paginacao']['pag']." de ".($Paginacao->getPages() != "" ? $Paginacao->getPages() : "1 <div class='clear'></div>" )."</div>";
			echo $pagina;
			echo "</div>";
		}
	?>
	</div>

	<div class="col-md-4 col-sm-12 mais-lidas">

		<div class="col-md-12 col-sm-12 titulo">
			<h2>mais lidas</h2>
			<div class="barra">
				<div class="parte1"></div>
				<div class="parte2"></div>
			</div>
		</div>

		<?php 


			$getNoticiasMaisLidas = new Read();
			$getNoticiasMaisLidas->fullRead("
				SELECT 
					N.*, 
					ANY_VALUE(T.nome) as tag, 
					ANY_VALUE(T.url) as url_tag 
				FROM ".PREFIX."noticia AS N
				LEFT JOIN ".PREFIX."tag_noticia AS TN ON (TN.id_noticia = N.id_noticia)
				LEFT JOIN ".PREFIX."tag AS T ON (T.id_tag = TN.id_tag)
				WHERE N.status = 1 
				GROUP BY N.id_noticia 
				ORDER BY N.acessos DESC 
				LIMIT 3
			");	
			
			if($getNoticiasMaisLidas->getResult()){

				foreach ($getNoticiasMaisLidas->getResult() AS $key => $noticia){

					echo "<div class='col-md-12 col-sm-12 noticia-mais-lida noticia'>
						<div class='img col-md-12 col-sm-12' style='background-image: url(".ROOT."{$noticia['img']}); margin-bottom:15px;'></div>
						<span>DE <a href='".ROOT."noticias/{$noticia['url_tag']}'>#{$noticia['tag']}</a></span>
						<h2>{$noticia['titulo']}</h2>
						<a href='".ROOT."noticias/{$noticia['link']}'>continue lendo <i class='fa fa-fw fa-arrow-right'></i></a>
					</div>";

				}
			}
		?>	
	</div>
	<div class="clear"></div>

</div>
</div>