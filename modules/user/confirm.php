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
                <div class="bg-white margin-bottom padding-all-prop">
                    <?php if (isset($confirm[1])) { ?>
                        <div class="align-center">
                            <h2 class="quicksand">Confirmação de Cadastro</h2>

                            <hr />
                            <div class="margin-top">
                                <div class="margin-auto" style="max-width: 400px">
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

                                    <form method="POST" action="" id="confirm-new">
                                        <p class="align-left">Código de Ativação</p>
                                        <div class="input-default align-center"><?= $confirm[1] ?></div>

                                        <p class="align-left">Confirme o e-mail</p>
                                        <input type="text" name="mail" id="mail" class="input-default align-center" placeholder="exemplo@exemplo.com" maxlength="<?= $config->length->maxMail ?>" />

                                        <p class="align-left">Código de Verificação</p>
                                        <input id="captcha" name="captcha" type="text" maxlength="6" placeholder="xxxxxx" pattern="([a-zA-Z0-9]+)" class="input-default align-center gunship" />

                                        <input type="hidden" name="hash" value="<?= $confirm[1] ?>" />
                                    </form>
                                </div>
                            </div>
                            <div class="margin-top-high align-center">
                                <button class="btn-info text-white shadow-on-hover" onclick="confirmNew(['<?= $config->length->minMail ?>', '<?= $config->length->maxMail ?>'])">Validar Cadastro <i class="icon-upload5"></i></button>
                            </div>
                        </div>

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
                                            <a class="refresh-captcha" title="Trocar Código" onclick="smc.formItens.capctha()">
                                                <div class="icon-loop3"></div>
                                            </a>
                                        </div>
                                    </div>

                                    <form method="POST" action="" id="re-mail">
                                        <p class="align-left">E-mail cadastrado</p>
                                        <input type="text" name="mail" id="mail" class="input-default align-center" maxlength="<?= $config->length->maxMail ?>" />

                                        <p class="align-left">Código de Verificação</p>
                                        <input id="captcha" name="captcha" type="text" maxlength="6" pattern="([a-zA-Z0-9]+)" class="input-default align-center gunship" />
                                    </form>
                                </div>
                            </div>

                            <div class="margin-top-high align-center">
                                <button class="btn-info text-white shadow-on-hover" onclick="reMail(['<?= $config->length->minMail ?>', '<?= $config->length->maxMail ?>'])"> Enviar nova Confirmação <i class="icon-upload5"></i>
                                </button>
                            </div>
                        </div>
                        <?php
                        if ($config->enable->user == 'y') {
                            ?>
                            <div class="align-right margin-top">
                                <a href="entrar" class="href-link"><i class="icon-user3"></i> Entrar</a> |
                                <a href="cadastro" class="href-link"><i class="icon-clipboard3"></i> Cadastro</a>
                            </div>
                            <?php
                        }
                    }
                    ?>
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
    }
}
