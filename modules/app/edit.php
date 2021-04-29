<?php
require_once (__DIR__ . '/../../system/config.php');
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
        <div class="fade-in" id="new-page">
            <h2 class="quicksand">Editar Página</h2>
            <hr />
            <form id="new-app">
                <div class="box-x-600 margin-auto padding-bottom">
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="input-default"
                        placeholder="Título da Página"
                        />
                </div>

                <div class="editor-area">
                    <textarea id="editor-page" name="editor" class="input-default"></textarea>
                </div>

                <input type="hidden" name="app" value="<?= $app ?>" />
            </form>

            <div class="bg-light padding-all align-right text-white">
                <button
                    class="btn-success shadow-on-hover"
                    title="Publicar Página"
                    onclick="newApp([
                                '<?= $config->length->minPageTitle ?>',
                                '<?= $config->length->maxPageTitle ?>',
                                '<?= $config->length->minPageData ?>',
                                '<?= $config->length->maxPageData ?>'
                            ])">
                    <i class="icon-file-plus2"></i>
                </button>
                <button
                    class="btn-info shadow-on-hover"
                    title="Pré visualizar"
                    onclick="sm_a.preview()">
                    <i class="icon-file-eye2"></i>
                </button>
                <button
                    class="btn-warning shadow-on-hover"
                    title="Cancelar"
                    onclick="sm_a.cancelNew()">
                    <i class="icon-file-minus2"></i>
                </button>
            </div>
        </div>
        <div id="preview-page"></div>

        <script>
            document.getElementById('title').value = decodeURIComponent(MEMORY.appTitle);
            document.getElementById('editor-page').value = decodeURIComponent(MEMORY.appContent);
            sm_e.init('editor-page', 'admin');
        </script>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo ("<script>smc.go.reload();</script>");
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../error/500.php');
            break;
    }
}