<?php
SeoData::breadCrumbs($url);
$categoryTitle = [];
?>
<div class="container padding-all-prop">
    <?php
    foreach ($docResult as $key => $value) {
        if (!in_array($value->c_hash, $categoryTitle)) {
            $categoryTitle[$key] = $value->c_hash;
            echo ($key != 0 ? '<hr />' : null);
            ?>
            <div class="padding-right-prop over-not">
                <h2 class="font-extra over-text gunship"><?= $value->c_title ?></h2>
            </div>
            <?php
        }
        ?>
        <div class="shadow margin-top bg-light item" style="margin-left: 50px">
            <div class="row">
                <img src="<?= (empty($value->s_icon) ? 'lib/image/sector-icon.png' : 'uploads/icons/' . $value->s_icon) ?>" alt="" class="radius-circle img-default float-left" style="max-width: 100px; margin-left: -50px" />

                <div class="bg-black padding-all-min font-large text-white">
                    <div class="margin-lr over-text">
                        <div class="icon-circle-small line-block vertical-middle"></div>
                        <div class="line-block">
                            <a href="<?= $value->s_link ?>">
                                <?= $value->s_title ?> 
                            </a>
                        </div>
                    </div>
                </div>
                <div class="padding-all-min">
                    <article class="padding-all bg-white break">
                        <?= SeoData::longText($value->s_info, $config->length->longStr) ?>
                    </article>
                </div>
            </div>
            <div class="padding-all-min align-right">
                <a href="<?= $value->s_link ?>" class="href-link italic">
                    Acessar &nbsp; <i class="icon-bubble-quote"></i>
                </a>
            </div>
        </div>
        <?php
    }
    ?>
</div>
