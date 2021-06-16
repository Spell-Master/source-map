<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$valid = new StrValid();
$clear = new StrClean();
$select = new Select();
$delete = new Delete();

$target = (isset($post->target) ? $post->target : false);

try {
    if (!isset($session->user->hash)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if (!$valid->strInt($session->user->hash)) {
        throw new ConstException('$_SESSION[\'user\'][\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$target) {
        throw new ConstException('Não recebido dados de $_POST[\'target\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->intCheck($target)) {
        throw new ConstException('$_POST[\'target\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $userHash = $clear->formatStr($session->user->hash);
        $targetID = (int) $clear->formatStr($target);

        $select->query("users_activity", "ua_user = :au", "au={$userHash}");
        if ($select->count()) {
            $activityData = $select->result();
            foreach ($activityData as $key => $value) {
                if ($value->ua_id == $targetID) {
                    unset($activityData[$key]);
                }
            }

            $delete->query("users_activity", "ua_id  = :aid", "aid={$targetID}");
            if ($delete->count()) {
                if (count($activityData) >= 1) {
                    $html = '<div class="align-right margin-top">';
                    $html .= '<div data-paginator=""></div>';
                    $html .= '</div>';
                    foreach ($activityData as $value) {
                        $html .= '<div class="margin-top shadow pag-item">';
                        $html .= '<div class="padding-all-min bg-light-black">';
                        $html .= '<div class="margin-left">';
                        $html .= '<a href="' . $value->ua_link . '" class="text-white list">' . $clear->dateTime($value->ua_date) . ' <p>' . html_entity_decode($value->ua_title) . '</p> </a>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="bg-light" style="padding: 5px">';
                        $html .= '<div class="bg-white padding-all over-x"><div class="italic">' . $value->ua_info . '</div></div>';
                        $html .= '</div>';
                        $html .= '<div class="padding-all-min align-right">';
                        $html .= '<ul class="list-none">';
                        $html .= '<li class="line-block padding-right-min">';
                        $html .= '<a href="' . $value->ua_link . '" target="_blank" class="href-link"> Acessar <i class="icon-paperplane"></i> </a> </li>';
                        $html .= '<li class="line-block padding-right-min">';
                        $html .= '<a class="text-black-hover cursor-pointer" onclick="smUser.delActivity(\'' . $value->ua_id . '\');" title="Exclui esse registro de atividade">';
                        $html .= ' Limpar Histórico <i class="icon-broom"></i> ';
                        $html .= '</a>';
                        $html .= '</li>';
                        $html .= '</ul>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<form method="POST" id="activity-' . $value->ua_id . '">';
                        $html .= '<input type="hidden" name="target" value="' . $value->ua_id . '" />';
                        $html .= '</form>';
                    }
                    $html .= '<div class="align-center padding-all">';
                    $html .= '<div data-paginator=""></div>';
                    $html .= '</div>';
                    ?>
                    <script>
                        document.getElementById('pag-activity').innerHTML = `<?= $html ?>`;
                        smTools.modal.close();
                        smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Histórico de atividade limpo</p>', true);
                        smTools.paginator.set('pag-item', <?= $config->rows->pag ?>, 'pag-activity');
                        smTools.paginator.init(1);
                    </script>
                    <?php
                } else {
                    ?>
                    <script>
                        smTools.modal.close();
                        document.getElementById('pag-activity').innerHTML = '<span class="italic font-small">Não há atividades recentes</span>';
                        smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Histórico de atividade limpo</p>', true);
                    </script>
                    <?php
                }
            } else if ($delete->error()) {
                throw new ConstException($delete->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('No momento não foi possível apagar o histórico'
                . '<p class=font-small>Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException(null, ConstException::MISC_RETURN);
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            include (__DIR__ . '/../../error/denied.php');
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../error/500.php');
            break;
        case ConstException::MISC_RETURN:
            ?>
            <script>
                smTools.modal.close();
                document.getElementById('pag-activity').innerHTML = '<span class="italic font-small">Não há atividades recentes</span>';
            </script>
            <?php
            break;
        case ConstException::INVALID_POST:
            echo ("<script>smCore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
