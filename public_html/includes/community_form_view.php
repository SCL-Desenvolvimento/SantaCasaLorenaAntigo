<?php // Caller provides $mode and $state.
$errorLabels = array('nome'=>'Nome completo','email'=>'E-mail','cidade'=>'Cidade','assunto'=>'Assunto','razao'=>'Motivo do contato','mensagem'=>'Mensagem','curriculum-tc'=>'Currículo');
foreach (require __DIR__.'/contact_questions.php' as [$key, $question, $options]) $errorLabels[$key] = $question;
?>
<?php if ($state['success']): ?><div class="community-notice success" role="status"><?= scl_escape($state['message']) ?></div><?php endif; ?>
<?php if ($state['errors']): ?><div class="community-notice error" role="alert" tabindex="-1" data-form-errors><strong>Revise as informações para continuar.</strong><ul><?php foreach ($state['errors'] as $field=>$error): ?><li><?php if (array_key_exists($field, scl_form_fields($mode)) || $field === 'curriculum-tc'): ?><a href="#field-<?= scl_escape($field) ?>"><?= scl_escape(($errorLabels[$field] ?? $field).': '.$error) ?></a><?php else: ?><?= scl_escape($error) ?><?php endif; ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="community-form" data-community-form>
<input type="hidden" name="form" value="<?= scl_escape($mode) ?>"><input type="hidden" name="community_token" value="<?= scl_escape($_SESSION['community_token']) ?>">
<p class="form-hint">Os campos com * são obrigatórios.</p><div class="community-fields">
<?php if ($mode === 'pesquisa'): ?>
<?php foreach (require __DIR__.'/contact_questions.php' as [$field, $question, $options]): $error = $state['errors'][$field] ?? ''; ?>
<fieldset class="survey-question field-wide" id="field-<?= scl_escape($field) ?>"<?= $error ? ' aria-describedby="error-'.scl_escape($field).'"' : '' ?>><legend><?= scl_escape($question) ?> *</legend><div class="survey-options">
<?php foreach ($options as $option): ?><label><input type="radio" name="<?= scl_escape($field) ?>" value="<?= scl_escape($option) ?>" required<?= ($state['values'][$field] ?? '') === $option ? ' checked' : '' ?>> <span><?= scl_escape($option) ?></span></label><?php endforeach; ?>
</div><?php if ($error): ?><span class="field-error" id="error-<?= scl_escape($field) ?>"><?= scl_escape($error) ?></span><?php endif; ?></fieldset>
<?php endforeach; ?>
<?php else: scl_form_field('nome', 'Nome completo', $state); scl_form_field('email', 'E-mail', $state, 'email', 254); scl_form_field('cidade', 'Cidade', $state); ?>
<?php if ($mode !== 'trabalhe_conosco'): scl_form_field('assunto', 'Assunto', $state, 'text', 200); endif; ?>
<?php if ($mode === 'contato'): scl_form_field('razao', 'Motivo do contato', $state, 'text', 200); endif; ?>
<?php if ($mode === 'trabalhe_conosco'): scl_form_field('curriculum-tc', 'Currículo', $state, 'file'); endif; ?>
<?php endif; ?>
<?php if ($mode !== 'trabalhe_conosco'): scl_form_field('mensagem', $mode === 'pesquisa' ? 'Observações' : 'Mensagem', $state, 'textarea', 5000); endif; ?>
</div><div class="form-security"><?php if (defined('SCL_PREVIEW')): ?><p>Prévia local: o envio e a verificação de segurança estão desativados.</p><?php else: ?><div class="g-recaptcha" data-sitekey="6LfM4TkUAAAAABw-GkATRquqIuSgp_KSKYN_XWjO"></div><noscript><p>Ative o JavaScript para realizar a verificação de segurança, ou utilize os contatos da instituição.</p></noscript><?php endif; ?></div>
<button class="scl-button" type="submit"<?= defined('SCL_PREVIEW') ? ' disabled' : '' ?>><?= $mode === 'trabalhe_conosco' ? 'Enviar currículo' : ($mode === 'pesquisa' ? 'Enviar avaliação' : 'Enviar mensagem') ?> <?= scl_icon('arrow') ?></button><p class="form-hint">Confira seus dados antes de enviar.</p></form>
