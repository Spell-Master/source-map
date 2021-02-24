<?php
/**
 * *********************************************************************
 * @function: Função para auto carregamento de classes
 * *********************************************************************
 * @autor: Spell Master
 * @copyright (c) 2014, Spell Master AND Zeed
 * @vesion: 6.0 2021, Spell Master
 * *********************************************************************
 */

spl_autoload_register(function ($Class) {
    $findDir = [
        'database',
        'helper',
        'mailer',
        'model'
    ];
    $includeDir = null;
    foreach ($findDir as $DirName) {
        if (!$includeDir && file_exists(__DIR__ . FindClass($DirName, $Class))
                && !is_dir(__DIR__ . FindClass($DirName, $Class))) {
            include_once (__DIR__ . FindClass($DirName, $Class));

            $includeDir = true;
        }
    }
    if (!$includeDir) {
        die("Erro interno no servidor ao encontrar dados cruciais de funcionamento!");
    }
});

function FindClass($dir, $class) {
    return (
        DIRECTORY_SEPARATOR
        . '..'
        . DIRECTORY_SEPARATOR . 'class'
        . DIRECTORY_SEPARATOR . $dir
        . DIRECTORY_SEPARATOR . $class . '.php'
    );
}
