<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);
$post = GlobalFilter::filterPost();

$valid = new StrValid();
$select = new Select();
$delete = new Delete();
$update = new Update();
$selectB = clone $select;

$hash = (isset($post->hash) ? $post->hash : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admin) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$hash) {
        throw new ConstException('Não recebido dados de $_POST[\'hash\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($hash)) {
        throw new ConstException('$_POST[\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $cHash = htmlentities($hash);
        $newOrder = 0;

        $select->query("doc_category");
        $selectB->query("doc_sectors", "s_category = :sc", "sc={$cHash}");

        if ($select->error() || $selectB->error()) {
            $error = "";
            $error .= (($select->error() !== null) ? '<p>' . $select->error() . '</p>' : null);
            $error .= (($selectB->error() !== null) ? '<p>' . $selectB->error() . '</p>' : null);
            throw new ConstException($error, ConstException::SYSTEM_ERROR);
        }
        if (!$select->count()) {
            throw new ConstException('Não foi possível localizar a categoria selecionada', ConstException::NOT_FOUND);
        } else if ($selectB->count()) {
            throw new ConstException('Não é possível apagar a categoria enquanto houver setores anexados a ela'
            . '<p class="font-small">Os setores devem ser removidos da categoria para poder apagar-la</p>', ConstException::INVALID_POST);
        } else {
            $categoryData = $select->result();

            foreach ($categoryData as $key => $del) {
                if ($del->c_hash == $cHash) {
                    $newOrder = (int) $del->c_order;
                    $delete->query("doc_category", "c_hash=:ch", "ch={$del->c_hash}");
                    unset($categoryData[$key]);
                }
            }

            if ($delete->count()) {
                foreach ($categoryData as $upd) {
                    $loop = (int) $upd->c_order;
                    if ($loop > $newOrder) {
                        $order = ((int) $upd->c_order -= 1);
                        $update->query("doc_category", ['c_order' => $order], "c_hash=:ch", "ch={$upd->c_hash}");
                    }
                }
                ?>
                <script>
                    smStf.pageAction.cancel();
                    smTools.modal.close();
                    smTools.scroll.top();
                    smTools.ajax.send('paginator', 'modules/admin/doc/categ-paginator.php?reload=true', false);
                    smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Categoria Apagada</p>', true);
                </script>
                <?php
            } else if ($delete->error()) {
                throw new ConstException($delete->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('Não é possível apagar a categoria'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo("<script>smCore.go.reload();</script>");
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../../error/500.php');
            break;
        case ConstException::INVALID_POST:
            echo ("<script>smCore.modal.error('{$e->getMessage()}', false);</script>");
            break;
        case ConstException::NOT_FOUND:
            ?>
            <script>
                smCore.modal.error('<?= $e->getMessage() ?>', false);
                smTools.modal.hiddenX();
                setTimeout(function () {
                    smCore.go.reload();
                }, <?= (int) $config->length->reload ?>000);
            </script>
            <?php
            break;
    }
}
