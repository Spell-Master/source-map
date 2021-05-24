<?php
require_once (__DIR__ . '/../../../system/config.php');
$get = GlobalFilter::filterGet();
$valid = new StrValid();
$select = new Select();

$hash = ($get->hash ? $get->hash : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docCategory) {
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
        $select->query("doc_category");

        $count = $select->count();
        if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else if ($count) {
            foreach ($select->result() as $value) {
                if ($value->c_hash === $hash) {
                    $categoryData = $value;
                    break;
                } else {
                    $categoryData = false;
                }
            }
        }

        if ($count && $categoryData) {
            ?>
            <div class="container padding-lr-prop fade-in">
                <form
                    method="POST"
                    action=""
                    id="edit-categ"
                    onsubmit="return smStf.doc.saveCateg([
                        '<?= $config->length->minPageTitle ?>',
                        '<?= $config->length->maxPageTitle ?>'
                    ], 'edit')">

                    <div class="row-pad">
                        <div class="col-twothird">
                            <p class="list margin-left font-medium">Título</p>
                            <input
                                type="text"
                                name="title"
                                id="title"
                                class="input-default"
                                placeholder="Título da Categoria"
                                maxlength="<?= $config->length->maxPageTitle ?>"
                                value="<?= $categoryData->c_title ?>"
                                />
                        </div>
                        <div class="col-third">
                            <p class="list margin-left font-medium">Posição</p>
                            <select name="order" class="select-options">
                                <?php
                                $order = 0;
                                for ($i = 0; $i < $count; $i++) {
                                    $order += 1;
                                    ?>
                                    <option value="<?= $order ?>" <?= ($order == $categoryData->c_order ? ' selected=""' : false) ?>><?= $order ?>º a exibir</option>");
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <input type="hidden" name="hash" value="<?= $categoryData->c_hash ?>" />

                    <div class="bg-light padding-all align-right text-white">
                        <button
                            type="submit"
                            class="btn-success shadow-on-hover"
                            title="Publicar Categoria"
                            >
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
                </form>
            </div>
            <script>
                smTools.select.init();
                document.getElementById('page-tools').classList.add('hide');
            </script>
            <?php
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
   