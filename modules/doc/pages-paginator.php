<?php
$sectorData = $docResult[0];
SeoData::breadCrumbs($url);
?>
<div id="page-base">
    <div class="container padding-all-prop">
        <div class="padding-right-prop over-not">
            <h1 class="font-extra over-text"><?= $sectorData->s_title ?></h1>
        </div>
        <div class="card">
            <?= PostData::showPost($sectorData->s_info) ?>
        </div>

        <div class="align-right margin-top">
            <div data-paginator=""></div>
        </div>

        <?php foreach ($docResult as $value) { ?>
            <div class="shadow margin-top pag-item">
                <div class="bg-black padding-all-min font-large text-white">
                    <div class="margin-lr over-text">
                        <div class="icon-circle-small line-block vertical-middle"></div>
                        <div class="line-block">
                            <a href="doc/<?= $value->s_link ?>/<?= $value->p_link ?>">
                                <?= $value->p_title ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="padding-all-min bg-light">
                    <article class="padding-all bg-white">
                        <?= SeoData::longText($value->p_text, $config->length->longStr) ?>
                    </article>
                    <div class="padding-all-min align-right">
                        <a href="doc/<?= $value->s_link ?>/<?= $value->p_link ?>" class="href-link italic">
                            Acessar
                            &nbsp; <i class="icon-bubble-quote"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="padding-all align-center">
            <div data-paginator=""></div>
        </div>
    </div>

    <script>
        sml.paginator.set('pag-item', <?= $config->rows->pag ?>, null);
        sml.paginator.init(1);
    </script>
</div>
