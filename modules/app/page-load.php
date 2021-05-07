<?php
require_once (__DIR__ . '/../../system/config.php');
$valid = new StrValid();
$clear = new StrClean();
$selectB = new Select();

if (!isset($url)) {
    $url = SeoData::parseUrl();
    $admin = (isset($session->admin) ? $session->admin : 0);
}

try {
    if (!isset($url[0])) {
        throw new ConstException('Não recebido variável $url[0]', ConstException::SYSTEM_ERROR);
    } else if (empty($url[0])) {
        throw new ConstException('$url[0] não possui valor para manipulação', ConstException::SYSTEM_ERROR);
    } else if (!$valid->urlCheck($url[0])) {
        throw new ConstException(null, ConstException::INVALID_URL);
    } else if ($url[0] !== 'css-padrao' && $url[0] !== 'js-padrao') {
        throw new ConstException(null, ConstException::INVALID_URL);
    }
    //
    else if (!isset($url[1])) {
        throw new ConstException('Não recebido variável $url[1]', ConstException::SYSTEM_ERROR);
    } else if (empty($url[1])) {
        throw new ConstException('$url[1] não possui valor para manipulação', ConstException::SYSTEM_ERROR);
    } else if (!$valid->urlCheck($url[1])) {
        throw new ConstException(null, ConstException::INVALID_URL);
    }
    //
    else {
        $appPage = ($clear->formatStr($url[1]));
        $key = explode('-', $url[0])[0];
        $selectB->query("app_page", "a_link = :al AND a_key = :ak", "al={$appPage}&ak={$key}");
        SeoData::breadCrumbs($url);
        ?>
        <div class="container padding-all-prop" id="page-base">
            <?php
            if ($selectB->count()) {
                $pageData = $selectB->result()[0];
                ?>
                <div class="row-pad">
                    <div class="col-twothird col-fix">
                        <div class="over-text" style="margin-top: -5px">
                            <h1><?= $pageData->a_title ?></h1>
                        </div>
                    </div>
                    <div class="col-third col-fix">
                        <?php if ($admin && $admin >= $config->admin) { ?>
                            <select class="select-options" id="manager-page">
                                <option value="">Ação</option>
                                <option value="edi">Editar</option>
                                <option value="del">Apagar</option>
                            </select>
                        <?php } ?>
                    </div>
                </div>
                <hr />
                <?= PostData::showPost($pageData->a_content) ?>

                <?php if ($admin && $admin >= $config->admin) { ?>
                    <form method="POST" action="" id="del-app">
                        <input type="hidden" name="app" value="<?= $pageData->a_key ?>" id="page-app" />
                        <input type="hidden" name="hash" value="<?= $pageData->a_hash ?>" id="page-hash" />
                    </form>
                <?php } ?>

                <script>
                    var $linkId = document.getElementById('link-<?= $pageData->a_hash ?>');
                    smc.spoiler();
                    Prism.highlightAll();
                    sm_a.managerPage();
                    if (sml.isReady($linkId)) {
                        $linkId.classList.add('active');
                    }
                </script>
                <?php
            } else {
                include (__DIR__ . '/../error/412.php');
            }
            ?>
        </div>
        <?php
        if ($admin && $admin >= $config->admin) {
            ?>
            <div class="container padding-all-prop fade-in" id="page-action"></div>
            <div id="preview-page" class="fade-in"></div>
            <?php
        }
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
