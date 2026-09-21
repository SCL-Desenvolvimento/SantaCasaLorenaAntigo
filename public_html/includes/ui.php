<?php
/** Shared presentation helpers. No database or session side effects. */
function scl_escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function scl_url($path = '') {
    return rtrim(ROOT, '/') . '/' . ltrim($path, '/');
}

function scl_asset($path) {
    if (preg_match('~^https?://~i', (string) $path)) {
        return $path;
    }
    return scl_url($path);
}

function scl_link($path) {
    $path = trim((string) $path);
    if ($path === '' || preg_match('~^(?:[a-z][a-z0-9+.-]*:|//)~i', $path)) {
        return preg_match('~^https?://~i', $path) ? $path : '';
    }
    return scl_url($path);
}

function scl_icon($name) {
    $paths = array(
        'arrow' => '<path d="M5 12h14m-6-6 6 6-6 6"/>',
        'heart' => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/>',
        'file' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Zm0 0v6h6M8 13h8m-8 4h5"/>',
        'cross' => '<path d="M9 3h6v6h6v6h-6v6H9v-6H3V9h6Z"/>',
        'people' => '<circle cx="9" cy="7" r="4"/><path d="M2 21v-3a7 7 0 0 1 14 0v3m1-18a4 4 0 0 1 0 8m3 10v-3a7 7 0 0 0-3-5"/>',
        'pin' => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
        'phone' => '<path d="m7 3 3 5-3 3a16 16 0 0 0 6 6l3-3 5 3-1 4C10 22 2 14 3 4Z"/>',
        'shield' => '<path d="m12 2 9 4v6c0 6-9 10-9 10S3 18 3 12V6Zm-4 10 3 3 5-6"/>',
        'search' => '<circle cx="10.5" cy="10.5" r="7"/><path d="m16 16 5 5"/>',
    );
    return '<svg class="scl-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? $paths['arrow']) . '</svg>';
}
