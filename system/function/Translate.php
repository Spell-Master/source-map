<?php

/*
 *  Dar nome ao cargo conforme o nv do usuário
 * @param {INT} $nv
 * Nível da coluna u_level no banco de dados
 */
function LevelToName($nv) {
    $name = [
        /* 0 => '<span class="text-black italic">Membro</span>', */
        1 => '<span class="text-orange italic bold">Moderador</span>',
        2 => '<span class="text-green italic bold">Desenvolvedor</span>',
        3 => '<span class="text-indigo italic bold">Coordernador</span>',
        4 => '<span class="text-red italic bold">Administrador</span>'
    ];
    return (array_key_exists($nv, $name) ? $name[$nv] : '<span class="text-black italic bold">Membro</span>');
}

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
    if ($comb < 5) {
        return (number_format($fileSize / pow(1024, $comb), 2, '.', ',') . ' ' . $path[$comb]);
    } else {
        return ('Maior que 1 Peta-byte');
    }
}
