<?php
require_once dirname(__DIR__).'/article_helpers.php';
$article = $r_DIR['noticia'] ?? array();
$articleId = max(0, (int)($article['id_noticia'] ?? 0));
if ($articleId && !defined('SCL_PREVIEW')) {
    $updateAcessos = new Update();
    $updateAcessos->ExeUpdate(PREFIX.'noticia', array('acessos'=>(int)($article['acessos'] ?? 0)+1), 'WHERE id_noticia = :id_noticia', 'id_noticia='.$articleId);
}
$getTags = new Read();
$getTags->fullRead('SELECT T.* FROM '.PREFIX.'tag AS T INNER JOIN '.PREFIX.'tag_noticia AS TN ON TN.id_tag = T.id_tag WHERE TN.id_noticia = :article', 'article='.$articleId);
$articleTags = array_values(array_filter($getTags->getResult() ?: array(), fn($tag)=>!empty($tag['url']) && !empty($tag['nome'])));
$tagIds = array_values(array_filter(array_map(fn($tag)=>(int)($tag['id_tag'] ?? 0), $articleTags)));
$related = array();
if ($tagIds) {
    $readRelated = new Read();
    $readRelated->fullRead('SELECT DISTINCT N.* FROM '.PREFIX.'noticia AS N INNER JOIN '.PREFIX.'tag_noticia AS TN ON TN.id_noticia = N.id_noticia WHERE N.status = 1 AND N.id_noticia != :article AND TN.id_tag IN ('.implode(',', $tagIds).') ORDER BY N.data_criacao DESC LIMIT 3', 'article='.$articleId);
    $related = $readRelated->getResult() ?: array();
}
$articleImage = scl_link($article['img'] ?? '');
$articlePath = 'noticias/'.rawurlencode($article['link'] ?? '');
$shareUrl = preg_match('~^https?://~i', HOME) ? rtrim(HOME, '/').'/'.$articlePath : scl_url($articlePath);
?>
<section class="section-space article-page"><div class="site-container">
<div class="article-layout"><article class="article-main" aria-label="Conteúdo da notícia">
<?php if (!empty($article['subtitulo'])): ?><p class="article-deck"><?= scl_escape(strip_tags($article['subtitulo'])) ?></p><?php endif; ?>
<?php if ($articleImage): ?><figure class="article-cover"><a class="article-image-link" href="<?= scl_escape($articleImage) ?>"><img src="<?= scl_escape($articleImage) ?>" alt="<?= scl_escape(strip_tags($article['titulo'] ?? '')) ?>" width="1000" height="620" fetchpriority="high"></a></figure><?php endif; ?>
<div class="article-prose"><?= scl_article_content($article['descricao'] ?? '') ?></div>
<?php if ($articleTags): ?><nav class="article-tags" aria-label="Assuntos desta notícia"><span>Assuntos</span><?php foreach ($articleTags as $tag): ?><a href="<?= scl_url('noticias/'.rawurlencode($tag['url'])) ?>"><?= scl_escape($tag['nome']) ?></a><?php endforeach; ?></nav><?php endif; ?>
<a class="text-link article-back" href="<?= scl_url('noticias') ?>">← Voltar para todas as notícias</a>
</article><aside class="article-aside"><div class="community-info"><span class="eyebrow">Compartilhe o cuidado</span><h2>Leve esta notícia adiante</h2><p>Compartilhe com quem acompanha a Santa Casa.</p><div class="article-share"><button class="scl-button" type="button" data-copy-article="<?= scl_escape(scl_url($articlePath)) ?>" hidden>Copiar link <?= scl_icon('file') ?></button><a data-share-article="<?= scl_escape(scl_url($articlePath)) ?>" href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode($shareUrl) ?>" target="_blank" rel="noopener noreferrer">Compartilhar no Facebook ↗</a><span role="status" data-copy-status></span></div></div><div class="community-help"><h3>Mais histórias da Santa Casa</h3><p>Acompanhe as novidades e ações da instituição.</p><a class="text-link" href="<?= scl_url('noticias') ?>">Explorar notícias <?= scl_icon('arrow') ?></a></div></aside></div>
<?php if ($related): ?><section class="article-related" aria-labelledby="related-title"><span class="eyebrow">Continue a leitura</span><h2 id="related-title">Notícias relacionadas</h2><div class="article-related-grid"><?php foreach ($related as $item): ?><article><a href="<?= scl_url('noticias/'.rawurlencode($item['link'])) ?>"><?php if (scl_link($item['img'] ?? '')): ?><img src="<?= scl_escape(scl_link($item['img'])) ?>" alt="" loading="lazy" width="480" height="300"><?php endif; ?><h3><?= scl_escape($item['titulo']) ?></h3><p><?= scl_escape(strip_tags($item['subtitulo'] ?? '')) ?></p><span class="text-link">Ler notícia →</span></a></article><?php endforeach; ?></div></section><?php endif; ?>
</div></section>
<dialog class="article-lightbox" aria-label="Imagem da notícia ampliada"><button type="button" data-article-close autofocus>Fechar ×</button><img alt=""></dialog>
