<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require (__DIR__ . '/../../system/config.php');
sleep((int) $config->length->colldown);
$post = GlobalFilter::filterPost();

if ($post) {
    switch ($post->form_id) {
        case 'new-app':
            $include = 'app-new.php';
            break;
        case 'edit-app':
            $include = 'app-edit.php';
            break;
        case 'search-app':
            $include = 'app-search.php';
            break;
    }
} else {
    $include = '../error/500.php';
}

include (__DIR__ . '/admin/' . $include);