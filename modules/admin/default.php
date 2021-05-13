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

            <div class="session-menu">
                <ul id="global-menu">
                    <?php if ($page) { ?>
                        <li><a href="admin" title="Voltar ao início"><i class="icon-home"></i> &nbsp; Início</a></li>
                    <?php } ?>
                    <li><a href="admin/usuarios" title="Usuários"><i class="icon-users"></i> &nbsp; Usuários</a></li>
                    <?php if ($admin >= $config->developer) { ?>
                        <li><a href="admin/apps-padrao" title="Apps Padrões"><i class="icon-unite"></i> &nbsp; Aplicações Padrões</a></li>
                        <li><a href="admin/web-docs" title="Web Doc"><i class="icon-book"></i> &nbsp; Documentação Web</a></li>
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
                case 'apps-padrao':
                    echo 'apps-padrao';
                    break;
                case 'web-docs':
                    echo 'web-docs';
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
    }
}
