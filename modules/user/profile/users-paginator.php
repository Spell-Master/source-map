<?php
require_once (__DIR__ . '/../../../system/function/Translate.php');
$clear = new StrClean();
?>
<div class="container padding-all-prop">
    <div class="padding-right-prop over-not">
        <h1 class="gunship over-text">Usuários</h1>
    </div>
    <hr />
    <div id="paginator">
        <div class="align-right margin-top">
            <div data-paginator=""></div>
        </div>
        <div class="row-pad margin-top">
            <?php foreach ($select->result() as $value) { ?>
                <div class="col-third pag-item">
                    <div class="relative">
                        <div class="absolute pos-top-center">
                            <img src="<?= (empty($value->u_photo) ? 'lib/image/profile.png' : 'uploads/photos/' . $userData->u_photo) ?>" alt="" class="box-x-100 radius-circle" onerror="this.src='lib/image/profile.png'" />
                        </div>
                    </div>
                    <div class="card" style="margin-top: 50px">
                        <div class="align-center" style="padding-top: 50px">
                            <span class="bold font-medium"><?= $value->u_name ?></span>
                            <hr />
                            <p> <?= LevelToName((int) $value->u_level) ?></p>
                            <p>Membro desde:</p>
                            <span class="font-small"><?= $clear->dateTime($value->u_date) ?></span>
                            <div class="padding-tb-min">
                                <a href="perfil/<?= $value->u_link ?>" class="btn-default button-block shadow-on-hover">Perfil</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="padding-all align-center">
            <div data-paginator=""></div>
        </div>
    </div>
</div>

<script>
    smTools.paginator.set('pag-item', <?= $config->rows->pag ?>, 'paginator');
    smTools.paginator.init(1);
</script>
