<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$valid = new StrValid();
$len = new LenMaxMin();
$social = new SocialLink();
$clear = new StrClean();
$code = new CreateCode();
$select = new Select();
$update = new Update();

try {
    if (!isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!isset($session->user->hash) || empty($session->user->hash)) {
        throw new ConstException('Dados de $_SESSION[\'user\'][\'hash\'] não definido', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($session->user->hash)) {
        throw new ConstException('$_SESSION[\'user\'][\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    } else if (!isset($session->user->name) || empty($session->user->name)) {
        throw new ConstException('Dados de $_SESSION[\'user\'][\'name\'] não definido', ConstException::SYSTEM_ERROR);
    } else if (!isset($session->user->mail) || empty($session->user->mail)) {
        throw new ConstException('Dados de $_SESSION[\'user\'][\'mail\'] não definido', ConstException::SYSTEM_ERROR);
    } else if (!isset($session->user->link) || empty($session->user->link)) {
        throw new ConstException('Dados de $_SESSION[\'user\'][\'link\'] não definido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($post->name)) {
        throw new ConstException('Não recebido dados de $_POST[\'name\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($post->name) >= 1 && $len->strLen($post->name, $config->length->minName, $config->length->maxName, '$_POST[\'name\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($post->name) >= 1 && !preg_match('/^([a-zA-Z À-ú 0-9 _ . -]+)$/i', $post->name)) {
        throw new ConstException('$_POST[\'name\'] não é válido', ConstException::SYSTEM_ERROR);
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
    else if (!isset($post->pass)) {
        throw new ConstException('Não recebido dados de $_POST[\'pass\']', ConstException::SYSTEM_ERROR);
    } else if (!isset($post->passb)) {
        throw new ConstException('Não recebido dados de $_POST[\'passb\']', ConstException::SYSTEM_ERROR);
    } else if (strlen($post->pass) >= 1 && $len->strLen($post->pass, $config->length->minPass, $config->length->maxPass, '$_POST[\'pass\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (strlen($post->pass) >= 1 && ($post->pass !== $post->passb)) {
        throw new ConstException('$_POST[\'pass\'] não é igual a $_POST[\'passb\']', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $userHash = $clear->formatStr($session->user->hash);
        $save = [
            'u_mail' => (strlen($post->mail) >= $config->length->minMail ? htmlentities($post->mail) : null),
            'u_pass' => (strlen($post->pass) >= $config->length->minPass ? password_hash(htmlentities($post->pass), PASSWORD_DEFAULT) : null),
            'u_name' => (strlen($post->name) >= $config->length->minName ? htmlentities($post->name) : null),
            'u_link' => (strlen($post->name) >= $config->length->minName ? $clear->formatStr(mb_strtolower($post->name)) . '_' . $code->intCode(20) : null)
        ];
        $newName = ($save['u_name'] ? $save['u_name'] : $session->user->name);
        $newMail = ($save['u_mail'] ? $save['u_mail'] : $session->user->mail);
        $newLink = ($save['u_link'] ? $save['u_link'] : $session->user->link);

        $select->query(
            "users",
            "u_mail = :um AND u_hash != :uh",
            "um={$save['u_mail']}&uh={$userHash}"
        );
        if ($select->count()) {
            throw new ConstException('Desculpe mas o endereço de e-mail fornecido não pode ser usado', ConstException::INVALID_POST);
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            $update->query(
                "users",
                array_filter($save),
                "u_hash = :uh",
                "uh={$userHash}"
            );
            if ($update->count()) {
                $session->user->name = $newName;
                $session->user->mail = $newMail;
                $session->user->link = $newLink;
                ?>
                <script>
                    var $name = document.getElementById('user-name');
                    var $link = document.getElementById('user-link');
                    if (smTools.check.isReady($name) && smTools.check.isReady($link)) {
                        $name.innerText = '<?= $newName ?>';
                        $link.href = 'perfil/<?= $newLink ?>';
                        smCore.crumbs(['perfil', '<?= $newLink ?>']);
                        window.history.replaceState(null, null, 'perfil/<?= $newLink ?>');
                        smUser.cancelEdit();
                        smTools.modal.close();
                        smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Dados cadastrais alterados</p>', true);
                    } else {
                        smCore.go.reload();
                    }
                </script>
                <?php
            } else if ($update->error()) {
                throw new ConstException($update->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('Nenhuma alteração foi realizada', ConstException::INVALID_POST);
            }
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
