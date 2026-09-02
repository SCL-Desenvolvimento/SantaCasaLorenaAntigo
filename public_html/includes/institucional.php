<?php 

$getInstitucionalInfo = new Read();
$getInstitucionalInfo->fullRead("SELECT * FROM ".PREFIX."paginas WHERE url_amigavel = 'acoes_sociais_ambientais'");

if($getInstitucionalInfo->getResult()){
	$servico = $getInstitucionalInfo->getResult()[0];
?>
	<section class='bloco-conteudo bloco-institucional'>

		<div class='img img-responsive' style="background-image: url('resources/img/santa-casa-home/acoes-sociais-ambientais-img.png');"></div>

		<div class='conteudo-texto'>
			<h1><?php echo $servico['titulo']; ?></h1>
			<!--<p><?php echo $servico['sub_titulo']; ?></p>-->
			<p>
				A Santa Casa vem ganhando mais força e possibilitando o aumento do bem
				estar de seus pacientes por meio de apoio, orientações e disposições
				em doar-se.
			</p>
			<a href='<?php echo ROOT."institucional/".$servico['url_amigavel']; ?>'>saiba mais</a>
		</div>

		<div class='img img-desktop pull-right' style="background-image: url('resources/img/santa-casa-home/acoes-sociais-ambientais-img.png');"></div>

	</section>
<?php 
}
?>
<div class="clear"></div>