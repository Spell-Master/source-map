<?php

echo ("<script>sml.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require (__DIR__ . '/../../system/config.php');
sleep((int) $config->length->colldown);
$post = GlobalFilter::filterPost();

if ($post) {
    switch ($post->form_id) {
        case 'search-page':
            $include = 'doc/search.php';
            break;
    }
} else {
    $include = '../error/500.php';
}

include (__DIR__ . '/' . $include);
