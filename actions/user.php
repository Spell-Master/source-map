<?php
echo ("<script>smlib.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../system/config.php');
sleep((int) $config->length->colldown);
$post = GlobalFilter::filterPost();

if ($post) {
    switch ($post->form_id) {
        case 'new-user':
            $include = 'user/new.php';
            break;
        default:
            $include = '../error/500.php';
            break;
    }
} else {
     $include = '../error/500.php';
}

include (__DIR__ . '/' . $include);
