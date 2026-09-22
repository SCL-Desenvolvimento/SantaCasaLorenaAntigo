<?php
require __DIR__.'/services.php';$before=$count;
$fixture=require __DIR__.'/fixtures/news-listing.php';
$Url=new class {public $tag='';public function getNoticiaTag(){return $this->tag;}};
Read::$fixtures=array('listing_total'=>array(array('listing_total'=>12)),'news_categories'=>array(array('nome'=>'Ações sociais','url'=>'acoes-sociais')),'noticia'=>array_slice($fixture,0,3));
$r_DIR=array('page'=>'noticias','info'=>array('titulo'=>'Notícias'),'noticias'=>array_slice($fixture,0,5),'limit'=>5,'paginacao'=>array('pag'=>1),'termos'=>'SELECT * FROM '.PREFIX.'noticia WHERE status=1 LIMIT :limit OFFSET :offset','places'=>'limit=5&offset=0');
$renderNews=function() use (&$r_DIR,&$Url){ob_start();require DIR.'includes/header.php';require DIR.'includes/topo_paginas.php';require DIR.'includes/paginas/noticias.php';require DIR.'includes/footer.php';return ob_get_clean();};
$html=$renderNews();verify(!str_contains($html,'jQuery')&&!str_contains($html,'bootstrap.min')&&str_contains($html,'news-listing.css'),'No legacy dependencies on listing');
verify(substr_count($html,'<h1>')===1&&substr_count($html,'class="news-listing-card"')===5,'One heading and five news records');
verify(str_contains($html,'Página 1 de 3')&&str_contains($html,'/hospital/noticias/2'),'Pagination total and next link');
verify(str_contains($html,'Mais lidas')&&str_contains($html,'datetime="2026-09-21"'),'Popular news and dates');
verify(str_contains($html,'/hospital/noticias/noticia-demonstrativa-1'),'Existing article paths');
$Url->tag='acoes-sociais';$r_DIR['paginacao']['pag']=2;$html=$renderNews();verify(str_contains($html,'/hospital/noticias/acoes-sociais/3')&&str_contains($html,'rel="prev"'),'Category preserved through pagination');
$r_DIR['paginacao']['pag']=3;$html=$renderNews();verify(!str_contains($html,'rel="next"'),'No next on last page');
$r_DIR['noticias']=array();$html=$renderNews();verify(str_contains($html,'Nenhuma notícia encontrada.')&&!str_contains($html,'class="news-pagination"'),'Empty state without invalid pagination');
$r_DIR['noticias']=array(array('titulo'=>'<script>bad()</script>','subtitulo'=>'<img src=x onerror=bad()>Texto','img'=>'javascript:bad()','link'=>'seguro','data_criacao'=>'invalid'));$html=$renderNews();verify(!str_contains($html,'<script>bad')&&!str_contains($html,'javascript:')&&!str_contains($html,'onerror='),'Escape news metadata and reject unsafe image');
verify(str_contains($html,'news-listing-image')&&!str_contains($html,'datetime="1970'),'Missing image and invalid date');
echo 'OK: '.($count-$before)." news listing checks.\n";
