<?php
/**
 * **************************************************
 * Carregar arquivos de páginas pelo parâmetro
 *  da URL.
 * **************************************************
 * 
 * @param STR $module
 * Informar o valor de $_GET['url'][0]
 * 
 * @copyright (c) 2021, Spell Master (Omar Pautz)
 * **************************************************
 */

function LoadModule($module) {
    $fileName = '';
    switch ($module) {
        case 'inicio': $fileName = 'home.php'; break;
        case 'cadastro': $fileName = 'user/new.php'; break;
        case 'recuperar-senha': $fileName = 'user/recover.php'; break;
        case 'entrar': case 'login': $fileName = 'user/login.php'; break;
    }
    if (file_exists('modules/' . $fileName)) {
        return ('modules/' . $fileName);
    } else {
        return ('error/404.php');
    }
}
