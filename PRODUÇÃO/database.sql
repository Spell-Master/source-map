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