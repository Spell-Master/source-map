<?php
require_once (__DIR__ . '/../../../system/config.php');
try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docSector) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        $get = GlobalFilter::filterGet();
        $clear = new StrClean();
        $sectors = new Select();

        $filter = (isset($get->filter) ? $clear->formatStr($get->filter) : '');
        switch ($filter) {
            case '':
            case 'all':
                $query = "";
                break;
            case 'lock':
                $query = "WHERE doc_sectors.s_status = '0'";
                break;
            default:
                $query = "WHERE doc_sectors.s_category = '{$filter}'";
                break;
        }

        $sectors->setQuery("
            SELECT
                doc_sectors.s_status,
                doc_sectors.s_date,
                doc_sectors.s_hash,
                doc_sectors.s_title,
                doc_sectors.s_link,
                doc_sectors.s_icon,
                doc_sectors.s_icon,
                doc_sectors.s_category,
                doc_sectors.s_info,
                doc_category.c_hash,
                doc_category.c_title
            FROM
                doc_sectors
            INNER JOIN
                doc_category
            ON
                doc_sectors.s_category = doc_category.c_hash
                {$query}
            ORDER BY
                s_link
        ");
        if ($sectors->count()) {
            ?>
            <div class="container padding-lr-prop">
                <div class="align-right margin-top">
                    <div data-paginator=""></div>
                </div>
                <?php foreach ($sectors->result() as $value) { ?>
                    <div class="shadow margin-top pag-item">
                        <div class="bg-black padding-all-min font-large text-white">
                            <div class="margin-lr over-text">
                                <div class="icon-circle-small line-block vertical-middle"></div>
                                <div class="line-block">
                                    <a href="doc/<?= $value->s_link ?>">
                                        <?= $value->s_title ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div id="row-<?= $value->s_hash ?>" class="bg-light<?= $value->s_status == '0' ? '-red' : false ?>" style="padding: 5px">
                            <div class="break bg-white padding-all">
                                <div class="row-pad">
                                    <div class="col-quarter">
                                        <button class="btn-info button-block text-white shadow-on-hover" onclick="smStf.doc.openEdit('sector', '<?= $value->s_hash ?>')">
                                            <i class="icon-pencil5"></i> Editar
                                        </button>
                                        <div class="align-center padding-all">
                                            <img src="<?= (empty($value->s_icon) ? 'lib/image/sector-icon.png' : 'uploads/icons/' . $value->s_icon) ?>" alt="" class="img-default radius-circle" id="icon-<?= $value->s_hash ?>" style="max-width: 100px" onerror="this.src='lib/image/sector-icon.png'" />
                                        </div>
                                    </div>
                                    <div class="col-threequarter">
                                        <table class="tbl streak">
                                            <tr>
                                                <td>
                                                    <span class="bold">Status:</span>
                                                    <?= ($value->s_status == '1' ? "<span data-lock=\"span\" class=\"text-green\">ATIVO</span>" : "<span data-lock=\"span\" class=\"text-red\">INATIVO</span>") ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="bold">Criado em:</span>
                                                    <?= $clear->dateTime($value->s_date) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="bold">Categoria:</span>
                                                    <?= $value->c_title ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <button class="acc-button">Descrição</button>
                                        <div class="acc-container">
                                            <?= PostData::showPost($value->s_info) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="padding-all-min align-right">
                                <ul class="list-none">
                                    <li class="line-block padding-right-min">
                                        <a data-lock="button" class="text-black-hover cursor-pointer" onclick="smStf.doc.openLock('sector', '<?= $value->s_hash ?>');"><?= $value->s_status == 0 ? '<i class="icon-unlocked"></i> Desbloquear' : '<i class="icon-lock4"></i> Bloquear' ?></a>
                                    </li>
                                    <li class="line-block padding-right-min">
                                        <a class="text-black-hover cursor-pointer" onclick="smStf.doc.openMove('sector', '<?= $value->s_hash ?>', '<?= $value->s_category ?>');"><i class="icon-new-tab2"></i> Mover</a>
                                    </li>
                                    <li class="line-block padding-right-min">
                                        <a class="text-black-hover cursor-pointer" onclick="smStf.doc.openDel('sector', '<?= $value->s_hash ?>')"><i class="icon-bin2"></i> Apagar</a> &nbsp; 
                                    </li>
                                    <li class="line-block">
                                        <a class="text-black-hover cursor-pointer" onclick="smStf.doc.secIcon('<?= $value->s_hash ?>', '<?= $config->store->iconSize ?>');"><i class="icon-image3"></i> Ícone</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="" id="target-<?= $value->s_hash ?>">
                        <input type="hidden" name="hash" value="<?= $value->s_hash ?>" />
                    </form>
                <?php } ?>

                <div class="padding-all align-center">
                    <div data-paginator=""></div>
                </div>
            </div>

            <script>
                smTools.paginator.set('pag-item', <?= $config->rows->pag ?>, 'paginator');
                smTools.paginator.init(1);
                smTools.acc.init();
            </script>
            <?php
        } else if ($sectors->error()) {
            throw new ConstException($sectors->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException(null, ConstException::NOT_FOUND);
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo ("<script>smCore.go.href('./');</script>");
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../error/500.php');
            break;
        case ConstException::NOT_FOUND:
            include (__DIR__ . '/../../error/412.php');
            break;
    }
}
