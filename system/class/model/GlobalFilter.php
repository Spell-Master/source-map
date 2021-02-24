<?php
/**
 * ********************************************
 * * @Class GlobalFilter
 * * @author Spell-Master (Omar Pautz)
 * * @version 2.0 (2019)
 * ********************************************
 * * Aplica um filtro padrão sobre
 * super-globais e retorna os mesmos como
 * objetos.
 * ********************************************
 */

class GlobalFilter {

    /**
     * ****************************************
     * Converte array singular em objeto.
     * ****************************************
     * @Param {ARR} $array
     * array para converter.
     * ****************************************
     */
    public static function StdArray($array) {
        $object = new stdClass();
        if (is_array($array)) {
            foreach ($array as $name => $value) {
                $object->$name = $value;
            }
        }
        return $object;
    }

    /**
     * ****************************************
     * Converte array multi-dimensional em
     *  objeto.
     * ****************************************
     * @Param {ARR} $array
     * array para converter.
     * ****************************************
     */
    public static function ObjArray($array) {
        if (is_array($array)) {
            return (object) array_map(__FUNCTION__, $array);
        } else {
            return $array;
        }
    }

    /**
     * ****************************************
     * Aplica um filtro padrão sobre entrada
     *  INPUT_GET.
     * ****************************************
     */
    public static function filterGet() {
        $filterGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        $filter = isset($filterGet) ? self::StdArray($filterGet) : false;
        return ($filter);
    }

    /**
     * ****************************************
     * Aplica um filtro padrão sobre entrada
     *  INPUT_POST.
     * ****************************************
     */
    public static function filterPost() {
        $filterPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $filter = isset($filterPost) ? self::StdArray($filterPost) : false;
        return ($filter);
    }

    /**
     * ****************************************
     * Aplica um filtro padrão sobre entrada
     *  INPUT_SESSION.
     * ****************************************
     */
    public static function filterSession() {
        $filterSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);
        $filter = isset($filterSession) ? self::StdArray($filterSession) : false;
        return ($filter);
    }

    /**
     * ****************************************
     * Aplica um filtro padrão sobre entrada
     *  INPUT_COOKIE.
     * ****************************************
     */
    public static function filterCookie() {
        $filterCookie = filter_input_array(INPUT_COOKIE, FILTER_DEFAULT);
        $filter = isset($filterCookie) ? self::StdArray($filterCookie) : false;
        return ($filter);
    }

    /**
     * ****************************************
     * Aplica um filtro padrão sobre entrada
     *  INPUT_SERVER.
     * ****************************************
     */
    public static function filterServe() {
        $filterServe = filter_input_array(INPUT_SERVER, FILTER_DEFAULT);
        $filter = isset($filterServe) ? self::StdArray($filterServe) : false;
        return ($filter);
    }

    /**
     * ****************************************
     * Aplica um filtro padrão sobre entrada
     *  INPUT_ENV.
     * ****************************************
     */
    public static function filterEven() {
        $filterEven = filter_input_array(INPUT_ENV, FILTER_DEFAULT);
        $filter = isset($filterEven) ? self::StdArray($filterEven) : false;
        return ($filter);
    }

    /**
     * ****************************************
     * Aplica um filtro padrão sobre entrada
     *  INPUT_REQUEST.
     * ****************************************
     */
    public static function filterRequest() {
        $filterRequest = filter_input_array(INPUT_REQUEST, FILTER_DEFAULT);
        $filter = isset($filterRequest) ? self::StdArray($filterRequest) : false;
        return ($filter);
    }

}
