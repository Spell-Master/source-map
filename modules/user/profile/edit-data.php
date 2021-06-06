<?php
require_once (__DIR__ . '/../../../system/config.php');

if (isset($session->user)) {
    ?>
    <div class="row-pad">
        <div class="col-threequarter over-not">
            <h2 class="gunship">Alterar dados de cadastro</h2>
        </div>
        <div class="col-quarter">
            <button class="btn-default button-block shadow-on-hover" onclick="smUser.cancelEdit()">
                <i class="icon-esc"></i> Voltar
            </button>
        </div>
    </div>
    <hr />

    <div class="box-x-900 margin-auto">
        <form
            method="POST"
            action=""
            id="edit-data"
            onsubmit="return smUser.editData([
                <?= $config->length->minName ?>,
                <?= $config->length->maxName ?>,
                <?= $config->length->minMail ?>,
                <?= $config->length->maxMail ?>,
                <?= $config->length->minPass ?>,
                <?= $config->length->maxPass ?>
            ])">
            <div class="row-pad">
                <div class="col-half">
                    <p class="list margin-left">Nome <sup class="text-red">* Deixe vazio para não alterar *</sup></p>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        maxlength="<?= $config->length->maxName ?>"
                        placeholder="Seu nome"
                        class="input-default"
                        value="<?= $session->user->name ?>"
                        />
                </div>
                <div class="col-half">
                    <p class="list margin-left">E-Mail <sup class="text-red">* Deixe vazio para não alterar *</sup></p>
                    <input
                        type="text"
                        id="mail"
                        name="mail"
                        class="input-default"
                        maxlength="<?= $config->length->maxMail ?>"
                        value="<?= $session->user->mail ?>"
                        />
                </div>

                <div class="col-half">
                    <p class="list margin-left">Senha <sup class="text-red">* Deixe vazio para não alterar *</sup></p>
                    <div class="row">
                        <div class="float-right">
                            <a class="btn-dark text-white box-y-50" title="Mostrar Senha" style="padding: 6px 14px" onclick="smCore.formItens.showPass()">
                                <i class="icon-eye font-large" id="pass-icon"></i>
                            </a>
                        </div>
                        <div class="over-not">
                            <input id="pass" name="pass" type="password" maxlength="<?= $config->length->maxPass ?>" placeholder="Informe a senha" class="input-default align-center" />
                        </div>
                    </div>
                </div>
                <div class="col-half">
                    <p class="list margin-left">Confirmar Senha</p>
                    <input
                        id="passb"
                        name="passb"
                        type="password"
                        maxlength="<?= $config->length->maxPass ?>"
                        placeholder="Confirme a senha"
                        class="input-default align-center box-y-50"
                        />
                </div>
            </div>

            <div class="row-pad margin-top">
                <div class="col-half">
                    <button
                        type="submit"
                        class="btn-info button-block shadow-on-hover text-white">
                        <i class="icon-file-check2"></i>
                        Salvar Alterações
                    </button>
                </div>

                <div class="col-half">
                    <button
                        type="button"
                        class="btn-default button-block shadow-on-hover"
                        onclick="smUser.cancelEdit()">
                        <i class="icon-blocked"></i>
                        Cancelar Alterações
                    </button>
                </div>
            </div>
        </form>
    </div>
    <?php
} else {
    include (__DIR__ . '/../../error/denied.php');
}
