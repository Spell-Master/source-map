<?php

/**
 * ********************************************
 * @copyright: Spell Master(c)
 * ********************************************
 * @Class: Classe registro de erros.
 * ********************************************
 */
class LogRegister {

    private $file;
    private $message;
    private $comment;

    /**
     * ****************************************
     * Registra no banco de dados erros
     * personalizados da aplicação através de
     * exceções lançadas.
     * * @param STR $file
     * Arquivo do disparo de erro "getFile()"
     * * @param STR $message
     * Mensagem do disparo "getMessage()"
     * * @param STR $comment
     * Comentários adicionais
     * ****************************************
     */
    public function registerError($file, $message, $comment = null) {
        $this->file = (string) $file;
        $this->message = (string) $message;
        $this->comment = (string) $comment;
        $insert = new Insert();
        $insert->query('log_error', [
            'lg_date' => date('Y-m-d'),
            'lg_hour' => date('H:i:s'),
            'lg_file' => $this->file,
            'lg_message' => htmlentities($this->message),
            'lg_comment' => htmlentities($this->comment)
        ]);
        if ($insert->error()) {
            $this->sqlError();
        }
    }

    /**
     * ****************************************
     * Faz a leitura de erros registrados no
     * banco de dados erros.
     * ****************************************
     */
    public function loadError() {
        $select = new Select();
        $select->query('log_error');
        if ($select->count()) {
            return ($select->result());
        } else if ($select->error()) {
            $this->file = __FILE__;
            $this->message = $select->error();
            $this->sqlError();
        } else {
            return (false);
        }
    }

    /**
     * ****************************************
     * Em caso de falha no resgistro de erro
     * no banco escrever em arquivo de TXT
     * para averiguação posterior.
     * ****************************************
     */
    private function sqlError() {
        $txt = "== [ ERROR ] ===========================\n"
                . "- Data: " . date('Y-m-d') . "\n"
                . "- Horário: " . date('H:i:s') . "\n"
                . "- Arquivo: " . $this->file . "\n"
                . ($this->message ? "- Mensagem:\n" . strip_tags($this->message) . "\n" : "")
                . ($this->comment ? "\n- Comentários:\n" . strip_tags($this->comment) : "")
                . "\n";
        $reg = fopen(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'error.txt', 'a');
        fwrite($reg, $txt);
        fclose($reg);
    }

}
