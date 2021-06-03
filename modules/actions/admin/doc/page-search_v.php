<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$len = new LenMaxMin();
$select = new Select();
$clear = new StrClean();

$search = (isset($post->search) ? trim($post->search) : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admPage) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$search) {
        throw new ConstException('Não recebido dados de $_POST[\'search\']', ConstException::SYSTEM_ERROR);
    } else if ($len->strLen($search, $config->length->minSearch, $config->length->maxSearch, '$_POST[\'search\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (!preg_match('/^([a-zà-ú0-9]+)$/i', $search)) {
        throw new ConstException('$_POST[\'search\'] não é um formato válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $find = htmlentities($search);

        $select->setQuery("
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
            WHERE
                p.p_title LIKE '%{$find}%'
                OR
                p.p_text LIKE '%{$find}%'
                OR
                u.u_name LIKE '%{$find}%'
            ORDER BY
                p.p_link ASC,
                p.p_date ASC,
                s.s_title ASC
            LIMIT 100
        ");
        $count = $select->count();
        if ($count) {
            $html = '<div class="container padding-all-prop">';
            $html .= '<div class="align-right margin-top"><div data-paginator=""></div></div>';
            foreach ($select->result() as $value) {
                $html .= '<div class="shadow margin-top pag-item">';
                $html .= '<div class="bg-black padding-all-min font-large text-white">';
                $html .= '<div class="margin-lr over-text">';
                $html .= '<div class="icon-circle-small line-block vertical-middle"></div>';
                $html .= '<div class="line-block"> <a href="doc/' . $value->s_link . '/' . $value->p_link . '" target="_blank"> ' . $value->p_title . ' </a></div></div>';
                $html .= '</div>';
                $html .= '<div id="row-' . $value->p_hash . '" class="bg-light' . ($value->p_status == '0' || $value->s_status == '0' ? '-red' : false) . '" style="padding: 5px">';
                $html .= '<div class="bg-white">';
                $html .= '<div class="row-pad">';
                $html .= '<div class="col-quarter float-right">';
                $html .= '<button class="btn-info button-block text-white shadow-on-hover" onclick="smStf.doc.openEdit(\'page\', \'' . $value->p_hash . '\');"> &nbsp; <i class="icon-pencil5"></i> Editar &nbsp; </button>';
                $html .= '</div>';
                $html .= '<div class="col-threequarter">';
                $html .= '<div class="row-pad">';
                $html .= '<div class="col-quarter col-fix">';
                $html .= '<div class="align-center">';
                $html .= '<p><span class="bold">Autor:</span> <a href="users/' . $value->u_link . '" target="_blank" class="href-link">' . $value->u_name . '</a></p>';
                $html .= '<img src="' . (empty($value->u_photo) ? 'lib/image/profile.png' : 'uploads/photos/' . $value->u_photo) . '" class="img-default radius-circle" style="max-width: 100px" onerror="this.src=\'lib/image/sector-icon.png\'" />';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="col-threequarter col-fix">';
                $html .= '<table class="tbl streak">';
                $html .= '<tr><td><span class="bold">Título:</span> ' . $value->p_title . '</td></tr>';
                $html .= '<tr><td>';
                $html .= '<span class="bold">Status:</span> ';
                if ($value->p_status == '0') {
                    $html .= '<span data-lock="span" class="text-red">OCULTADA</span>';
                } else if ($value->s_status == '0') {
                    $html .= '<span data-lock="span" class="text-red">OCULTADA (SETOR INATIVO)</span>';
                } else {
                    $html .= '<span data-lock="span" class="text-green">VISÍVEL</span>';
                }
                $html .= '</td></tr>';
                $html .= '<tr><td><span class="bold">Setor:</span> ' . $value->s_title . ' </td></tr>';
                $html .= '<tr><td><span class="bold">Criada em:</span> ' . $clear->dateTime($value->p_date) . '</td></tr>';
                if (strtotime($value->p_last_date) > strtotime($value->p_date)) {
                    $html .= '<tr><td><span class="bold">Versão:</span> ' . $clear->dateTime($value->p_last_date) . '</td></tr>';
                }
                $html .= '</table>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<hr style="margin: 0 2%" />';
                $html .= '<article class="padding-all bg-white">' . SeoData::longText($value->p_text, $config->length->longStr) . '</article>';
                $html .= '</div>';
                $html .= '<div class="padding-all-min align-right">';
                $html .= '<ul class="list-none">';
                if ($value->s_status == '1') {
                    $html .= '<li class="line-block padding-right-min"><a data-lock="button" class="text-black-hover cursor-pointer" onclick="smStf.doc.openLock(\'page\', \'' . $value->p_hash . '\');">' . ($value->p_status == 0 ? '<i class="icon-unlocked"></i> Desocultar' : '<i class="icon-lock4"></i> Ocultar') . '</a></li>';
                }
                $html .= '<li class="line-block padding-right-min"><a class="text-black-hover cursor-pointer" onclick="smStf.doc.openMove(\'page\', \'' . $value->p_hash . '\', \'' . $value->p_sector . '\');"><i class="icon-new-tab2"></i> Mover</a></li>';
                $html .= '<li class="line-block padding-right-min"><a class="text-black-hover cursor-pointer" onclick="smStf.doc.openDel(\'page\', \'' . $value->p_hash . '\')"><i class="icon-bin2"></i> Apagar</a> &nbsp; </li>';
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<form method="POST" action="" id="target-' . $value->p_hash . '">';
                $html .= '<input type="hidden" name="hash" value="' . $value->p_hash . '" />';
                $html .= '</form>';
            }
            $html .= '<div class="padding-all align-center"><div data-paginator=""></div></div>';
            $html .= '</div>';
            ?>
            <script>
                var $paginator = document.getElementById('paginator');
                MEMORY.selectedIndex = 'all';
                $paginator.innerHTML = `<?= $html ?>`;
                document.getElementById('search').value = '';
                smTools.paginator.set('pag-item', <?= $config->rows->pag ?>, 'paginator');
                smTools.paginator.init(1);
                smTools.acc.init();
                smTools.modal.close();
                smTools.scroll.top();
                smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p><?= $count ?> resultado<?= ($count > 1 ? "s encontrados" : " encontrado") ?></p>', true);
            </script>
            <?php
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Nenhuma página encontrada com (<span class="bold">' . $find . '</span>)', ConstException::INVALID_POST);
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo ("<script>smCore.go.reload();</script>");
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../../error/500.php');
            break;
        case ConstException::INVALID_POST:
            echo ("<script>smCore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
