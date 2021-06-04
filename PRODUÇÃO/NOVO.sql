-- ----------------------------
-- Informação de Usuários
-- ----------------------------
DROP TABLE IF EXISTS `users_info`;
CREATE TABLE `users_info` (
    `ui_id` int(11) NOT NULL AUTO_INCREMENT,
    `ui_hash` varchar(50) NOT NULL DEFAULT '' COMMENT 'identificador único',
    `ui_website` varchar(200) NOT NULL DEFAULT '' COMMENT 'website',
    `ui_mail` varchar(200) NOT NULL DEFAULT '' COMMENT 'email de contato',
    `ui_git` varchar(200) NOT NULL DEFAULT '' COMMENT 'perfil do girhub',
    `ui_face` varchar(200) NOT NULL DEFAULT '' COMMENT 'perfil do facebook',
    `ui_insta` varchar(200) NOT NULL DEFAULT '' COMMENT 'perfil do instagram',
    `ui_twit` varchar(200) NOT NULL DEFAULT '' COMMENT 'perfil do twitter',
    `ui_tube` varchar(200) NOT NULL DEFAULT '' COMMENT 'perfil do youtub',
    `ui_whats` varchar(200) NOT NULL DEFAULT '' COMMENT 'número do whatsapp',
    `ui_about` text NOT NULL DEFAULT '' COMMENT 'informações sobre',
    PRIMARY KEY (`ui_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
