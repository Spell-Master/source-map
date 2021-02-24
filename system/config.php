<?php
/**
 * ****************************************************
 * @author Spell Master (Omar Pautz)
 * ****************************************************
 * @Nota: Arquivo e definições avançadas.
 * * Requisita, define configurações mais complexas.
 * ****************************************************
 */

/* Fixar o buffer do cabeçalho */
ob_start();

$phpConf = require_once (__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'settings.php');
$xmlConf = simplexml_load_file(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'settings.xml');

try {
    if (!isset($phpConf['isReady'])) { // Previne a exposição da configuração em caso de erro no arquivo
        throw new Exception(null, E_ERROR);
    } else {
        /* Fuso horário do servidor */
        date_default_timezone_set('America/Sao_Paulo');

        /* Auto carregamento de classes */
        require_once (__DIR__ . DIRECTORY_SEPARATOR . 'function' . DIRECTORY_SEPARATOR . 'LoadClass.php');

        /* Mesclar dados de configuração PHP com o XML */
        foreach ($phpConf as $key => $value) {
            $xmlConf->addChild($key, $value);
        }

        /* Define a variável geral para configurações */
        $config = $xmlConf;

        /* Constants para conexão do banco */
        defined('HOST') || define('HOST', $config->dbHost);
        defined('USER') || define('USER', $config->dbUser);
        defined('PASS') || define('PASS', $config->dbPass);
        defined('DATA') || define('DATA', $config->dbName);

        /* Constants para conexão SMTP */
        defined('MAILTYPE') || define('MAILTYPE', $config->mailType);
        defined('MAILHOST') || define('MAILHOST', $config->mailHost);
        defined('MAILPORT') || define('MAILPORT', $config->mailPort);
        defined('MAILUSER') || define('MAILUSER', $config->mailUser);
        defined('MAILPASS') || define('MAILPASS', $config->mailPass);

        /* Nome global para o website */
        defined('NAME') || define('NAME', $config->siteName);

        /* Define e inicia a única cessão válida dentro do website */
        $session = Session::startSession(NAME);
    }
} catch (Exception $e) {
    header('location : http500/');
}

/* Erros de execusão EM PRODUÇÃO */
error_reporting(E_ALL);
ini_set('display_errors', true);
