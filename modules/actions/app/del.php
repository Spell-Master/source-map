<?php
$valid = new StrValid();
$clear = new StrClean();
$select = new Select();
$delete = new Delete();

$hash = (isset($post->hash) ? $post->hash : false);
$app = (isset($post->app) ? $post->app : false);

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
        $pageHash = $clear->formatStr($hash);

        $select->query("app_page", "a_hash = :ah", "ah={$pageHash}");
        if ($select->count()) {
            $delete->query("app_page", "a_hash = :ah", "ah={$pageHash}");
            if ($delete->count()) {
                ?>
                <div class="align-center padding-all">
                    <?php SeoData::showProgress() ?>
                    <p class="font-medium">Página apagada com sucesso</p>
                    <p class="font-small">Redirecionando...</p>
                </div>
                <script>
                    setTimeout(function () {
                        smc.go.href('<?= $app ?>-padrao');
                    }, <?= $config->length->reload ?>000);
                </script>
                <?php
            } else if ($delete->error()) {
                throw new ConstException($delete->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('No momento não é possível apagar a página'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        } else if ($delete->error()) {
            throw new ConstException($delete->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Não foi possível localizar a página para apagar'
            . '<p class="font-small">Redirecionando...</p>', ConstException::MISC_RETURN);
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
        case ConstException::MISC_RETURN:
            ?>
            <div class="text-red align-center padding-all">
                <div><i class="icon-warning icn-4x"></i></div>
                    <?= $e->getMessage() ?>
            </div>
            <script>
                sml.modal.open('Inválido', false);
                setTimeout(function () {
                    smc.go.href('<?= $app ?>-padrao');
                }, <?= (int) $config->length->reload ?>000);
            </script>
            <?php
            break;
    }
}
