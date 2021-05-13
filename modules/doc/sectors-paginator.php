<?php
SeoData::breadCrumbs($url);
$inArr = [];
$category = [];

foreach ($docResult as $key => $value) {
    if (!in_array($value->c_hash, $inArr)) {
        $inArr[] = $value->c_hash;
        $category[$key]['c_hash'] = $value->c_hash;
        $category[$key]['c_title'] = $value->c_title;
    }
}
?>
<div class="container padding-all-prop">
    <?php
    foreach ($category as $idx => $categ) {
        ?>
        <div id="categ-<?= $idx ?>">
            <div<?= ($idx != 0 ? ' class="border-top border-dark padding-top" style="margin-top: 100px"' : null) ?>>
                <div class="row">
                    <div class="col-twothird padding-right-prop over-not">
                        <h2 class="font-extra over-text gunship text-red"><?= $categ['c_title'] ?></h2>
                    </div>
                    <div class="col-third align-right">
                        <div data-paginator=""></div>
                    </div>
                </div>
            </div>
            <?php
            foreach ($docResult as $sect) {
                if ($categ['c_hash'] == $sect->s_category) {
                    ?>
                    <div class="shadow margin-top bg-light pag-item" style="margin-left: 50px">
                        <div class="row">
                            <img src="<?= (empty($sect->s_icon) ? 'lib/image/sector-icon.png' : 'uploads/icons/' . $sect->s_icon) ?>" alt="" class="radius-circle img-default float-left" style="max-width: 100px; margin-left: -50px" />

                            <div class="bg-black padding-all-min font-large text-white">
                                <div class="margin-lr over-text">
                                    <div class="icon-circle-small line-block vertical-middle"></div>
                                    <div class="line-block">
                                        <a href="doc/<?= $sect->s_link ?>">
                                            <?= $sect->s_title ?> 
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="padding-all-min">
                                <article class="padding-all bg-white break">
                                    <?= SeoData::longText($sect->s_info, $config->length->longStr) ?>
                                </article>
                            </div>
                        </div>
                        <div class="padding-all-min align-right">
                            <a href="doc/<?= $sect->s_link ?>" class="href-link italic">
                                Acessar &nbsp; <i class="icon-bubble-quote"></i>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>

<script>
    <?php foreach ($category as $nun => $v) { ?>
        sml.paginator.set('pag-item', 3, 'categ-<?= $nun ?>');
        sml.paginator.init(1);
    <?php } ?>
</script>
