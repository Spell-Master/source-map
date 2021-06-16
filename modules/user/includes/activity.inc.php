<?php
$activity = new Select();

$activity->setQuery("
    SELECT
        *
    FROM
        users_activity
    WHERE
        ua_user = :au
    ORDER BY
        ua_date DESC
    LIMIT
        100
    ",
        "au={$userData->u_hash}"
);

if ($activity->count()) {
    $clear = new StrClean();
    ?>
    <div class="align-right margin-top">
        <div data-paginator=""></div>
    </div>
    <?php foreach ($activity->result() as $value) { ?>
        <div class="margin-top shadow pag-item">
            <div class="padding-all-min bg-light-black">
                <div class="margin-left">
                    <a href="<?= $value->ua_link ?>" class="text-white list">
                        <?= $clear->dateTime($value->ua_date) ?>
                        <p><?= html_entity_decode($value->ua_title) ?></p>
                    </a>
                </div>
            </div>
            <div class="bg-light" style="padding: 5px">
                <div class="bg-white padding-all over-x">
                    <div class="italic"><?= $value->ua_info ?></div>
                </div>
            </div>

            <div class="padding-all-min align-right">
                <ul class="list-none">
                    <li class="line-block padding-right-min">
                        <a href="<?= $value->ua_link ?>" target="_blank" class="href-link">
                            Acessar <i class="icon-paperplane"></i>
                        </a>
                    </li>
                    <?php if ($userPage) { ?>
                        <li class="line-block padding-right-min">
                            <a class="text-black-hover cursor-pointer" onclick="smUser.delActivity('<?= $value->ua_id ?>');" title="Exclui esse registro de atividade">
                                Limpar Histórico <i class="icon-broom"></i>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <form method="POST" id="activity-<?= $value->ua_id ?>">
            <input type="hidden" name="target" value="<?= $value->ua_id ?>" />
        </form>
    <?php } ?>
    <div class="align-center padding-all">
        <div data-paginator=""></div>
    </div>

    <script>
        smTools.paginator.set('pag-item', <?= $config->rows->pag ?>, 'pag-activity');
        smTools.paginator.init(1);
    </script>
    <?php
} else if ($activity->error()) { // Executa a partir do profile.php
    throw new ConstException($activity->error(), ConstException::SYSTEM_ERROR);
} else {
    ?>
    <span class="italic font-small">Não há atividades recentes</span>
    <?php
}