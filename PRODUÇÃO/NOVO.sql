-- ----------------------------
-- Uploads
-- ----------------------------
DROP TABLE IF EXISTS `uploads`;
CREATE TABLE `uploads` (
    `up_id` int(9) NOT NULL AUTO_INCREMENT,
    `up_name` varchar(200) NOT NULL DEFAULT '' COMMENT 'nome do arquivo',
    `up_size` int(11) COMMENT 'tamanho do arquivo',
    `up_user` varchar(50) NOT NULL DEFAULT '' COMMENT 'identificador do usuário que enviou',
    `up_date` date COMMENT 'data do envio',
    `up_type` ENUM('file','image') NOT NULL DEFAULT 'file' COMMENT 'file = arquivos, image = imagens',
    PRIMARY KEY (`up_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;