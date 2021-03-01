<?php
$uri = GlobalFilter::filterServe();

$len = new LenMaxMin();
$social = new SocialLink();
$user = new SmUser();
$select = new Select();
$clear = new StrClean();

$mail = (isset($post->mail) ? trim($post->mail) : false);
$pass = (isset($post->pass) ? trim($post->pass) : false);

try {
    if (isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if ($config->enable->loginError == 'y' && $user->loginCheck()) {
        throw new ConstException('Devido errar dados de acesso por mais de ' . $config->length->loginError . ' vezes'
        . '<p class="font-small">Por segurança seu dispositivo foi bloqueado por 24 horas</p>', ConstException::MISC_RETURN);
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
    }
    //
    else {
        $save = [
            'mail' => htmlentities($mail),
            'pass' => htmlentities($pass)
        ];

        // Verificar existência do e-mail
        $select->query("users", "u_mail = :um", "um={$save['mail']}");
        if ($select->count()) {
            $userData = $select->result()[0];

            // Bloquear acesso de usuários comuns caso o sistema esteja restrito
            if ($config->enable->user == 'n' && $userData->u_level < $config->staff) {
                throw new ConstException('Desculpe, no momento nosso sistema está restrito só para a administração', ConstException::MISC_RETURN);
            }
            // Verificar se a senha está correta
            else if (password_verify($save['pass'], $userData->u_pass)) {
                // Verificar banimento
                if (strtotime($userData->u_bandate) >= strtotime(date('Y-m-d'))) {
                    throw new ConstException('Seu acesso foi bloqueado até: ' . $clear->dateTime($userData->u_bandate) . ', devido a violações em nossos termos de uso', ConstException::MISC_RETURN);
                } else if ($userData->u_ban == '1') {
                    throw new ConstException('Seu acesso foi permanentimente bloqueado, devido a violações em nossos termos de uso', ConstException::MISC_RETURN);
                }
                // Nenhum banimento
                else {
                    // Iniciar cessão
                    $user->setLogin([
                        'hash' => $userData->u_hash,
                        'mail' => $userData->u_mail,
                        'name' => $userData->u_name,
                        'link' => $userData->u_link,
                        'level' => $userData->u_level
                    ]);
                }
                SeoData::showProgress();
                ?>
                <div class="align-center">
                    <p>Carregando dados de acesso</p>
                    <p class="font-small">Aguarde...</p>
                </div>
                <script>
                    smlib.modal.open('Entrar', false);
                    setTimeout(function () {
                        <?= (in_array('entrar', explode('/', $uri->HTTP_REFERER)) ? 'smcore.go.href(\'./\');' : 'smcore.go.reload();') ?>
                    }, <?= (int) $config->length->reload ?>000);
                </script>
                <?php
            } else {
                if ($config->enable->loginError == 'y') {
                    $user->loginError($config->length->loginError);
                }
                throw new ConstException('Não foi possível validar os dados fornecidos', ConstException::INVALID_POST);
            }
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            if ($config->enable->loginError == 'y') {
                $user->loginError($config->length->loginError);
            }
            throw new ConstException('Não foi possível validar os dados fornecidos', ConstException::INVALID_POST);
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
            echo ("<script>smcore.modal.error('{$e->getMessage()}', true);</script>");
            break;
        case ConstException::MISC_RETURN:
            ?>
            <div class="text-red align-center padding-all">
                <i class="icon-warning icn-4x"></i>
                <p class="font-default"><?= $e->getMessage() ?></p>
                <p class="font-small">Redirecionando...</p>
            </div>
            <script>
                smlib.modal.open('Não Autorizado', false);
                setTimeout(function () {
                    smcore.go.href('./');
                }, <?= (int) $config->length->reload ?>000);
            </script>
            <?php
            break;
    }
}
exit();
