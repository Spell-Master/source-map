<?php
if ($admin < $config->docPage) { // Executa pelo default.php
    throw new ConstException(null, ConstException::INVALID_ACESS);
} else {
    $sector = new Select();
    $sector->setQuery("
        SELECT
            s_hash AS h,
            s_title AS t,
            s_link AS l
        FROM
            doc_sectors
        ORDER BY
            l ASC
    ");
    if ($sector->count()) {
        ?>
        <div class="container padding-all-prop">
            <div class="padding-right-prop over-not">
                <h1 class="gunship over-text">Páginas</h1>
            </div>
            <hr />
            <div class="row-pad" id="page-tools">
                <div class="col-half">
                    <p class="font-medium">Localizar Página</p>
                    <form method="POST" id="search-page" onsubmit="return smStf.doc.search([
                            <?= $config->length->minSearch ?>,
                            <?= $config->length->maxSearch ?>
                        ], 'page');">
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
                    <select class="select-options" id="filter-page">
                        <option value="all">Tudo</option>
                        <option value="lock">Bloqueadas</option>
                        <?php foreach ($sector->result() as $s) { ?>
                            <option value="<?= $s->h ?>"><?= $s->t ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-quarter">
                    <p class="font-medium">Adicionar</p>
                    <button class="btn-success button-block text-white" onclick="smStf.doc.openNew('page')">
                        Criar <i class="icon-file-plus2"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="margin-top">
            <div id="paginator" class="fade-in">
                <?php include (__DIR__ . '/doc/page-paginator.php'); ?>
            </div>
            <div id="page-action"></div>
            <div id="page-preview"></div>
        </div>

        <script>
            MEMORY.secList = JSON.parse('<?= json_encode($sector->result()) ?>');
            MEMORY.selectedIndex = 'all';
            var $filter = null;
            smTools.select.init();
            document.getElementById('filter-page').addEventListener('change', function (e) {
                $filter = (e.target).value;
                if (MEMORY.selectedIndex !== $filter) {
                    MEMORY.selectedIndex = $filter;
                    smStf.doc.filterView('page', $filter);
                }
            }, false);
        </script>
        <?php
    } else if ($sector->error()) { // Executa pelo default.php
        throw new ConstException($sector->error(), ConstException::SYSTEM_ERROR);
    } else {
        ?>
        <div class="bg-light-orange patern-bg align-center box-y-250 vertical-wrap">
            <div class="box-x-500 margin-auto">
                <p class="font-jumbo text-red">Não existem setores registrados</p>
                <?php if ($session->admin >= $config->docSector) { ?>
                    <div class="margin-top">
                        <a href="admin/doc-sector" class="btn-warning text-white shadow-on-hover">
                            Ir para setores
                        </a>
                    </div>
                <?php } else { ?>
                    <i class="icon-confused icn-4x text-red"></i>
                <?php } ?>
            </div>
        </div>
        <?php
    }
}
