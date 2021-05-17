<?php
switch ($url[1]) {
    case 'padrao-css':
        $app = ['key' => 'css', 'name' => 'CSS'];
        break;
    case 'padrao-js':
        $app = ['key' => 'js', 'name' => 'Javascript'];
        break;
}

try {
    if ($admin < $config->admin) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        $select = new Select();
        $select->query("app_page", "a_key = :ak", "ak={$app['key']}");

        $count = $select->count();
        if ($count) {
            $appResult = $select->result();
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        }
        ?>
        <div class="container padding-all-prop">
            <div class="padding-right-prop over-not">
                <h1 class="gunship over-text"><?= $app['name'] ?> Padrão</h1>
            </div>
            <hr />
            <div class="row-pad">
                <div class="col-quarter">
                    <p class="font-medium">Adicionar</p>
                    <button class="btn-success button-block text-white" onclick="smStf.app.newPage('<?= $app['key'] ?>')">
                        Criar <i class="icon-file-plus2"></i>
                    </button>
                </div>
                <div class="col-threequarter">
                    <?php if ($count > $config->rows->search) { ?>
                        <p class="font-medium">Localizar Página</p>
                        <form method="POST" id="search-app" onsubmit="return smStf.app.searchPage([<?= $config->length->minSearch ?>, <?= $config->length->maxSearch ?>])">
                            <div class="row">
                                <div class="float-right">
                                    <button class="btn-success box-y-50 text-white"><i class="icon-search3"></i></button>
                                </div>
                                <div class="over-not">
                                    <input type="text" name="search" id="search" class="input-default" placeholder="pesquisar..." maxlength="<?= $config->length->maxFind ?>" />
                                </div>
                            </div>
                            <input type="hidden" name="app" value="<?= $app['key'] ?>"/>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="margin-top">
            <div id="paginator" class="fade-in">
                <?php
                if (!$count) {
                    include (__DIR__ . '/../error/412.php');
                } else {
                    include (__DIR__ . '/app/paginator.php');
                }
                ?>
            </div>
            <div id="page-action"></div>
            <div id="page-preview"></div>
        </div>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            include (__DIR__ . '/../error/denied.php');
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../error/500.php');
            break;
    }
}
