<?php
$selectB = new Select();
$selectB->query("app_page", "a_key = :ak", "ak={$app['key']}");

try {
    if ($selectB->error()) {
        throw new ConstException($selectB->error(), ConstException::SYSTEM_ERROR);
    } else {
        SeoData::breadCrumbs($url);
        $doc = simplexml_load_file(__DIR__ . '/../../system/docs/sm-' . $app['key'] . '.xml');
        ?>
        <div class="container padding-all-prop" id="page-base">
            <div class="padding-right-prop over-not">
                <h1 class="font-extra over-text"><?= $app['name'] ?> Padrão</h1>
            </div>
            <div class="card">
                <?= $doc->info ?>
            </div>
            <?php if ($selectB->count()) { ?>
                <div class="align-right margin-top">
                    <div data-paginator=""></div>
                </div>

                <?php foreach ($selectB->result() as $value) { ?>
                    <div class="shadow margin-top item">
                        <div class="bg-black padding-all-min font-large text-white">
                            <div class="margin-lr over-text">
                                <div class="icon-circle-small line-block vertical-middle"></div>
                                <div class="line-block">
                                    <a href="<?= $url[0] ?>/<?= $value->a_link ?>">
                                        <?= $value->a_title ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="padding-all-min bg-light">
                            <article class="padding-all bg-white">
                                <?= SeoData::longText($value->a_text, $config->length->longStr) ?>
                            </article>
                            <div class="padding-all-min align-right">
                                <a href="<?= $url[0] ?>/<?= $value->a_link ?>" class="href-link italic">
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

                <script>
                    sml.paginator.set('item', <?= $config->rows->pag ?>);
                    sml.paginator.init(1);
                </script>
                <?php
            } else {
                include (__DIR__ . '/../error/412.php');
            }
            ?>
        </div>
        <?php
        if ($admin && $admin >= $config->admin) {
            ?>
            <div class="container padding-all-prop" id="page-action"></div>
            <?php
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../error/500.php');
            break;
    }
}
