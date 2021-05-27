<?php
require_once (__DIR__ . '/../../../system/config.php');
try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docPage) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        $get = GlobalFilter::filterGet();
        $clear = new StrClean();
        $pages = new Select();

        $filter = (isset($get->filter) ? $clear->formatStr($get->filter) : '');
        switch ($filter) {
            case '':
            case 'all':
                $query = "";
                break;
            case 'lock':
                $query = "WHERE p.p_status = '0' OR s.s_status = '0'";
                break;
            default:
                $query = "WHERE p.p_sector = '{$filter}'";
                break;
        }

        $pages->setQuery("
            SELECT
                p.p_status, p.p_hash, p.p_title, p.p_link, p.p_sector, p.p_text, p.p_created, p.p_date, p.p_last_date,
                s.s_status, s.s_hash, s.s_title, s.s_link,
                u.u_hash, u.u_level, u.u_name, u.u_link, u.u_photo
            FROM
                doc_pages AS p
            INNER JOIN
                doc_sectors AS s
            ON
                p.p_sector = s.s_hash
            INNER JOIN
                users AS u
            ON
                p.p_created = u.u_hash
            {$query}
            ORDER BY
                p.p_link ASC,
                p.p_date ASC,
                s.s_title ASC
            LIMIT 100
        ");
        if ($pages->count()) {
            ?>
            <div class="container padding-lr-prop">
                <div class="align-right margin-top">
                    <div data-paginator=""></div>
                </div>
                <?php foreach ($pages->result() as $value) { ?>
                    <div class="shadow margin-top pag-item">
                        <div class="bg-black padding-all-min font-large text-white">
                            <div class="margin-lr over-text">
                                <div class="icon-circle-small line-block vertical-middle"></div>
                                <div class="line-block">
                                    <a href="doc/<?= $value->s_link ?>/<?= $value->p_link ?>" target="_blank">
                                        <?= $value->p_title ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div id="row-<?= $value->p_hash ?>" class="bg-light<?= ($value->p_status == '0' || $value->s_status == '0' ? '-red' : false) ?>" style="padding: 5px">
                            <div class="bg-white">
                                <div class="row-pad">
                                    <div class="col-quarter float-right">
                                        <button class="btn-info button-block text-white shadow-on-hover" onclick="smStf.doc.openEdit('page', '<?= $value->p_hash ?>');">
                                            &nbsp; <i class="icon-pencil5"></i> Editar &nbsp;
                                        </button>
                                    </div>
                                    <div class="col-threequarter">
                                        <div class="row-pad">
                                            <div class="col-quarter col-fix">
                                                <div class="align-center">
                                                    <p><span class="bold">Autor:</span>
                                                        <a href="users/<?= $value->u_link ?>" target="_blank" class="href-link">
                                                            <?= $value->u_name ?>
                                                        </a>
                                                    </p>
                                                    (<?= $clear->dateTime($value->p_date) ?>)
                                                    <img src="<?= (empty($value->u_photo) ? 'lib/image/profile.png' : 'uploads/photos/' . $value->u_photo) ?>" class="img-default radius-circle" style="max-width: 100px" onerror="this.src='lib/image/sector-icon.png'" />
                                                </div>
                                            </div>
                                            <div class="col-threequarter col-fix">
                                                <table class="tbl streak">
                                                    <tr>
                                                        <td>
                                                            <span class="bold">Título:</span>
                                                            <?= $value->p_title ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="bold">Status:</span>
                                                            <?php
                                                            if ($value->p_status == '0') {
                                                                echo ("<span class=\"text-red\">OCULTADA</span>");
                                                            } else if ($value->s_status == '0') {
                                                                echo ("<span class=\"text-red\">OCULTADA (SETOR INATIVO)</span>");
                                                            } else {
                                                                echo ("<span class=\"text-green\">VISÍVEL</span>");
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="bold">Setor:</span>
                                                            <?= $value->s_title ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span class="bold">Versão:</span>
                                                            <?= $clear->dateTime(strtotime($value->p_last_date) > strtotime($value->p_date) ? $value->p_last_date : $value->p_date) ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr style="margin: 0 2%" />
                                <article class="padding-all bg-white">
                                    <?= SeoData::longText($value->p_text, $config->length->longStr) ?>
                                </article>
                            </div>
                            <div class="padding-all-min align-right">
                                <ul class="list-none">
                                    <li class="line-block padding-right-min">
                                        <a data-lock="" class="text-black-hover cursor-pointer" onclick="smStf.doc.openLock('page', '<?= $value->p_hash ?>');"><?= $value->p_status == 0 ? '<i class="icon-unlocked"></i> Desbloquear' : '<i class="icon-lock4"></i> Bloquear' ?></a>
                                    </li>
                                    <li class="line-block padding-right-min">
                                        <a class="text-black-hover cursor-pointer" onclick="smStf.doc.openMove('page', '<?= $value->p_hash ?>', '<?= $value->p_sector ?>');"><i class="icon-new-tab2"></i> Mover</a>
                                    </li>
                                    <li class="line-block padding-right-min">
                                        <a class="text-black-hover cursor-pointer" onclick="smStf.doc.openDel('page', '<?= $value->p_hash ?>')"><i class="icon-bin2"></i> Apagar</a> &nbsp; 
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="" id="target-<?= $value->p_hash ?>">
                        <input type="hidden" name="hash" value="<?= $value->p_hash ?>" />
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
        } else if ($pages->error()) {
            throw new ConstException($pages->error(), ConstException::SYSTEM_ERROR);
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
