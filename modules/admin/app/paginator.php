<?php
require_once (__DIR__ . '/../../../system/config.php');
require_once (__DIR__ . '/../../../system/function/Translate.php');
$get = GlobalFilter::filterGet();

$clear = new StrClean();

if (isset($get->reload)) { // Recarregamento pelo ajax (Criar/Editar) páginas
    $select = new Select();
    $select->query("app_page", "a_key = :ak", "ak={$clear->formatStr($get->reload)}");
    $appResult = ($select->count() ? $select->result() : []);
}

if (count($appResult)) {
    ?>
    <div class="container padding-lr-prop">
        <div class="align-right margin-top">
            <div data-paginator=""></div>
        </div>
        <?php foreach ($appResult as $value) { ?>
            <div class="shadow margin-top pag-item">
                <div class="bg-black padding-all-min font-large text-white">
                    <div class="margin-lr over-text">
                        <div class="icon-circle-small line-block vertical-middle"></div>
                        <div class="line-block"><?= $value->a_title ?></div>
                    </div>
                </div>
                <div class="padding-all-min bg-light">
                    <div class="bg-light" style="padding: 5px">
                        <div class="bg-white">
                            <div class="row-pad">
                                <div class="col-s-half float-right align-right">
                                    <button class="btn-info text-white maximize-min shadow-on-hover" onclick="sm_stf.app.editPage('<?= $value->a_hash ?>')">
                                        &nbsp; <i class="icon-pencil5"></i> Editar &nbsp;
                                    </button>
                                </div>
                                <div class="col-s-half">
                                    <table>
                                        <tr>
                                            <td><span class="bold">Grupo:</span></td>
                                            <td class="padding-left"><?= strtoupper($value->a_key) ?> Padrão</td>
                                        </tr>
                                        <tr>
                                            <td><span class="bold">Tamanho:</span></td>
                                            <td class="padding-left"><?= sizeName(strlen($value->a_content)) ?></td>
                                        </tr>
                                        <tr>
                                            <td><span class="bold">Data de Criação:</span></td>
                                            <td class="padding-left"><?= $clear->dateTime($value->a_date) ?></td>
                                        </tr>
                                        <?php if ($value->a_version) { ?>
                                            <tr>
                                                <td><span class="bold">Versão:</span></td>
                                                <td class="padding-left"><?= $clear->dateTime($value->a_version) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                            <hr style="margin: 0 2%" />
                            <article class="padding-all bg-white">
                                <?= SeoData::longText($value->a_text, $config->length->longStr) ?>
                            </article>
                        </div>
                    </div>

                    <div class="padding-all-min align-right">
                        <a class="text-black-hover cursor-pointer"><i class="icon-bin2"></i> Apagar</a> &nbsp; 
                        <a href="<?= $value->a_key ?>-padrao/<?= $value->a_link ?>" target="_blank" class="href-link"><i class="icon-earth"></i> Acessar</a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="padding-all align-center">
            <div data-paginator=""></div>
        </div>
    </div>

    <script>
        sml.paginator.set('pag-item', <?= $config->rows->pag ?>, 'paginator');
        sml.paginator.init(1);
    </script>
    <?php
}
