<?php
try {
    if (isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($config->enable->user == 'n') {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        ?>
        <div class="patern-bg fixed bg-dark-red" style="height: 100vh; width: 100vw"></div>
        <div class="vertical-wrap padding-all-prop" style="height: 100vh">
            <div class="box-x-900" style="z-index: 1">
                <div class="bg-black padding-all-min font-large">
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
                        <h2 class="quicksand">Recuperar Senha</h2>
                        <hr />
                        <div class="margin-top">
                            <div class="margin-auto" style="max-width: 400px">
                                <?php if ($config->enable->mail == 'y') { ?>
                                    <p class="list margin-left align-left">Código de verificação</p>
                                    <div class="row">
                                        <div class="col-threequarter col-fix">
                                            <img src="lib/image/captcha.php" alt="captcha" id="captchaimg" class="border-all-wide border-light-black radius-left-small bg-dark" />
                                        </div>
                                        <div class="col-quarter col-fix">
                                            <a class="refresh-captcha" title="Trocar Código" onclick="smc.formItens.capctha()">
                                                <div class="icon-loop3"></div>
                                            </a>
                                        </div>
                                    </div>

                                    <form method="POST" action="" id="recover-pass">
                                        <p class="align-left">E-Mail Cadastrado</p>
                                        <input type="text" name="mail" id="mail" class="input-default align-center" maxlength="<?= $config->length->maxMail ?>" />

                                        <p class="align-left">Código de Verificação</p>
                                        <input id="captcha" name="captcha" type="text" maxlength="6" pattern="([a-zA-Z0-9]+)" class="input-default align-center gunship" />
                                    </form>

                                    <div class="margin-top-high align-center">
                                        <button class="btn-warning text-white shadow-on-hover" onclick="recoverPass(['<?= $config->length->minMail ?>', '<?= $config->length->maxMail ?>'])"> Redefinir Senha <i class="icon-shield-notice"></i></button>
                                    </div>
                                <?php } else { ?>
                                    <i class="icon-wondering text-red icn-4x"></i>
                                    <p class="font-medium">Desculpe!</p>
                                    Esse serviço está temporáriamente indisponível
                                <?php } ?>
                            </div>
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
    }
}
