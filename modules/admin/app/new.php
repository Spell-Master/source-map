<?php
require_once (__DIR__ . '/../../../system/config.php');
$get = GlobalFilter::filterGet();
$app = ($get->app ? $get->app : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admin) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$app) {
        throw new ConstException('Não recebido dados de $_GET[\'app\']', ConstException::SYSTEM_ERROR);
    } else if ($app !== 'css' && $app !== 'js') {
        throw new ConstException('$_GET[\'app\'] não é um modelo válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        ?>
        <div class="container padding-lr-prop fade-in">
            <form
                method="POST"
                action=""
                id="new-app"
                onsubmit="return smStf.app.save([
                    '<?= $config->length->minPageTitle ?>',
                    '<?= $config->length->maxPageTitle ?>',
                    '<?= $config->length->minPageData ?>',
                    '<?= $config->length->maxPageData ?>'
                ], 'new')">

                <p class="list margin-left font-medium">Título</p>
                <input
                    type="text"
                    name="title"
                    id="title"
                    class="input-default"
                    placeholder="Título da Página"
                    />

                <p class="list margin-left font-medium">Conteúdo</p>
                <div class="editor-area">
                    <?php SeoData::showProgress() ?>
                    <textarea id="editor-page" name="editor" class="hide"></textarea>
                </div>

                <input type="hidden" name="app" value="<?= $app ?>" />

                <div class="bg-light padding-all align-right text-white">
                    <button
                        type="submit"
                        class="btn-success shadow-on-hover"
                        title="Publicar Página"
                        onclick="">
                        <i class="icon-file-plus2"></i>
                    </button>
                    <button
                        type="button"
                        class="btn-info shadow-on-hover"
                        title="Pré visualizar"
                        onclick="smStf.app.pageAction.preview('new-app', 'app')">
                        <i class="icon-file-eye2"></i>
                    </button>
                    <button
                        type="button"
                        class="btn-warning shadow-on-hover"
                        title="Cancelar"
                        onclick="smStf.pageAction.cancel()">
                        <i class="icon-file-minus2"></i>
                    </button>
                </div>
            </form>
        </div>

        <script>
            var loading = document.querySelector('div.load-local');
            smEditor.init('editor-page', 'admin');
            CKEDITOR.instances['editor-page'].on('instanceReady', function (e) {
                loading.parentNode.removeChild(loading);
            });
        </script>
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
