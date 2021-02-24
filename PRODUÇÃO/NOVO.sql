-- ----------------------------
-- Usuários temporários
-- ----------------------------
DROP TABLE IF EXISTS `users_temp`;
CREATE TABLE `users_temp` (
    `ut_code` varchar(50) NOT NULL DEFAULT '' COMMENT 'Código de ativação',
    `ut_mail` varchar(90) NOT NULL DEFAULT '' COMMENT 'Endereço de email',
    `ut_pass` varchar(500) NOT NULL DEFAULT '' COMMENT 'Senha de acesso',
    `ut_name` varchar(50) NOT NULL DEFAULT '' COMMENT 'Nome do usuário',
    `ut_link` varchar(50) NOT NULL DEFAULT '' COMMENT 'Link do perfil',
    `ut_date` date COMMENT 'Data do registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Usuários
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `u_id` int(11) NOT NULL AUTO_INCREMENT,
    `u_mail` varchar(90) NOT NULL DEFAULT '' COMMENT 'Endereço de email',
    `u_hash` varchar(50) NOT NULL DEFAULT '' COMMENT 'Hash identificador',
    `u_pass` varchar(500) NOT NULL DEFAULT '' COMMENT 'Senha de acesso',
    `u_level` int(1) NOT NULL DEFAULT 0 COMMENT 'Nível de acesso',
    `u_name` varchar(50) NOT NULL DEFAULT '' COMMENT 'Nome do usuário',
    `u_link` varchar(50) NOT NULL DEFAULT '' COMMENT 'Link do perfil',
    `u_photo` varchar(100) NOT NULL DEFAULT '' COMMENT 'Imagem do perfil',
    `u_date` date COMMENT 'Data do registro',
    `u_warn` int(1) NOT NULL DEFAULT 0 COMMENT 'Quantidade de alertas',
    `u_ban` ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Banido permanente / 0 = acessivel, 1 = banido',
    `u_bandate` date COMMENT 'Data de desbanimento',
    PRIMARY KEY (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
