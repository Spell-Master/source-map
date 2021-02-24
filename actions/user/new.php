<?php
$len = new LenMaxMin();
$social = new SocialLink();

$name = (isset($post->name) ? trim($post->name) : false);
$mail = (isset($post->mail) ? trim($post->mail) : false);
$pass = (isset($post->pass) ? trim($post->pass) : false);
$passb = (isset($post->pass) ? $post->passb : false);
$terms = (isset($post->terms) ? $post->terms : false);
$captcha = (isset($post->terms) ? trim($post->captcha) : false);

try {
    if (isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
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
    }
    //
    else if ($config->enable->captchaSensitive == 'y' && ($captcha !== $session->code)) {
        throw new ConstException('Código de verificação não está correto', ConstException::INVALID_POST);
    } else if ($config->enable->captchaSensitive == 'n' && (strtolower($captcha) !== strtolower($session->code))) {
        throw new ConstException('Código de verificação não está correto', ConstException::INVALID_POST);
    }
    //
    else {
        $code = new CreateCode();
        $clear = new StrClean();
        $select = new Select();
        $selectB = clone $select;

        $save = [
            'code' => $code->defCode(20) . time(),
            'mail' => htmlentities($mail),
            'pass' => password_hash(htmlentities($pass), PASSWORD_DEFAULT),
            'name' => htmlentities($name),
            'link' => $clear->formatStr($name),
            'date' => date('Y-m-d')
        ];

        $select->query(
                "users",
                "u_mail = :um OR u_link = :ul",
                "um={$save['mail']}&ul={$save['link']}"
        );

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
            $insert = new Insert();

            ////////////////////////////////////////////////////
            //
            // Confimar cadastro por e-mail
            // 
            ////////////////////////////////////////////////////
            if ($config->enable->mail == 'y' && $config->enable->newConfirm == 'y') {
                $uri = GlobalFilter::filterServe();
                $hostUrl = substr($uri->HTTP_REFERER, 0, strpos($uri->HTTP_REFERER, 'cadastro'));

                // APAGAR TODOS REGISTROS COM MAIS DE 1 DIA
                $day = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 day'));
                $selectC = clone $select;
                $delete = new Delete();
                $selectC->setQuery("SELECT ut_code, ut_date FROM users_temp WHERE ut_date < '{$day}'");
                if ($selectC->count()) {
                    foreach ($selectC->result() as $value) {
                        $delete->query("users_temp", "ut_code = :utc", "utc={$value->ut_code}");
                    }
                } else if ($selectC->error()) {
                    throw new ConstException($selectC->error(), ConstException::SYSTEM_ERROR);
                }

                // SALVAR USUÁRIO TEMPORÁRIO
                $insert->query("users_temp", [
                    'ut_code' => $save['code'],
                    'ut_mail' => $save['mail'],
                    'ut_pass' => $save['pass'],
                    'ut_name' => $save['name'],
                    'ut_link' => $save['link'],
                    'ut_date' => $save['date']
                ]);

                if ($insert->count()) {
                    $mailer = new Mailer();
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
                            smcore.go.href('./');
                        }, <?= $config->length->reload ?>000);
                    </script>
                    <?php
                    exit();
                } else if ($insert->error()) {
                    throw new ConstException($insert->error(), ConstException::SYSTEM_ERROR);
                } else {
                    throw new ConstException('No momento não foi possível concluir o cadastro'
                            . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
                }
            }

            ////////////////////////////////////////////////////
            //
            // Cadastrar sem confirmação de e-mail
            // 
            ////////////////////////////////////////////////////
            else {
                $insert->query("users", [
                    'u_mail' => $save['mail'],
                    'u_pass' => $save['pass'],
                    'u_name' => $save['name'],
                    'u_link' => $save['link'],
                    'u_date' => $save['date']
                ]);
                if ($insert->count()) {

                    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    // CRIAR SESSÃO !!!!!!!!!!!!!!!!
                    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    ?>
                    <div class="align-center">
                        Sua solicitação de cadastro foi enviada
                        <p class="font-small">
                            Consulte seu e-mail <span class="text-red bold"><?= $mail ?></span> para mais informações
                        </p>
                    </div>
                    <div class="padding-all">
                        <a href="./" class="btn-default button-block">Voltar ao início</a>
                    </div>

                    <script>
                        setTimeout(function () {
                            smcore.go.href = 'perfil/<?= $save['link'] ?>';
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
            echo ("<script>smcore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
