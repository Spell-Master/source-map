<?php
$valid = new StrValid();
$select = new Select();
$clear = new StrClean();

$sectorJoin = (isset($url[1]) ? true : false); // Se acessado algum setor
$pageJoin = (isset($url[2]) ? true : false); // Se acessado alguma página
$login = (isset($session->user) ? $session->user : false);

try {
    if ($sectorJoin && !$valid->urlCheck($url[1])) {
        throw new ConstException(null, ConstException::INVALID_URL);
    } else if ($pageJoin && !$valid->urlCheck($url[2])) {
        throw new ConstException(null, ConstException::INVALID_URL);
    } else {
        if (!$sectorJoin) { // Nenhum setor ou página acessado (Carregar setores em suas respectivas categorias)
            $select->setQuery("
                SELECT
                    doc_category.c_hash,
                    doc_category.c_title,
                    doc_category.c_order,
                    doc_sectors.s_status,
                    doc_sectors.s_title,
                    doc_sectors.s_link,
                    doc_sectors.s_icon,
                    doc_sectors.s_category,
                    doc_sectors.s_info
                FROM
                    doc_category
                INNER JOIN
                    doc_sectors
                ON
                    doc_category.c_hash = doc_sectors.s_category
                WHERE
                    doc_sectors.s_status = '1'
                GROUP BY
                    doc_sectors.s_link
                ORDER BY
                    doc_category.c_order ASC,
                    doc_sectors.s_link ASC
            ");
        } else { // ALGUM SETOR ACESSADO (Carregar todas páginas desse setor)
            $select->setQuery("
                SELECT
                    doc_sectors.s_status,
                    doc_sectors.s_hash,
                    doc_sectors.s_title,
                    doc_sectors.s_link,
                    doc_sectors.s_info,
                    doc_pages.p_status,
                    doc_pages.p_hash,
                    doc_pages.p_title,
                    doc_pages.p_link,
                    doc_pages.p_sector,
                    doc_pages.p_text
                FROM
                    doc_sectors
                INNER JOIN
                    doc_pages
                ON
                    doc_sectors.s_hash = doc_pages.p_sector
                WHERE
                    (doc_sectors.s_status = :enable AND doc_pages.p_status = :enable)
                AND
                    (doc_sectors.s_link = :link OR doc_sectors.s_hash = :link)
                ORDER BY
                    doc_pages.p_title ASC
            ", "enable=1&link={$clear->formatStr($url[1])}");
        }
        $docCount = $select->count();

        if ($docCount) {
            $docResult = $select->result();
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            $docResult = false;
        }

        /* ============== */
        ?>
        <div id="header-bottom" class="bg-dark-black">
            <div class="bottom-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-third col-fix over-text padding-lr">
                            <div class="line-block vertical-middle">
                                <a href="doc" class="font-large">Web Doc</a>
                            </div>
                        </div>
                        <div class="col-twothird col-fix">
                            <ul class="bar-menu">
                                <li data-open="session-folder" title="Sessões"></li>
                                <?php
                                if ($login && $config->enable->search == 'y' && $docCount >= $config->rows->search) {
                                    ?>
                                    <li data-open="session-search" title="Pesquisa"></li>
                                <?php } if ($sectorJoin && $docCount > 1) { ?>
                                    <li data-open="session-menu" title="Páginas"></li>
                                    <?php
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
            if ($login && $config->enable->search == 'y' && $docCount >= $config->rows->search) {
                ?>
                <div class="session-search" data-open="fix">
                    <div class="container padding-all-prop">
                        <p class="font-large align-center gunship">Pesquisar</p>
                        <hr class="border-dark-black" />
                        <div class="box-x-900 margin-auto">
                            <form method="POST" action="" id="search-page" onsubmit="return smPage.search('doc', [<?= $config->length->minSearch ?>, <?= $config->length->maxSearch ?>])">
                                <div class="row">
                                    <div class="float-left">
                                        <button class="btn-info box-y-50 text-white">
                                            <i class="icon-search3 font-medium"></i>
                                        </button>
                                    </div>
                                    <div class="over-not">
                                        <input type="text" name="search" id="search" class="input-default" placeholder="Procurar páginas" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } if ($sectorJoin && $docCount > 1) { ?>
                <div class="session-menu">
                    <ul id="global-menu">
                        <?php foreach ($docResult as $value) { ?>
                            <li>
                                <a
                                    href="doc/<?= $value->s_link . '/' . $value->p_link ?>"
                                    title="<?= $value->p_title ?>"
                                    id="link-<?= $value->p_hash ?>">
                                        <?= $value->p_title ?>
                                </a>
                            </li>
                        <?php } ?> 
                    </ul>
                </div>
                <?php
            }
            ?>
        </div>

        <div id="page-load">
            <?php
            if ($docCount) {
                if (!$sectorJoin) { // NÃO ACESSOU NENHUM SETOR
                    include (__DIR__ . '/sectors-paginator.php');
                } else if ($sectorJoin && !$pageJoin) { // ACESSOU O SETOR MAS NENHUMA PÁGINA
                    include (__DIR__ . '/pages-paginator.php');
                } else {  // ACESSOU ALGUMA PÁGINA
                    include (__DIR__ . '/page-load.php');
                }
            } else {
                SeoData::breadCrumbs($url);
                include (__DIR__ . '/../error/412.php');
            }
            ?>
        </div>

        <script>
            smPage.menu('doc');
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
