<section id="banners" class='bloco-conteudo'>
	<div class="banner-principal owl-carousel owl-banner">
		<?php 

			$getBanner = new Read();
			$getBanner->fullRead("SELECT * FROM ".PREFIX."banner WHERE status = 1");
			if($getBanner->getResult()){
				foreach ($getBanner->getResult() AS $key => $banner) {
					echo "<div class='item'><img src='{$banner['img']}' alt='{$banner['titulo']}' onclick=\"".Check::setLink($banner['link'])."\"></div>";
				}
			}

		?>
	</div>
</section>
<div class="clear"></div>