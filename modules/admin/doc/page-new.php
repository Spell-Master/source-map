<?php
require_once (__DIR__ . '/../../../system/config.php');
try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docPage) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        ?>
        <div class="container padding-lr-prop fade-in">
            <form
                method="POST"
                action=""
                id="new-page"
                onsubmit="return smStf.doc.savePage([
                    '<?= $config->length->minPageTitle ?>',
                    '<?= $config->length->maxPageTitle ?>',
                    '<?= $config->length->minPageData ?>',
                    '<?= $config->length->maxPageData ?>'
                ], 'new')">
                <div class="row-pad">
                    <div class="col-twothird">
                        <p class="font-medium">Título</p>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="input-default"
                            maxlength="<?= $config->length->maxPageTitle ?>"
                            placeholder="Título da página"
                            />
                    </div>
                    <div class="col-third">
                        <div class="row">
                            <div class="col-threequarter col-fix">
                                <p class="font-medium">Setor</p>
                                <select name="sector" id="sector" class="select-options">
                                    <option value="">Selecionar..</option>
                                </select>
                            </div>
                            <div class="col-quarter col-fix">
                                <p class="font-medium align-center">Visível?</p>
                                <div class="box-y-50 vertical-wrap">
                                    <div class="relative">
                                        <input
                                            type="checkbox"
                                            name="view"
                                            id="view-page"
                                            class="checkmark"
                                            checked="" />
                                        <label for="view-page" class="checkmark-line"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-single">
                        <p class="font-medium">Conteúdo</p>
                        <div class="editor-area">
                            <?php SeoData::showProgress() ?>
                            <textarea id="editor-page" name="editor" class="hide"></textarea>
                        </div>
                        <div class="bg-light padding-all align-right text-white">
                            <button
                                type="submit"
                                class="btn-success shadow-on-hover"
                                title="Publicar Página"
                                >
                                <i class="icon-file-plus2"></i>
                            </button>
                            <button
                                type="button"
                                class="btn-info shadow-on-hover"
                                title="Pré visualizar"
                                onclick="smStf.pageAction.preview('new-page', 'doc')">
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
                    </div>
                </div>
            </form>
        </div>

        <script>
            if (smTools.check.isReady(MEMORY.secList)) {
                var $sector = document.getElementById('sector'), $option;

                MEMORY.secList.forEach(function (e) {
                    $option = document.createElement('option');
                    $option.value = e.h;
                    $option.innerText = e.t;
                    $sector.appendChild($option);
                });
                smTools.select.init();

                var loading = document.querySelector('div.load-local');
                smEditor.init('editor-page', '<?= ($session->admin >= $config->admin ? 'admin' : 'advance') ?>');
                CKEDITOR.instances['editor-page'].on('instanceReady', function (e) {
                    loading.parentNode.removeChild(loading);
                });
            } else {
                document.getElementById('page-load').innerHTML = null;
                smCore.modal.error('Falha ao carregar objeto global\n\
                <p class="font-small">Recarregue a página para tentar corrigir o problema</p>', true);
                console.error('Falha ao carregar objeto global secList');
            }
        </script>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo ("<script>smCore.go.reload();</script>");
            break;
    }
}
exit();
