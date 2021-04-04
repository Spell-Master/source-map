<?php

$login = (isset($session->user) ? $session->user : false);
if ($url[0] == 'cadastro' || $url[0] == 'recuperar-senha' || $url[0] == 'entrar' || $url[0] == 'confirmar') {
    if ($login) {
        header('LOCATION: ' . $baseUri);
    }
} else if ($url[0] == 'inicio') {
    include (__DIR__ . '/../user/includes/header-home.inc.php');
} else {
    include (__DIR__ . '/../user/includes/header.inc.php');
}
