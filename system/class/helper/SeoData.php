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

}
