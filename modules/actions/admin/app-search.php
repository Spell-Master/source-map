<?php
require_once (__DIR__ . '/../../../system/function/Translate.php');
$len = new LenMaxMin();

$search = (isset($post->search) ? trim($post->search) : false);
$app = (isset($post->app) ? $post->app : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admin) {
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
    else if (!$app) {
        throw new ConstException('Não recebido dados de $_POST[\'app\']', ConstException::SYSTEM_ERROR);
    } else if ($app !== 'css' && $app !== 'js') {
        throw new ConstException('$_POST[\'app\'] não é um modelo válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $find = [
            'search' => htmlentities($search),
            'app' => htmlentities($app)
        ];
        $select = new Select();
        $clear = new StrClean();

        $select->setQuery("
            SELECT
            *
            FROM
                app_page
            WHERE
                (a_title LIKE '%{$find['search']}%' OR
                a_link LIKE '%{$find['search']}%' OR
                a_text LIKE '%{$find['search']}%')
            AND
                a_key = '{$find['app']}'
            LIMIT
                100
        ");

        $count = $select->count();
        if ($count) {
            $html = '<div class="container padding-lr-prop">';
            $html .= '<div class="alert-default">Exibindo (<span class="text-red bold">' . $count . '</span>)';
            $html .= ' resultado' . ($count > 1 ? "s" : null);
            $html .= ' para (<span class="text-green bold">' . $find['search'] . '</span>)</div>';

            $html .= '<div class="align-right margin-top"><div data-paginator=""></div></div>';
            foreach ($select->result() as $value) {
                $html .= '<div class="shadow margin-top pag-item">';
                $html .= '<div class="bg-black padding-all-min font-large text-white">';
                $html .= '<div class="margin-lr over-text">';
                $html .= '<div class="icon-circle-small line-block vertical-middle"></div>';
                $html .= '<div class="line-block">' . $value->a_title . '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="padding-all-min bg-light">';
                $html .= '<div class="bg-light" style="padding: 5px">';
                $html .= '<div class="bg-white">';
                $html .= '<div class="row-pad">';
                $html .= '<div class="col-s-half float-right align-right">';
                $html .= '<button class="btn-info text-white maximize-min shadow-on-hover" onclick="sm_stf.app.editPage(\'' . $value->a_hash . '\')"> &nbsp; <i class="icon-pencil5"></i> Editar &nbsp; </button>';
                $html .= '</div>';
                $html .= '<div class="col-s-half">';
                $html .= '<table>';
                $html .= '<tr>';
                $html .= '<td><span class="bold">Grupo:</span></td>';
                $html .= '<td class="padding-left">' . strtoupper($value->a_key) . ' Padrão</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td><span class="bold">Tamanho:</span></td>';
                $html .= '<td class="padding-left">' . sizeName(strlen($value->a_content)) . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td><span class="bold">Data de Criação:</span></td>';
                $html .= '<td class="padding-left">' . $clear->dateTime($value->a_date) . '</td>';
                $html .= '</tr>';
                if ($value->a_version) {
                    $html .= '<tr>';
                    $html .= '<td><span class="bold">Versão:</span></td>';
                    $html .= '<td class="padding-left">' . $clear->dateTime($value->a_version) . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<hr style="margin: 0 2%" />';
                $html .= '<article class="padding-all bg-white">' . SeoData::longText($value->a_text, $config->length->longStr) . '</article>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="padding-all-min align-right">';
                $html .= '<a class="text-black-hover cursor-pointer" onclick="sm_stf.app.delPage(\'' . $value->a_hash . '\')"><i class="icon-bin2"></i> Apagar</a> &nbsp; ';
                $html .= '<a href="' . $value->a_key . '-padrao/' . $value->a_link . '" target="_blank" class="href-link"><i class="icon-earth"></i> Acessar</a>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<form method="POST" action="" id="del-' . $value->a_hash . '">';
                $html .= '<input type="hidden" name="hash" value="' . $value->a_hash . '" />';
                $html .= '<input type="hidden" name="app" value="' . $value->a_key . '" />';
                $html .= '</form>';
            }
            $html .= '<div class="padding-all align-center"><div data-paginator=""></div></div>';
            $html .= '</div>';
            ?>
            <script>
                var $paginator = document.getElementById('paginator');
                $paginator.innerHTML = `<?= $html ?>`;
                if ($paginator.classList.contains('hide')) {
                    document.getElementById('page-action').innerHTML = null;
                    document.getElementById('page-preview').innerHTML = null;
                    document.getElementById('paginator').classList.remove('hide');
                }
                document.getElementById('search').value = '';
                sml.paginator.set('pag-item', <?= $config->rows->pag ?>);
                sml.paginator.init(1);
                sml.modal.close();
                sml.scrollTop();
                smc.notify('<i class="icon-bubble-notification icn-2x"></i><p><?= $count ?> resultado<?= ($count > 1 ? "s encontrados" : " encontrado") ?></p>', true);
                window.$itemOpen.forceClose();
            </script>
            <?php
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Nada encontrado com (<span class="bold">' . $find['search'] . '</span>)', ConstException::INVALID_POST);
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo("<script>smc.go.reload();</script>");
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
