<?php
if ($admin < $config->docSector) { // Executa pelo default.php
    throw new ConstException(null, ConstException::INVALID_ACESS);
} else {
    $category = new Select();
    $category->setQuery("
        SELECT
            c_hash AS h,
            c_title AS t,
            c_order AS o
        FROM
            doc_category
        ORDER BY
            o ASC
    ");
    if ($category->count()) {
        ?>
        <div class="container padding-all-prop">
            <div class="padding-right-prop over-not">
                <h1 class="gunship over-text">Setores</h1>
            </div>
            <hr />
            <div class="row-pad">
                <div class="col-half">
                    <p class="font-medium">Localizar Setor</p>
                    <form method="POST" id="search-app" onsubmit="return false;">
                        <div class="row">
                            <div class="float-right">
                                <button class="btn-success box-y-50 text-white"><i class="icon-search3"></i></button>
                            </div>
                            <div class="over-not">
                                <input type="text" name="search" id="search" class="input-default" placeholder="pesquisar..." maxlength="<?= $config->length->maxSearch ?>" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-quarter">
                    <p class="font-medium">Filtragem</p>
                    <select class="select-options" id="filter-sector">
                        <option value="">Tudo</option>
                        <option value="lock">Bloqueados</option>
                        <?php foreach ($category->result() as $c) { ?>
                            <option value="<?= $c->h ?>"><?= $c->t ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-quarter">
                    <p class="font-medium">Adicionar</p>
                    <button class="btn-success button-block text-white" onclick="smStf.doc.addSector()">
                        Criar <i class="icon-file-plus2"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="margin-top">
            <div id="paginator" class="fade-in">
                <?php include (__DIR__ . '/doc/sector-paginator.php'); ?>
            </div>
            <div id="page-action"></div>
            <div id="page-preview"></div>
        </div>

        <script>
            MEMORY.catList = JSON.parse('<?= json_encode($category->result()) ?>');

            var $last = null, $filter = null;
            smTools.select.init();
            document.getElementById('filter-sector').addEventListener('change', function (e) {
                $filter = (e.target).value;
                if ($last !== $filter) {
                    $last = $filter;
                    smStf.doc.filterSector($filter);
                }
            }, false);
        </script>
        <?php
    } else if ($category->error()) { // Executa pelo default.php
        throw new ConstException($category->error(), ConstException::SYSTEM_ERROR);
    } else {
        echo "Não tem categoria";
    }
}
