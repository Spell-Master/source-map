<?php

/**
 * Description of SeoData
 *
 * @author Spell Master
 */
class SeoData {

    /**
     * ****************************************
     * Obtem os índices das entradas na url.
     * ****************************************
     * @return {ARRAY} Índices URI
     * ****************************************
     */
    public static function parseUrl() {
        $filter = filter_input(INPUT_GET, 'url', FILTER_DEFAULT);
        $setUrl = empty($filter) ? 'inicio' : $filter;
        $explode = explode('/', $setUrl);
        $arr = array_filter($explode);
        return ($arr);
    }

    /**
     * ****************************************
     * Exibe a animação de progresso padrão
     * da AjaxRequest.
     * ****************************************
     */
    public static function showProgress() {
        $svg = "<div class=\"load-local\">";
        $svg .= "<svg class=\"load-pre\" viewBox=\"25 25 50 50\">";
        $svg .= "<circle class=\"load-path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke=\"#555\" stroke-width=\"4\" stroke-miterlimit=\"10\"/></svg>";
        $svg .= "</div>";
        echo($svg);
    }

    /**
     * ****************************************
     * Obtem links dinâmicos de cada entrada
     * da url.
     * ****************************************
     */
    public static function breadCrumbs($newCrumb = null) {
        $crumb = ($newCrumb ? $newCrumb : self::parseUrl());
        $count = count($crumb);
        $loop = 0;
        $strA = "";
        $strB = "<div id=\"crumbs\" class=\"padding-all-min bg-light over-text\"><div class=\"container\">";
        $strB .= "<a href=\"./\" title=\"Página Inicial\" class=\"href-link margin-left\" style=\"font-weight:0\"><span class=\"icon-home5\"></span></a> / ";
        foreach ($crumb as $value) {
            $loop++;
            if ($loop != $count) {
                $strB .= '<a href="' . $strA . $value . '" class="href-link">' . self::longText(strtolower($value), 10) . '</a> / ';
            } else {
                $strB .= self::longText(strtolower($value), 18);
            }
            $strA .= "{$value}/";
        }
        $strB .= "</div></div>";
        echo ($strB);
    }

    /**
     * ****************************************
     * Reduz quantidade de caracteres em uma
     * string.
     * ****************************************
     * @param {STR} $string
     * Informar string para reduzir
     * @param {INT} $length
     * Informar quantidade de caracteres que
     * serão retornados.
     * @return {STR} retorna o parâmetro 
     * $string com a quantidade de caracteres
     * de $leng.
     * ****************************************
     */
    public static function longText($string, $length) {
        $str = (string) html_entity_decode($string);
        $numb = (int) $length;
        $Keys = ($numb + 3);
        return (mb_strimwidth($str, 0, $Keys, '...'));
    }

}
