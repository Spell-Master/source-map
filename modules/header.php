<?php
$user = (isset($session->user) ? $session->user : false);
if ($url[0] == 'cadastro' || $url[0] == 'recuperar-senha' || $url[0] == 'entrar') {
    if ($user) {
        header('LOCATION: ' . BaseURI());
    }
} else if ($url[0] == 'inicio') {
    ?>
    <div class="padding-all-min patern-bg align-right font-small text-light-grey">
        <ul class="list-none">
            <?php
            if ($user) {
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
                if ($config->enable->users == 'y') {
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
            } // $user
            ?>
        </ul>
    </div>
    <?php
} else {
    echo __FILE__;
}
