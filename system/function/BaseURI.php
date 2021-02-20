<?php

/**
 * ****************************************************
 * @copyright : 2017, Spell Master(c)
 * @version : 1.0
 * ****************************************************
 * @info : Obtem o diretório base do sistema
 * ****************************************************
 */
function BaseURI() {
    $serve = filter_input_array(INPUT_SERVER, FILTER_DEFAULT);
    $rootUrl = strlen($serve['DOCUMENT_ROOT']);
    $fileUrl = substr($serve['SCRIPT_FILENAME'], $rootUrl, -9);
    if ($fileUrl[0] == '/') {
        $baseDir = $fileUrl;
    } else {
        $baseDir = '/' . $fileUrl;
    }
    return ($baseDir);
}
