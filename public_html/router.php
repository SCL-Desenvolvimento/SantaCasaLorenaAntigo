<?php
/** Router for php -S 127.0.0.1:8000 -t . router.php; Apache uses .htaccess. */
if(PHP_SAPI!=='cli-server'){http_response_code(404);exit;}
$path=rawurldecode(parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_PATH)?:'/');
if(str_contains($path,chr(0))||str_contains($path,'\\')||preg_match('#(?:^|/)\.\.(?:/|$)#',$path)||preg_match('#^/(?:_app|tests|scripts|deploy|admin/system|arquivos/curriculuns)(?:/|$)#i',$path)||preg_match('#(?:^|/)\.|\.(?:sql|log|ini|env|bak|old|md|toml|ya?ml)$#i',$path)||preg_match('#^/resources/.*\.(?:php[0-9]?|phtml|phar)$#i',$path)||preg_match('#^/includes/(?!servicos/galeria\.php$).*\.php$#i',$path)){
 http_response_code(403);exit('Acesso não permitido.');
}
$file=realpath(__DIR__.$path);
if($file&&str_starts_with($file,__DIR__.DIRECTORY_SEPARATOR)&&(is_file($file)||is_dir($file)))return false;
if(str_starts_with($path,'/admin/')||preg_match('#\.(?:css|js|png|jpe?g|svg|pdf|ico|woff2?)$#i',$path)){http_response_code(404);exit('Não encontrado.');}
require __DIR__.'/index.php';
