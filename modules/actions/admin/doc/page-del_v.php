<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$valid = new StrValid();
$delete = new Delete();
$clear = new StrClean();

$hash = (isset($post->hash) ? $post->hash : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admPage) {
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
        $delete->query("doc_pages", "p_hash = :ph", "ph={$clear->formatStr($hash)}");
        if ($delete->count()) {
            ?>
            <script>
                MEMORY.selectedIndex = 'all';
                smStf.pageAction.cancel();
                smTools.modal.close();
                smTools.scroll.top();
                smTools.ajax.send('paginator', 'modules/admin/doc/page-paginator.php', false);
                smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Página Apagada</p>', true);
            </script>
            <?php
        } else if ($delete->error()) {
            throw new ConstException($delete->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('No momento não é possível apagar a página'
            . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
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
    }
}
exit();
