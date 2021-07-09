<?php
require_once (__DIR__ . '/../../../../system/config.php');
$valid = new StrValid();
$clear = new StrClean();
$select = new Select();

try {
    if (!isset($session->user->hash)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if (!$valid->strInt($session->user->hash)) {
        throw new ConstException('$_SESSION[\'user\'][\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $admin = (isset($session->admin) ? $session->admin : 0);
        $maxStorage = 0;
        $upImg = [];
        $upFile = [];

        $select->query("uploads", "up_user = :us", "us={$clear->formatStr($session->user->hash)}");
        if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            if ($select->count()) {
                foreach ($select->result() as $key => $value) {
                    $maxStorage += $value->up_size;
                    if ($value->up_type == 'image') {
                        $upImg[] = $value;
                    } else {
                        $upFile[] = $value;
                    }
                }
            }
            ?>
            <ul class="tab-menu">
                <li><a class="tab-link"><p class="font-large icon-images3"></p></a></li>
                <li><a class="tab-link"><p class="font-large icon-archive"></p></a></li>
                <li><a class="tab-link"><p class="font-large icon-cloud-upload2"></p></a></li>
            </ul>
            <div class="tab-body">
                <div class="align-center" id="img-att">
                    <?php
                    if (count($upImg)) {
                        foreach ($upImg as $img) {
                            ?>
                            <div class="user-attachment-b" data-atttype="img" data-attvalue="<?= $img->up_name ?>">
                                <img src="uploads/<?= $img->up_user ?>/<?= $img->up_name ?>" alt="" />
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div style="opacity: .5" id="not-img">
                            <i class="icon-images3 icn-5x"></i>
                            <p class="padding-top">Você não possui imagens</p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="tab-body">
                <div class="align-center" id="file-att">
                    <?php
                    if (count($upFile)) {
                        foreach ($upFile as $file) {
                            ?>
                            <div class="user-attachment-b" data-atttype="file" data-attvalue="<?= $file->up_name ?>">
                                <p><?= SeoData::longText($file->up_name, 11) ?></p>
                                <div class="box-xy-50 bg-dark radius-circle margin-auto">
                                    <i class="icon-file-zip2"></i>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div style="opacity: .5" id="not-file">
                            <i class="icon-archive icn-5x"></i>
                            <p class="padding-top">Você não possui arquivos</p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="tab-body">
                <?php if ($admin < $config->admin && $maxStorage > $config->store->maxSize) { ?>
                    <div class="alert-danger align-center">
                        Você não pode mais enviar arquivos porque você atingiu o limite do seu armazenamento
                        <p>Acesse a página do seu perfil e apague um ou mais arquivos</p>
                    </div>
                <?php } else { ?>
                    <div id="upload-error" class="alert-danger patern-bg fade-in hide"></div>
                    <form
                        id="upload-file"
                        action=""
                        method="POST"
                        enctype="multipart/form-data"
                        class="margin-top align-center"
                        onsubmit="return (false);">
                        <input id="file-send" name="upload" type="file" accept="*" class="hide" />

                        <label for="file-send" class="btn-default button-block border-all dashed">
                            <p>Enviar Anexo</p>
                            <i class="icon-drawer-out icn-3x"></i>
                        </label>
                    </form>
                    <div id="upload-status"></div>
                <?php } ?>
            </div>
            <script>
                smTools.tab.init();
                smEditor.upload();

                var attFile = document.querySelectorAll('div.user-attachment-b');
                attFile.forEach(function (e) {
                    e.addEventListener('click', function (ev) {
                        if (e.dataset.atttype == 'img') {
                            insertEditorImg('<?= $session->user->hash ?>/' + e.dataset.attvalue);
                        } else if (e.dataset.atttype == 'file') {
                            insertEditorFile('<?= $session->user->hash ?>/' + e.dataset.attvalue);
                        } else {
                            return (false);
                        }
                    }, false);
                });
            </script>
            <?php
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            SeoData::showProgress();
            echo ("<script>smCore.go.reload();</script>");
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../../../modules/error/500.php');
            break;
    }
}
