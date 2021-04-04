<?php
$uri = GlobalFilter::filterServe();

$len = new LenMaxMin();
$social = new SocialLink();
$select = new Select();
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
        $select->query("users_temp", "ut_mail = :utm", "utm={$saveMail}");
        if ($select->count()) {
            $tempData = $select->result()[0];
            $hostUrl = substr($uri->HTTP_REFERER, 0, strpos($uri->HTTP_REFERER, 'confirmar'));

            $mailer->sendMail(
                $tempData->ut_mail,
                'Requisição de Cadastro',
                __DIR__ . '/../../../system/mail/re_mail.html',
                [
                    'link' => $hostUrl,
                    'sitename' => NAME,
                    'mail' => $tempData->ut_mail,
                    'hour' => date('H:m:s'),
                    'date' => $clear->dateTime(date('Y-m-d')),
                    'name' => $tempData->ut_name,
                    'code' => $tempData->ut_code
                ]
            );
            ?>
            <div class="align-center padding-all">
                <p class="font-medium">Sua solicitação de cadastro foi enviada novamente</p>
                <p>Consulte seu e-mail
                    (<a href="mailto:<?= $tempData->ut_mail ?>" target="_blank" class="href-link">
                        <?= $tempData->ut_mail ?>
                    </a>)
                    para mais informações</p>
                <div class="alert-info align-left">
                    <p class="bold">Caso não tenha recebido verfique em seu e-mail:</p>
                    Confira se a confirmação não está na "Caixa de SPAM" ou na "Lixeira"
                    </ul>
                </div>
            </div>
            <script>
                setTimeout(function () {
                    smcore.go.href('./');
                }, <?= $config->length->reload ?>000);
            </script>
            <?php
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Nenhum cadastro esperando ser aprovado com esse endereço de e-mail', ConstException::INVALID_POST);
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
            echo ("<script>smcore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
