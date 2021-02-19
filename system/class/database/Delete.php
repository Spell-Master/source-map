<?php
/**
 * ********************************************
 * @Copyright (c) 2014, Spell Master.
 * ********************************************
 * @Class: "PDO" Apaga dados no banco.
 * ********************************************
 */

class Delete extends Connect {

    /** @Attr: Armazena a tabela * */
    private $deleteTable;

    /** @Attr: Armazena os campos * */
    private $deleteFields;

    /** @Attr: Armazena os valores * */
    private $deleteValues;

    /** @Attr: Armazena a query * */
    private $deleteSyntax;

    /** @Attr: Armazena a conexão * */
    private $deleteConn;

    /** @Attr: Armazena os dados * */
    private $deleteData;

    /** @Attr: Armazena o erro para personalizar a saída * */
    private $deleteError;

    /**
     * ****************************************
     * Recebe os dados para apagar.
     * ****************************************
     * @Param {STR} $table
     * Tabela para buscar.
     * @Param {STR} $fields
     * Campos da tabela.
     * @Param {STR} $statements
     * Valor a ser apagado.
     * ****************************************
     */
    public function query($table, $fields, $statements) {
        $this->deleteTable = (string) $table;
        $this->deleteFields = (string) $fields;
        parse_str($statements, $this->deleteValues);
        $this->deleteConstruct();
        $this->deleteExecute();
    }

    /**
     * ****************************************
     * Informa quantos valores foram apagados.
     * ****************************************
     * @Return: (INT) Quantidade.
     * ****************************************
     */
    public function count() {
        if ($this->deleteData) {
            return ($this->deleteSyntax->rowCount());
        }
    }

    /**
     * ****************************************
     * Informa se houve valores apagados.
     * @Return: (BOOL) TRUE/FALSE.
     * ****************************************
     */
    public function result() {
        if ($this->deleteData) {
            return ($this->deleteData);
        }
    }

    /**
     * ****************************************
     * Informa erros de consulta.
     * ****************************************
     * @Return: (STRING/BOLL) Resultado 
     * a partir de PDOException
     * ****************************************
     */
    public function error() {
        if (!$this->deleteData) {
            return ($this->deleteError);
        }
    }

    /**
     * ****************************************
     * Constroi a Syntax da query.
     * ****************************************
     */
    private function deleteConstruct() {
        $this->deleteSyntax = "DELETE FROM {$this->deleteTable} WHERE {$this->deleteFields}";
    }

    /**
     * ****************************************
     * Inicia a conexão e faz o
     * Prepare Statements.
     * ****************************************
     */
    private function deleteConnect() {
        $this->deleteConn = parent::callConnect();
        $this->deleteSyntax = $this->deleteConn->prepare($this->deleteSyntax);
    }

    /**
     * ****************************************
     * Executa os métodos e obtem os
     * resultados.
     * ****************************************
     * @Return: (EXCEPTION) TRUE or FALSE
     * ****************************************
     */
    private function deleteExecute() {
        $this->deleteConnect();
        try {
            $this->deleteSyntax->execute($this->deleteValues);
            $this->deleteData = true;
        } catch (PDOException $error) {
            $this->deleteData = null;
            $this->deleteError = "Erro ao ler dados: {$error->getMessage()} {$error->getCode()}";
        }
    }
}
