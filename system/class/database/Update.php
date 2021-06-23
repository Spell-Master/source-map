<?php
/**
 * ********************************************
 * @Copyright (c) 2014, Spell Master.
 * ********************************************
 * @Class: "PDO" Faz modificações de
 * dados no banco.
 * ********************************************
 */

class Update extends Connect {

    /** @Attr: Armazena a tabela * */
    private $updateTable;

    /** @Attr: Armazena a coluna * */
    private $updateColumn;

    /** @Attr: Armazena os campos * */
    private $updateFields;

    /** @Attr: Armazena os valores * */
    private $updateValues;

    /** @Attr: Armazena a query * */
    private $updateSyntax;

    /** @Attr: Armazena a conexão * */
    private $updateConn;

    /** @Attr: Armazena o erro para personalizar a saída * */
    private $updateError;
    
    /** @Attr: Armazena se houve sucesso na incerssão * */
    private $updateData;

    /**
     * ********************************************
     * Recebe os dados para modificar.
     * ****************************************
     * @Param {STR} $table
     * Tabela para modificar.
     * @Param {ARR} $change
     * Array com valores para modificar.
     * @Param {STR} $target
     * Onde deve ser modificado.
     * @Param {STR} $statements
     * Valor do $fields.
     * ********************************************
     */
    public function query($table, array $change, $target, $statements) {
        $this->updateTable = (string) $table;
        $this->updateColumn = $change;
        $this->updateFields = (string) $target;

        parse_str($statements, $this->updateValues);
        $this->updateConstruct();
        $this->updateExecute();
    }

    /**
     * ********************************************
     * Retorna se quantidade de dados modificados
     * e quantos campos foram modificados.
     * ********************************************
     */
    public function count() {
        if ($this->updateData) { 
            return ($this->updateSyntax->rowCount());
        } else {
            return (0);
        }
    }

    /**
     * ********************************************
     * Informa erros na alteração.
     * ****************************************
     * @Return: (STRING/BOLL) Resultado a partir
     * de PDOException
     * ********************************************
     */
    public function error() {
        if (!empty($this->updateError)) {
            return ($this->updateError);
        }
    }

    /**
     * ********************************************
     * Constroi a Syntax da query 
     * ********************************************
     */
    private function updateConstruct() {
        foreach ($this->updateColumn as $Key => $Value) {
            $setKey[] = $Key . ' = :' . $Key;
        }
        $Value = array();
        $setKey = implode(', ', $setKey);
        $this->updateSyntax = "UPDATE {$this->updateTable} SET {$setKey} WHERE {$this->updateFields}";
    }

    /**
     * ********************************************
     * Inicia a conexão e faz o
     * Prepare Statements.
     * ********************************************
     */
    private function updateConnect() {
        $this->updateConn = parent::callConnect();
        $this->updateSyntax = $this->updateConn->prepare($this->updateSyntax);
    }

    /**
     * ********************************************
     * Executa os métodos e obtem os resultados.
     * ****************************************
     * @Return: (EXCEPTION) TRUE or FALSE
     * ********************************************
     */
    private function updateExecute() {
        $this->updateConnect();
        try {
            $this->updateSyntax->execute(array_merge($this->updateColumn, $this->updateValues));
            $this->updateData = true;
        } catch (PDOException $error) {
            $this->updateData = false;
            $this->updateError = "Erro ao alterar dados: {$error->getMessage()} {$error->getCode()}";
        }
    }
}
