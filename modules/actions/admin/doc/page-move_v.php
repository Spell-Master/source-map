<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$valid = new StrValid();
$clear = new StrClean();
$select = new Select();
$update = new Update();
$selectB = clone $select;
$updateB = clone $update;

$sector = (isset($post->move[0]) ? trim($post->move[0]) : false);
$page = (isset($post->target) ? trim($post->target) : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admPage) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$sector) {
        throw new ConstException('Não recebido dados de $_POST[\'move\'][]', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($sector)) {
        throw new ConstException('$_POST[\'move\'][] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$page) {
        throw new ConstException('Não recebido dados de $_POST[\'target\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($page)) {
        throw new ConstException('$_POST[\'target\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $save = [
            'sector' => $clear->formatStr($sector),
            'page' => $clear->formatStr($page)
        ];

        $select->query("doc_pages", "p_hash = :ph", "ph={$save['page']}");
        $selectB->query("doc_sectors", "s_hash = :sh", "sh={$save['sector']}");

        if ($select->error() || $selectB->error()) {
            $error = "";
            $error .= (($select->error() !== null) ? '<p>' . $select->error() . '</p>' : null);
            $error .= (($selectB->error() !== null) ? '<p>' . $selectB->error() . '</p>' : null);
            throw new ConstException($error, ConstException::SYSTEM_ERROR);
        } else if (!$select->count()) {
            throw new ConstException('No momento não é possível mover a página'
            . '<p class=font-small>Tente outra página ou tente novamente mais tarde</p>', ConstException::INVALID_POST);
        } else if (!$selectB->count()) {
            throw new ConstException('No momento não é possível mover a página'
            . '<p class=font-small>Tente outro setor ou tente novamente mais tarde</p>', ConstException::INVALID_POST);
        } else {
            $pageData = $select->result()[0];
            $sectorData = $selectB->result()[0];

            // Atualizara página
            $update->query(
                "doc_pages",
                ['p_sector' => $sectorData->s_hash],
                "p_hash = :ph",
                "ph={$pageData->p_hash}"
            );

            // Atualizar links das atividades vinculadas a página
            $updateB->query(
                "users_activity",
                ['ua_link' => $sectorData->s_link . '/' . $pageData->p_link],
                "ua_bound = :ub",
                "ub={$pageData->p_hash}"
            );

            if ($update->count()) {
                ?>
                <script>
                    MEMORY.selectedIndex = 'all';
                    smStf.pageAction.cancel();
                    smTools.modal.close();
                    smTools.scroll.top();
                    smTools.ajax.send('paginator', 'modules/admin/doc/page-paginator.php', false);
                    smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Página movida para <?= $sectorData->s_title ?></p>', true);
                </script>
                <?php
            } else if ($update->error()) {
                throw new ConstException($update->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('No momento não é possível mover a página'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
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
