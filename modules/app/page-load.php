<?php
require_once (__DIR__ . '/../../system/config.php');
$valid = new StrValid();
$clear = new StrClean();
$selectB = new Select();

if (!isset($url)) {
    $url = SeoData::parseUrl();
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
        if ($selectB->count()) {
            $pageData = $selectB->result()[0];
            ?>
            <div class="container padding-all-prop" id="action-page">
                <h1 class="over-text"><?= $pageData->a_title ?></h1>
                <hr />
                <article class="margin-top-high">
                    <?= PostData::showPost($pageData->a_content) ?>
                </article>

                <script>
                    var $linkId = document.getElementById('link-<?= $pageData->a_hash ?>');
                    smc.spoiler();
                    Prism.highlightAll();
                    if (sml.isReady($linkId)) {
                        $linkId.classList.add('active');
                    }
                </script>
            </div>
            <?php
        } else {
            include (__DIR__ . '/../error/412.php');
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
