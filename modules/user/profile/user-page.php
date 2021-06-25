<?php
require_once (__DIR__ . '/../../../system/function/Translate.php');
$clear = new StrClean();

if (!isset($userData)) { // Executa a partir do profile.php
    throw new ConstException('Array de dados de usuário "$userData[]" perdia', ConstException::SYSTEM_ERROR);
} else {
    $selectB = clone $select;
    $selectB->query("users_info", "ui_hash = :uih", "uih={$userData->u_hash}");

    if ($selectB->error()) { // Executa a partir do profile.php
        throw new ConstException($selectB->error(), ConstException::SYSTEM_ERROR);
    } else {
        if ($selectB->count()) {
            //$merge = array_merge((array) $userData, (array) $selectB->result()[0]);
            //$userData = GlobalFilter::StdArray($merge);
            foreach ($selectB->result()[0] as $key => $value) {
                $userData->$key = $value;
            }
        }
        // Se quem está acessando é o próprio usuário dono do perfil
        $userPage = ($login && $login->hash == $userData->u_hash ? true : false);
        ?>
        <div class="container padding-all-prop fade-in" id="profile-display">
            <div class="align-center">
                <p><?= LevelToName($userData->u_level); ?></p>
                <div class="box-xy-250 margin-auto relative">
                    <img
                        src="<?= (empty($userData->u_photo) ? 'lib/image/profile.png' : 'uploads/photos/' . $userData->u_photo) ?>"
                        alt=""
                        class="radius-circle img-default"
                        data-photo=""
                        onerror="this.src='lib/image/profile.png'"
                        />
                    <?php if ($userPage) { ?>
                        <div class="pos-bottom-right padding-bottom">
                            <button class="btn-success shadow-on-hover text-white" onclick="smUser.getPhoto()">
                                <i class="icon-camera"></i> Foto do Perfil
                            </button>
                        </div>
                    <?php } ?>
                </div>
                <h1 class="gunship text-light-blue"><?= $userData->u_name ?></h1>
                <p>Membro desde: <?= $clear->dateTime($userData->u_date); ?></p>
                <?php
                $ban = strtotime($userData->u_bandate);
                $today = strtotime(date('Y-m-d'));
                if ($userData->u_ban == '1') {
                    echo ("<span class=\"text-red bold\">BANIDO!</span>");
                } else if ($ban >= $today) {
                    echo ("<span class=\"text-red bold\">ACESSO BLOQUEADO! - {$clear->dateTime($value->u_bandate)}</span>");
                }
                $warns = (int) $userData->u_warn;
                if ($warns >= 1) {
                    if (($userPage) || ($admin >= $config->admUser)) {
                        if ($warns >= 3) {
                            $color = 'red';
                        } else if ($warns == 2) {
                            $color = 'orange';
                        } else if ($warns == 1) {
                            $color = 'green';
                        }
                        ?>
                        Alertas:
                        <i class="icon-star-<?= ($warns >= 1) ? 'full text-' . $color : 'empty' ?>"></i>
                        <i class="icon-star-<?= ($warns >= 2) ? 'full text-' . $color : 'empty' ?>"></i>
                        <i class="icon-star-<?= ($warns >= 3) ? 'full text-' . $color : 'empty' ?>"></i>
                        <?php
                    }
                }
                ?>
            </div>
            <hr />

            <?php if ($login) { ?>
                <div class="row-pad">
                    <?php if ($userPage) { ?>
                        <div class="col-quarter text-white hide-large">
                            <button class="btn-dark button-block shadow-on-hover"
                                    onclick="smUser.openEdit('data');">
                                <i class="icon-certificate"></i> Dados
                            </button>
                        </div>
                        <div class="col-quarter text-white hide-large">
                            <button class="btn-dark button-block shadow-on-hover"
                                    onclick="smUser.openEdit('info');">
                                <i class="icon-profile"></i> Informações
                            </button>
                        </div>
                        <div class="col-quarter text-white hide-large">
                            <button  class="btn-dark button-block shadow-on-hover"
                                     onclick="smUser.openEdit('attachment');">
                                <i class="icon-archive"></i> Anexos
                            </button>
                        </div>
                        <div class="col-quarter text-white hide-large"
                             onclick="smUser.openEdit('content');">
                            <button  class="btn-dark button-block shadow-on-hover">
                                <i class="icon-stack3"></i> Conteúdos
                            </button>
                        </div>
                    <?php } else { ?>
                        <?php if ($config->enable->message == 'y') { ?>
                            <div class="col-half col-fix text-white">
                                <button  class="btn-dark button-block shadow-on-hover">
                                    <i class="icon-bubble-lines3"></i> Mensagem
                                </button>
                            </div>
                        <?php } if ($admin >= $config->admUser) { ?>
                            <div class="col-half col-fix text-white">
                                <button  class="btn-dark button-block shadow-on-hover">
                                    <i class="icon-stamp"></i> Administrar
                                </button>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            <?php } ?>

            <div class="row-pad margin-top">
                <!-- CONTATOS -->
                <div class="col-third">
                    <div class="bg-light padding-all">
                        <p class="list margin-left gunship">Contatos</p>
                        <hr class="border-bottom border-dark" />
                        <div class="over-not" id="user-contact">
                            <?php include (__DIR__ . '/../includes/contact.inc.php'); ?>
                        </div>
                    </div>
                </div>

                <!-- SOBRE -->
                <div class="col-twothird">
                    <div class="bg-light padding-all">
                        <p class="list margin-left gunship">Sobre</p>
                        <hr class="border-bottom border-dark" />
                        <div id="user-about">
                            <?php if (!empty($userData->ui_about)) { ?>
                                <?= PostData::showPost($userData->ui_about) ?>
                            <?php } else { ?>
                                <span class="italic font-small">Não há informações disponíveis</span>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- ATIVIDADES -->
                <div class="col-single">
                    <div class="bg-light padding-all margin-top">
                        <p class="list margin-left gunship">Atividades</p>
                        <hr class="border-bottom border-dark" />
                        <div id="pag-activity">
                            <?php include (__DIR__ . '/../includes/activity.inc.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container padding-all-prop" id="profile-manager"></div>
        <?php
    }
}
    