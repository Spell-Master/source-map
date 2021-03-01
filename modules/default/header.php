<?php
$login = (isset($session->user) ? $session->user : false);
if ($url[0] == 'cadastro' || $url[0] == 'recuperar-senha' || $url[0] == 'entrar' || $url[0] == 'confirmar') {
    if ($login) {
        header('LOCATION: ' . $baseUri);
    }
} else if ($url[0] == 'inicio') {
    ?>
    <div class="padding-all-min patern-bg align-right font-small text-light-grey">
        <ul class="list-none">
            <?php
            if ($login) {
                ?>
                <li class="line-block">
                    <a href="perfil/" class="padding-lr-min text-white-hover"><i class="icon-user3"></i> PERFIL</a>
                </li>
                <?php if (isset($session->admin)) { ?>
                    <li class="line-block">
                        <span class="bold">|</span>
                        <a href="admin" class="padding-lr-min text-white-hover"><i class="icon-balance"></i> ADMIN</a></li>
                <?php } ?>
                <li class="line-block">
                    <span class="bold">|</span>
                    <a class="padding-lr-min text-dark-red text-white-hover cursor-pointer"><i class="icon-switch"></i> SAIR</a>
                </li>
                <?php
            } else {
                if ($config->enable->user == 'y') {
                    ?>
                    <li class="line-block">
                        <a href="entrar" class="padding-lr-min text-white-hover"><i class="icon-user3"></i> ENTRAR</a>
                    </li>
                    <li class="line-block">
                        <span class="bold">|</span>
                        <a href="cadastro" class="padding-lr-min text-white-hover"><i class="icon-clipboard3"></i> REGISTRO</a>
                    </li>
                    <?php
                }
                if ($config->enable->mail == 'y') {
                    ?>
                    <li class="line-block">
                        <span class="bold">|</span>
                        <a href="recuperar-senha" class="padding-lr-min text-white-hover"><i class="icon-lock"></i> SENHA</a>
                    </li>
                    <?php
                }
            } // $login
            ?>
        </ul>
    </div>
    <?php
} else {
    ?>
    <div id="header">
        <div class="bg-red patern-bg padding-top-min" style="height: 55px">
            <div class="container">
                <div class="row">
                    <div class="col-third col-fix">
                        <div class="logo-img"></div>
                    </div>
                    <div class="col-twothird col-fix">
                        <ul class="user-menu list-none align-right">
                            <?php if ($login) { ?>
                                <li class="line-block" title="Perfil">
                                    <img src="<?= (empty($login->photo) ? 'lib/image/profile.png' : $login->photo) ?>" class="radius-circle" data-open="user-box" alt="" />
                                    <div class="user-box arrow-box"></div>
                                </li>
                                <li class="line-block login-name over-text" data-open="user-box" title="Perfil">
                                    <?= $login->name ?>ssssssssss
                                </li>
                                <?php if ($config->enable->message == 'y') { ?>
                                    <li class="line-block" data-open="msg-box" title="Mensagens">
                                        <div class="msg-box arrow-box"></div>
                                    </li>
                                <?php } ?>
                            <?php } else { ?>
                                <li class="line-block" data-open="login-box" title="Entrar">
                                    <div class="login-box arrow-box"></div>
                                </li>
                                <?php if ($config->enable->user == 'y') { ?>
                                    <li class="line-block register" title="Cadastro">
                                        <a href="cadastro"></a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <?php if ($login) { ?>
                <div id="user-box" class="user-box">
                    <ul class="list-none">
                        <?php if (isset($url[1]) && ($url[1] != $login->link)) { ?>
                            <li>
                                <a href="perfil/<?= $login->link ?>" alt="<?= $login->name ?>">Meu perfil</a>
                            </li>
                        <?php } if ($config->enable->message == 'y' && $url[0] != 'mensageiro') { ?>
                            <li>
                                <a href="mensageiro" alt="Mensagens & Notificações">Mensagens</a>
                            </li>  
                        <?php } if (isset($session->admin) && $url[0] != 'admin') { ?>
                            <li>
                                <a href="admin" alt="administração">Administração</a>
                            </li>
                        <?php } ?>
                        <li class="logout">
                            <a title="Encerrar Entrada" onclick="logOut()">
                                <i class="icon-switch"></i>
                                Sair
                            </a>
                        </li>
                    </ul>
                </div>
                <?php if ($config->enable->message == 'y') { ?>
                    <div id="msg-box" class="msg-box">
                        <span class="font-extra text-pink">msg-box</span>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div id="login-box" class="login-box" data-open="fix">
                    <div class="padding-all">
                        <form method="POST" action="" id="user-login" onsubmit="return (false)">
                            <span class="underline"> <i class="icon-at-sign"></i> e-mail: &nbsp; </span>
                            <input value="admin@admin.com" type="text" name="mail" id="mail" class="input-line" maxlength="<?= $config->length->maxMail ?>" />

                            <span class="underline"> <i class="icon-key2"></i> senha: &nbsp; </span>
                            <input value="123456" type="password" name="pass" id="pass" class="input-line" maxlength="<?= $config->length->maxPass ?>" />

                            <div class="margin-top">
                                <button class="btn-dark button-block text-white shadow-on-hover" onclick="userLogin(['<?= $config->length->minMail ?>', '<?= $config->length->maxMail ?>', '<?= $config->length->minPass ?>', '<?= $config->length->maxPass ?>'])">
                                    Entrar
                                    <i class="icon-enter"></i>
                                </button>
                            </div>
                            <div class="align-right margin-top-min"><a href="recuperar-senha" class="href-link">Recuperar Senha <i class="icon-lock2"></i></a></div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
    <?php
}
