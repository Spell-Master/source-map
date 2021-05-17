<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require (__DIR__ . '/../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$uri = GlobalFilter::filterServe();

$len = new LenMaxMin();
$social = new SocialLink();
$select = new Select();
$code = new CreateCode();
$update = new Update();
$mailer = new Mailer();
$clear = new StrClean();

$mail = (isset($post->mail) ? trim($post->mail) : false);
$captcha = (isset($post->captcha) ? trim($post->captcha) : false);

try {
    if (isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($config->enable->mail == 'n') {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$mail) {
        throw new ConstException('Não recebido dados de $_POST[\'mail\']', ConstException::SYSTEM_ERROR);
    } else if ($len->strLen($mail, $config->length->minMail, $config->length->maxMail, '$_POST[\'mail\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (!$social->eMail($mail)) {
        throw new ConstException('$_POST[\'mail\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$captcha) {
        throw new ConstException('Não recebido dados de $_POST[\'captcha\']', ConstException::SYSTEM_ERROR);
    } else if ($config->enable->captchaSensitive == 'y' && ($captcha !== $session->code)) {
        throw new ConstException('Código de verificação não está correto', ConstException::INVALID_POST);
    } else if ($config->enable->captchaSensitive == 'n' && (strtolower($captcha) !== strtolower($session->code))) {
        throw new ConstException('Código de verificação não está correto', ConstException::INVALID_POST);
    }
    //
    else {
        $saveMail = htmlentities($mail);

        // Verificar se o usuário existe
        $select->query("users", "u_mail = :um", "um={$saveMail}");
        if ($select->count()) {
            $userData = $select->result()[0];
            $newPass = $code->defCode(25); // NOVA SENHA

            // Alterar a senha
            $update->query(
                "users",
                ['u_pass' => password_hash(htmlentities($newPass), PASSWORD_DEFAULT)],
                "u_hash = :uh",
                "uh={$userData->u_hash}"
            );

            if ($update->count()) {
                // Enviar a nova senha para o e-mail
                $mailer->sendMail(
                    $userData->u_mail,
                    'Redefinir Senha',
                    __DIR__ . '/../../../system/mail/re_mail.html',
                    [
                        'link' => substr($uri->HTTP_REFERER, 0, strpos($uri->HTTP_REFERER, 'recuperar-senha')),
                        'sitename' => NAME,
                        'date' => $clear->dateTime(date('Y-m-d')),
                        'hour' => date('H:m:s'),
                        'pass' => $newPass
                    ]
                );
                ?>
                <div class="align-center padding-all">
                    <p class="font-medium">Uma nova senha foi gerada e eviada para seu e-mail</p>
                    <p>Veja em:
                        (<a href="mailto:<?= $saveMail ?>" target="_blank" class="href-link">
                            <?= $saveMail ?>
                        </a>)</p>
                    <div class="alert-info align-left">
                        <p class="bold">Caso não tenha recebido verfique em seu e-mail:</p>
                        Confira se a confirmação não está na "Caixa de SPAM" ou na "Lixeira"
                        </ul>
                    </div>
                </div>
                <script>
                    setTimeout(function () {
                        smCore.go.href('entrar');
                    }, <?= $config->length->reload ?>000);
                </script>
                <?php
            } else if ($update->error()) {
                throw new ConstException($update->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('Não momento não foi possível redefinir a senha'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Não foi possível redefinir a senha', ConstException::INVALID_POST);
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../error/500.php');
            break;
        case ConstException::INVALID_ACESS:
            include (__DIR__ . '/../../error/denied.php');
            break;
        case ConstException::INVALID_POST:
            echo ("<script>smCore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
