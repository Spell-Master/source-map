<?php
/**
 * ********************************************
 * * @class StrClean
 * * @copyright (c) Spell master
 * * @version 4.0
 * * @see Classe para limpeza de string
 * ********************************************
 */

class StrClean {

    /**
     * ****************************************
     * Formata uma string que contenha
     * caracteres ilegais.
     * ****************************************
     * @param {STR} $string
     * Entrada para tratamento.
     * @return $string formatada
     * 
     * @example :
     * ENTRADA -> João e maria. @<src>Oi</src>
     * SAÍDA   -> Joao-e-maria-src-Oi-src- 
     * ****************************************
     */
    public function formatStr($string) {
        $match = [];
        $match['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_+={[}]/?;:.,\\\'<>°ºª`';
        $match['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
        $decode = strtr(utf8_decode($string), utf8_decode($match['a']), $match['b']);
        $stroke = preg_replace('/[ -]+/', '-', $decode);
        $leftStroke = ltrim($stroke, '-');
        $rightStroke = rtrim($leftStroke, '-');
        return (utf8_encode($rightStroke));
    }

    /**
     * ****************************************
     * Converte datas com horas para o formato
     * latino americano.
     * ****************************************
     * @param {STR} $dateTime
     * Entrada para tratamento.
     * @return {STR} $dateTime convertida
     * 
     * @example
     * ENTRADA -> 2010-12-01 23:59:59
     * SAÍDA   -> 01/12/2010 23:59:59
     * 
     * @todo datetime
     * ****************************************
     */
    public function dateTime($dateTime) {
        $timestamp = explode(' ', $dateTime);
        $getDate = implode('/', array_reverse(explode('-', $timestamp[0])));
        return ($getDate . (isset($timestamp[1]) && preg_match('/:/', $timestamp[1] ) ? ' (' . $timestamp[1]  . ')' : null) );
    }

    /**
     * ****************************************
     * Criptograga dados para binário em
     * base 64.
     * ****************************************
     * @example :
     * ENTRADA -> Olá mundo
     * SAÍDA   -> T2zDoSBtdW5kbw==
     * ****************************************
     */
    public function baseEncode($base64) {
        return (base64_encode($base64));
    }

    /**
     * ****************************************
     * Remove criptografia de dados binário
     * com base 64.
     * ****************************************
     * @example :
     * ENTRADA -> T2zDoSBtdW5kbw==
     * SAÍDA   -> Olá mundo
     * ****************************************
     */
    public function baseDecode($base64) {
        return (base64_decode($base64));
    }

    /**
     * ****************************************
     * Limpa espaços duplicados em uma string
     * Remove também espaços antes e depois
     * da string.
     * ****************************************
     * @param {STR} $text
     * Entrada para limpeza.
     * ****************************************
     */
    public function clearSpaces($text) {
        $string = trim(preg_replace('/ {2,}/', ' ', $text));
        return ($string);
    }

}
