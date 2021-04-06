DROP TABLE IF EXISTS `app_page`;
CREATE TABLE `app_page` (
    `a_id` int(9) NOT NULL AUTO_INCREMENT,
    `a_hash` varchar(50) NOT NULL DEFAULT '' COMMENT 'Hash identificador',
    `a_title` varchar(100) NOT NULL DEFAULT '' COMMENT 'Título',
    `a_link` varchar(200) NOT NULL DEFAULT '' COMMENT 'Link',
    `a_key` enum('css', 'js') NOT NULL DEFAULT 'css' NULL COMMENT 'css = css padrão, js = javascript padrão',
    `a_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Conteúdo',
    `a_text` text NOT NULL DEFAULT '' COMMENT 'Conteúdo simplificado sem marcação html',
    `a_date` date COMMENT 'Data da criação',
    `a_version` date COMMENT 'Data da última edição',
    PRIMARY KEY (`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
