<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$len = new LenMaxMin();
$valid = new StrValid();
$select = new Select();
$update = new Update();
$updateB = clone $update;

$title = (isset($post->title) ? trim($post->title) : false);
$order = (isset($post->order) ? $post->order : false);
$hash = (isset($post->hash) ? $post->hash : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docCategory) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$title) {
        throw new ConstException('Não recebido dados de $_POST[\'title\']', ConstException::SYSTEM_ERROR);
    } else if ($len->strLen($title, $config->length->minPageTitle, $config->length->maxPageTitle, '$_POST[\'title\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (!preg_match('/^([a-zA-Z À-ú 0-9 _ . -]+)$/i', $title)) {
        throw new ConstException('$_POST[\'title\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$order) {
        throw new ConstException('Não recebido dados de $_POST[\'order\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->intCheck($order)) {
        throw new ConstException('$_POST[\'order\'] não é um caractere numérico', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$hash) {
        throw new ConstException('Não recebido dados de $_POST[\'hash\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($order)) {
        throw new ConstException('$_POST[\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $categHash = htmlentities($hash);
        $cTitle = 0; // Contagem de títulos duplicados
        $newOrder = false; // Atualizar outra categoria caso a ordem seja alterada
        $save = [
            'title' => htmlentities($title),
            'order' => (int) $order
        ];

        $select->query("doc_category");
        if ($select->count()) {
            foreach ($select->result() as $key => $value) {
                // Identificar a categoria
                if ($value->c_hash == $categHash) {
                    $categoryData = $select->result()[$key];
                }
                // Identificar título duplicado
                if ($value->c_title == $save['title'] && $value->c_hash != $categHash) {
                    $cTitle++;
                }
                // Identificar outra categoria com a mesma ordem
                if ($value->c_order == $save['order'] && $value->c_hash != $categHash) {
                    $newOrder = $select->result()[$key];
                }
            }

            if (!isset($categoryData)) {
                throw new ConstException('Não foi possível localizar a categoria para edição'
                . '<p class="font-small">Redirecionando...</p>', ConstException::NOT_FOUND);
            } else if ($cTitle >= 1) {
                throw new ConstException('Já existe uma categoria com o mesmo título</p>', ConstException::INVALID_POST);
            } else {
                $update->query(
                    "doc_category",
                    ['c_title' => $save['title'], 'c_order' => $save['order']],
                    "c_hash = :ch",
                    "ch={$categHash}"
                );

                if ($newOrder) {
                    $updateB->query(
                        "doc_category",
                        ['c_order' => $categoryData->c_order],
                        "c_hash = :ch",
                        "ch={$newOrder->c_hash}"
                    );
                }

                if ($update->error() || $updateB->error()) {
                    $error = "";
                    $error .= (($update->error() !== null) ? '<p>' . $update->error() . '</p>' : null);
                    $error .= (($updateB->error() !== null) ? '<p>' . $updateB->error() . '</p>' : null);
                    throw new ConstException($error, ConstException::SYSTEM_ERROR);
                } else if ($update->count()) {
                    ?>
                    <script>
                        smStf.pageAction.cancel();
                        smTools.modal.close();
                        smTools.scroll.top();
                        smTools.ajax.send('paginator', 'modules/admin/doc/categ-paginator.php?reload=true', false);
                        smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Categoria Editada</p>', true);
                    </script>
                    <?php
                } else {
                    throw new ConstException('No momento não foi possível salvar a categoria'
                    . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
                }
            }
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Não foi possível localizar a categoria para edição'
            . '<p class="font-small">Redirecionando...</p>', ConstException::NOT_FOUND);
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
exit();
