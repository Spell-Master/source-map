<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$valid = new StrValid();
$len = new LenMaxMin();
$social = new SocialLink();
$clear = new StrClean();
$select = new Select();
$update = new Update();
$insert = new Insert();

$about = (isset($post->about) ? PostData::parseStr($post->about) : '');

try {
    if (!isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if (!isset($session->user->hash) || empty($session->user->hash)) {
        throw new ConstException('Dados de $_SESSION[\'user\'][\'hash\'] não definido', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($session->user->hash)) {
        throw new ConstException('$_SESSION[\'user\'][\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($post->about)) {
        throw new ConstException('Não recebido dados de $_POST[\'about\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($about) >= 1 && $len->strLen($about, $config->length->minText, $config->length->maxText, '$_POST[\'name\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($about) >= 1 && preg_match('/<script[^>]*>([\S\s]*?)<\/script>/', $post->about)) {
        throw new ConstException('Tentativa de anexar XSS no $_POST[\'about\']', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($post->site)) {
        throw new ConstException('Não recebido dados de $_POST[\'site\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($post->site) >= 1 && $len->strLen($post->site, 10, 100, '$_POST[\'site\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($post->site) >= 1 && !$social->webSite($post->site)) {
        throw new ConstException('$_POST[\'site\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($post->mail)) {
        throw new ConstException('Não recebido dados de $_POST[\'mail\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($post->mail) >= 1 && $len->strLen($post->mail, $config->length->minMail, $config->length->maxMail, '$_POST[\'mail\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($post->mail) >= 1 && !$social->eMail($post->mail)) {
        throw new ConstException('$_POST[\'mail\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($post->git)) {
        throw new ConstException('Não recebido dados de $_POST[\'git\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($post->git) >= 1 && $len->strLen($post->git, 10, 100, '$_POST[\'git\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($post->git) >= 1 && !$social->gitHub($post->git)) {
        throw new ConstException('$_POST[\'git\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($post->face)) {
        throw new ConstException('Não recebido dados de $_POST[\'face\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($post->face) >= 1 && $len->strLen($post->face, 10, 100, '$_POST[\'face\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($post->face) >= 1 && !$social->faceBook($post->face)) {
        throw new ConstException('$_POST[\'face\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($post->insta)) {
        throw new ConstException('Não recebido dados de $_POST[\'insta\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($post->insta) >= 1 && $len->strLen($post->insta, 10, 100, '$_POST[\'insta\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($post->insta) >= 1 && !$social->instagram($post->insta)) {
        throw new ConstException('$_POST[\'insta\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($post->twit)) {
        throw new ConstException('Não recebido dados de $_POST[\'twit\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($post->twit) >= 1 && $len->strLen($post->twit, 10, 100, '$_POST[\'twit\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($post->twit) >= 1 && !$social->twitter($post->twit)) {
        throw new ConstException('$_POST[\'twit\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($post->tube)) {
        throw new ConstException('Não recebido dados de $_POST[\'tube\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($post->tube) >= 1 && $len->strLen($post->tube, 10, 100, '$_POST[\'tube\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($post->tube) >= 1 && !$social->youTube($post->tube)) {
        throw new ConstException('$_POST[\'tube\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($post->what)) {
        throw new ConstException('Não recebido dados de $_POST[\'what\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($post->what) >= 1 && $len->strLen($post->what, 10, 100, '$_POST[\'what\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($post->what) >= 1 && !$social->whatsApp($post->what)) {
        throw new ConstException('$_POST[\'what\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $userHash = $clear->formatStr($session->user->hash);
        $save = [
            'ui_website' => htmlentities($post->site),
            'ui_mail' => htmlentities($post->mail),
            'ui_git' => htmlentities($post->git),
            'ui_face' => htmlentities($post->face),
            'ui_insta' => htmlentities($post->insta),
            'ui_twit' => htmlentities($post->twit),
            'ui_tube' => htmlentities($post->tube),
            'ui_whats' => htmlentities($post->what),
            'ui_about' => PostData::savePost(preg_replace('/<script[^>]*>([\S\s]*?)<\/script>/', '', $post->about))
        ];

        $select->query("users_info", "ui_hash = :uh", "uh={$userHash}");

        if ($select->count()) { // Se já existir registro (ATUALIZA)
            $update->query("users_info", $save, "ui_hash = :uh", "uh={$userHash}");
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else { // Se não existir registro (INSERE)
            $save['ui_hash'] = $userHash;
            $insert->query("users_info", $save);
        }

        // ========================================
        if ($update->count() || $insert->count()) {
            $contact = [];
            $key = 0;
            if (strlen($post->site) > 10) {
                $key++;
                $contact[$key]['name'] = 'WebSite';
                $contact[$key]['icon'] = 'earth';
                $contact[$key]['link'] = $post->site;
            }
            if (strlen($post->mail) > $config->length->minMail) {
                $key++;
                $contact[$key]['name'] = 'E-Mail';
                $contact[$key]['icon'] = 'at-sign';
                $contact[$key]['link'] = $post->mail;
            }
            if (strlen($post->git) > 10) {
                $key++;
                $contact[$key]['name'] = 'GitHub';
                $contact[$key]['icon'] = 'github';
                $contact[$key]['link'] = $post->git;
            }
            if (strlen($post->face) > 10) {
                $key++;
                $contact[$key]['name'] = 'Facebook';
                $contact[$key]['icon'] = 'facebook2';
                $contact[$key]['link'] = $post->face;
            }
            if (strlen($post->insta) > 10) {
                $key++;
                $contact[$key]['name'] = 'Instagram';
                $contact[$key]['icon'] = 'instagram';
                $contact[$key]['link'] = $post->insta;
            }
            if (strlen($post->twit) > 10) {
                $key++;
                $contact[$key]['name'] = 'Twitter';
                $contact[$key]['icon'] = 'twitter';
                $contact[$key]['link'] = $post->twit;
            }
            if (strlen($post->tube) > 10) {
                $key++;
                $contact[$key]['name'] = 'Youtube';
                $contact[$key]['icon'] = 'youtube';
                $contact[$key]['link'] = $post->tube;
            }
            if (strlen($post->what) > 10) {
                $key++;
                $contact[$key]['name'] = 'WhatsApp';
                $contact[$key]['icon'] = 'whatsapp';
                $contact[$key]['link'] = $post->what;
            }
            if ($key >= 1) {
                $html = '';
                foreach ($contact as $value) {
                    if ($value['name'] == 'WhatsApp') {
                        $html .= '<div class="padding-all-prop">';
                        $html .= '<i class="icon-' . $value['icon'] . '"></i> ' . $value['name'] . ' ';
                        $html .= '<p class="font-small italic break">' . $value['link'] . '</p>';
                        $html .= '</div>';
                    } else {
                        $html .= '<div class="padding-all-prop">';
                        $html .= '<a href="' . $value['link'] . '" target="_blank">';
                        $html .= '<i class="icon-' . $value['icon'] . '"></i> ' . $value['name'] . ' </a>';
                        $html .= '<p class="font-small italic break">' . $value['link'] . '</p>';
                        $html .= '</div>';
                    }
                }
            } else {
                $html = '<span class="italic font-small">Não há informações disponíveis</span>';
            }

            $aboutStr = (strlen($about) >= 1 ? PostData::showPost($filter['ui_about']) : '<span class="italic font-small">Não há informações disponíveis</span>');
            ?>
            <script>
                document.getElementById('user-about').innerHTML = `<?= $aboutStr ?>`;
                document.getElementById('user-contact').innerHTML = `<?= $html ?>`;
                smUser.cancelEdit();
                smTools.modal.close();
                smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Dados de informações alterados</p>', true);
            </script>
            <?php
        } else if ($update->error() || $insert->error()) {
            $error = "";
            $error .= (($update->error() !== null) ? '<p>' . $update->error() . '</p>' : null);
            $error .= (($insert->error() !== null) ? '<p>' . $insert->error() . '</p>' : null);
            throw new ConstException($error, ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Nenhuma alteração foi realizada', ConstException::INVALID_POST);
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
        case ConstException::INVALID_POST:
            echo ("<script>smCore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
