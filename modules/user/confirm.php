<?php
try {
    if (isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($config->enable->user == 'n') {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        $uri = GlobalFilter::filterServe();
        $confirm = explode('?chave=', $uri->REQUEST_URI);
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
                    <?php if (isset($config[1])) { ?>

                    <?php } else { ?>
                        <div class="align-center">
                            <h2 class="quicksand">Re-enviar e-mail de confirmação</h2>

                            <hr />
                            <div class="margin-top">
                                <div class="margin-auto" style="max-width: 400px">
                                    <p class="list margin-left align-left">Código de verificação</p>
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

                                    <form method="POST" action="" id="re-mail">
                                        <p class="align-left">E-mail cadastrado</p>
                                        <input type="text" name="mail" id="mail" class="input-default align-center" maxlength="<?= $config->length->maxMail ?>" />

                                        <p class="align-left">Insira o código de verificação</p>
                                        <input id="captcha" name="captcha" type="text" maxlength="6" pattern="([a-zA-Z0-9]+)" class="input-default align-center gunship" />
                                    </form>
                                </div>
                            </div>

                            <div class="margin-top-high align-center">
                                <button class="btn-info text-white shadow-on-hover" onclick="reMail([
                                                        '<?= $config->length->minMail ?>',
                                                        '<?= $config->length->maxMail ?>'
                                                    ])">
                                    Enviar nova Confirmação
                                    <i class="icon-upload5"></i>
                                </button>
                            </div>
                        </div>
                    <?php } ?>
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

