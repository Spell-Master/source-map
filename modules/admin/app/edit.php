<?php
require_once (__DIR__ . '/../../../system/config.php');
$get = GlobalFilter::filterGet();
$valid = new StrValid();
$clear = new StrClean();
$select = new Select();

$hash = ($get->hash ? $get->hash : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admin) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$hash) {
        throw new ConstException('Não recebido dados de $_GET[\'hash\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($hash)) {
        throw new ConstException('$_GET[\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $select->query(
            "app_page",
            "a_hash = :ah",
            "ah={$clear->formatStr($hash)}"
        );
        if ($select->count()) {
            $pageData = $select->result()[0];
            ?>
            <div class="container padding-lr-prop fade-in">
                <form
                    method="POST"
                    action=""
                    id="edit-app"
                    onsubmit="return smStf.app.save([
                        '<?= $config->length->minPageTitle ?>',
                        '<?= $config->length->maxPageTitle ?>',
                        '<?= $config->length->minPageData ?>',
                        '<?= $config->length->maxPageData ?>'
                    ], 'edit')">

                    <p class="list margin-left font-medium">Título</p>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="input-default"
                        placeholder="Título da Página"
                        value="<?= $pageData->a_title ?>"
                        />

                    <p class="list margin-left font-medium">Conteúdo</p>
                    <div class="editor-area">
                        <?php SeoData::showProgress() ?>
                        <textarea id="editor-page" name="editor" class="hide"><?= $pageData->a_content ?></textarea>
                    </div>

                    <input type="hidden" name="hash" value="<?= $pageData->a_hash ?>" />
                    <input type="hidden" name="app" value="<?= $pageData->a_key ?>" />

                    <div class="bg-light padding-all align-right text-white">
                        <button
                            type="submit"
                            class="btn-success shadow-on-hover"
                            title="Editar Página"
                            onclick="">
                            <i class="icon-file-plus2"></i>
                        </button>
                        <button
                            type="button"
                            class="btn-info shadow-on-hover"
                            title="Pré visualizar"
                            onclick="smStf.app.previewPage('edit')">
                            <i class="icon-file-eye2"></i>
                        </button>
                        <button
                            type="button"
                            class="btn-warning shadow-on-hover"
                            title="Cancelar"
                            onclick="smStf.app.cancelAction()">
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
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException(null, ConstException::NOT_FOUND);
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
            include (__DIR__ . '/../../error/500.php');
            break;
        case ConstException::NOT_FOUND:
            include (__DIR__ . '/../../error/412.php');
            break;
    }
}
