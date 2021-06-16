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
    if ($module == 'teste') { /* Testes de produção */
        return ('modules/teste.php');
    }
    //
    else if ($module == 'inicio') {
        return ('modules/default/home.php');
    } else {
        switch ($module) {
            case 'admin': $folder = 'admin'; break;
            case 'perfil': $folder = 'user'; break;
            case 'css-padrao': case 'js-padrao': $folder = 'app'; break;
            case 'doc': $folder = 'doc'; break;
            case 'termos': $folder = 'info'; break;
            default: $folder = false; break;
        }
        if ($folder) {
            return ('modules/' . $folder . '/default.php');
        } else {
            return ('modules/error/404.php');
        }
    }
}
