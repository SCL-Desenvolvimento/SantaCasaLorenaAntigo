<?php
// Callers supply $searchId and $searchLabel; controls are enabled by the native filter script.
?>
<div class="service-search" data-service-controls hidden role="search"><label for="<?= scl_escape($searchId) ?>"><?= scl_escape($searchLabel) ?></label><div><input id="<?= scl_escape($searchId) ?>" type="search" data-service-search autocomplete="off" placeholder="Digite para buscar" aria-describedby="<?= scl_escape($searchId) ?>-count"><button type="button" data-service-clear>Limpar busca</button></div><p id="<?= scl_escape($searchId) ?>-count" data-service-count role="status" aria-live="polite"></p></div>
<p class="about-empty" data-service-empty hidden>Nenhum resultado encontrado. Tente outra palavra ou limpe a busca.</p>
