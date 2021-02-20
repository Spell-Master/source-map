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

}
