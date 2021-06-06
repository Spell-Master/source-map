<?php
$select = new Select();

$profilePage = (isset($url[1]) ? htmlentities($url[1]) : false);
$login = (isset($session->user) ? $session->user : false);
$admin = (isset($session->admin) ? $session->admin : 0);
$hide = ($admin < $config->admUser ? "WHERE u_ban = '0' AND (u_bandate IS NULL OR u_bandate < NOW())" : null);

if ($profilePage) {
    $select->query(
            "users",
            "u_link = :ul",
            "ul={$profilePage}"
    );
} else {
    $select->setQuery("
        SELECT
            u_hash,
            u_level,
            u_name,
            u_link,
            u_photo,
            u_date,
            u_ban,
            u_bandate
        FROM
            users
        {$hide}
        ORDER BY
            u_link,
            u_date
        LIMIT 100
    ");
}

try {
    if ($select->error()) {
        throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
    } else {
        $count = $select->count();
        $userData = ($profilePage && $count ? $select->result()[0] : false);
        ?>
        <div id="header-bottom" class="bg-dark-black">
            <div class="bottom-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-third col-fix over-text padding-lr">
                            <div class="line-block vertical-middle">
                                <a href="perfil" class="font-large">USUÁRIO<?= ($count > 1 ? null : 'S') ?></a>
                            </div>
                        </div>
                        <div class="col-twothird col-fix">
                            <ul class="bar-menu">
                                <li data-open="session-folder" title="Sessões"></li>
                                <?php
                                if ($login && $count) {
                                    if (!$profilePage && $config->enable->search == 'y' && $count >= $config->rows->search) {
                                        ?>
                                        <li data-open="session-search" title="Pesquisa"></li>
                                        <?php
                                    }
                                    if ($profilePage && $userData->u_hash == $login->hash) {
                                        ?>
                                        <li data-open="session-menu" title="Ações"></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="session-folder">
                session-folder
            </div>

            <?php
            if ($login && $count) {
                if (!$profilePage && $config->enable->search == 'y' && $count >= $config->rows->search) {
                    ?>
                    <div class="session-search" data-open="fix">
                        <div class="container padding-all-prop">
                            <p class="font-large align-center gunship">Encontrar Usuários</p>
                            <hr class="border-dark-black" />
                            <div class="box-x-900 margin-auto">
                                <form method="POST" action="" id="search-page" onsubmit="return false">
                                    <div class="row">
                                        <div class="float-left">
                                            <button class="btn-info box-y-50 text-white">
                                                <i class="icon-search3 font-medium"></i>
                                            </button>
                                        </div>
                                        <div class="over-not">
                                            <input type="text" name="search" id="search" class="input-default" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if ($profilePage && $userData->u_hash == $login->hash) {
                    ?>
                    <div class="session-menu">
                        <ul id="global-menu" class="align-center">
                            <li>
                                <a title="Editar dados cadastrais" onclick="smUser.openEdit('data');">
                                    <i class="font-large icon-certificate"></i><p>Dados Cadastrais</p>
                                </a>
                            </li>
                            <li>
                                <a title="Editar dados informacionais" onclick="smUser.openEdit('info');">
                                    <i class="font-large icon-profile"></i><p>Dados de Informação</p>
                                </a>
                            </li>
                            <li>
                                <a title="Editar dados imagens" onclick="smUser.openEdit('img');">
                                    <i class="font-large icon-images2"></i><p>Dados de Imagem</p>
                                </a>
                            </li>
                            <li>
                                <a title="Editar dados de Conteúdos" onclick="smUser.openEdit('content');">
                                    <i class="font-large icon-stack3"></i><p>Dados de Conteúdo</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <div id="page-load">
            <?php
            SeoData::breadCrumbs($url);
            if ($profilePage) {
                if ($count) {
                    include (__DIR__ . '/profile/user-page.php');
                } else {
                    include (__DIR__ . '/../error/404.php');
                }
            } else {
                if ($count) {
                    include (__DIR__ . '/profile/users-paginator.php');
                } else {
                    include (__DIR__ . '/../error/412.php');
                }
            }
            ?>
        </div>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../error/500.php');
            break;
    }
}
