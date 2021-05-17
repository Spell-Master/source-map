<?php
require_once (__DIR__ . '/../../../system/config.php');
$get = GlobalFilter::filterGet();

if (isset($get->reload)) { // Recarregamento pelo ajax (Criar/Editar)
    $select = new Select();
    $select->setQuery("SELECT * FROM doc_category ORDER BY c_order ASC");
    $categoryResult = ($select->count() ? $select->result() : []);
}
if (count($categoryResult)) {
    ?>
    <div class="container padding-lr-prop">
        <div class="align-right margin-top">
            <div data-paginator=""></div>
        </div>
        <?php foreach ($categoryResult as $value) { ?>
            <div class="shadow margin-top pag-item">
                <div class="bg-black padding-all-min font-large text-white">
                    <div class="margin-lr over-text">
                        <div class="icon-circle-small line-block vertical-middle"></div>
                        <div class="line-block"><?= $value->c_title ?></div>
                    </div>
                </div>
                <div class="padding-all-min bg-light">
                    <div class="bg-light" style="padding: 5px">
                        <div class="bg-white">
                            <div class="row-pad">
                                <div class="col-s-half float-right align-right">
                                    <button class="btn-info text-white maximize-min shadow-on-hover" onclick="smStf.doc.edit('categ', '<?= $value->c_hash ?>')">
                                        &nbsp; <i class="icon-pencil5"></i> Editar &nbsp;
                                    </button>
                                </div>
                                <div class="col-s-half">
                                    <table>
                                        <tr>
                                            <td><span class="bold">Título:</span></td>
                                            <td class="padding-left"><?= $value->c_title ?></td>
                                        </tr>
                                        <tr>
                                            <td><span class="bold">Order de Exibir:</span></td>
                                            <td class="padding-left"><?= $value->c_order ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="padding-all-min align-right">
                        <a class="text-black-hover cursor-pointer" onclick="smStf.doc.del('categ', '<?= $value->c_hash ?>')"><i class="icon-bin2"></i> Apagar</a> &nbsp; 
                    </div>
                </div>
            </div>

            <form method="POST" action="" id="del-categ-<?= $value->c_hash ?>">
                <input type="hidden" name="hash" value="<?= $value->c_hash ?>" />
            </form>
        <?php } ?>

        <div class="padding-all align-center">
            <div data-paginator=""></div>
        </div>
    </div>

    <script>
        smTools.paginator.set('pag-item', <?= $config->rows->pag ?>, 'paginator');
        smTools.paginator.init(1);
    </script>
    <?php
} else {
    include (__DIR__ . '/../../error/412.php');
}
