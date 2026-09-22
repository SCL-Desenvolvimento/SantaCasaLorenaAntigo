<?php
/** Render the editor's article content and galleries without legacy plugins. */
function scl_article_content($value) {
    if (!class_exists('DOMDocument')) return nl2br(scl_escape(strip_tags((string)$value)));
    if (trim((string)$value) === '') return '';
    $document = new DOMDocument();
    $previous = libxml_use_internal_errors(true);
    $document->loadHTML('<?xml encoding="UTF-8"><html><body>'.$value.'</body></html>', LIBXML_NONET);
    libxml_clear_errors(); libxml_use_internal_errors($previous);
    $galleryNumber = 0;
    $render = function($node) use (&$render, &$galleryNumber) {
        if ($node instanceof DOMText) return scl_escape($node->nodeValue);
        if (!($node instanceof DOMElement)) return '';
        $tag = strtolower($node->tagName);
        if (in_array($tag, array('script','style','object','embed','svg','math','template','input','button','form'), true)) return '';
        if (in_array('ck-galleria', explode(' ', $node->getAttribute('class')), true)) {
            $input = $node->getElementsByTagName('input')->item(0);
            $id = $input ? filter_var($input->getAttribute('value'), FILTER_VALIDATE_INT, array('options'=>array('min_range'=>1))) : false;
            if (!$id) return '';
            $read = new Read();
            $read->fullRead('SELECT DISTINCT A.* FROM '.PREFIX.'galeria_anexo AS GA INNER JOIN '.PREFIX.'anexo AS A ON A.id_anexo = GA.id_anexo WHERE GA.id_galeria = :gallery', 'gallery='.$id);
            $photos = array();
            foreach ($read->getResult() ?: array() as $photo) if (scl_link($photo['url'] ?? '')) $photos[] = array('img'=>$photo['url'], 'descricao'=>$photo['descricao'] ?? '');
            if (!$photos) return '';
            $galleryId = 'article-gallery-'.(++$galleryNumber); $galleryLabel = 'Galeria da notícia '.$galleryNumber;
            ob_start(); require __DIR__.'/institutional_gallery.php'; return ob_get_clean();
        }
        if ($tag === 'img') {
            $src = scl_link($node->getAttribute('src'));
            return $src ? '<a class="article-image-link" href="'.scl_escape($src).'"><img src="'.scl_escape($src).'" alt="'.scl_escape($node->getAttribute('alt')).'" loading="lazy" decoding="async"></a>' : '';
        }
        if ($tag === 'iframe') {
            $src = $node->getAttribute('src');
            if (!preg_match('~^https://(?:www\.)?(?:youtube(?:-nocookie)?\.com/embed/[a-zA-Z0-9_-]+|player\.vimeo\.com/video/[0-9]+)(?:[?][^"<>]*)?$~', $src)) return '';
            return '<iframe class="article-video" src="'.scl_escape($src).'" title="Vídeo da notícia" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>';
        }
        $content = '';
        foreach ($node->childNodes as $child) $content .= $render($child);
        if ($tag === 'a') {
            $href = scl_link($node->getAttribute('href'));
            // An editor image already has an enlargement link; do not nest anchors.
            if (str_contains($content, '<a ')) return $content;
            return $href ? '<a href="'.scl_escape($href).'">'.$content.'</a>' : $content;
        }
        if ($tag === 'br' || $tag === 'hr') return '<'.$tag.'>';
        if ($tag === 'table') return '<div class="article-table" tabindex="0" role="region" aria-label="Tabela da notícia"><table>'.$content.'</table></div>';
        if ($tag === 'h1') $tag = 'h2';
        if (in_array($tag, array('p','h2','h3','h4','h5','h6','strong','b','em','i','u','s','sub','sup','ul','ol','li','blockquote','figure','figcaption','thead','tbody','tfoot','tr','th','td'), true)) {
            $attributes = '';
            if ($tag === 'th' || $tag === 'td') foreach (array('colspan','rowspan') as $attr) {
                $span = filter_var($node->getAttribute($attr), FILTER_VALIDATE_INT, array('options'=>array('min_range'=>1,'max_range'=>100)));
                if ($span) $attributes .= ' '.$attr.'="'.$span.'"';
            }
            return '<'.$tag.$attributes.'>'.$content.'</'.$tag.'>';
        }
        return $content;
    };
    $html = $render($document->getElementsByTagName('body')->item(0));
    return preg_match('~<(?:p|h[2-6]|div|ul|ol|blockquote)\b~i', $html) ? $html : nl2br($html);
}
