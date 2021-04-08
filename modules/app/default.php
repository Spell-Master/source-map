<?php
$select = new Select();
$admin = (isset($session->admin) ? $session->admin : 0);

switch ($url[0]) {
    case 'css-padrao':
        $app = ['key' => 'css', 'name' => 'CSS'];
        break;
    case 'js-padrao':
        $app = ['key' => 'js', 'name' => 'Javascript'];
        break;
}

$select->setQuery("
    SELECT
        a_hash,
        a_link,
        a_title,
        a_key
    FROM
        app_page
    WHERE
        a_key = :ak",
        "ak={$app['key']}"
);

try {
    if ($select->error()) {
        throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
    } else {
        $appCount = $select->count();
        $appResult = $select->result();
        ?>
        <div id="header-bottom" class="bg-dark-black">
            <div class="bottom-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-third col-fix over-text padding-lr">
                            <div class="line-block vertical-middle">
                                <a href="<?= $app['key'] ?>-padrao" class="font-large">SM <?= $app['name'] ?></a>
                            </div>
                        </div>
                        <div class="col-twothird col-fix">
                            <ul class="bar-menu">
                                <?php if ($admin && $admin->level >= $config->admin) { ?>
                                    <li class="session-add" title="Adicionar Conteúdo"></li>
                                <?php } ?> 
                                <li data-open="session-folder" title="Sessões"></li>
                                <?php if ($config->enable->search == 'y' && $appCount >= $config->rows->search) { ?>
                                    <li data-open="session-search" title="Pesquisa"></li>
                                <?php } if ($appCount > 1) { ?> 
                                    <li data-open="session-menu" title="Páginas"></li>
                                <?php } ?> 
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="session-folder">
                session-folder
            </div>

            <?php if ($config->enable->search == 'y' && $appCount >= $config->rows->search) { ?>
                <div class="session-search" data-open="fix">
                    <div class="container padding-all-prop">
                        <p class="font-large align-center gunship">Pesquisar</p>
                        <hr class="border-dark-black" />
                        <div class="box-x-900 margin-auto">
                            <div class="row">
                                <div class="float-left">
                                    <button class="btn-info box-y-50 text-white">
                                        <i class="icon-search3 font-medium"></i>
                                    </button>
                                </div>
                                <div class="over-not">
                                    <input type="text" class="input-default" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } if ($appCount > 1) { ?> 
                <div class="session-menu">
                    <ul id="global-menu">
                        <?php foreach ($appResult as $value) { ?>
                            <li>
                                <a
                                    href="<?= $app['key'] ?>-padrao/<?= $value->a_link ?>"
                                    title="<?= $value->a_title ?>"
                                    data-list="link-<?= $value->a_hash ?>">
                                        <?= $value->a_title ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?> 
        </div>

        <div id="page-load">
            <?php
            if ($appCount) {
                if (isset($url[1])) {
                    include (__DIR__ . '/page-load.php');
                } else {
                    include (__DIR__ . '/paginator.php');
                }
            } else {
                SeoData::breadCrumbs($url);
                include (__DIR__ . '/../error/412.php');
            }
            ?>
        </div>
        <script>
            smcore.globalMenu('app');
        </script>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_URL:
            include (__DIR__ . '/../error/406.php');
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../error/500.php');
            break;
    }
}
