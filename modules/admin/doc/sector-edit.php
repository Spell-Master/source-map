<?php
require_once (__DIR__ . '/../../../system/config.php');
$get = GlobalFilter::filterGet();
$valid = new StrValid();

$hash = ($get->hash ? $get->hash : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docSector) {
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
        $select->query("doc_sectors", "s_hash = :sh", "sh={$clear->formatStr($hash)}");
        if ($select->count()) {
            $sectorData = $select->result()[0];
            ?>
            <div class="container padding-lr-prop fade-in">
                <form
                    method="POST"
                    action=""
                    id="edit-sector"
                    onsubmit="return smStf.doc.saveSector([
                        '<?= $config->length->minSectorTitle ?>',
                        '<?= $config->length->maxSectorTitle ?>',
                        '<?= $config->length->minSectorDesc ?>',
                        '<?= $config->length->maxSectorDesc ?>'
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
                                placeholder="Título do setor"
                                value="<?= $sectorData->s_title ?>"
                                />
                        </div>
                        <div class="col-third">
                            <div class="row">
                                <div class="col-threequarter col-fix">
                                    <p class="font-medium">Categoria</p>
                                    <select name="category" id="category" class="select-options">
                                        <option value="">Selecionar..</option>
                                    </select>
                                </div>
                                <div class="col-quarter col-fix">
                                    <p class="font-medium align-center">Ativo?</p>
                                    <div class="box-y-50 vertical-wrap">
                                        <div class="relative">
                                            <input
                                                type="checkbox"
                                                name="view"
                                                id="view-sector"
                                                class="checkmark"
                                                <?= ($sectorData->s_status == '1' ? 'checked=""' : null) ?>
                                                />
                                            <label for="view-sector" class="checkmark-line"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-single">
                            <p class="font-medium">Descrição</p>
                            <div class="editor-area">
                                <?php SeoData::showProgress() ?>
                                <textarea
                                    id="editor-sector"
                                    name="editor"
                                    class="hide"><?= $sectorData->s_info ?></textarea>
                            </div>
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
                                    class="btn-warning shadow-on-hover"
                                    title="Cancelar"
                                    onclick="smStf.pageAction.cancel()">
                                    <i class="icon-file-minus2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="hash" value="<?= $sectorData->s_hash ?>" />
                </form>
            </div>

            <script>
                if (smTools.check.isReady(MEMORY.catList)) {
                    var $category = document.getElementById('category'), $option;

                    MEMORY.catList.forEach(function (e) {
                        $option = document.createElement('option');
                        $option.value = e.h;
                        $option.innerText = e.t;
                        if (e.h == '<?= $sectorData->s_category ?>') {
                            $option.setAttribute('selected', '');
                        }
                        $category.appendChild($option);
                    });
                    smTools.select.init();

                    var loading = document.querySelector('div.load-local');
                    smEditor.init('editor-sector', 'basic');
                    CKEDITOR.instances['editor-sector'].on('instanceReady', function (e) {
                        loading.parentNode.removeChild(loading);
                    });
                } else {
                    document.getElementById('page-load').innerHTML = null;
                    smCore.modal.error('Falha ao carregar objeto global\n\
                        <p class="font-small">Recarregue a página para tentar corrigir o problema</p>', true);
                    console.error('Falha ao carregar objeto global catList');
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
            include (__DIR__ . '/../../error/412.php');
            break;
    }
}
