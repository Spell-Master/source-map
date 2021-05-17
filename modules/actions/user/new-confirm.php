<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require (__DIR__ . '/../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$uri = GlobalFilter::filterServe();

$len = new LenMaxMin();
$social = new SocialLink();
$valid = new StrValid();
$clear = new StrClean();
$select = new Select();
$insert = new Insert();
$delete = new Delete();
$mailer = new Mailer();
$user = new SmUser();

$mail = (isset($post->mail) ? trim($post->mail) : false);
$captcha = (isset($post->captcha) ? trim($post->captcha) : false);
$hash = (isset($post->hash) ? $post->hash : false);

try {
    if (isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($config->enable->user == 'n') {
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
    else if (!$hash) {
        throw new ConstException('Não recebido dados de $_POST[\'hash\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($hash)) {
        throw new ConstException('$_POST[\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $save = [
            'mail' => htmlentities($mail),
            'hash' => $clear->formatStr($hash)
        ];

        // Verificar existência do registro
        $select->query(
            "users_temp",
            "ut_code = :utc AND ut_mail = :utm",
            "utc={$save['hash']}&utm={$save['mail']}"
        );
        if ($select->count()) {
            $tempData = $select->result()[0];

            $insert->query("users", [
                'u_hash' => $tempData->ut_code,
                'u_mail' => $tempData->ut_mail,
                'u_pass' => $tempData->ut_pass,
                'u_name' => $tempData->ut_name,
                'u_link' => $tempData->ut_link,
                'u_date' => date('Y-m-d')
            ]);
            if ($insert->count()) {
                // Apagar registro de cadastro temporário
                $delete->query("users_temp", "ut_code = :utc", "utc={$tempData->ut_code}");
                if ($delete->error()) {
                    throw new ConstException($delete->error(), ConstException::SYSTEM_ERROR);
                } else {
                    // Iniciar cessão
                    $user->setLogin([
                        'hash' => $tempData->ut_code,
                        'mail' => $tempData->ut_mail,
                        'name' => $tempData->ut_name,
                        'link' => $tempData->ut_link,
                        'level' => 0
                    ]);

                    if ($config->enable->mail == 'y') {
                        $mailer->sendMail(
                            $tempData->ut_mail,
                            'Confirmação de Cadastro',
                            __DIR__ . '/../../mail/new_confirm.html',
                            [
                                'link' => substr($uri->HTTP_REFERER, 0, strpos($uri->HTTP_REFERER, 'confirmar')),
                                'sitename' => NAME,
                                'mail' => $tempData->ut_mail,
                                'hour' => date('H:m:s'),
                                'date' => $clear->dateTime(date('Y-m-d')),
                                'name' => $tempData->ut_name,
                                'profile' => $tempData->ut_link
                            ]
                        );
                    }
                    ?>
                    <div class="align-center padding-all">
                        <i class="icon-warning icn-4x"></i>
                        <p>Cadastro Concluído</p>
                        <div class="margin-top">
                            <a class="btn-default shadow-on-hover" href="perfil/<?= $tempData->ut_link ?>">Ir para sua página</a>
                        </div>
                    </div>
                    <script>
                        setTimeout(function () {
                            smCore.go.href('perfil/<?= $tempData->ut_link ?>');
                        }, <?= (int) $config->length->reload ?>000);
                    </script>
                    <?php
                }
            } else if ($insert->error()) {
                throw new ConstException($insert->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('No momento não foi possível validar o cadastro'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Nenhum cadastro para ser aprovado com os dados fornecidos'
            . '<div class="font-small">'
            . '<p class="bold">Talvez pelas seguintes razões:</p>'
            . '<p>Já tenha sido ativado,'
            . ' não foi ativado durante um período de 24 horas,'
            . ' o código de ativação ou e-mail não conferem,'
            . ' também é possível que esse e-mail não tenha solicitado o cadastro</p>'
            . '</div>', ConstException::INVALID_POST);
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
