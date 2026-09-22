<?php
/** Shared destinations for navigation and the four historical contact entries. */
function scl_contact_url($channel = 'contato') {
    $base = rtrim(defined('ROOT') ? ROOT : '/', '/') . '/fale-conosco';
    if ($channel === 'localizacao') return $base . '#localizacao';
    if (!in_array($channel, array('contato', 'trabalhe_conosco', 'pesquisa'), true)) $channel = 'contato';
    return $base . '?canal=' . $channel . '#formulario';
}

function scl_contact_redirect_target($page) {
    $channels = array('ouvidoria'=>'contato', 'trabalhe_conosco'=>'trabalhe_conosco', 'pesquisa_atendimento'=>'pesquisa', 'localizacao'=>'localizacao');
    $key = str_replace('-', '_', (string)$page);
    return isset($channels[$key]) ? scl_contact_url($channels[$key]) : null;
}

function scl_redirect_contact($page) {
    $target = scl_contact_redirect_target($page);
    if ($target === null) return;
    header('Location: ' . $target, true, 302);
    exit;
}
