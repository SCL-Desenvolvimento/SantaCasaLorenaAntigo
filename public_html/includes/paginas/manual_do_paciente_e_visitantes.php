<?php
require_once __DIR__.'/../about_helpers.php';
$getPagina->fullRead('SELECT * FROM '.PREFIX.'pagina_manual_paciente_visitante ORDER BY data DESC LIMIT 1');$manualContent=($getPagina->getResult() ?: array())[0] ?? array();
$query=new Read();$query->fullRead('SELECT * FROM '.PREFIX.'manual_paciente ORDER BY data_criacao ASC');$topics=$query->getResult() ?: array();
$downloadQuery=new Read();$downloadQuery->fullRead('SELECT * FROM '.PREFIX.'download_manual_paciente ORDER BY data_criacao DESC');$downloads=$downloadQuery->getResult() ?: array();
?>
<div class="facility-page">
<nav class="about-section-nav site-container" aria-label="Nesta página"><span>Nesta página</span><a href="#manual-orientacoes">Orientações</a><?php if($downloads): ?><a href="#manual-downloads">Documentos</a><?php endif; ?><a href="<?= scl_url('fale-conosco') ?>">Fale conosco</a></nav>
<section id="manual-orientacoes" class="site-container service-section" aria-labelledby="manual-title" data-service-directory>
<div class="section-heading"><div><span class="eyebrow">PACIENTES E VISITANTES</span><h2 id="manual-title"><?= scl_cms_heading($manualContent['bloco1'] ?? '', 'Orientações para sua visita.') ?></h2><?= scl_cms_introduction($manualContent['bloco1'] ?? '') ?><p>Selecione um assunto para ler as orientações disponibilizadas pela Santa Casa.</p></div></div>
<?php if($topics): ?>
<?php $searchId='manual-search';$searchLabel='Buscar nas orientações';require __DIR__.'/../service_search.php'; ?>
<div class="service-topic-tools" data-service-expand-tools hidden><button type="button" data-service-expand>Expandir assuntos</button><button type="button" data-service-collapse>Recolher assuntos</button></div>
<?php foreach($topics as $topic): ?><details class="service-topic" data-service-item><summary><?= scl_escape($topic['titulo'] ?? 'Orientações') ?><span aria-hidden="true">+</span></summary><div class="about-prose"><?= scl_about_content($topic['descricao'] ?? '') ?></div></details><?php endforeach; ?>
<?php else: ?><p class="about-empty">As orientações serão disponibilizadas nesta página. Para informações, <a href="<?= scl_url('fale-conosco') ?>">fale com a nossa equipe</a>.</p><?php endif; ?>
</section>
<?php if($downloads): ?><section id="manual-downloads" class="facility-environments" aria-labelledby="manual-downloads-title"><div class="site-container"><div class="section-heading"><div><span class="eyebrow">PARA CONSULTAR</span><h2 id="manual-downloads-title">Documentos e downloads.</h2><p>Os documentos abrem em uma nova aba.</p></div></div><ul class="service-downloads">
<?php foreach($downloads as $download): $fileUrl=scl_link($download['pdf'] ?? '');$downloadId=filter_var($download['id_download_manual_paciente'] ?? null,FILTER_VALIDATE_INT);if(!$fileUrl && $downloadId>1) $fileUrl=scl_url('servicos/manual-paciente-visitante/file-'.$downloadId); ?>
<li><?php if($fileUrl): ?><a href="<?= scl_escape($fileUrl) ?>" target="_blank" rel="noopener"><span aria-hidden="true"><?= scl_icon('file') ?></span><span><?= scl_escape($download['titulo'] ?? 'Documento') ?></span><span aria-hidden="true">↗</span></a><?php else: ?><span><?= scl_escape($download['titulo'] ?? 'Documento') ?> — arquivo indisponível</span><?php endif; ?></li>
<?php endforeach; ?></ul></div></section><?php endif; ?>
<section class="site-container service-contact"><h2>Precisa de mais informações?</h2><a class="scl-button" href="<?= scl_url('fale-conosco') ?>">Fale com a nossa equipe <?= scl_icon('arrow') ?></a></section>
</div>
