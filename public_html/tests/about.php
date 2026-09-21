<?php
require __DIR__ . '/modernization.php';
require_once DIR . 'includes/about_helpers.php';
$before = $count;
$fixture = require __DIR__ . '/fixtures/about.php';
Read::$fixtures = $fixture;
$getPagina = new Read();
$r_DIR = array('page'=>'sobre-a-santa-casa', 'info'=>array('titulo'=>'Sobre a Santa Casa'));
ob_start(); require DIR.'includes/header.php'; require DIR.'includes/paginas/sobre_a_santa_casa.php'; require DIR.'includes/footer.php'; $html=ob_get_clean();
verify(!str_contains($html,'jQuery') && !str_contains($html,'bootstrap.min') && !str_contains($html,'owlCarousel'), 'About page has no legacy dependencies');
verify(str_contains($html,'about.js') && str_contains($html,'about.css'), 'Load isolated modern assets');
verify(str_contains($html,'Primeiro valor cadastrado') && str_contains($html,'Nome do provedor'), 'Preserve CMS fields');
verify(str_contains($html, '/hospital/arquivos/galeria_sobre/'), 'Gallery images support subdirectories');
verify(str_contains($html,'<dialog') && str_contains($html,'aria-controls="about-gallery-track"'), 'Accessible gallery markup');
foreach (array('script','onclick','javascript:','onerror','style=') as $unsafe) {
    $safe=scl_about_content('<p onclick="alert(1)" style="position:fixed">Olá <strong>mundo</strong></p><script>alert(1)</script><img src=x onerror=alert(1)><a href="javascript:alert(1)">Link</a>');
    verify(!str_contains($safe,$unsafe), 'Remove unsafe CMS HTML: '.$unsafe);
}
verify(str_contains($safe,'<strong>mundo</strong>'), 'Preserve safe formatting');
verify(str_contains(scl_about_content("Linha 1\nLinha 2"),'<br'), 'Preserve plain text line breaks');
Read::$fixtures = array_fill_keys(array_keys($fixture), array());
ob_start(); require DIR.'includes/paginas/sobre_a_santa_casa.php'; $html=ob_get_clean();
verify(str_contains($html,'As informações institucionais serão disponibilizadas'), 'Empty CMS state');
verify(!str_contains($html,'href="#historia"') && !str_contains($html,'<dialog'), 'No empty section links or gallery');
Read::$fixtures = $fixture; Read::$fixtures['galeria_sobre'] = array_slice($fixture['galeria_sobre'],0,1);
ob_start(); require DIR.'includes/paginas/sobre_a_santa_casa.php'; $html=ob_get_clean();
verify(substr_count($html,'class="about-slide"') === 1, 'Single image renders once');
Read::$fixtures['provedor'] = array(array('nome'=>'<img src=x onerror=alert(1)>', 'data1'=>'1900', 'data2'=>'', 'img'=>''));
ob_start(); require DIR.'includes/paginas/sobre_a_santa_casa.php'; $html=ob_get_clean();
verify(str_contains($html,'&lt;img') && !str_contains($html,'<img src=x'), 'Escape provider metadata');
verify(!str_contains($html,'1900 —'), 'No dangling date separator');
$r_DIR = array('page'=>'humanizacao', 'info'=>array('titulo'=>'Humanização'));
ob_start(); require DIR.'includes/header.php'; $html=ob_get_clean();
verify(str_contains($html,'jQuery-2.1.4') && !str_contains($html,'about.js'), 'Preserve dependencies on unconverted pages');
echo 'OK: ' . ($count-$before) . " about-page checks.\n";
