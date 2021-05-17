<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);
$post = GlobalFilter::filterPost();

$valid = new StrValid();
$clear = new StrClean();
$delete = new Delete();

$hash = (isset($post->hash) ? $post->hash : false);
$app = (isset($post->hash) ? $post->app : false);

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
    else if (!$app) {
        throw new ConstException('Não recebido dados de $_POST[\'app\']', ConstException::SYSTEM_ERROR);
    } else if ($app !== 'css' && $app !== 'js') {
        throw new ConstException('$_POST[\'app\'] não é um modelo válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $delete->query("app_page", "a_hash = :ah", "ah={$clear->formatStr($hash)}");
        if ($delete->count()) {
            ?>
            <script>
                smStf.app.cancelAction();
                smTools.modal.close();
                smTools.scroll.top();
                smTools.ajax.send('paginator', 'modules/admin/app/paginator.php?reload=<?= $app ?>', false);
                smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Página Apagada</p>', true);
            </script>
            <?php
        } else if ($delete->error()) {
            throw new ConstException($delete->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Não é possível apagar a página'
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