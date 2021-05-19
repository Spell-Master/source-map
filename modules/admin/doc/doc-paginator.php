<?php
require_once (__DIR__ . '/../../../system/config.php');
try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docSector) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        $sector = new Select();
        $clear = new StrClean();

        $sector->setQuery("
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
            ORDER BY
                s_link
        ");
        if ($sector->count()) {
            ?>
            <div class="container padding-lr-prop">
                <div class="align-right margin-top">
                    <div data-paginator=""></div>
                </div>
                <?php foreach ($sector->result() as $value) { ?>
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

                        <div class="bg-light<?= $value->s_status == '0' ? '-red' : false ?>" style="padding: 5px">
                            <div class="break bg-white padding-all">
                                <div class="row-pad">
                                    <div class="col-quarter">
                                        <button class="btn-info button-block text-white shadow-on-hover" onclick="smStf.doc.editSector('<?= $value->s_hash ?>')">
                                            <i class="icon-pencil5"></i> Editar
                                        </button>
                                        <div class="align-center padding-all">
                                            <img src="<?= (empty($value->s_icon) ? 'lib/image/sector-icon.png' : 'uploads/icons/' . $value->s_icon) ?>" alt="" alt="" class="img-default radius-circle" style="max-width: 100px" onerror="this.src='lib/image/sector-icon.png'"/>
                                        </div>
                                    </div>
                                    <div class="col-threequarter">
                                        <table class="tbl streak">
                                            <tr>
                                                <td>
                                                    <span class="bold">Status:</span>
                                                    <?= ($value->s_status == '1' ? "<span class=\"text-green\">ATIVO</span>" : "<span class=\"text-red\">INATIVO</span>") ?>
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
                                        <div class="spoiler">
                                            <div class="spoiler-read">Descrição</div>
                                            <div class="spoiler-body">
                                                <?= PostData::showPost($value->s_info) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="padding-all-min align-right">
                                <a class="text-black-hover cursor-pointer" onclick="smStf.doc.delSector('<?= $value->s_hash ?>')"><i class="icon-bin2"></i> Apagar</a> &nbsp; 
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="" id="del-<?= $value->s_hash ?>">
                        <input type="hidden" name="hash" value="<?= $value->s_hash ?>" />
                    </form>
                <?php } ?>

                <div class="padding-all align-center">
                    <div data-paginator=""></div>
                </div>
            </div>

            <script>
                smCore.spoiler();
                smTools.paginator.set('pag-item', <?= $config->rows->pag ?>, 'paginator');
                smTools.paginator.init(1);
            </script>
            <?php
        } else if ($sector->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
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
