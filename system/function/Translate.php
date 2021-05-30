<?php

/**
 * Função para converter numero em formato de tamanho
 * @param {INT} $fileSize
 * Informar um numero de análize
 * @example sizeName(999)
 * @copyright (c) 2021, Spell Master
 */
function sizeName($fileSize) {
    $path = ['Bit\'s', 'KB\'s', 'MB\'s', 'GB\'s', 'TB\'s'];
    $comb = ($fileSize > 0 ? floor(log($fileSize, 1024)) : 0);
    return (number_format($fileSize / pow(1024, $comb), 2, '.', ',') . ' ' . $path[$comb]);
}
