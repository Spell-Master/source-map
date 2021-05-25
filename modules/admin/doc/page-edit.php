<?php
require_once (__DIR__ . '/../../../system/config.php');
$get = GlobalFilter::filterGet();
$valid = new StrValid();


$hash = ($get->hash ? $get->hash : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docPage) {
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
        $select = new Select();
        $clear = new StrClean();
        $select->query("doc_pages", "p_hash = :ph", "ph={$clear->formatStr($hash)}");
        if ($select->count()) {
            $pageData = $select->result()[0];
            ?>
            <div class="container padding-lr-prop fade-in">
                <form
                    method="POST"
                    action=""
                    id="edit-page"
                    onsubmit="return smStf.doc.savePage([
                        '<?= $config->length->minPageTitle ?>',
                        '<?= $config->length->maxPageTitle ?>',
                        '<?= $config->length->minPageData ?>',
                        '<?= $config->length->maxPageData ?>'
                    ], 'edit')">
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
                                value="<?= $pageData->p_title ?>"
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
                                                <?= ($pageData->p_status == '1' ? 'checked=""' : null) ?>
                                                />
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
                                <textarea
                                    id="editor-page"
                                    name="editor"
                                    class="hide"><?= $pageData->p_content ?></textarea>
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
                                    onclick="smStf.pageAction.preview('edit-page', 'doc')">
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
                    
                    <input type="hidden" name="hash" value="<?= $pageData->p_hash ?>" />
                </form>
            </div>

            <script>
                if (smTools.check.isReady(MEMORY.secList)) {
                    var $sector = document.getElementById('sector'), $option;

                    MEMORY.secList.forEach(function (e) {
                        $option = document.createElement('option');
                        $option.value = e.h;
                        $option.innerText = e.t;
                        if (e.h == '<?= $pageData->p_sector ?>') {
                            $option.setAttribute('selected', '');
                        }
                        $sector.appendChild($option);
                    });
                    smTools.select.init();

                    var loading = document.querySelector('div.load-local');
                    smEditor.init('editor-page', '<?= ($session->admin >= $config->admin ? 'admin' : 'advance') ?>');
                    CKEDITOR.instances['editor-page'].on('instanceReady', function (e) {
                        loading.parentNode.removeChild(loading);
                    });
                    document.getElementById('page-tools').classList.add('hide');
                } else {
                    document.getElementById('page-load').innerHTML = null;
                    smCore.modal.error('Falha ao carregar objeto global\n\
                    <p class="font-small">Recarregue a página para tentar corrigir o problema</p>', true);
                    console.error('Falha ao carregar objeto global secList');
                }
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
            echo ("<script>smStf.doc.filterView('page', 'all');</script>");
            break;
    }
}
