<?php 
	
	$dadosNoticia = array("acessos" => $r_DIR['noticia']['acessos'] + 1);
	$updateAcessos = new Update();
	$updateAcessos->ExeUpdate(PREFIX."noticia", $dadosNoticia, "WHERE id_noticia = :id_noticia", "id_noticia={$r_DIR['noticia']['id_noticia']}");

?>
<section class='bloco-conteudo noticia-descricao'>

	<div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12">
		<center>
			<br><br>
			<img src='<?php echo ROOT.$r_DIR['noticia']['img'] ; ?>' alt='<?php echo $r_DIR['noticia']['titulo'] ; ?>' class='img-responsive'>
			<br><br>
		</center>
		<?php echo $r_DIR['noticia']['descricao'] ; ?>
	</div>

	<div class="btn-compartilhar">
		<div class="fb-share-button" data-href="<?php echo HOME.substr(str_replace("SCL/", "", $_SERVER['REDIRECT_URL']), 1) ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo HOME.substr(str_replace("SCL/", "", $_SERVER['REDIRECT_URL']), 1) ?>&amp;src=sdkpreparse">Compartilhar</a></div>
		<div class="clear"></div>
	</div>

	<?php 

		$getTags = new Read();
		$getTags->fullRead("SELECT T.* 
							FROM ".PREFIX."tag AS T
							INNER JOIN ".PREFIX."tag_noticia AS TN ON (TN.id_tag = T.id_tag)
							WHERE TN.id_noticia = {$r_DIR['noticia']['id_noticia']}");

		if($getTags->getResult()){

			$tagsRelacionadas = "(";

			echo "<div class='tags'> Tags:";
				foreach ($getTags->getResult() AS $key => $tag) {

					$tagsRelacionadas .= "T.id_tag = {$tag['id_tag']}".(($getTags->getRowCount() - 1) == $key ? "" : " OR ");
					echo "<a href='".ROOT."noticias/{$tag['url']}'>#{$tag['nome']}</a>".(($getTags->getRowCount() - 1) == $key ? "" : ( ($getTags->getRowCount() - 2) == $key ? "<span> e </span>" : "<span>, </span>" ));
				}

			$tagsRelacionadas .= ") AND N.id_noticia != {$r_DIR['noticia']['id_noticia']} AND ";

			echo "</div>";
		}

	?>	
</section>
<div class="clear"></div>

<?php 

	if($getTags->getResult()){
		include_once(DIR."includes/noticias.php");
	}

?>

<!-- Magnific Popup CSS tema -->
<link rel="stylesheet" href="<?php echo HOME; ?>resources/plugins/magnific-popup/magnific-popup.css"> 

<!-- Magnific Popup core JS file -->
<script src="<?php echo HOME; ?>resources/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>

<script type="text/javascript">
	
	$(document).ready(function(){

		function getPopUp(){
			$('.pop-up').magnificPopup({
				delegate: 'a',
				type: 'image',
				tLoading: 'Carregando imagem #%curr%...',
				mainClass: 'mfp-img-mobile',
				gallery: {
					enabled: true,
					navigateByImgClick: true,
					preload: [0,1] // Will preload 0 - before current, and 1 after the current image
				},
				image: {
					tError: '<a href="%url%">A imagem #%curr%</a> não pode ser carregada.',
					titleSrc: function(item) {
						//return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
						return item.el.attr('title') + '<small>'+item.el.attr('alt')+'</small>';
					}
				}
			});
		}

		function getGallerias(){

			gallerias = $(".ck-galleria");

			if($("#galleria-aux").length){
				$("#galleria-aux").html("");
				$("#galleria-aux").fadeIn();
			}

			$.each(gallerias, function( key, cat ){
				//dataType : "json",
				//console.log($(cat).find('input').val());
				var request = $.ajax({ url: '<?php echo ROOT; ?>includes/servicos/galeria.php', dataType : "json", type: 'POST', data: {acao:'getGalleria', galleria: $(cat).find('input').val()}, async: false });
				request.done(function(g){

					console.log(g);

					newGallery = "<div class='galeria-fotos pop-up'>";
					$.each(g, function(){
						//"+this.galeria+"
						newGallery = newGallery.concat("<a href='<?php echo ROOT; ?>"+this.url+"' title='<?php echo (isset($r_DIR['noticia']) ? $r_DIR['noticia']['titulo'] : ""); ?>' alt='"+this.descricao+"'><div class='item-slide' style='background: url(<?php echo ROOT; ?>"+this.url+") no-repeat; background-size: cover; background-position: top;'></div></a>");
					});
					newGallery = newGallery.concat("</div>");
					//console.log(newGallery);

					if($("#galleria-aux").length){
						$("#galleria-aux").append(newGallery);
					}else{
						$(cat).replaceWith(newGallery);
					}

					
				});

			});

			/*
			items:2,
			loop:true,
			margin:20,
			lazyLoad:true, //in this example only applied on video thumbnails
			merge: true, 
			video: true,
			autoplay:true,
			autoplayTimeout:3000,
			autoplaySpeed:1000,
			dots: false,
			nav: false,
			//navText: ["<img src='<?php echo ROOT; ?>resources/img/icones/left.png'>","<img src='<?php echo ROOT; ?>resources/img/icones/right.png'>"],
			*/

			$('.galeria-fotos').owlCarousel({
				loop:true,
    			margin:20,
    			responsiveClass:true,
				responsive:{  
					0:{
						dots: false,
					   	items:1
					},
					480:{
					 	dots: false,
					   	items:2
					},
					678:{
					 	dots: false,
					   	items:3
					},
					960:{
					 	dots: false,
					   	items:4
					}
				}
			});

			getPopUp();

		}


		function setPopUp(){
			images = $(".insert-popup");
			$.each(images, function( key, img ){
				//console.log($(img));
				$(img).replaceWith("<div class='pop-up'><a href='"+$(img).attr('src')+"' title='"+$(img).attr('alt')+"' alt=''>"+img.outerHTML+"</a></div>");
			});
		}

		setPopUp();
		getGallerias();

	});
</script>