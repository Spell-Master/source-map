<?php
$admin = (isset($session->admin) ? $session->admin : false);
$page = (isset($url[1]) ? $url[1] : false);
try {
    if (!$admin) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($admin < $config->staff) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        ?>
        <div id="header-bottom" class="bg-dark-black">
            <div class="bottom-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-third col-fix over-text padding-lr">
                            <div class="line-block vertical-middle">
                                <a href="admin" class="font-large">Administração</a>
                            </div>
                        </div>
                        <div class="col-twothird col-fix">
                            <ul class="bar-menu">
                                <li data-open="session-folder" title="Sessões"></li>
                                <li data-open="session-menu" title="Administrar"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="session-folder">
                session-folder
            </div>

            <div class="session-menu ">
                <ul id="global-menu">
                    <?php if ($page) { ?>
                        <li><a href="admin" title="Voltar ao início"><i class="icon-home"></i> &nbsp; Início</a></li>
                    <?php } ?>
                    <li><a href="admin/usuarios" title="Usuários"><i class="icon-users"></i> &nbsp; Usuários</a></li>

                    <?php if ($admin >= $config->admin) { ?>
                        <li>
                            <a data-open="app-menu"><i class="icon-share4"></i> &nbsp; Aplicações Padrões</a>
                            <ul class="sub-menu app-menu">
                                <li><a data-open="session-menu"><i class="icon-menu8"></i> &nbsp; Menu</a></li>
                                <li><a href="admin/padrao-css"><i class="icon-file-css2"></i> &nbsp; CSS</a></a></li>
                                <li><a href="admin/padrao-js"><i class="icon-code"></i> &nbsp; JavaScript</a></a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if ($admin >= $config->docCategory || $admin >= $config->docCategory || $admin >= $config->docSector || $admin >= $config->docPage) { ?>
                        <li>
                            <a data-open="doc-menu"><i class="icon-book3"></i> &nbsp; Documentação Web</a>
                            <ul class="sub-menu doc-menu">
                                <li><a data-open="session-menu"><i class="icon-menu8"></i> &nbsp; Menu</a></li>
                                <?php if ($admin >= $config->docCategory) { ?>
                                    <li><a href="admin/doc-categoria"><i class="icon-archive"></i> &nbsp; Categorias</a></a></li>
                                <?php } if ($admin >= $config->docSector) { ?>
                                    <li><a href="admin/doc-sector"><i class="icon-folder2"></i> &nbsp; Setores</a></a></li>
                                <?php } if ($admin >= $config->docPage) { ?>
                                    <li><a href="admin/"><i class="icon-book"></i> &nbsp; Páginas</a></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </div>

        </div>

        <div id="page-load">
            <?php
            SeoData::breadCrumbs($url);

            switch ($page) {
                case 'usuarios':
                    echo 'usuarios';
                    break;
                case 'padrao-css':
                case 'padrao-js':
                    include (__DIR__ . '/app.php');
                    break;
                case 'doc-categoria':
                    include (__DIR__ . '/doc-category.php');
                    break;
                case 'doc-sector':
                    include (__DIR__ . '/doc-sector.php');
                    break;
                case 'doc-page':
                    include (__DIR__ . '/doc-page.php');
                    break;
                default:
                    echo "início";
                    break;
            }
            ?>
        </div>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            header('Location: ' . $baseUri); // $baseUri (index.php)
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../error/500.php');
            break;
    }
}
