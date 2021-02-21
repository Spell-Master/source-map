<?php
try {
    if (isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($config->enable->users == 'n') {
        throw new ConstException(null, ConstException::INVALID_ACESS);
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
                                            <button class="btn-dark text-white box-y-50" title="Mostrar Senha" style="padding: 6px 14px">
                                                <i class="icon-eye font-large"></i>
                                            </button>
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
                                        <input class="checkmark" name="exemplo_radio" id="terms-a" type="radio">
                                        <label for="terms-a" class="text-green"> SIM <i class="icon-thumbs-up3"></i></label>

                                        <input class="checkmark" name="exemplo_radio" id="terms-b" type="radio" checked="">
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
                                        <a class="refresh-captcha" title="Trocar Código">
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
                        <button class="btn-info text-white">
                            Enviar solicitação de Cadastro
                            <i class="icon-upload5"></i>
                        </button>
                    </div>

                    <div class="margin-top align-right">
                        <a href="entrar" class="href-link">Início</a> |
                        <a href="entrar" class="href-link">Entrar</a> |
                        <?php if ($config->enable->mail == 'y') { ?>
                            <a href="recuperar-senha" class="href-link">Recuperar Senha</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            header('LOCATION: ' . BaseURI());
            break;
    }
}
