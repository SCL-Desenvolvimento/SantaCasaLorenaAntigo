<?php
// Listing uses the records and active category already resolved by Url.
$newsItems=$r_DIR['noticias'] ?? array();
$newsPage=max(1,(int)($r_DIR['paginacao']['pag'] ?? 1));
$newsLimit=max(1,(int)($r_DIR['limit'] ?? 5));
$newsTag=isset($Url) ? (string)$Url->getNoticiaTag() : '';
$newsTotal=count($newsItems);
if (!empty($r_DIR['termos'])) {
    $countSql=preg_replace('~\s+LIMIT\s+:limit\s+OFFSET\s+:offset\s*$~i','',$r_DIR['termos']);
    parse_str($r_DIR['places'] ?? '',$countParams);unset($countParams['limit'],$countParams['offset']);
    $countQuery=new Read();$countQuery->fullRead('SELECT COUNT(*) AS listing_total FROM ('.$countSql.') AS news_listing',http_build_query($countParams));
    $newsTotal=(int)(($countQuery->getResult() ?: array())[0]['listing_total'] ?? count($newsItems));
}
$newsPages=max(1,(int)ceil($newsTotal/$newsLimit));
$categoryQuery=new Read();$categoryQuery->fullRead('SELECT DISTINCT T.nome, T.url FROM '.PREFIX.'tag AS T INNER JOIN '.PREFIX.'tag_noticia AS TN ON TN.id_tag=T.id_tag INNER JOIN '.PREFIX.'noticia AS N ON N.id_noticia=TN.id_noticia WHERE N.status=1 ORDER BY T.nome ASC');
$newsCategories=$categoryQuery->getResult() ?: array();
$popularQuery=new Read();$popularQuery->fullRead('SELECT N.*, ANY_VALUE(T.nome) AS tag, ANY_VALUE(T.url) AS url_tag FROM '.PREFIX.'noticia AS N LEFT JOIN '.PREFIX.'tag_noticia AS TN ON TN.id_noticia=N.id_noticia LEFT JOIN '.PREFIX.'tag AS T ON T.id_tag=TN.id_tag WHERE N.status=1 GROUP BY N.id_noticia ORDER BY N.acessos DESC LIMIT 3');
$popularNews=$popularQuery->getResult() ?: array();
$newsBase='noticias/'.($newsTag!=='' ? rawurlencode($newsTag).'/' : '');
?>
<section class="site-container news-listing">
<nav class="news-categories" aria-label="Categorias de notícias"><a href="<?= scl_url('noticias') ?>" <?= $newsTag==='' ? 'aria-current="page"' : '' ?>>Todas as notícias</a><?php foreach($newsCategories as $category): if(empty($category['url']))continue; ?><a href="<?= scl_url('noticias/'.rawurlencode($category['url'])) ?>" <?= $newsTag===$category['url'] ? 'aria-current="page"' : '' ?>><?= scl_escape($category['nome'] ?? '') ?></a><?php endforeach; ?></nav>
<div class="news-listing-layout"><div>
<div class="news-listing-heading"><h2><?= $newsTag!=='' ? 'Notícias da categoria' : 'Últimas notícias' ?></h2><?php if($newsItems): ?><p>Página <?= $newsPage ?> de <?= $newsPages ?></p><?php endif; ?></div>
<?php if($newsItems): ?><div class="news-listing-grid">
<?php foreach($newsItems as $news): $newsImage=scl_link($news['img'] ?? '');$newsHref=scl_url('noticias/'.rawurlencode($news['link'] ?? ''));$newsDate=strtotime($news['data_criacao'] ?? ''); ?>
<article class="news-listing-card">
<a class="news-listing-image" href="<?= $newsHref ?>" tabindex="-1" aria-hidden="true"><?php if($newsImage): ?><img src="<?= scl_escape($newsImage) ?>" alt="" width="640" height="400" loading="lazy" decoding="async"><?php else: ?><span><?= scl_icon('file') ?></span><?php endif; ?></a>
<div class="news-listing-copy"><div class="news-listing-meta"><?php if(!empty($news['url_tag'])): ?><a href="<?= scl_url('noticias/'.rawurlencode($news['url_tag'])) ?>"><?= scl_escape($news['tag'] ?? '') ?></a><?php endif; ?><?php if($newsDate!==false): ?><time datetime="<?= date('Y-m-d',$newsDate) ?>"><?= date('d/m/Y',$newsDate) ?></time><?php endif; ?></div>
<h3><a href="<?= $newsHref ?>"><?= scl_escape($news['titulo'] ?? '') ?></a></h3><p><?= scl_escape(strip_tags($news['subtitulo'] ?? '')) ?></p><a class="text-link" href="<?= $newsHref ?>" aria-label="Ler notícia: <?= scl_escape($news['titulo'] ?? '') ?>">Ler notícia <?= scl_icon('arrow') ?></a></div>
</article><?php endforeach; ?></div>
<?php if($newsPages>1): ?><nav class="news-pagination" aria-label="Paginação de notícias">
<?php if($newsPage>1): ?><a rel="prev" href="<?= scl_url($newsBase.($newsPage-1)) ?>">← Anterior</a><?php endif; ?>
<?php $pageLinks=array_unique(array_merge(array(1),range(max(1,$newsPage-1),min($newsPages,$newsPage+1)),array($newsPages)));sort($pageLinks);$lastPage=0;foreach($pageLinks as $pageNumber):if($pageNumber>$lastPage+1): ?><span aria-hidden="true">…</span><?php endif; ?><a href="<?= scl_url($newsBase.$pageNumber) ?>" aria-label="Página <?= $pageNumber ?>" <?= $pageNumber===$newsPage ? 'aria-current="page"' : '' ?>><?= $pageNumber ?></a><?php $lastPage=$pageNumber;endforeach; ?>
<?php if($newsPage<$newsPages): ?><a rel="next" href="<?= scl_url($newsBase.($newsPage+1)) ?>">Próxima →</a><?php endif; ?></nav><?php endif; ?>
<?php else: ?><div class="news-empty"><h3>Nenhuma notícia encontrada.</h3><p>Não há notícias disponíveis nesta página. Consulte as demais publicações da Santa Casa.</p><a class="scl-button" href="<?= scl_url('noticias') ?>">Ver todas as notícias <?= scl_icon('arrow') ?></a></div><?php endif; ?>
</div>
<?php if($popularNews): ?><aside class="news-popular" aria-labelledby="popular-title"><span class="eyebrow">EM DESTAQUE</span><h2 id="popular-title">Mais lidas</h2><ol><?php foreach($popularNews as $news): ?><li><a href="<?= scl_url('noticias/'.rawurlencode($news['link'] ?? '')) ?>"><?= scl_escape($news['titulo'] ?? '') ?></a></li><?php endforeach; ?></ol></aside><?php endif; ?>
</div></section>
