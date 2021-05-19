<?php
try {
    if ($admin < $config->docCategory) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        $select = new Select();
        $select->setQuery("SELECT * FROM doc_category ORDER BY c_order ASC");

        $count = $select->count();
        if ($count) {
            $categoryResult = $select->result();
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        }
        ?>
        <div class="container padding-all-prop">
            <div class="padding-right-prop over-not">
                <h1 class="gunship over-text">Categorias</h1>
            </div>
            <hr />
            <p class="font-medium">Adicionar</p>
            <button class="btn-success text-white" onclick="smStf.doc.addCateg()">
                Criar <i class="icon-file-plus2"></i>
            </button>
        </div>

        <div class="margin-top">
            <div id="paginator" class="fade-in">
                <?php
                if ($count) {
                    include (__DIR__ . '/doc/categ-paginator.php');
                } else {
                    include (__DIR__ . '/../error/412.php');
                }
                ?>
            </div>
            <div id="page-action"></div>
            <div id="page-preview"></div>
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
            include (__DIR__ . '/../error/500.php');
            break;
    }
}
