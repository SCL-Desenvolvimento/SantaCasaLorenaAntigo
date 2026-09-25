<?php
$homeNews = new Read();
$homeNews->fullRead('SELECT * FROM ' . PREFIX . 'noticia WHERE status = 1 ORDER BY data_criacao DESC LIMIT 3');
$homeBanners = new Read();
$homeBanners->fullRead('SELECT * FROM ' . PREFIX . 'banner WHERE status = 1');
$homePlans = new Read();
$homePlans->fullRead('SELECT * FROM ' . PREFIX . 'convenio ORDER BY data_criacao ASC');
?>
<section class="home-hero">
<div class="site-container hero-grid">
<div class="hero-copy"><span class="eyebrow"><span class="status-dot"></span> PRESENTE NA SUA VIDA</span><h1>Cuidar de você.<br>É o que nos <em>move.</em></h1><p>Acolhimento, dedicação e cuidado em cada encontro. A Santa Casa de Lorena está ao seu lado e de quem você ama.</p><div class="hero-actions"><a class="scl-button" href="<?= scl_url('servicos/especialidades') ?>">Conheça nossos serviços <?= scl_icon('arrow') ?></a><a class="text-link" href="<?= scl_url('institucional/sobre-a-santa-casa') ?>">Nossa história <span aria-hidden="true">↗</span></a></div><div class="hero-footnote"><?= scl_icon('heart') ?><span>Uma história de cuidado.<br><strong>Um compromisso com a vida.</strong></span></div></div>
<div class="hero-visual"><img class="hero-photo" src="<?= scl_url('resources/img/santa-casa-home/pronto-atendimento.png') ?>" alt="Profissional de saúde em ambiente hospitalar" width="660" height="520" fetchpriority="high"><div class="hero-caption"><span class="caption-icon"><?= scl_icon('cross') ?></span><div><strong>Gente cuidando de gente.</strong><span>Saúde com acolhimento e respeito.</span></div></div><span class="hero-decoration" aria-hidden="true">+</span></div>
</div>
</section>
<section class="quick-section site-container" aria-labelledby="quick-title"><div class="quick-heading"><span class="eyebrow">PODEMOS AJUDAR?</span><h2 id="quick-title">O que você precisa hoje?</h2></div><div class="quick-grid">
<?php foreach (array(
array('file', 'Resultados de exames', 'Acesse o portal de resultados', 'https://1741prd-vivace-portal.cloudmv.com.br:432/login'),
array('shield', 'Convênios', 'Conheça os planos atendidos', scl_url('servicos/convenios')),
array('cross', 'Especialidades', 'Encontre o cuidado que precisa', scl_url('servicos/especialidades')),
array('people', 'Pacientes e visitantes', 'Prepare-se para sua visita', scl_url('servicos/manual-do-paciente-e-visitantes'))
) as $item): ?><a class="quick-card" href="<?= scl_escape($item[3]) ?>"><span class="quick-icon"><?= scl_icon($item[0]) ?></span><strong><?= $item[1] ?></strong><span><?= $item[2] ?></span><span class="card-arrow"><?= scl_icon('arrow') ?></span></a><?php endforeach; ?>
</div></section>
<section class="care-section section-space"><div class="site-container"><div class="section-heading"><div><span class="eyebrow">ESTRUTURA PARA ACOLHER</span><h2>Cuidado em cada detalhe.</h2></div><p>Conheça os espaços e os serviços que fazem parte da nossa dedicação a você.</p></div><div class="care-grid">
<?php foreach (array(
array('pronto-atendimento', 'Pronto atendimento SUS', 'Acolhimento e assistência para quem precisa de cuidado.', 'pronto-atendimento.png', '01'),
array('centro-diagnostico-por-imagem', 'Diagnóstico por imagem', 'Estrutura dedicada a apoiar o diagnóstico e o seu cuidado.', 'centro-diagnose-imagen.png', '02'),
array('unidades-de-internacao', 'Unidades de internação', 'Conforto e atenção em cada etapa da recuperação.', 'unidade-de-internacao.png', '03')
) as $care): ?><a class="care-card" href="<?= scl_url('instalacoes/' . $care[0]) ?>"><div class="care-image"><img src="<?= scl_url('resources/img/santa-casa-home/' . $care[3]) ?>" alt="" loading="lazy" width="420" height="280"><span><?= $care[4] ?></span></div><div class="care-copy"><h3><?= $care[1] ?></h3><p><?= $care[2] ?></p><span class="text-link">Conheça o serviço <?= scl_icon('arrow') ?></span></div></a><?php endforeach; ?>
</div></div></section>
<?php if ($homeBanners->getResult()): ?>
<section class="site-container campaign-section" data-campaign-carousel aria-label="Comunicados e campanhas"><div class="section-heading"><div><span class="eyebrow">FIQUE POR DENTRO</span><h2>Acontece na Santa Casa</h2></div></div><div class="campaign-list" tabindex="0" id="home-campaigns" aria-label="Campanhas">
<?php foreach ($homeBanners->getResult() as $banner): $bannerLink = scl_link($banner['link']); ?>
<figure class="campaign-item"><?php if ($bannerLink): ?><a href="<?= scl_escape($bannerLink) ?>"><?php endif; ?><img src="<?= scl_escape(scl_asset($banner['img'])) ?>" alt="<?= scl_escape($banner['titulo']) ?>" loading="lazy"><?php if ($bannerLink): ?></a><?php endif; ?></figure>
<?php endforeach; ?></div><div class="carousel-controls" hidden><button type="button" data-gallery-prev aria-label="Campanha anterior" aria-controls="home-campaigns">←</button><span data-gallery-count aria-live="polite"></span><button type="button" data-gallery-next aria-label="Próxima campanha" aria-controls="home-campaigns">→</button></div></section>
<?php endif; ?>
<section class="section-space"><div class="site-container about-grid"><div class="about-visual"><img src="<?= scl_url('resources/img/santa-casa-home/acoes-sociais-ambientais-img.png') ?>" alt="Pessoa idosa participando de uma atividade manual" loading="lazy" width="580" height="450"><span class="image-label">NOSSA ESSÊNCIA É CUIDAR</span></div><div class="about-copy"><span class="eyebrow">PERTENCER. ACOLHER. CUIDAR.</span><h2>Parte da história de Lorena.<br><em>Parte da sua vida.</em></h2><p>Somos uma instituição feita de pessoas e para pessoas. Nossa história se constrói no cuidado com os pacientes, no apoio às famílias e no vínculo com a comunidade.</p><a class="text-link" href="<?= scl_url('institucional/sobre-a-santa-casa') ?>">Conheça a Santa Casa <?= scl_icon('arrow') ?></a><div class="about-values"><span><?= scl_icon('heart') ?> Humanização</span><span><?= scl_icon('shield') ?> Respeito à vida</span></div></div></div></section>
<?php if ($homePlans->getResult()): ?>
<section class="plans-section"><div class="site-container"><div class="section-heading"><div><span class="eyebrow">MAIS ACESSO AO CUIDADO</span><h2>Convênios atendidos</h2></div><a class="text-link" href="<?= scl_url('servicos/convenios') ?>">Ver todos os convênios <?= scl_icon('arrow') ?></a></div><div class="plans-grid"><?php foreach (array_slice($homePlans->getResult(), 0, 8) as $plan): ?><a href="<?= scl_url('servicos/convenios') ?>"><img src="<?= scl_escape(scl_asset($plan['img'])) ?>" alt="<?= scl_escape($plan['nome']) ?>" loading="lazy" width="140" height="64"></a><?php endforeach; ?></div></div></section>
<?php endif; ?>
<section class="section-space news-section"><div class="site-container"><div class="section-heading"><div><span class="eyebrow">NOSSA COMUNIDADE</span><h2>Notícias e histórias</h2></div><a class="text-link" href="<?= scl_url('noticias') ?>">Todas as notícias <?= scl_icon('arrow') ?></a></div><div class="news-grid">
<?php if ($homeNews->getResult()): foreach ($homeNews->getResult() as $news): ?>
<article class="news-card"><a href="<?= scl_escape(scl_url('noticias/' . $news['link'])) ?>"><img src="<?= scl_escape(scl_asset($news['img'] ?: 'resources/img/no-image.png')) ?>" alt="" loading="lazy" width="420" height="260"><div class="news-copy"><time datetime="<?= scl_escape(date('Y-m-d', strtotime($news['data_criacao']))) ?>"><?= date('d.m.Y', strtotime($news['data_criacao'])) ?></time><h3><?= scl_escape($news['titulo']) ?></h3><p><?= scl_escape(strip_tags($news['subtitulo'])) ?></p><span class="text-link">Ler notícia <?= scl_icon('arrow') ?></span></div></a></article>
<?php endforeach; else: ?><p class="empty-state">As novidades da Santa Casa serão publicadas aqui. Conheça também nossas <a href="<?= scl_url('institucional/acoes-sociais-ambientais') ?>">ações sociais e ambientais</a>.</p><?php endif; ?>
</div></div></section>
<section class="site-container support-section"><div class="support-icon"><?= scl_icon('heart') ?></div><div><span class="eyebrow">SOLIDARIEDADE QUE TRANSFORMA</span><h2>Seu apoio também é cuidado.</h2><p>Faça parte dessa história. Conheça as formas de contribuir com a Santa Casa de Lorena.</p></div><a class="scl-button light" href="<?= scl_url('doacoes') ?>">Quero contribuir <?= scl_icon('arrow') ?></a></section>

