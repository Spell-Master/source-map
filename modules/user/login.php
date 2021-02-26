<?php
try {
    if (isset($session->user)) {
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
                    <h2 class="quicksand align-center">Entrar</h2>
                    <hr />

                    <div class="margin-top">
                        <div class="margin-auto" style="max-width: 400px">
                            <form method="POST" action="" id="user-login" onsubmit="return (false)">
                                <p>Endereço de e-mail</p>
                                <input type="text" name="mail" id="mail" maxlength="<?= $config->length->maxMail ?>" class="input-default" />

                                <p>Senha de acesso</p>
                                <input type="password" name="pass" id="pass" maxlength="<?= $config->length->maxPass ?>" class="input-default" />

                                <div class="padding-all align-center">
                                    <button class="btn-info button-block text-white shadow-on-hover" onclick="userLogin(['<?= $config->length->minMail ?>', '<?= $config->length->maxMail ?>', '<?= $config->length->minPass ?>', '<?= $config->length->maxPass ?>'])"> Confirmar Acesso <i class="icon-enter3"></i> </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php if ($config->enable->user == 'y') { ?>
                        <div class="margin-top align-right">
                            <a href="cadastro" class="href-link"><i class="icon-clipboard3"></i> Registro </a> |
                            <?php if ($config->enable->mail == 'y') { ?>
                                <a href="recuperar-senha" class="href-link"><i class="icon-lock"></i> Senha</a>
                            <?php } ?>
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
            header('LOCATION: ' . $baseUri);
    }
}
