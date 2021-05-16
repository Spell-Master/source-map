<div id="header">
    <div class="top-bar bg-dark-red patern-bg">
        <div class="container">
            <div class="row">
                <div class="col-quarter col-fix">
                    <div class="logo"></div>
                </div>
                <div class="col-threequarter col-fix">
                    <ul class="user-menu list-none align-right">
                        <?php
                        if ($login) { // LOGADO
                            ?>
                            <li class="line-block" title="Perfil">
                                <img src="<?= (empty($login->photo) ? 'lib/image/profile.png' : $login->photo) ?>" class="radius-circle" data-open="user-box" alt="" />
                                <div class="arrow-top user-box"></div>
                            </li>
                            <li class="line-block login-name over-text" data-open="user-box" title="Perfil">
                                <?= $login->name ?>
                            </li>
                            <?php if ($config->enable->notification == 'y') { ?>
                                <li class="line-block" data-open="note-box" title="Notificações">
                                    <div class="relative">
                                        <div class="top-cont-icon" data-open="note-box">
                                            <div class="emblem-circle bg-black" data-open="note-box">99+</div>
                                        </div>
                                    </div>
                                    <div class="arrow-top note-box"></div>
                                </li>
                            <?php } if ($config->enable->message == 'y') { ?>
                                <li class="line-block" data-open="msg-box" title="Mensagens">
                                    <div class="relative">
                                        <div class="top-cont-icon" data-open="msg-box">
                                            <div class="emblem-circle bg-black" data-open="msg-box">99+</div>
                                        </div>
                                    </div>
                                    <div class="arrow-top msg-box"></div>
                                </li>
                            <?php } ?>
                        <?php } else { // DESLOGADO ?>
                            <li class="line-block" data-open="login-box" title="Entrar">
                                <div class="arrow-top login-box"></div>
                            </li>
                            <?php if ($config->enable->user == 'y') { ?>
                                <li class="line-block register" title="Cadastro">
                                    <a href="cadastro"></a>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($login) { // LOGADO 
        ?>
        <div id="user-box" class="user-box" data-open="fix">
            <ul class="top-menu list-none">
                <li>
                    <a href="perfil/<?= $login->link ?>" alt="<?= $login->name ?>">Meu perfil</a>
                </li>
                <?php if ($config->enable->notification == 'y' && $url[0] != 'notificacoes') { ?>
                    <li>
                        <a href="notificacoes" alt="Notificações">Notificações</a>
                    </li>  
                <?php } if ($config->enable->message == 'y' && $url[0] != 'mensageiro') { ?>
                    <li>
                        <a href="mensageiro" alt="Mensagens">Mensagens</a>
                    </li>  
                <?php } if (isset($session->admin) && $url[0] != 'admin') { ?>
                    <li>
                        <a href="admin" alt="administração">Administração</a>
                    </li>
                <?php } ?>
                <li class="logout">
                    <a title="Encerrar Entrada" onclick="smUser.logOut()">
                        <i class="icon-switch"></i>
                        Sair
                    </a>
                </li>
            </ul>
        </div>

        <?php if ($config->enable->notification == 'y') { ?>
            <div id="note-box" class="note-box" data-open="fix">
                <div class="padding-all-min text-red gunship border-bottom border-dark">
                    <i class="icon-bell2"></i> Notificações
                </div>
                <ul class="top-menu list-none">
                    <li>
                        <a href="notificacoes/a" alt="administração">Noti A</a>
                    </li>
                    <li>
                        <a href="notificacoes/b" alt="administração">Noti B</a>
                    </li>
                </ul>
            </div>
        <?php } if ($config->enable->message == 'y') { ?>
            <div id="msg-box" class="msg-box" data-open="fix">
                <div class="padding-all-min text-red gunship border-bottom border-dark">
                    <i class="icon-envelop3"></i> Mensagens
                </div>
                <ul class="top-menu list-none">
                    <li>
                        <a href="mensageiro/a" alt="administração">MSG A</a>
                    </li>
                    <li>
                        <a href="mensageiro/b" alt="administração">msg B</a>
                    </li>
                </ul>
            </div>
            <?php
        }
    } else {
        ?>
        <div id="login-box" class="login-box" data-open="fix">
            <div class="padding-all">
                <form method="POST" action="" id="user-login" onsubmit="return (false)">
                    <i class="icon-at-sign"></i> e-mail:
                    <input value="admin@admin.com" type="text" name="mail" id="mail" class="input-line" maxlength="<?= $config->length->maxMail ?>" />

                    <i class="icon-key2"></i> senha:
                    <input value="123456" type="password" name="pass" id="pass" class="input-line" maxlength="<?= $config->length->maxPass ?>" />

                    <div class="margin-top">
                        <button class="btn-dark button-block text-white shadow-on-hover" onclick="smUser.login(['<?= $config->length->minMail ?>', '<?= $config->length->maxMail ?>', '<?= $config->length->minPass ?>', '<?= $config->length->maxPass ?>'])">
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