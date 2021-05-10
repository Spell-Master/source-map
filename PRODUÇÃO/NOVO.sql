-- ----------------------------
-- Documentações Página
-- ----------------------------
DROP TABLE IF EXISTS `doc_pages`;
CREATE TABLE `doc_pages` (
    `p_id` int(9) NOT NULL AUTO_INCREMENT,
    `p_status` ENUM('0','1') NOT NULL DEFAULT '1' COMMENT '0 = escondido, 1 = visivel',
    `p_hash` varchar(50) NOT NULL DEFAULT '' COMMENT 'identificador único',
    `p_title` varchar(100) NOT NULL DEFAULT '' COMMENT 'título',
    `p_link` varchar(200) NOT NULL DEFAULT '' COMMENT 'link',
    `p_sector` varchar(50) NOT NULL DEFAULT '' COMMENT 'setor correspondente',
    `p_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'conteúdo',
    `p_text` text NOT NULL DEFAULT '' COMMENT 'conteúdo simplificado sem marcação html',
    `p_created` varchar(50) NOT NULL DEFAULT '' COMMENT 'quem criou a página',
    `p_date` date COMMENT 'data da criação',
    `p_last_edit` varchar(50) NOT NULL DEFAULT '' COMMENT 'quem editou por último',
    `p_last_date` date COMMENT 'data da última edição',
    PRIMARY KEY (`p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- ----------------------------
-- Documentações Setor
-- ----------------------------
DROP TABLE IF EXISTS `doc_sectors`;
CREATE TABLE `doc_sectors` (
    `s_id` int(9) NOT NULL AUTO_INCREMENT,
    `s_status` ENUM('0','1') DEFAULT '1' COMMENT '0 = escondido, 1 = visivel',
    `s_date` date COMMENT 'data de criação',
    `s_hash` varchar(50) NOT NULL DEFAULT '' COMMENT 'identificador único',
    `s_title` varchar(100) NOT NULL DEFAULT '' COMMENT 'título',
    `s_link` varchar(200) NOT NULL DEFAULT '' COMMENT 'link',
    `s_icon` varchar(100) NOT NULL DEFAULT '' COMMENT 'endereço e nome do ícone',
    `s_category` varchar(50) NOT NULL DEFAULT '' COMMENT 'pertence a qual categoria',
    `s_info` text NOT NULL DEFAULT '' COMMENT 'informação do setor',
    PRIMARY KEY (`s_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Documentações Categoria
-- ----------------------------
DROP TABLE IF EXISTS `doc_category`;
CREATE TABLE `doc_category` (
  `c_id` int(9) NOT NULL AUTO_INCREMENT,
  `c_hash` varchar(50) NOT NULL DEFAULT '' COMMENT 'identificador único',
  `c_title` varchar(100) NOT NULL DEFAULT '' COMMENT 'título da categoria',
  `c_order` int(9) DEFAULT NULL COMMENT 'ordem de exibição',
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
