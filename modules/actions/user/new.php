<?php
$uri = GlobalFilter::filterServe();

$user = new SmUser();
$len = new LenMaxMin();
$social = new SocialLink();
$code = new CreateCode();
$clear = new StrClean();
$select = new Select();
$insert = new Insert();
$delete = new Delete();
$mailer = new Mailer();

$selectB = clone $select;
$selectC = clone $select;

$hostUrl = substr($uri->HTTP_REFERER, 0, strpos($uri->HTTP_REFERER, 'cadastro'));
$name = (isset($post->name) ? trim($post->name) : false);
$mail = (isset($post->mail) ? trim($post->mail) : false);
$pass = (isset($post->pass) ? trim($post->pass) : false);
$passb = (isset($post->pass) ? $post->passb : false);
$terms = (isset($post->terms) ? $post->terms : false);
$captcha = (isset($post->captcha) ? trim($post->captcha) : false);

try {
    if (isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($config->enable->user == 'n') {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if ($config->enable->loginError == 'y' && $user->loginCheck()) {
        throw new ConstException('Devido errar dados de acesso por mais de ' . $config->length->loginError . ' vezes'
        . '<p class="font-small">Por segurança seu dispositivo foi bloqueado por 24 horas</p>', ConstException::MISC_RETURN);
    }
    //
    else if (!$name) {
        throw new ConstException('Não recebido dados de $_POST[\'name\']', ConstException::SYSTEM_ERROR);
    } else if ($len->strLen($name, $config->length->minName, $config->length->maxName, '$_POST[\'name\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (!preg_match('/^([a-zA-Z À-ú 0-9 _ . -]+)$/i', $name)) {
        throw new ConstException('$_POST[\'name\'] não é válido', ConstException::SYSTEM_ERROR);
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
    else if (!$pass) {
        throw new ConstException('Não recebido dados de $_POST[\'pass\']', ConstException::SYSTEM_ERROR);
    } else if ($len->strLen($mail, $config->length->minPass, $config->length->maxPass, '$_POST[\'pass\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (!$passb) {
        throw new ConstException('Não recebido dados de $_POST[\'passb\']', ConstException::SYSTEM_ERROR);
    } else if ($pass !== $passb) {
        throw new ConstException('$_POST[\'pass\'] não é igual a $_POST[\'passb\']', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$terms) {
        throw new ConstException('Não recebido dados de $_POST[\'terms\']', ConstException::SYSTEM_ERROR);
    } else if ($terms !== 'a') {
        throw new ConstException('$_POST[\'terms\'] não é um valor válido', ConstException::SYSTEM_ERROR);
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
        $save = [
            'code' => $code->defCode(20) . time(),
            'mail' => htmlentities($mail),
            'pass' => password_hash(htmlentities($pass), PASSWORD_DEFAULT),
            'name' => htmlentities($name),
            'link' => $clear->formatStr(mb_strtolower($name)),
            'date' => date('Y-m-d')
        ];

        // Verificar se já existe um usuário cadastrado
        $select->query(
            "users",
            "u_mail = :um OR u_link = :ul",
            "um={$save['mail']}&ul={$save['link']}"
        );

        // Verificar se já existe um cadastro aguardando confirmação
        $selectB->query(
            "users_temp",
            "ut_code = :utc OR ut_mail = :utm",
            "utc={$save['code']}&utm={$save['mail']}"
        );

        if ($select->count()) {
            throw new ConstException('Não é possível concluir o cadastro'
            . '<p class="font-small">Tente um nome ou e-mail diferentes</p>', ConstException::INVALID_POST);
        } else if ($selectB->count()) {
            throw new ConstException('Já exite uma requisição de cadastro esperando confirmação'
            . '<p class="font-small">Verifique a caixa de entrada do e-mail de ' . $save['mail'] . '</p>', ConstException::INVALID_POST);
        } else if ($select->error() || $selectB->error()) {
            $error = "";
            $error .= (($select->error() !== null) ? '<p>' . $select->error() . '</p>' : null);
            $error .= (($selectB->error() !== null) ? '<p>' . $selectB->error() . '</p>' : null);
            throw new ConstException($error, ConstException::SYSTEM_ERROR);
        } else {

            ////////////////////////////////////////////////////
            // CONFIRMAR CADASTRO POR E-MAIL
            ////////////////////////////////////////////////////
            if ($config->enable->mail == 'y' && $config->enable->newConfirm == 'y') {
                // Salvar usuário temporário
                $insert->query("users_temp", [
                    'ut_code' => $save['code'],
                    'ut_mail' => $save['mail'],
                    'ut_pass' => $save['pass'],
                    'ut_name' => $save['name'],
                    'ut_link' => $save['link'],
                    'ut_date' => $save['date']
                ]);

                // Apagar registros temporários com mais de 1 dia
                $day = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 day'));
                $selectC->setQuery("SELECT ut_code, ut_date FROM users_temp WHERE ut_date < '{$day}'");
                if ($selectC->count()) {
                    foreach ($selectC->result() as $value) {
                        $delete->query("users_temp", "ut_code = :utc", "utc={$value->ut_code}");
                    }
                } else if ($selectC->error()) {
                    throw new ConstException($selectC->error(), ConstException::SYSTEM_ERROR);
                }

                if ($insert->count()) {
                    // Enviar e-mail de confirmação
                    $mailer->sendMail(
                        $save['mail'],
                        'Requisição de Cadastro',
                        __DIR__ . '/../../mail/new_user.html',
                        [
                            'link' => $hostUrl,
                            'sitename' => NAME,
                            'mail' => $save['mail'],
                            'hour' => date('H:m:s'),
                            'date' => $clear->dateTime($save['date']),
                            'name' => $save['name'],
                            'code' => $save['code']
                        ]
                    );
                    ?>
                    <div class="align-center padding-all">
                        <p class="font-medium">Sua solicitação de cadastro foi enviada</p>
                        <p>Consulte seu e-mail
                            (<a href="mailto:<?= $mail ?>" target="_blank" class="href-link">
                                <?= $mail ?>
                            </a>)
                            para mais informações</p>
                        <hr />
                        <p class="font-small">Redirecionando...</p>
                    </div>
                    <script>
                        setTimeout(function () {
                            smc.go.href('./');
                        }, <?= $config->length->reload ?>000);
                    </script>
                    <?php
                } else if ($insert->error()) {
                    throw new ConstException($insert->error(), ConstException::SYSTEM_ERROR);
                } else {
                    throw new ConstException('No momento não foi possível concluir o cadastro'
                    . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
                }
            }

            ////////////////////////////////////////////////////
            // CADASTRAR SEM CONFIRMAÇÃO DE E-MAIL
            ////////////////////////////////////////////////////
            else {
                // Salvar usuário
                $insert->query("users", [
                    'u_hash' => $save['code'],
                    'u_mail' => $save['mail'],
                    'u_pass' => $save['pass'],
                    'u_name' => $save['name'],
                    'u_link' => $save['link'],
                    'u_date' => $save['date']
                ]);
                if ($insert->count()) {
                    // Iniciar cessão
                    $user->setLogin([
                        'hash' => $save['code'],
                        'mail' => $save['mail'],
                        'name' => $save['name'],
                        'link' => $save['link'],
                        'level' => 0
                    ]);
                    ?>
                    <div class="align-center padding-all">
                        <i class="icon-warning icn-4x"></i>
                        <p>Cadastro Concluído</p>
                        <div class="margin-top">
                            <a class="btn-default shadow-on-hover" href="perfil/<?= $save['link'] ?>">Ir para sua página</a>
                        </div>
                    </div>
                    <script>
                        setTimeout(function () {
                            smc.go.href('perfil/<?= $save['link'] ?>');
                        }, <?= (int) $config->length->reload ?>000);
                    </script>
                    <?php
                } else if ($insert->error()) {
                    throw new ConstException($insert->error(), ConstException::SYSTEM_ERROR);
                } else {
                    throw new ConstException('No momento não foi possível concluir o cadastro'
                    . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
                }
            }
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
            echo ("<script>smc.modal.error('{$e->getMessage()}', false);</script>");
            break;
        case ConstException::MISC_RETURN:
            ?>
            <div class="text-red align-center padding-all">
                <i class="icon-warning icn-4x"></i>
                <p class="font-default"><?= $e->getMessage() ?></p>
                <p class="font-small">Redirecionando...</p>
            </div>
            <script>
                sml.modal.title('Não Autorizado');
                setTimeout(function () {
                    smc.go.href('./');
                }, <?= (int) $config->length->reload ?>000);
            </script>
            <?php
            break;
    }
}
exit();
