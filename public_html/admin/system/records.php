<?php
if (!defined('SCL_ADMIN_PANEL')) { http_response_code(403); exit; }
$kind=$linkTo[0]; $mode=$linkTo[1]??'index';
$labels=['usuario'=>['Usuários','usuário'],'banner'=>['Banners','banner'],'noticias'=>['Notícias','notícia']];
[$plural,$single]=$labels[$kind]; $idKey=['usuario'=>'id_usuario','banner'=>'id_banner','noticias'=>'id_noticia'][$kind];
$id=$mode==='update'?filter_var($_GET[$idKey]??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]):0;
if($mode==='update'&&!$id)scl_deny(400);
$isProfile=$kind==='usuario'&&$id===(int)$usuarioLogin['id_usuario'];
$config=['kind'=>$kind,'mode'=>$mode,'id'=>$id,'idKey'=>$idKey,'self'=>$isProfile];
?>
<div data-records='<?= admin_escape(json_encode($config)) ?>'>
<h1><?= $isProfile?'Meu perfil':admin_escape($plural) ?><small><?= $mode==='index'?'Gerencie os registros e acompanhe sua publicação.':($mode==='create'?'Novo cadastro':'Editar informações') ?></small></h1>
<div class="admin-toolbar"><a class="btn btn-default" href="painel.php?exe=<?= $kind ?>/index">← Listagem</a><?php if($mode!=='create'): ?><a class="btn btn-primary" href="painel.php?exe=<?= $kind ?>/create">+ Adicionar <?= $single ?></a><?php endif ?></div>
<div class="admin-feedback" role="status" aria-live="polite"></div>
<?php if($mode==='index'): ?>
<section class="box"><div class="box-header admin-toolbar"><div><h2 class="h5 mb-1">Todos os registros</h2><p class="text-muted mb-0" data-record-count>Carregando…</p></div><label>Situação <select class="form-select" data-status-filter><option value="">Todas</option><option value="1"><?= $kind==='usuario'?'Ativos':'Publicados' ?></option><option value="0"><?= $kind==='usuario'?'Inativos':'Rascunhos' ?></option></select></label><button class="btn btn-default" type="button" data-record-refresh>Atualizar</button></div><div class="box-body table-responsive"><table class="table align-middle" id="records-table"><thead><tr><th><?= $kind==='usuario'?'Nome':'Título' ?></th><th><?= $kind==='usuario'?'E-mail':'Imagem' ?></th><th>Situação</th><th>Ações</th></tr></thead><tbody></tbody></table></div></section>
<?php else: ?>
<form class="box record-form" data-record-form <?= $mode==='update'?'hidden':'' ?>>
<div class="box-body record-grid"><section>
<h2 class="h5">Informações <?= $kind==='usuario'?'da conta':'do conteúdo' ?></h2>
<div class="form-group"><label for="record-title"><?= $kind==='usuario'?'Nome completo':'Título' ?> *</label><input class="form-control" id="record-title" name="<?= $kind==='usuario'?'nome':'titulo' ?>" required maxlength="150" autocomplete="<?= $kind==='usuario'?'name':'off' ?>"></div>
<?php if($kind==='usuario'): ?>
<div class="form-group"><label for="record-email">E-mail *</label><input class="form-control" type="email" id="record-email" name="email" autocomplete="email" required maxlength="254"></div>
<div class="form-group"><label for="record-user">Usuário de acesso *</label><input class="form-control" id="record-user" name="usuario" required pattern="[a-zA-Z0-9_.@-]{3,100}" autocomplete="username"><p class="help-block">Use ao menos 3 caracteres: letras, números, ponto, hífen ou sublinhado.</p></div>
<div class="form-group"><label for="record-password"><?= $mode==='create'?'Senha *':'Nova senha' ?></label><input class="form-control" type="password" id="record-password" name="senha" minlength="12" maxlength="72" autocomplete="new-password" <?= $mode==='create'?'required':'' ?>><p class="help-block"><?= $mode==='update'?'Deixe em branco para manter a senha atual. ':'' ?>Use uma frase longa, com pelo menos 12 caracteres.</p></div>
<p class="help-block">Acesso administrativo completo ao site. <?= $isProfile?'Ao salvar seu perfil, será necessário entrar novamente.':'' ?></p>
<?php else: ?>
<?php if($kind==='noticias'): ?><div class="form-group"><label for="record-summary">Resumo</label><textarea class="form-control" id="record-summary" name="subtitulo" maxlength="250" rows="3"></textarea></div><?php endif ?>
<div class="form-group"><label for="record-link"><?= $kind==='banner'?'Link de destino':'Endereço da notícia' ?></label><input class="form-control" id="record-link" name="link" <?= $kind==='banner'?'placeholder="https:// ou /pagina"':'placeholder="Gerado a partir do título"' ?>><p class="help-block"><?= $kind==='banner'?'Informe o endereço para abrir ao selecionar o banner.':'Use palavras separadas por hífen; deixe vazio para gerar automaticamente.' ?></p></div>
<?php if($kind==='noticias'): ?><div class="form-group"><label for="record-tags">Categorias</label><select id="record-tags" name="id_tag[]" multiple></select><p class="help-block">Selecione categorias ou digite uma nova.</p></div><?php endif ?>
<?php endif ?>
</section><aside class="record-publication"><h2 class="h5"><?= $kind==='usuario'?'Perfil e acesso':'Imagem e publicação' ?></h2>
<img class="record-cover" data-cover alt="Prévia da imagem selecionada" hidden>
<div class="form-group"><label for="record-image"><?= $kind==='usuario'?'Foto de perfil':'Imagem principal' ?><?= $mode==='create'&&$kind!=='usuario'?' *':'' ?></label><input class="form-control" type="file" id="record-image" name="<?= $mode==='update'&&$kind!=='usuario'?'newimagem':'img' ?>" accept="image/jpeg,image/png" <?= $mode==='create'&&$kind!=='usuario'?'required':'' ?>><p class="help-block">JPG ou PNG, até 10 MB. <?= $mode==='update'?'Selecione apenas se desejar substituir a imagem.':'' ?></p></div>
<label class="publication-switch"><input class="form-check-input" type="checkbox" name="status" value="1" <?= $kind==='usuario'?'checked':'' ?> <?= $isProfile?'disabled':'' ?>> <?= $kind==='usuario'?'Conta ativa':'Publicado no site' ?></label><p class="help-block"><?= $kind==='usuario'?'Contas inativas não conseguem entrar.':'Desmarcado: o conteúdo fica salvo como rascunho.' ?></p>
</aside>
<?php if($kind==='noticias'): ?><section class="record-body"><label for="record-description" class="form-label fw-semibold">Conteúdo da notícia</label><textarea id="record-description" name="descricao" rows="14"></textarea></section><?php endif ?>
</div><div class="box-footer admin-toolbar"><span class="text-muted">Revise as informações antes de salvar.</span><a class="btn btn-default" href="painel.php?exe=<?= $kind ?>/index">Cancelar</a><button class="btn btn-primary" type="submit">Salvar <?= $single ?></button></div></form>
<?php endif ?></div>
<?php $js='system/records.js'; ?>
