<?php
require_once (__DIR__ . '/../../../system/config.php');

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docCategory) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        ?>
        <div class="container padding-lr-prop fade-in">
            <form
                method="POST"
                action=""
                id="new-categ"
                onsubmit="return smStf.doc.saveCateg([
                            '<?= $config->length->minPageTitle ?>',
                            '<?= $config->length->maxPageTitle ?>'
                        ], 'new')">

                <p class="list margin-left font-medium">Título</p>
                <input
                    type="text"
                    name="title"
                    id="title"
                    class="input-default"
                    placeholder="Título da Categoria"
                    />

                <div class="bg-light padding-all align-right text-white margin-top">
                    <button
                        type="submit"
                        class="btn-success shadow-on-hover"
                        title="Publicar Categoria"
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
            </form>
        </div>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo ("<script>smCore.go.reload();</script>");
            break;
    }
}
