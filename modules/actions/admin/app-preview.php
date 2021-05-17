<?php
require_once (__DIR__ . '/../../../system/config.php');
$post = GlobalFilter::filterPost();
$editor = PostData::savePost($post->editor);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admin) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$editor) {
        throw new ConstException('Não recebido dados de $_POST[\'editor\']', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        ?>
        <div class="container padding-all-prop">
            <h1><?= empty($post->title) ? 'Título' : htmlentities($post->title) ?></h1>
            <hr />
            <?= PostData::showPost($editor) ?>
        </div>
        <div class="bg-light">
            <div class="container padding-all align-right">
                <button
                    class="btn-info shadow-on-hover text-white"
                    onclick="smStf.app.cancelPreview()">
                    <i class="icon-minus-circle"></i>
                    Fechar Visualização
                </button>
            </div>
        </div>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo ("<script>smCore.go.reload();</script>");
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../error/500.php');
            break;
    }
}
exit();
