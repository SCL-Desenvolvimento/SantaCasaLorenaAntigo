<?php 
	$getPagina->fullRead("SELECT * FROM ".PREFIX."pagina_manual_paciente_visitante ORDER BY data DESC LIMIT 1");
	$info = $getPagina->getResult()[0];
?>
<section class='bloco-conteudo'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo'>
		<div class='titulos'>
			<div class='col-md-1'></div>
			<div class='col-md-10'>
				<h2><?php echo nl2br($info['bloco1']); ?></h2>
			</div>
			<div class='clearBoth'></div>
		</div>
	</div>
	<div class='clearBoth'></div>
</section>
<div class='clearBoth'></div>


<section class='bloco-conteudo'>
	<div class='bloco-conteudo-padding bloco-conteudo-conteudo bg-gray bloco-paciente'>
		<div class='titulos'>
			<div class='col-md-1'></div>
			<div id='accordion' class='col-md-10'>

				<?php 

					$getManual = new Read(); 
					$getManual->fullRead("SELECT * FROM ".PREFIX."manual_paciente ORDER BY data_criacao ASC"); 
					if($getManual->getResult()){

						foreach ($getManual->getResult() AS $x => $manual) {
							echo "<div class='item-manual'>
								<header>
									<img src='".ROOT."resources/img/icon-logo.png' alt='Santa Casa de Lorena'>
									<strong>{$manual['titulo']}</strong>
									<button class='btn bg-blue'>LEIA MAIS</button>
								</header>
								<article class='fonte2'>
									{$manual['descricao']}
								</article>
							</div>";
						}
					}

				?>

				<?php 

					$getManualDownload = new Read(); 
					$getManualDownload->fullRead("SELECT * FROM ".PREFIX."download_manual_paciente ORDER BY data_criacao DESC"); 
					if($getManualDownload->getResult()){

						echo "<div class='item-manual'>
								<header>
									<img src='".ROOT."resources/img/icon-logo.png' alt='Santa Casa de Lorena'>
									<strong>Downloads</strong>
									<button class='btn bg-blue'>LEIA MAIS</button>
								</header>
								<article>
									<ul>";

						foreach ($getManualDownload->getResult() AS $x => $item) {
							echo "<li><a href='".ROOT."servicos/manual-paciente-visitante/file-{$item['id_download_manual_paciente']}"."' target='_blank'>{$item['titulo']}</a></li>";
						}

						echo "</ul></article></div>";
					}

				?>
					
			</div>
			<div class='clearBoth'></div>
		</div>
	</div>
	<div class='clearBoth'></div>
</section>
<div class='clearBoth'></div>


<script type="text/javascript">
	$(function(){


		/*Accordion - jf*/
		let over = false;

		$(".item-manual button").mouseover(function(){
			over = true;
		});
		$(".item-manual button").mouseout(function(){
			over = false;
		});

		$(".item-manual").click(function(){
			if(over){
				const status1 = $(this).attr('class');
				const status2 = $(".open article").css("display");
				if(status1 == "item-manual"){

					if(status2 == "block"){$(".open article").slideUp();}

					$(".item-manual").removeClass("open");
					$(this).addClass("open");
				}
				else if(status1 == "item-manual open"){
					const status4 = $(".open article").css("display");
					if(status4 == "block"){$(".open article").slideUp();}
					$(this).removeClass("open");
				}
				const status3 = $(".open article").css("display");
				if(status3 == "none"){$(".open article").slideDown();}
			}
		});
		/*/Accordion - jf*/


		
	});
</script>