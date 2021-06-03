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
    } else if ($session->admin < $config->admSector) {
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
            WHERE
                doc_sectors.s_title LIKE '%{$find}%'
            ORDER BY
                s_link
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
                $html .= '<div class="line-block"><a href="doc/' . $value->s_link . '">' . $value->s_title . '</a></div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div id="row-' . $value->s_hash . '" class="bg-light' . ($value->s_status == '0' ? '-red' : false) . '" style="padding: 5px">';
                $html .= '<div class="break bg-white padding-all">';
                $html .= '<div class="row-pad">';
                $html .= '<div class="col-quarter">';
                $html .= '<button class="btn-info button-block text-white shadow-on-hover" onclick="smStf.doc.openEdit(\'sector\', \'' . $value->s_hash . '\')"> <i class="icon-pencil5"></i> Editar </button>';
                $html .= '<div class="align-center padding-all">';
                $html .= '<img src="' . (empty($value->s_icon) ? 'lib/image/sector-icon.png' : 'uploads/icons/' . $value->s_icon) . '" alt="" class="img-default radius-circle" id="icon-' . $value->s_hash . '" style="max-width: 100px" onerror="this.src=\'lib/image/sector-icon.png\'" />';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="col-threequarter">';
                $html .= '<table class="tbl streak">';
                $html .= '<tr><td><span class="bold">Status:</span>' . ($value->s_status == '1' ? "<span data-lock=\"span\" class=\"text-green\">ATIVO</span>" : "<span data-lock=\"span\" class=\"text-red\">INATIVO</span>") . '</td></tr>';
                $html .= '<tr><td><span class="bold">Criado em:</span>' . $clear->dateTime($value->s_date) . '</td></tr>';
                $html .= '<tr><td><span class="bold">Categoria:</span>' . $value->c_title . '</td></tr>';
                $html .= '</table>';
                $html .= '<button class="acc-button">Descrição</button>';
                $html .= '<div class="acc-container">' . PostData::showPost($value->s_info) . '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="padding-all-min align-right">';
                $html .= '<ul class="list-none">';
                $html .= '<li class="line-block padding-right-min"> <a data-lock="button" class="text-black-hover cursor-pointer" onclick="smStf.doc.openLock(\'sector\', \'' . $value->s_hash . '\');">' . ($value->s_status == 0 ? '<i class="icon-unlocked"></i> Desbloquear' : '<i class="icon-lock4"></i> Bloquear') . '</a> </li>';
                $html .= '<li class="line-block padding-right-min"> <a class="text-black-hover cursor-pointer" onclick="smStf.doc.openMove(\'sector\', \'' . $value->s_hash . '\', \'' . $value->s_category . '\');"><i class="icon-new-tab2"></i> Mover</a> </li>';
                $html .= '<li class="line-block padding-right-min"> <a class="text-black-hover cursor-pointer" onclick="smStf.doc.openDel(\'sector\', \'' . $value->s_hash . '\')"><i class="icon-bin2"></i> Apagar</a> &nbsp; </li>';
                $html .= '<li class="line-block"> <a class="text-black-hover cursor-pointer" onclick="smStf.doc.secIcon(\'' . $value->s_hash . '\', \'' . $config->store->iconSize . '\');"><i class="icon-image3"></i> Ícone</a> </li>';
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<form method="POST" action="" id="target-' . $value->s_hash . '">';
                $html .= '<input type="hidden" name="hash" value="' . $value->s_hash . '" />';
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
            throw new ConstException('Nenhum título de setor encontrado com (<span class="bold">' . $find . '</span>)', ConstException::INVALID_POST);
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
