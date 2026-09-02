<?php 

	$getNoticias = new Read();
	$getNoticias->fullRead("
		SELECT 
			N.*, 
			ANY_VALUE(T.nome) as tag, 
			ANY_VALUE(T.url) as url_tag 
		FROM ".PREFIX."noticia AS N
		LEFT JOIN ".PREFIX."tag_noticia AS TN ON (TN.id_noticia = N.id_noticia)
		LEFT JOIN ".PREFIX."tag AS T ON (T.id_tag = TN.id_tag)
		WHERE ".(isset($getTags) && $getTags->getResult() ? $tagsRelacionadas : "" )."
		".(isset($r_DIR['page']) && $r_DIR['page'] == "acoes-sociais-ambientais" ? "T.url = 'acoes-sociais' AND " : "" )."
		N.status = 1 
		GROUP BY N.id_noticia 
		ORDER BY N.data_criacao DESC 
		LIMIT 3
	");
	
	if($getNoticias->getResult()){
?>

	<section class='bloco-conteudo bg-noticias'>

		<div class='bloco-conteudo-padding'>

			<div class="col-md-5 col-sm-12 titulo">
				<h1><?php echo (isset($getTags) && $getTags->getResult() ? "Notícias Relacionadas" : (isset($r_DIR['page']) && $r_DIR['page'] == "acoes-sociais-ambientais" ? "Últimas notícias de nossas ações sociais" : "Notícias" )); ?></h1>
				<div class="barra">
					<div class="parte1"></div>
					<div class="parte2"></div>
				</div>
			</div>

			<p class="col-md-7 col-sm-12 contra-titulo">
				Confira as últimas notícias da Santa Casa e fique por dentro de todas as <br> novidades, melhorias e ações.
			</p>

		</div>

		<div class="clear" style="height: 0; "></div>

		<div class='bloco-conteudo-padding'>
			<div class='carousel-noticias'>
				<?php 

					foreach ($getNoticias->getResult() AS $key => $noticia) {
						
						//col-md-4 col-sm-6 col-xs-12
						echo "<div style='padding-bottom:5px'>
							<div class='noticias'>
								<span>DE <a href='".ROOT."noticias/{$noticia['url_tag']}'>#{$noticia['tag']}</a></span>
								<div class='img' style='background-image: url(".ROOT."{$noticia['img']});'></div>
								<h2>{$noticia['titulo']}</h2>
								<p>".(mb_strlen(strip_tags($noticia['subtitulo'], '<(.*?)>')) > 120 ? mb_substr(strip_tags($noticia['subtitulo'], '<(.*?)>') ,0,120)."..." : strip_tags($noticia['subtitulo'], '<(.*?)>'))."</p>
								<a href='".ROOT."noticias/{$noticia['link']}'>continue lendo <i class='iconsvg'><img src='".ROOT."resources/img/seta.svg'></i></a>
							</div>
						</div>";
					}

				?>
			</div>
		</div>
	</section>
<?php 
	}
?>