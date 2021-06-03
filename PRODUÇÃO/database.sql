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

-- ----------------------------
-- Aplicações padrão páginas
-- ----------------------------
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
    `u_link` varchar(100) NOT NULL DEFAULT '' COMMENT 'Link do perfil',
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