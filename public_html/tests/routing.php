<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require dirname(__DIR__).'/includes/request_route.php';require dirname(__DIR__).'/includes/ui.php';require dirname(__DIR__).'/includes/about_helpers.php';
$count=0;foreach([
 ['/',[], '/', ''],['/index.php',[],'/',''],['/institucional/humanizacao',[],'/','institucional/humanizacao'],['/noticias?busca=saude',[],'/','noticias'],['/index.php/noticias',[],'/','noticias'],['/hospital/servicos/convenios',[],'/hospital/','servicos/convenios'],['/index.php',['url'=>'noticias'],'/','noticias'],['/index.php',['qs'=>'doacoes'],'/','doacoes'],['/index.php',['url'=>['x']],'/','404'],['/index.php',['url'=>'../../_app/Config.inc'],'/','404'],['/index.php',['url'=>'a\\b'],'/','404']
] as [$uri,$query,$root,$expected]){if(scl_request_route(['REQUEST_URI'=>$uri],$query,$root)!==$expected)throw new RuntimeException('Route failed: '.$uri);$count++;}
$text=str_repeat('Texto institucional preservado. ',12);if(scl_cms_heading($text,'Humanização')!=='Humanização'||!str_contains(scl_cms_introduction($text),trim($text)))throw new RuntimeException('Long content must remain as body text.');$count++;
if(scl_cms_heading('Título curto','Fallback')!=='Título curto'||scl_cms_introduction('Título curto')!=='')throw new RuntimeException('Short title duplicated.');$count++;
echo 'OK: '.$count." routing and real CMS content shape checks.\n";
