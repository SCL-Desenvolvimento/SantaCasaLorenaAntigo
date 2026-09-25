<?php
/** Preserve simple CMS formatting without executing embedded HTML or inline styles. */
function scl_about_content($value) {
    $value = trim((string) $value);
    if ($value === '') return '';
    if (!class_exists('DOMDocument')) return nl2br(scl_escape(strip_tags($value)));
    $document = new DOMDocument();
    $previous = libxml_use_internal_errors(true);
    $document->loadHTML('<?xml encoding="UTF-8"><html><body>' . $value . '</body></html>', LIBXML_NONET);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    $render = function ($node) use (&$render) {
        if ($node instanceof DOMText) return scl_escape($node->nodeValue);
        if (!($node instanceof DOMElement)) return '';
        $tag = strtolower($node->tagName);
        if (in_array($tag, array('script', 'style', 'iframe', 'object', 'svg', 'math', 'template'), true)) return '';
        $content = '';
        foreach ($node->childNodes as $child) $content .= $render($child);
        if ($tag === 'br') return '<br>';
        if ($tag === 'a') {
            $href = scl_link($node->getAttribute('href'));
            return $href ? '<a href="' . scl_escape($href) . '">' . $content . '</a>' : $content;
        }
        if (in_array($tag, array('p', 'strong', 'b', 'em', 'i', 'ul', 'ol', 'li', 'blockquote'), true)) return '<' . $tag . '>' . $content . '</' . $tag . '>';
        return $content;
    };
    $body = $document->getElementsByTagName('body')->item(0);
    $html = $body ? $render($body) : '';
    return preg_match('~<(?:p|ul|ol|blockquote)\b~i', $html) ? $html : nl2br($html);
}

/** Older CMS blocks may contain paragraphs instead of short section headings. */
function scl_cms_heading_text($value): string {
    return trim(preg_replace('/\s+/u',' ',html_entity_decode(strip_tags((string)$value),ENT_QUOTES|ENT_HTML5,'UTF-8')) ?? '');
}
function scl_cms_heading($value, string $fallback): string {
    $text=scl_cms_heading_text($value);
    return scl_escape($text!==''&&mb_strlen($text)<=160?$text:$fallback);
}
function scl_cms_introduction($value): string {
    return mb_strlen(scl_cms_heading_text($value))>160?'<div class="about-prose cms-introduction">'.scl_about_content($value).'</div>':'';
}
