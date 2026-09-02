<?php 

$getServicosInfo = new Read();
$getServicosInfo->fullRead("SELECT * FROM ".PREFIX."paginas WHERE url_amigavel = 'manual_do_paciente_e_visitantes'");

if($getServicosInfo->getResult()){
	$servico = $getServicosInfo->getResult()[0];
?>
	<section class='bloco-conteudo bloco-servicos'>

		<div class='img' style="background-image: url(<?php echo $servico['img_principal']; ?>);"></div>

		<div class='conteudo-texto'>
			<h1><?php echo $servico['titulo']; ?></h1>
			<!-- <p><?php echo $servico['sub_titulo']; ?></p> -->
			<p>
				Este manual foi desenvolvido para facilitar o relacionamento 
				entre o paciente, familiares e o hospital garantindo total 
				segurança, conforto e tranquilidade ao utilizar nossos serviços.
			</p>
			<a href='<?php echo ROOT."servicos/".$servico['url_amigavel']; ?>'>clique aqui para acessar</a>
		</div>

	</section>
<?php 
}
?>