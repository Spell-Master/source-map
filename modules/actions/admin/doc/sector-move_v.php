<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$valid = new StrValid();
$clear = new StrClean();
$select = new Select();
$update = new Update();

$category = (isset($post->move[0]) ? trim($post->move[0]) : false);
$sector = (isset($post->target) ? trim($post->target) : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admSector) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$category) {
        throw new ConstException('Não recebido dados de $_POST[\'move\'][]', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($category)) {
        throw new ConstException('$_POST[\'move\'][] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$sector) {
        throw new ConstException('Não recebido dados de $_POST[\'target\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($sector)) {
        throw new ConstException('$_POST[\'target\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $save = [
            'category' => $clear->formatStr($category),
            'sector' => $clear->formatStr($sector)
        ];

        $select->query("doc_category", "c_hash = :ch", "ch={$save['category']}");
        if ($select->count()) {
            $categoryData = $select->result()[0];

            $update->query(
                "doc_sectors",
                ['s_category' => $save['category']],
                "s_hash = :sh",
                "sh={$save['sector']}"
            );
            if ($update->count()) {
                ?>
                <script>
                    MEMORY.selectedIndex = 'all';
                    smStf.pageAction.cancel();
                    smTools.modal.close();
                    smTools.scroll.top();
                    smTools.ajax.send('paginator', 'modules/admin/doc/sector-paginator.php', false);
                    smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Setor movido para <?= $categoryData->c_title ?></p>', true);
                </script>
                <?php
            } else if ($update->error()) {
                throw new ConstException($update->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('No momento não é possível mover o setor'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Não encontrado dados da categoria selecionada'
            . '<p class="font-small">Tente recarregar a página para corrigir o problema</p>', ConstException::INVALID_POST);
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
