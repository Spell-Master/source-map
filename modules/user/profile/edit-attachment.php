<?php
require_once (__DIR__ . '/../../../system/config.php');
require_once (__DIR__ . '/../../../system/function/Translate.php');

$clear = new StrClean();
$select = new Select();

$admin = (isset($session->admin) ? $session->admin : 0);
$maxStorage = 0;
$upImg = [];
$upFile = [];

try {
    if (!isset($session->user)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else {
        $select->query("uploads", "up_user = :u", "u={$clear->formatStr($session->user->hash)}");
        if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else if ($select->count()) {
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
        <div class="row-pad">
            <div class="col-threequarter over-not">
                <h2 class="gunship">Anexos Enviados</h2>
            </div>
            <div class="col-quarter">
                <button class="btn-default button-block shadow-on-hover" onclick="smUser.cancelEdit()">
                    <i class="icon-esc"></i> Voltar
                </button>
            </div>
        </div>
        <hr />

        <div class="box-x-900 margin-auto<?= ($admin < $config->admin && $maxStorage > $config->store->maxSize ? ' hide' : null) ?>" id="box-upload">
            <p class="list margin-left">Enviar Anexo</p>
            <div id="upload-error" class="alert-danger patern-bg fade-in hide"></div>
            <div class="margin-top">
                <form
                    id="upload-file"
                    action=""
                    method="POST"
                    enctype="multipart/form-data"
                    class="margin-top align-center"
                    onsubmit="return (false);">
                    <input id="file-send" name="upload" type="file" accept="*" class="hide" />
                    <div class="row">
                        <div class="float-left">
                            <label for="file-send" class="btn-info text-white font-large box-y-50" style="line-height: 27px">
                                <i class="icon-drawer-out"></i>
                            </label>
                        </div>
                        <div class="over-not">
                            <label id="file-name" for="photo-send" class="input-default"></label>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php
        $width = round(($maxStorage / (int) $config->store->maxSize) * 100);
        $totalUp = ($width > 100 ? 100 : $width);
        $totaltext = $totalUp . '% = ' . sizeName($maxStorage);
        ?>
        <div class="margin-top">
            <p class="list margin-left font-medium">Armazenamento Utilizado</p>
            <div class="bg-light-black relative shadow" style="height: 20px; width: 100%;">
                <div class="absolute pos-top-left bg-red patern-bg" style="height: 20px; width: <?= $totalUp ?>%" id="totalup">
                </div>
                <div class="absolute pos-center align-center text-white" id="totaltext"><?= $totaltext ?></div>
            </div>
        </div>

        <div class="margin-top">
            <p class="list margin-left font-medium">Anexos Enviados</p>
            <div id="user-files">
                <ul class="tab-menu">
                    <li><a class="tab-link"><p class="font-large icon-images3"></p>Imagens</a></li>
                    <li><a class="tab-link"><p class="font-large icon-archive"></p>Arquivos</a></li>
                </ul>
                <div class="tab-body">
                    <div class="align-center" id="img-att">
                        <?php
                        $idx = 0;
                        if (count($upImg)) {
                            foreach ($upImg as $img) {
                                $idx++;
                                ?>
                                <div class="user-attachment" data-att="<?= $img->up_id ?>">
                                    <a class="href-link" onclick="smTools.transfer.download('uploads/<?= $img->up_user ?>/<?= $img->up_name ?>', true)">
                                        Transferir <i class="icon-download4"></i>
                                    </a>

                                    <img src="uploads/<?= $img->up_user ?>/<?= $img->up_name ?>" alt="" />
                                    <p>
                                        <?= sizeName($img->up_size) ?>
                                        (<?= $clear->dateTime($img->up_date) ?>)
                                    </p>
                                    <input id="delfile-<?= $idx ?>" name="delfile[]" type="checkbox" value="<?= $img->up_name ?>" class="checkbox" />
                                    <label for="delfile-<?= $idx ?>"> <span class="italic">Apagar</span></label>
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
                                $idx++;
                                ?>
                                <div class="user-attachment" data-att="<?= $file->up_id ?>">
                                    <a class="href-link" onclick="smTools.transfer.download('uploads/<?= $file->up_user ?>/<?= $file->up_name ?>', true)">
                                        Transferir <i class="icon-download4"></i>
                                    </a>
                                    <div class="box-xy-50 bg-dark radius-circle margin-auto">
                                        <i class="icon-file-zip2"></i>
                                    </div>
                                    <p class="bold"><?= $file->up_name ?></p>
                                    <?= sizeName($file->up_size) ?>
                                    <?= $clear->dateTime($file->up_date) ?>
                                    <input id="delfile-<?= $idx ?>" name="delfile[]" type="checkbox" value="<?= $file->up_name ?>" class="checkbox" />
                                    <label for="delfile-<?= $idx ?>"></label>
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
            </div>
        </div>

        <div id="upload-status"></div>

        <div id="shopping-cart">
            <div data-cart="" class="line-table vertical-middle"></div>
            <div class="line-table vertical-middle">
                <form method="POST" action="" id="delatt"></form>
                <button
                    class="box-xy-50 radius-circle margin-left bg-light-black bg-red-hover cursor-pointer"
                    onclick="smUser.delAttach();">
                    <i class="icon-bin font-medium"></i>
                </button>
            </div>
        </div>

        <script>
            smUser.uploadFile('<?= (int) $config->store->uploadSize ?>');
            smTools.tab.init();
            smTools.cart.init('checkbox');
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
