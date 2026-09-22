<?php
require_once __DIR__ . '/about_helpers.php';

function scl_form_fields($mode) {
    if ($mode === 'pesquisa') {
        $fields = array_fill_keys(array_column(require __DIR__.'/contact_questions.php', 0), 100);
        $fields['mensagem'] = 5000;
        return $fields;
    }
    $fields = array('nome'=>150, 'email'=>254, 'cidade'=>150);
    if ($mode !== 'trabalhe_conosco') $fields += array('assunto'=>200, 'mensagem'=>5000);
    if ($mode === 'contato') $fields['razao'] = 200;
    return $fields;
}

function scl_validate_form($mode, $post) {
    $data = $errors = array();
    if (!in_array($mode, array('contato','trabalhe_conosco','pesquisa','deposito','boleto'), true)) return array(array(), array('form'=>'Escolha um canal válido.'));
    foreach (scl_form_fields($mode) as $field=>$limit) {
        $value = isset($post[$field]) && is_string($post[$field]) ? trim($post[$field]) : '';
        $data[$field] = $value;
        if ($value === '' && !($mode === 'pesquisa' && $field === 'mensagem')) $errors[$field] = 'Preencha este campo.';
        elseif (!preg_match('//u', $value) || strlen($value) > $limit * 4 || preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', $value)) $errors[$field] = 'Revise o conteúdo deste campo.';
        elseif (preg_match_all('/./us', $value) > $limit) $errors[$field] = 'Use até '.$limit.' caracteres.';
    }
    if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Informe um e-mail válido.';
    if ($mode === 'pesquisa') foreach (require __DIR__.'/contact_questions.php' as [$field, $label, $options]) {
        if (!in_array($data[$field], $options, true)) $errors[$field] = 'Selecione uma das opções.';
    }
    return array($data, $errors);
}

function scl_process_form($allowed, $secret, $verifyCaptcha = null, $mailerFactory = null) {
    if (empty($_SESSION['community_token'])) $_SESSION['community_token'] = bin2hex(random_bytes(32));
    $result = array('values'=>array(), 'errors'=>array(), 'message'=>'', 'success'=>false, 'mode'=>'');
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') return $result;
    if (!isset($_POST['form'])) {
        if (empty($_POST) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) $result['errors']['form']='O envio excedeu o limite do servidor. Envie um currículo em PDF de até 5 MB.';
        return $result;
    }
    $mode = is_string($_POST['form']) ? $_POST['form'] : '';
    if (!in_array($mode, $allowed, true)) { $result['errors']['form']='Escolha um canal válido.'; return $result; }
    $result['mode'] = $mode;
    [$result['values'], $result['errors']] = scl_validate_form($mode, $_POST);
    if (defined('SCL_PREVIEW')) { $result['errors']['form']='Esta prévia não realiza envios.'; return $result; }
    $token = $_POST['community_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['community_token'], $token)) $result['errors']['form']='Atualize a página e tente novamente.';
    if (time() - ($_SESSION['community_last_sent'] ?? 0) < 60) $result['errors']['form']='Aguarde um minuto antes de realizar outro envio.';
    $file = $_FILES['curriculum-tc'] ?? array();
    if ($mode === 'trabalhe_conosco' && !scl_valid_resume($file)) $result['errors']['curriculum-tc']='Anexe um currículo em PDF de até 5 MB.';
    if ($result['errors']) return $result;
    $captcha = $_POST['g-recaptcha-response'] ?? '';
    if (!is_string($captcha) || $captcha === '' || !$secret) { $result['errors']['captcha']='Conclua a verificação de segurança.'; return $result; }
    $verifyCaptcha = $verifyCaptcha ?? 'scl_captcha_valid';
    if (!$verifyCaptcha($captcha, $secret)) { $result['errors']['captcha']='Não foi possível validar a verificação de segurança. Tente novamente.'; return $result; }
    $data = $result['values'];
    $data['data_cadastro'] = date('Y-m-d H:i:s');
    $tables = array('contato'=>'ouvidoria','trabalhe_conosco'=>'trabalhe_conosco','pesquisa'=>'pesquisa_atendimento','deposito'=>'doacoes','boleto'=>'doacoes');
    if (in_array($mode, array('deposito','boleto'), true)) $data['tipo'] = $mode;
    $attachment = '';
    try {
        if ($mode === 'trabalhe_conosco') {
            $directory = DIR.'arquivos/curriculuns';
            if (!is_dir($directory) && !mkdir($directory, 0750, true)) throw new RuntimeException('Upload directory unavailable');
            $data['curriculum'] = 'arquivos/curriculuns/'.bin2hex(random_bytes(24)).'.pdf';
            $attachment = DIR.$data['curriculum'];
            if (!move_uploaded_file($file['tmp_name'], $attachment)) throw new RuntimeException('Upload failed');
        }
        $create = new Create();
        $create->ExeCreate(PREFIX.$tables[$mode], $data);
        if (!$create->getResult()) {
            if ($attachment && is_file($attachment)) unlink($attachment);
            throw new RuntimeException('Insert failed');
        }
    } catch (Throwable $error) {
        if ($attachment && is_file($attachment)) unlink($attachment);
        $result['errors']['form']='Não foi possível registrar seu envio. Tente novamente mais tarde.';
        return $result;
    }
    // A persisted submission is successful even when its email notification fails.
    $_SESSION['community_last_sent'] = time();
    $_SESSION['community_token'] = bin2hex(random_bytes(32));
    $result['success'] = true;
    $result['message'] = 'Seu envio foi registrado. Obrigado pelo contato!';
    $result['values'] = array();
    try {
        require_once __DIR__.'/community_mailer.php';
        $mailerFactory = $mailerFactory ?? 'scl_community_mailer';
        $mail = $mailerFactory();
        $mail->CharSet = 'UTF-8';
        $mail->SetFrom('webmaster@santacasalorena.org.br', 'Santa Casa de Lorena');
        $recipient = $mode === 'trabalhe_conosco' ? 'rh.sl@santacasalorena.org.br' : 'secretaria@santacasalorena.org.br';
        $mail->AddAddress($recipient);
        if (isset($data['email'])) $mail->AddReplyTo($data['email'], $data['nome']);
        $titles = array('contato'=>'Ouvidoria','trabalhe_conosco'=>'Trabalhe conosco','pesquisa'=>'Pesquisa de atendimento','deposito'=>'Depósito Bancário','boleto'=>'Boleto Bancário');
        $mail->Subject = $titles[$mode].' - Novo contato através do site';
        $body = '<h1>'.scl_escape($titles[$mode]).'</h1>';
        foreach ($data as $key=>$value) if ($key !== 'curriculum') $body .= '<p><strong>'.scl_escape(str_replace('_',' ',$key)).'</strong><br>'.nl2br(scl_escape($value)).'</p>';
        $mail->MsgHTML($body);
        if ($attachment) $mail->AddAttachment($attachment, 'curriculo.pdf');
        if (!$mail->Send()) error_log('Santa Casa: falha na notificação de formulário '.$mode);
    } catch (Throwable $error) { error_log('Santa Casa: falha na notificação de formulário '.$mode); }
    return $result;
}

function scl_captcha_valid($captcha, $secret) {
    $context = stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-Type: application/x-www-form-urlencoded\r\n",'content'=>http_build_query(array('secret'=>$secret,'response'=>$captcha)),'timeout'=>10)));
    $response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $response = json_decode($response ?: '{}', true);
    return !empty($response['success']);
}

function scl_valid_resume($file) {
    if (!class_exists('finfo')) return false;
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_string($file['tmp_name'] ?? null) || !is_uploaded_file($file['tmp_name'])) return false;
    return ($file['size'] ?? 0) > 0 && $file['size'] <= 5 * 1024 * 1024
        && strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION)) === 'pdf'
        && (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']) === 'application/pdf';
}

function scl_form_field($name, $label, $state, $type = 'text', $limit = 150) {
    $value = $state['values'][$name] ?? '';
    $error = $state['errors'][$name] ?? '';
    $required = $name === 'mensagem' && $state['mode'] === 'pesquisa' ? '' : ' required';
    $attributes = ' id="field-'.scl_escape($name).'" name="'.scl_escape($name).'"'.$required.' maxlength="'.$limit.'"'.($error ? ' aria-invalid="true" aria-describedby="error-'.scl_escape($name).'"' : '');
    echo '<div class="community-field'.($type === 'textarea' ? ' field-wide' : '').'"><label for="field-'.scl_escape($name).'">'.scl_escape($label).($required ? ' <span aria-hidden="true">*</span>' : ' (opcional)').'</label>';
    if ($type === 'textarea') echo '<textarea'.$attributes.' rows="6">'.scl_escape($value).'</textarea>';
    elseif ($type === 'file') echo '<input type="file"'.$attributes.' accept=".pdf,application/pdf"><small>PDF de até 5 MB. Após um erro de envio, selecione o arquivo novamente.</small>';
    else echo '<input type="'.scl_escape($type).'"'.$attributes.' value="'.scl_escape($value).'"'.(in_array($name,array('nome','email','cidade')) ? ' autocomplete="'.array('nome'=>'name','email'=>'email','cidade'=>'address-level2')[$name].'"' : '').'>';
    if ($error) echo '<span class="field-error" id="error-'.scl_escape($name).'">'.scl_escape($error).'</span>';
    echo '</div>';
}
