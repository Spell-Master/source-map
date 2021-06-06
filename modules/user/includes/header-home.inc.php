<div class="padding-all-min bg-black patern-bg align-right text-light-grey">
    <ul class="list-none align-center">
        <?php if ($login) { // LOGADO ?>
            <li class="line-block">
                <a href="perfil/<?= $login->link ?>" class="padding-lr-min text-white-hover"><i class="icon-user3"></i> PERFIL</a>
            </li>
            <?php if (isset($session->admin)) { ?>
                <li class="line-block">
                    <span class="bold">|</span>
                    <a href="admin" class="padding-lr-min text-white-hover"><i class="icon-balance"></i> ADMIN</a></li>
            <?php } ?>
            <li class="line-block">
                <span class="bold">|</span>
                <a class="padding-lr-min text-dark-red text-white-hover cursor-pointer" onclick="smUser.logOut()"><i class="icon-switch"></i> SAIR</a>
            </li>
        <?php } else { // DESLOGADO ?>
            <?php if ($config->enable->user == 'y') { ?>
                <li class="line-block">
                    <a href="entrar" class="padding-lr-min text-white-hover"><i class="icon-user3"></i> ENTRAR</a>
                </li>
                <li class="line-block">
                    <span class="bold">|</span>
                    <a href="cadastro" class="padding-lr-min text-white-hover"><i class="icon-clipboard3"></i> REGISTRO</a>
                </li>
            <?php } ?>

            <?php if ($config->enable->mail == 'y') { ?>
                <li class="line-block">
                    <span class="bold">|</span>
                    <a href="recuperar-senha" class="padding-lr-min text-white-hover"><i class="icon-lock"></i> SENHA</a>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>
