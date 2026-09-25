<?php
/** Resolve pretty URLs with Apache rewrites and the PHP development server. */
function scl_request_route(array $server, array $query, string $root = '/'): string {
    foreach (['url','qs'] as $key) {
        if (array_key_exists($key,$query)) return is_string($query[$key]) ? scl_safe_route($query[$key]) : '404';
    }
    $path = rawurldecode(parse_url($server['REQUEST_URI'] ?? '/',PHP_URL_PATH) ?: '/');
    $base = rtrim($root,'/');
    if ($base !== '' && str_starts_with($path,$base.'/')) $path=substr($path,strlen($base));
    $path=trim($path,'/');
    if ($path==='index.php' || $path==='') return '';
    if (str_starts_with($path,'index.php/')) $path=substr($path,10);
    return scl_safe_route($path);
}

function scl_safe_route(string $path): string {
    if(str_contains($path,chr(0))||str_contains($path,'\\')||preg_match('#(?:^|/)\.{1,2}(?:/|$)#',$path))return '404';
    return trim($path,'/');
}
