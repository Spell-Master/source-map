<?php
require_once (__DIR__ . '/../../../system/config.php');

$valid = new StrValid();
$select = new Select();
$clear = new StrClean();

try {
    if (!isset($session->user->hash)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if (!$valid->strInt($session->user->hash)) {
        throw new ConstException('$_SESSION[\'user\'][\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    } else {
        $select->query("users_info", "ui_hash = :uh", "uh={$clear->formatStr($session->user->hash)}");
        if ($select->count()) {
            $userData = $select->result()[0];
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            $userData = GlobalFilter::StdArray([
                'ui_website' => '',
                'ui_mail' => '',
                'ui_git' => '',
                'ui_face' => '',
                'ui_insta' => '',
                'ui_twit' => '',
                'ui_tube' => '',
                'ui_whats' => '',
                'ui_about' => ''
            ]);
        }
        ?>
        <div class="row-pad">
            <div class="col-threequarter over-not">
                <h2 class="gunship">Alterar dados de Informação</h2>
            </div>
            <div class="col-quarter">
                <button class="btn-default button-block shadow-on-hover" onclick="smUser.cancelEdit()">
                    <i class="icon-esc"></i> Voltar
                </button>
            </div>
        </div>
        <hr />

        <form
            method="POST"
            action=""
            id="edit-info"
            onsubmit="return smUser.editInfo([
                <?= $config->length->minText ?>,
                <?= $config->length->maxText ?>,
                <?= $config->length->minMail ?>,
                <?= $config->length->maxMail ?>
            ])">
            <div class="row-pad">
                <div class="col-single">
                    <p class="list margin-left font-medium">Comente sobre você...</p>
                    <div class="editor-area">
                        <?php SeoData::showProgress() ?>
                        <textarea id="editor-about" name="about" class="hide"><?= $userData->ui_about ?></textarea>
                    </div>
                </div>
            </div>

            <div class="list margin-left font-medium margin-top">Contatos</div>
            <div class="row-pad">
                <div class="col-half">
                    <p><i class="icon-earth"></i> Website</p>
                    <input type="text" name="site" id="site" class="input-default" value="<?= $userData->ui_website ?>" />
                </div>
                <div class="col-half">
                    <p><i class="icon-at-sign"></i> E-Mail<sup class="text-red">* Por segurança não use o mesmo do cadastro *</sup></p>
                    <input type="text" name="mail" id="mail" class="input-default" value="<?= $userData->ui_mail ?>" />
                </div>
                <div class="col-half">
                    <p><i class="icon-github"></i> Repositórios no Git-Hub</p>
                    <input type="text" name="git" id="git" class="input-default" value="<?= $userData->ui_git ?>" />
                </div>
                <div class="col-half">
                    <p><i class="icon-facebook2"></i> Perfil no Facebook</p>
                    <input type="text" name="face" id="face" class="input-default" value="<?= $userData->ui_face ?>" />
                </div>
                <div class="col-half">
                    <p><i class="icon-instagram"></i> Perfil no Instagram</p>
                    <input type="text" name="insta" id="insta" class="input-default" value="<?= $userData->ui_insta ?>" />
                </div>
                <div class="col-half">
                    <p><i class="icon-twitter"></i> Perfil no Twitter</p>
                    <input type="text" name="twit" id="twit" class="input-default" value="<?= $userData->ui_twit ?>" />
                </div>
                <div class="col-half">
                    <p><i class="icon-youtube"></i> Canal no YouTub</p>
                    <input type="text" name="tube" id="tube" class="input-default" value="<?= $userData->ui_tube ?>" />
                </div>
                <div class="col-half">
                    <p><i class="icon-whatsapp"></i> WhatsApp</p>
                    <input type="text" name="what" id="what" class="input-default" value="<?= $userData->ui_whats ?>" />
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

        <script>
            var loading = document.querySelector('div.load-local');
            smEditor.init('editor-about', 'basic');
            CKEDITOR.instances['editor-about'].on('instanceReady', function (e) {
                loading.parentNode.removeChild(loading);
            });
        </script>
        <?php
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            include (__DIR__ . '/../../error/denied.php');
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../error/500.php');
            break;
    }
}
