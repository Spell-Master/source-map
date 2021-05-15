<?php
/**
 * Função para converter numero em formato de tamanho
 * @param {INT} $bytes
 * Informar um numero de análize
 * @example sizeName(999)
 * @copyright (c) 2021, Spell Master
 */
function sizeName($bytes) {
    $float = floatval($bytes);
    $return = false;
    $size = [
        ['TB\'s', pow(1024, 4)],
        ['GB\'s', pow(1024, 3)],
        ['MB\'s', pow(1024, 2)],
        ['KB\'s', 1024],
        ['KB\'s', 1024],
        ['Bit\'s', 1]
    ];
    foreach ($size as $value) {
        if ($float >= $value[1]) {
            $return = str_replace('.', ',', strval(round($float / $value[1], 2))) . ' ' . $value[0];
            break;
        }
    }
    return ($return ? $return : 'Indefinido');
}


