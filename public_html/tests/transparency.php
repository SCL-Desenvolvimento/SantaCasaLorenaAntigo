<?php
require __DIR__.'/patient-safety.php';
$before=$count;
$r_DIR=array('page'=>'portal-transparencia','info'=>array('titulo'=>'Portal da transparência'));
ob_start(); require DIR.'includes/header.php'; require DIR.'includes/topo_paginas.php'; require DIR.'includes/paginas/portal_transparencia.php'; require DIR.'includes/footer.php'; $html=ob_get_clean();
verify(!str_contains($html,'jQuery') && !str_contains($html,'bootstrap.min') && !str_contains($html,'owlCarousel'), 'Transparency without legacy dependencies');
verify(str_contains($html,'transparency.css') && str_contains($html,'transparency.js'), 'Dedicated portal assets');
verify(substr_count($html,'<h1>')===1, 'One portal page heading');
libxml_use_internal_errors(true); $dom=new DOMDocument(); $dom->loadHTML('<?xml encoding="UTF-8">'.$html); $xpath=new DOMXPath($dom);
$links=$xpath->query('//a[@class="tp-document"]'); $urls=array(); $paths=array();
foreach($links as $link) {
 $url=$link->getAttribute('href'); $urls[]=$url;
 $path=DIR.rawurldecode(substr($url,strlen(ROOT))); $paths[]=realpath($path);
 verify(is_file($path), 'Document target exists: '.$url);
 verify($link->getAttribute('target')==='_blank' && $link->getAttribute('rel')==='noopener', 'Safe document tab');
}
verify(count($urls)===count(array_unique($urls)), 'No duplicate documents');
verify(count($urls)===$tpTotalCount, 'Displayed total matches links');
$inventory=array();
foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(DIR.'includes/paginas/transparencia',FilesystemIterator::SKIP_DOTS)) as $file) {
 if($file->isFile() && tpIsDocumentFile($file->getFilename())) $inventory[]=$file->getRealPath();
}
verify(!array_diff($inventory,$paths) && !array_diff($paths,$inventory), 'Every local document is represented');
verify($xpath->query('//section[@data-category]')->length===4, 'Four document categories');
verify(tpEncodedPath('pasta com espaço/ação.pdf')==='pasta%20com%20espa%C3%A7o/a%C3%A7%C3%A3o.pdf', 'Encoded paths');
verify(tpCountDocuments(DIR.'tests/nonexistent-archive')===0, 'Missing archive safe');
verify(!tpIsDocumentFile('script.php') && tpIsDocumentFile('documento.PDF'), 'Recognized documents only');
verify(tpExistingStaticDocuments(array(array('file'=>'missing.pdf','title'=>'Missing')))===array(), 'Missing static file excluded');
$r_DIR['page']='portal_transparencia';ob_start();require DIR.'includes/header.php';$alias=ob_get_clean();
verify(str_contains($alias,'transparency.css') && !str_contains($alias,'jQuery'), 'Portal underscore alias');
echo 'OK: '.($count-$before).' transparency checks; '.count($urls)." real documents.\n";
