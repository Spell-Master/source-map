<?php
echo ("<script>sml.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require (__DIR__ . '/../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$len = new LenMaxMin();
$select = new Select();

$search = (isset($post->search) ? trim($post->search) : false);

try {
    if (!isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($config->enable->search == 'n') {
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
                doc_sectors.s_hash,
                doc_sectors.s_title,
                doc_sectors.s_link,
                doc_pages.p_status,
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
                doc_sectors.s_status = '1'
            AND
                doc_pages.p_status = '1'
            AND
                (doc_pages.p_title LIKE '%{$find}%' OR
                doc_pages.p_link LIKE '%{$find}%' OR
                doc_pages.p_text LIKE '%{$find}%')
            LIMIT
                100
        ");
        $count = $select->count();

        if ($count) {
            $html = '<div class="container padding-all-prop">';
            $html .= '<div class="alert-default">Exibindo (<span class="text-red bold">' . $count . '</span>)';
            $html .= ' resultado' . ($count > 1 ? "s" : null);
            $html .= ' para (<span class="text-green bold">' . $find . '</span>)</div>';
            $html .= '<div class="align-right margin-top"><div data-paginator=""></div></div>';
            foreach ($select->result() as $value) {
                $html .= '<div class="shadow margin-top pag-item">';
                $html .= '<div class="bg-black padding-all-min font-large text-white">';
                $html .= '<div class="margin-lr over-text">';
                $html .= '<div class="icon-circle-small line-block vertical-middle"></div>';
                $html .= '<div class="line-block">';
                $html .= '<a href="doc/' . $value->s_link . '/' . $value->p_link . '">' . $value->p_title . '</a>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="padding-all-min bg-light">';
                $html .= '<article class="padding-all bg-white">';
                $html .= '<p><span class="bold">Setor:</span> <a href="doc/' . $value->s_link . '" class="href-link">' . $value->s_title . '</a></p><hr />';
                $html .= SeoData::longText($value->p_text, $config->length->longStr);
                $html .= '</article>';
                $html .= '<div class="padding-all-min align-right">';
                $html .= '<a href="doc/' . $value->s_link . '/' . $value->p_link . '" class="href-link italic"> Acessar &nbsp; <i class="icon-bubble-quote"></i> </a>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '<div class="padding-all align-center"><div data-paginator=""></div></div>';
            $html .= '</div>'; // "container padding-all-prop"
            ?>
            <script>
                var $paginator = document.getElementById('target-action');
                $paginator.innerHTML = `<?= $html ?>`;
                document.getElementById('search').value = '';
                sml.paginator.set('pag-item', <?= $config->rows->pag ?>, 'target-action');
                sml.paginator.init(1);
                sml.modal.close();
                sml.scrollTop();
                smc.crumbs(['doc']);
                smc.notify('<i class="icon-bubble-notification icn-2x"></i><p><?= $count ?> resultado<?= ($count > 1 ? "s encontrados" : " encontrado") ?></p>', true);
                window.$itemOpen.forceClose();
                window.history.replaceState(null, null, 'doc');
            </script>
            <?php
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Nada encontrado com (<span class="bold">' . $find . '</span>)', ConstException::INVALID_POST);
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            include (__DIR__ . '/../../error/denied.php');
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../error/500.php');
            break;
        case ConstException::INVALID_POST:
            echo ("<script>smc.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
