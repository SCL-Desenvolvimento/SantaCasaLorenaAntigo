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
