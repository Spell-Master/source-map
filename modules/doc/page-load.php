<?php
require_once (__DIR__ . '/../../system/config.php');

if (!isset($url)) {
    $url = SeoData::parseUrl();
    $valid = new StrValid();
    $clear = new StrClean();
    $selectPage = new Select();
    $selectUser = clone $selectPage;
} else {
    $selectPage = clone $select;
    $selectUser = clone $select;
}

try {
    if (!isset($url[1])) {
        throw new ConstException('Não recebido variável $url[1]', ConstException::SYSTEM_ERROR);
    } else if (!$valid->urlCheck($url[1])) {
        throw new ConstException(null, ConstException::INVALID_URL);
    }
    //
    else if (!isset($url[2])) {
        throw new ConstException('Não recebido variável $url[1]', ConstException::SYSTEM_ERROR);
    } else if (!$valid->urlCheck($url[2])) {
        throw new ConstException(null, ConstException::INVALID_URL);
    }
    //
    else {
        SeoData::breadCrumbs($url);

        $selectPage->setQuery("
            SELECT
                doc_sectors.s_status,
                doc_sectors.s_hash,
                doc_sectors.s_link,
                doc_pages.p_status,
                doc_pages.p_hash,
                doc_pages.p_title,
                doc_pages.p_link,
                doc_pages.p_sector,
                doc_pages.p_content,
                doc_pages.p_created,
                doc_pages.p_date,
                doc_pages.p_last_edit,
                doc_pages.p_last_date
            FROM
                doc_sectors
            INNER JOIN
                doc_pages
            ON
                doc_sectors.s_hash = doc_pages.p_sector
            WHERE
                doc_sectors.s_link = :sl
            AND
                doc_pages.p_link = :pl
        ", "sl={$clear->formatStr($url[1])}&pl={$clear->formatStr($url[2])}");

        if ($selectPage->count()) {
            $pageData = $selectPage->result()[0];
            $selectUser->setQuery("
                SELECT
                    u_name,
                    u_hash,
                    u_link
                FROM
                    users
                WHERE
                    u_hash = :create
                OR
                    u_hash = :edit
            ", "create={$pageData->p_created}&edit={$pageData->p_last_edit}");

            if ($selectUser->error()) {
                throw new ConstException($selectUser->error(), ConstException::SYSTEM_ERROR);
            } else {
                $userData = ($selectUser->count() ? $selectUser->result() : false);
                ?>
                <div class="container padding-all-prop" id="page-base">
                    <h1 class="over-text"><?= $pageData->p_title ?></h1>
                    <hr />
                    <article class="margin-top-high">
                        <?= PostData::showPost($pageData->p_content) ?>
                    </article>
                </div>
                <div class="bg-light padding-all">
                    <?php
                    if ($userData) {
                        foreach ($userData as $value) {
                            if ($value->u_hash == $pageData->p_created && $value->u_hash != $pageData->p_last_edit) {
                                echo ('<p class="list margin-left"><span class="bold">Autor:</span> <a href="users/' . $value->u_link . '" target="_blank" class="href-link">' . $value->u_name . '</a> ' . $clear->dateTime($pageData->p_date) . '</p>');
                            } else if ($value->u_hash == $pageData->p_last_edit) {
                                echo ('<p class="list margin-left"><span class="bold">Edição:</span> <a href="users/' . $value->u_link . '" target="_blank" class="href-link">' . $value->u_name . '</a> ' . $clear->dateTime($pageData->p_last_date) . '</p>');
                            }
                        }
                    } else {
                        echo ('<p class="list margin-left"><span class="bold">Autor:</span> ????</p>');
                    }
                    ?>
                </div>

                <script>
                    var $linkId = document.getElementById('link-<?= $pageData->p_hash ?>');
                    smc.spoiler();
                    Prism.highlightAll();
                    if (sml.isReady($linkId)) {
                        $linkId.classList.add('active');
                    }
                </script>
                <?php
            }
        } else if ($selectPage->error()) {
            throw new ConstException($selectPage->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException(null, ConstException::NOT_FOUND);
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
        case ConstException::NOT_FOUND:
            include (__DIR__ . '/../error/412.php');
            break;
    }
}
