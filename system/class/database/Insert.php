<?php
/**
 * ********************************************
 * @Copyright (c) 2014, Spell Master.
 * ********************************************
 * @Class: "PDO" Faz incessão de dados no banco
 * ********************************************
 */

class Insert extends Connect {

    /** @Attr: Armazena a tabela * */
    private $insertTable;

    /** @Attr: Armazena a ARRAY de valores * */
    private $insertFilds;

    /** @Attr: Armazena a syntax da query * */
    private $insertSyntax;

    /** @Attr: Armazena a conexão * */
    private $insertConn;

    /** @Attr: Armazena se houve sucesso na incerssão * */
    private $insertData;

    /** @Attr: Armazena o erro para personalizar a saída * */
    private $insertError;

    /**
     * ****************************************
     * Recebe os dados para inserir.
     * * @Param: {STR} $table
     * Tabela para inserir.
     * * @Param: {ARR} $field
     * Valores a inserir.
     * ****************************************
     */
    public function query($table, array $fields) {
        $this->insertTable = (string) $table;
        $this->insertFilds = $fields;
        $this->insertConstruct();
        $this->insertExecute();
    }

    /**
     * ****************************************
     * Informa se o registro foi concluído.
     * ****************************************
     * @Return: (INT)/(BOOL) 1  registro
     * ****************************************
     */
    public function count() {
        if ($this->insertData) {
            return ($this->insertSyntax->rowCount());
        } else {
            return (0);
        }
    }

    /**
     * ****************************************
     * Informa se o registro foi concluído.
     * @Return: (bool) TRUE or FALSE
     * ****************************************
     */
    public function result() {
        if ($this->insertData) {
            return ($this->insertData);
        }
    }

    /**
     * ****************************************
     * Informa erros de consulta.
     * @Return: (STRING/BOLL) Resultado
     * a partir de PDOException
     * ****************************************
     */
    public function error() {
        if (!empty($this->insertError)) {
            return ($this->insertError);
        }
    }

    /**
     * ****************************************
     * Controi a Syntax da query através
     * da array recebida, para o
     * Prepare Statements
     * ****************************************
     */
    private function insertConstruct() {
        $Column = implode(', ', array_keys($this->insertFilds));
        $values = ':' . implode(', :', array_keys($this->insertFilds));
        $this->insertSyntax = "INSERT INTO {$this->insertTable} ({$Column}) VALUES ({$values})";
    }

    /**
     * ****************************************
     * Inicia a conexão e faz o
     * Prepare Statements.
     * ****************************************
     */
    private function insertConnect() {
        $this->insertConn = parent::callConnect();
        $this->insertSyntax = $this->insertConn->prepare($this->insertSyntax);
    }

    /**
     * ****************************************
     * Executa os métodos e obtem os
     * resultados.
     * @Return: (EXCEPTION) TRUE or FALSE
     * ****************************************
     */
    private function insertExecute() {
        $this->insertConnect();
        try {
            $this->insertSyntax->execute($this->insertFilds);
            $this->insertData = $this->insertConn->lastInsertId();
        } catch (PDOException $error) {
            $this->insertData = null;
            $this->insertError = "Erro ao inserir dados: {$error->getMessage()} {$error->getCode()}";
        }
    }

}
