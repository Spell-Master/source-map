<?php
echo ("<script>smlib.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require (__DIR__ . '/../../system/config.php');
sleep((int) $config->length->colldown);
$post = GlobalFilter::filterPost();

if ($post) {
    switch ($post->form_id) {
        case 'new-user':
            $include = 'user/new.php';
            break;
        case 're-mail':
            $include = 'user/re-mail.php';
            break;
        case 'confirm-new':
            $include = 'user/new-confirm.php';
            break;
        case 'recover-pass':
            $include = 'user/recover-pass.php';
            break;
        case 'user-login':
            $include = 'user/login.php';
            break;
        default:
            $include = 'error/500.php';
            break;
    }
} else {
     $include = 'error/500.php';
}

include (__DIR__ . '/' . $include);
