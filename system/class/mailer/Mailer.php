<?php
/**
 * **************************************************
 * @Copyright (c) 2016, Spell Master.
 * @Version 4.0 (2021)
 * @Class Envio de e-mail
 * **************************************************
 * @requires
 * * PHPMailer 5.2.17
 * * Contantes de definição :
 * @const MAILTYPE (Tipo de acesso tls/ssl ao SMTP)
 * @const MAILHOST (Endereço do SMTP)
 * @const MAILPORT (Porta de Acesso)
 * @const MAILUSER (Endereço do e-mail que envia)
 * @const MAILPASS (Senha do e-mail que envia)
 * @const NAME (Nome de quem envia)
 * **************************************************
 * 
 * @tutorial localhost
 * - Abra o arquivo httpd.conf do apache
 * Habilite o ssl_module
 * - Abra o arquivo php.ini
 * Ative as extensões:
 * php_curl (Se existir)
 * php_openssl (Se existir)
 * php_sockets (Se existir)
 * php_smtp  (Se existir)
 * - NOTA -
 * Caso o SMTP do e-mail que envia tenha segurança
 *  de criptografia de dados como é o caso do GMAIL:
 * Acessar: https://myaccount.google.com/security
 * Procure por:
 * "Acesso a aplicativos menos seguros" e libere a
 *  autorização.
 * - NOTA 2 -
 * Em localhost ou um servidor local qualquer cujo
 *  seu SO seja windows e possua o software Avast,
 *  esse mesmo irá bloquear o envio, nesse caso
 *  desabilite o bloqueio da porta no firewall.
 * **************************************************
 */

class Mailer {

    private $mailer;
    private $address;
    private $template;
    private $title;
    private $content;
    private $find;
    private $replaces;
    private $sendError;
    private $sendAcept;

    /**
     * *********************************************
     * Construtor, inicia e define o formato para
     *  a class PHPMailer.
     * *********************************************
     */
    function __construct() {
        $this->mailer = new PHPMailer;
        $this->mailer->IsSMTP();
        $this->mailer->IsHTML(true);
        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPSecure = MAILTYPE;
        $this->mailer->Host = MAILHOST;
        $this->mailer->Port = (int) MAILPORT;
        $this->mailer->Username = MAILUSER;
        $this->mailer->Password = MAILPASS;
        $this->mailer->FromName = utf8_decode(NAME);
        $this->mailer->setLanguage('pt_br', __DIR__ . DIRECTORY_SEPARATOR .'language' . DIRECTORY_SEPARATOR);
        
        $this->mailer->AddEmbeddedImage(__DIR__ . '/../../../lib/image/logo-mail.png', 'smlogo');
    }

    /**
     * *********************************************
     * Recebe os dados
     * 
     * @Param {STR} $Address
     * Para quem o e-mail vai ser enviado.
     * @Param {STR} $title
     * Título do e-mail
     * @Param {STR} $html
     * Arquivo que contém o html do e-mail.
     * @Param {ARR} $values
     * Array com as informações que serão enviadas.
     * 
     * @example 
     * sendMail(
     *      'recebe@recebe.com',
     *      'Título do envio',
     *      '{valorA} e {valorB}',
     *      [
     *          'valorA' => 'Valor de A',
     *          'valorB' => 'Valor de B',
     *      ]
     * );
     * *********************************************
     */
    public function sendMail($Address, $title, $html, $values = []) {
        $this->address = (string) $Address;
        $this->title = (string) utf8_decode($title);
        $this->template = (string) $html;
        $this->templateDir();
        $this->find = [];
        $this->replaces = [];
        foreach ($values as $key => $value) {
            $this->find[] = '{' . $key . '}';
            $this->replaces[] = $value;
        }
        $this->objectValues();
    }

    /**
     * *********************************************
     * Quando não consegue enviar informa o erro
     *  que ocorreu.
     * *********************************************
     */
    public function mailError() {
        return $this->sendError;
    }

    /**
     * *********************************************
     * Informa o e-mail foi enviado.
     * @return (bool)
     * *********************************************
     */
    public function sendStatus() {
        if ($this->sendAcept) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * *********************************************
     * Checa se o arquivo de template do html
     *  existe.
     * @access private
     * *********************************************
     */
    private function templateDir() {
        if (!file_exists($this->template)) {
            die('Erro ao solicitar dados para envio de e-mail');
        }
    }

    /**
     * *********************************************
     * Cria os objetos de envio
     * @access private
     * *********************************************
     */
    private function objectValues() {
        ob_start();
        include($this->template);
        $content = ob_get_clean();
        if (!empty($this->find) && !empty($this->replaces)) {
            $this->content = str_replace($this->find, $this->replaces, $content);
        }
        if ($this->sendValues()) {
            return true;
        }
    }

    /**
     * *********************************************
     * Envia os dados
     * @access private
     * @return Exeption (true/false)
     * *********************************************
     */
    private function sendValues() {
        $this->mailer->AddAddress($this->address);
        $this->mailer->Subject = $this->title;
        $this->mailer->Body = $this->content;
        try {
            $this->mailer->Send();
            $this->sendAcept = true;
        } catch (Exception $e) {
            $this->sendError = "Erro ao enviar e-mail linha: {$e->getCode()}<br/>Arquivo: {$e->getFile()}<br/>Detalhes: {$e->getMessage()}";
            $this->sendAcept = false;
        }
    }
}
