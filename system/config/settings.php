<?php
return [
    'isReady' => true, // Indica que o arquivo foi carregado com sucesso

    /*
     * Níveis de Acesso
     */
    'staff' => 1, // Moderador
    'developer' => 2, // Desenvolvedor
    'manager' => 3, // Coordenador
    'admin' => 4, // Administrador

    /*
     * Conexão com o banco de dados 
     */
    'dbHost' => 'localhost', // Endereço do banco de dados
    'dbUser' => 'root', // Usuário do banco de dados
    'dbPass' => '', // Senha do banco de dados
    'dbName' => 'source_map', // Nome do banco de dados
    
    /*
     * Conexão SMTP
     */
    'mailType' => 'tls', // Tipo de criptografia de acesso do servidor SMTP ('tls'/'sll')
    'mailHost' => 'smtp.gmail.com', // Endereço de acesso ao servidor SMTP
    'mailPort' => 587, // Porta de acesso ao servidor SMTP
    'mailUser' => '', // Endereço do usuário do servidor SMTP
    'mailPass' => '', // Senha do usuário do servidor SMTP

];
