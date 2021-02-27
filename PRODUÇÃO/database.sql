-- ----------------------------
-- Erros de login
-- ----------------------------
DROP TABLE IF EXISTS `user_error`;
CREATE TABLE `user_error` (
    `ue_count` int(1) NOT NULL DEFAULT '1' COMMENT 'contagem de quantas vezes errou',
    `ue_bound` text NOT NULL DEFAULT '' COMMENT 'dados da máquina que errou o login',
    `ue_time` date COMMENT 'tempo para desbloquear'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

-- ----------------------------
-- Erros
-- ----------------------------
DROP TABLE IF EXISTS `log_error`;
CREATE TABLE `log_error` (
    `lg_id` int(9) NOT NULL AUTO_INCREMENT,
    `lg_date` date COMMENT 'Data do erro',
    `lg_hour` time COMMENT 'Horário do erro',
    `lg_file` varchar(200) NOT NULL DEFAULT '' COMMENT 'Arquivo que gerou o erro',
    `lg_message` text NOT NULL DEFAULT '' COMMENT 'Mensagem de erro',
    `lg_comment` text NOT NULL DEFAULT '' COMMENT 'Comentários adicionais',
    PRIMARY KEY (`lg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;