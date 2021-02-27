<?php
try {
    if (isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($config->enable->user == 'n') {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }  else if ($config->enable->loginError == 'y' && isset($cookie->loginerror)) {
        throw new ConstException('Devido errar dados de acesso por mais de ' . $config->length->loginError . ' vezes'
        . '<p class="font-small">Por segurança seu dispositivo foi bloqueado por 24 horas</p>', ConstException::MISC_RETURN);
    } else {
        ?>
        <div class="patern-bg fixed bg-dark-red" style="height: 100vh; width: 100vw"></div>
        <div class="vertical-wrap padding-all" style="height: 100vh">
            <div class="box-x-900" style="z-index: 1">
                <div class="bg-black padding-all font-large">
                    <div class="row">
                        <div class="float-left">
                            <img src="lib/image/logo.png" class="line-block vertical-middle box-x-50" />
                            <div class="line-block vertical-middle gunship">
                                <span class="text-blue">SOURCE</span>-<span class="text-red">MAP</span>
                            </div>
                        </div>
                        <div class="float-right">
                            <div class="padding-lr-min float-right">
                                <a href="./" class="href-link" title="voltar ao início"><i class="icon-home5"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white margin-bottom padding-all">
                    <div class="align-center">
                        <h1 class="quicksand">Bem Vindo(a)</h1>

                        <div class="alert-warning margin-top-high shadow">
                            <p class="bold">Antes de prosseguir com o cadastro, leia com atenção nossos termos uso.</p>
                            <p>Você pode visualiza-los através desse <a href="termos" target="_black" class="href-link bold underline">endereço</a></p>
                        </div>
                    </div>

                    <form method="POST" action="" id="new-user">
                        <div class="margin-top">
                            <div class="row-pad">
                                <div class="col-half">
                                    <p>Nome</p>
                                    <input id="name" name="name" type="text" maxlength="<?= $config->length->maxName ?>" placeholder="Seu nome" class="input-default align-center" />
                                </div>

                                <div class="col-half">
                                    <p>E-Mail</p>
                                    <input id="mail" name="mail" type="text" maxlength="<?= $config->length->maxMail ?>" class="input-default align-center" placeholder="Endereço de e-mail" />
                                </div>

                                <div class="col-half">
                                    <p>Senha</p>
                                    <div class="row">
                                        <div class="float-right">
                                            <a class="btn-dark text-white box-y-50" title="Mostrar Senha" style="padding: 6px 14px" onclick="smcore.formItens.showPass()">
                                                <i class="icon-eye font-large" id="pass-icon"></i>
                                            </a>
                                        </div>
                                        <div class="over-not">
                                            <input id="pass" name="pass" type="password" maxlength="<?= $config->length->maxPass ?>" placeholder="Informe a senha" class="input-default align-center" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-half">
                                    <p>Confirmar Senha</p>
                                    <input id="passb" name="passb" type="password" maxlength="<?= $config->length->maxPass ?>" placeholder="Confirme a senha" class="input-default align-center box-y-50" />
                                </div>

                                <div class="col-single align-center">
                                    <p class="font-medium">Você aceita e garante seguir todos os <a href="termos" target="_black" class="href-link underline">termos de uso</a>?</p>
                                    <div class="relative margin-top">
                                        <input class="checkmark" name="terms" value="a" id="terms-a" type="radio">
                                        <label for="terms-a" class="text-green"> SIM <i class="icon-thumbs-up3"></i></label>

                                        <input class="checkmark" name="terms" value="b" id="terms-b" type="radio" checked="">
                                        <label for="terms-b" class="text-red"> NÃO <i class="icon-thumbs-down3"></i></label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <hr />
                        <div class="row">
                            <div class="col-half" style="padding: 3px">
                                <p>Código de verificação</p>
                                <div class="row">
                                    <div class="col-threequarter col-fix">
                                        <img src="lib/image/captcha.php" alt="captcha" id="captchaimg" class="border-all-wide border-light-black radius-left-small bg-dark" />
                                    </div>
                                    <div class="col-quarter col-fix">
                                        <a class="refresh-captcha" title="Trocar Código" onclick="smcore.formItens.capctha()">
                                            <div class="icon-loop3"></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-half" style="padding: 3px">
                                <p>Insira o código</p>
                                <input id="captcha" name="captcha" type="text" maxlength="6" pattern="([a-zA-Z0-9]+)" class="input-default align-center gunship" />
                            </div>
                        </div>
                    </form>

                    <div class="margin-top-high align-center">
                        <button class="btn-info text-white shadow-on-hover" onclick="newUser(['<?= $config->length->minName ?>', '<?= $config->length->maxName ?>', '<?= $config->length->minMail ?>', '<?= $config->length->maxMail ?>', '<?= $config->length->minPass ?>', '<?= $config->length->maxPass ?>'])">
                            Enviar solicitação de Cadastro
                            <i class="icon-upload5"></i>
                        </button>
                    </div>

                    <div class="row-pad margin-top">
                        <div class="col-half col-fix">
                            <?php if ($config->enable->mail == 'y' && $config->enable->newConfirm == 'y') { ?>
                                <a href="confirmar" class="btn-empty href-link" title="Enviar outro e-mail de confirmação do cadastro relaziado">
                                    <i class="icon-mail4"></i>
                                    Re-enviar e-mail
                                </a>
                            <?php } ?>
                        </div>
                        <div class="col-half col-fix align-right">
                            <a href="entrar" class="href-link"><i class="icon-user3"></i> Entrar</a> |
                            <?php if ($config->enable->mail == 'y') { ?>
                                <a href="recuperar-senha" class="href-link"><i class="icon-lock"></i> Senha</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            header('LOCATION: ' . $baseUri);
            break;
        case ConstException::MISC_RETURN:
            ?>
            <div class="patern-bg fixed bg-dark-red" style="height: 100vh; width: 100vw"></div>
            <script>
                smlib.modal.open('Não Autorizado', false);
                document.getElementById('modal-load').innerHTML = '<div class="text-red align-center padding-all"><i class="icon-warning icn-4x"></i><div class="font-medium"><?= $e->getMessage() ?></div></div>';
                setTimeout(function () {
                    smcore.go.href('<?= $baseUri ?>');
                }, <?= (int) $config->length->reload ?>000);
            </script>
            <?php
            break;
    }
}
