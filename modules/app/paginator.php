<?php
SeoData::breadCrumbs($url);
$doc = simplexml_load_file(__DIR__ . '/../../system/docs/sm-' . $app['key'] . '.xml');
?>
<div class="container padding-all-prop">
    <div class="padding-right-prop over-not">
        <h1 class="font-extra over-text"><?= $app['name'] ?> Padrão</h1>
    </div>
    <hr />
    <?= $doc->info ?>

    <hr />

    <div class="align-right">
        <div data-paginator=""></div>
    </div>
    <div id="paginator">
        <?php foreach ($appResult as $appValue) { ?>
            <div class="shadow margin-top item">
                <div class="bg-black padding-all-min font-large text-white">
                    <div class="margin-lr over-text">
                        <div class="icon-circle-small line-block vertical-middle"></div>
                        <div class="line-block">
                            <a href="app/<?= $url[1] ?>/<?= $appValue->a_link ?>">
                                <?= $appValue->a_title ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="padding-all-min bg-light">
                    <article class="padding-all bg-white">
                        <?= SeoData::longText($appValue->a_text, $config->length->longStr) ?>
                    </article>
                    <div class="padding-all-min align-right">
                        <a href="app/<?= $url[1] ?>/<?= $appValue->a_link ?>" class="href-link italic">
                            Acessar
                            &nbsp; <i class="icon-bubble-quote"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="padding-all align-center">
        <div data-paginator=""></div>
    </div>
</div>

<script>
    smlib.paginator.set('item', <?= $config->rows->pag ?>);
    smlib.paginator.init(1);
</script>