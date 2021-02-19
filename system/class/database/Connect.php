<?php
/**
 * ********************************************
 * @copyright: 2014, Spell Master(c)
 * @version: 6.5 - 2017, Spell Master(c)
 * ********************************************
 * @Class: Classe de conexão PDO SingleTon.
 * ********************************************
 */

class Connect {

    private static $host = HOST;
    private static $user = USER;
    private static $pass = PASS;
    private static $data = DATA;
    private static $isConnect = null;
    private static $isError = null;

    /**
     * ****************************************
     * Constroi a conexão
     * ****************************************
     * @access private
     * ****************************************
     */
    private static function makeConnect() {
        try {
            if (self::$isConnect == null) {
                $dsn = 'mysql:host=' . self::$host . '; dbname=' . self::$data;
                $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
                self::$isConnect = new PDO($dsn, self::$user, self::$pass, $options);
            }
        } catch (PDOException $e) {
            self::$isError = '<br>Não foi possível conectar com o banco de dados!<br> Descrição:' . $e->getMessage() . '<br>';
            //die('Erro interno no servidor. Código de referência 500');
            die($e->getMessage());
        }
        self::$isConnect->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return (self::$isConnect);
    }

    /**
     * ****************************************
     * Chama o construtor da conexão
     * ****************************************
     * @access public
     * ****************************************
     */
    protected static function callConnect() {
        return (self::makeConnect());
    }

    /**
     * ****************************************
     * Informa erros de conexão quando são
     * disparados.
     * ****************************************
     * @access public
     * ****************************************
     */
    protected static function getError() {
        if (self::$isError) {
            return (self::$isError);
        }
    }
}
