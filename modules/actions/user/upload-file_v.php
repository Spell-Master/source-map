<?php
require_once (__DIR__ . '/../../../system/config.php');
require_once (__DIR__ . '/../../../system/function/Translate.php');
//sleep((int) $config->length->colldown);

$valid = new StrValid();
$finfo = new finfo(FILEINFO_MIME);
$clear = new StrClean();
$select = new Select();
$insert = new Insert();

$file = (isset($_FILES['upload']) ? $_FILES['upload'] : false);
$admin = (isset($session->admin) ? $session->admin : 0); // O administrador pode enviar quantos arquivos quiser
$mime = (isset($file['tmp_name']) ? $finfo->file($file['tmp_name']) : false);

try {
    if (!isset($session->user->hash)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if (!$valid->strInt($session->user->hash)) {
        throw new ConstException('$_SESSION[\'user\'][\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($file)) {
        throw new ConstException('Não recebidos dados para $_FILES[\'file\']', ConstException::SYSTEM_ERROR);
    } else if ($file['error']) {
        throw new ConstException('Erro no armazenamento do arquivo pelo $_FILES[\'file\'][\'error\'] Código do erro: <p class="bold">' . $file['error'] . '</p>', ConstException::SYSTEM_ERROR);
    } else if (empty($file['name'])) {
        throw new ConstException('$_FILES[\'file\'][\'name\'] está vazio', ConstException::SYSTEM_ERROR);
    } else if ($file['size'] > $config->store->uploadSize) {
        throw new ConstException('Arquivo enviado é maior que o limite de armazenamento permitido', ConstException::SYSTEM_ERROR);
    } else if (!$mime || !isset($file['type'])) {
        throw new ConstException('Não é possível determinar o mime-type do arquivo', ConstException::SYSTEM_ERROR);
    }
    //
    else if (strlen($file['name']) < $config->length->minFileName) {
        throw new ConstException('O nome do arquivo é muito curto'
        . '<p class=font-small>Envie um arquivo com o nome maior</p>', ConstException::INVALID_POST);
    } else if (strlen($file['name']) > $config->length->maxFileName) {
        throw new ConstException('O nome do arquivo é muito grande'
        . '<p class=font-small>Envie um arquivo com o nome menor</p>', ConstException::INVALID_POST);
    }
    //
    else {
        $fileType = substr($mime, 0, strpos($mime, ';'));
        switch ($fileType) {
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/png':
            case 'image/x-png':
                $storeModel = 'image';
                break;
            case 'application/zip': // .zip
            case 'application/application/x-zip': // . 7z
            case 'application/application/x-zip-compressed': // . 7z
            case 'application/x-rar': // .ra
            case 'application/x-rar-compressed': // .ra
            case 'application/x-bzip': // .bz
            case 'application/x-bzip2': // .bz2
                $storeModel = 'compress';
                break;
            default;
                $storeModel = 'zip';
                break;
        }

        $pathInfo = pathinfo($file['name']);
        if (!isset($pathInfo['extension']) || !isset($pathInfo['filename'])) {
            throw new ConstException('Não é possível salvar esse tipo de arquivo', ConstException::INVALID_POST);
        } else {
            $extension = strtolower($pathInfo['extension']);
            $maxStore = 0;
            $fileName = mb_strtolower($clear->formatStr($pathInfo['filename']) . '_' . time());
            $userHash = $clear->formatStr($session->user->hash);
            $fileDir = __DIR__ . '/../../../uploads/' . $userHash . '/';

            // Cria a pasta do usuário caso ela não exista
            if (!file_exists($fileDir) && !is_dir($fileDir)) {
                mkdir($fileDir, 0777);
            }

            $select->query("uploads", "up_user = :u", "u={$userHash}");
            $count = ($select->count() ? $select->count() : 0);
            if ($select->error()) {
                throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
            } else if ($count) {
                foreach ($select->result() as $value) {
                    $maxStore += $value->up_size;
                }
            }

            // Checagem se o usuário ainda pode armazenar arquivos
            if ($admin < $config->admin && $count > $config->store->maxFiles) {
                throw new ConstException('Você não pode enviar mais arquivos porque já atingiu o limite de'
                . ' <span class="bold">' . $config->store->maxFiles . '</span>'
                . ' em arquivos no armazenamento'
                . '<p class="font-small">Apague um ou mais arquivos para poder enviar outro</p>', ConstException::INVALID_POST);
            } else if ($admin < $config->admin && $maxStore > $config->store->maxSize) {
                throw new ConstException('Você não pode enviar mais arquivos porque já atingiu o limite de'
                . ' <span class="bold">' . sizeName($config->store->maxSize) . '</span>'
                . ' em espaço no armazenamento'
                . '<p class="font-small">Apague um ou mais arquivos para poder enviar outro</p>', ConstException::INVALID_POST);
            } else {
                if ($storeModel == 'image') { // Se for imagens
                    $upload = new ImageUpload($fileDir);
                    $upload->sendImage($file, $fileName, 1024);
                    if ($upload->getResult()) {
                        $save = [
                            'up_name' => $upload->getImgName(),
                            'up_size' => filesize($fileDir . $upload->getImgName()),
                            'up_user' => $userHash,
                            'up_date' => date('Y-m-d'),
                            'up_type' => 'image'
                        ];
                    }
                } else if ($storeModel == 'compress') { // Se for arquivo compactado
                    if (move_uploaded_file($file['tmp_name'], $fileDir . $fileName . '.' . $extension) && is_writable($fileDir)) {
                        $save = [
                            'up_name' => $fileName . '.' . $extension,
                            'up_size' => filesize($fileDir . $fileName . '.' . $extension),
                            'up_user' => $userHash,
                            'up_date' => date('Y-m-d'),
                            'up_type' => 'file'
                        ];
                    }
                } else { // Qualquer outro tipo de arquivo "COMPACTAR ELE ENTÂO"
                    $zip = new ZipArchive();
                    if ($zip->open($fileDir . $fileName . '.zip', ZipArchive::CREATE) === true) {
                        $zip->addFromString($file['name'], file_get_contents($file['tmp_name']));
                        $zip->close();
                        $save = [
                            'up_name' => $fileName . '.zip',
                            'up_size' => filesize($fileDir . $fileName . '.zip'),
                            'up_user' => $userHash,
                            'up_date' => date('Y-m-d'),
                            'up_type' => 'file'
                        ];
                    }
                }
            }

            // Se o arquivo foi armazenado
            if (isset($save)) {
                $insert->query("uploads", $save);
                if ($insert->count()) {
                    ?>
                    <script>
                        smUser.uploadAtt(
                            params = {
                                id: `<?= $insert->result() ?>`,
                                model: `<?= ($storeModel) ?>`,
                                hash: `<?= $userHash ?>`,
                                size: `<?= sizeName($save['up_size']) ?>`,
                                date: `<?= $clear->dateTime($save['up_date']) ?>`,
                                name: `<?= $save['up_name'] ?>`,
                                idx: `<?= ($count + 1) ?>`,
                                img: `<?= ($storeModel == 'image' ? $upload->getImgName() : '') ?>`
                            }
                        );
                    </script>
                    <?php
                } else if ($insert->error()) {
                    unlink($fileDir . $save['up_name']); // Remove o arquivo pois ele não foi registrado no banco
                    throw new ConstException($insert->error());
                } else {
                    unlink($fileDir . $save['up_name']); // Remove o arquivo pois ele não foi registrado no banco
                    throw new ConstException('No momento não foi possível salvar o arquivo'
                    . '<p class=font-small>Tente novamente mais tarde</p>', ConstException::INVALID_POST);
                }
            } else {
                throw new ConstException('No momento não foi possível salvar o arquivo'
                . '<p class=font-small>Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        }
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
        case ConstException::INVALID_POST:
            ?>
            <script>
                var $erro = document.getElementById('upload-error');
                var $form = document.getElementById('upload-file');
                $erro.innerHTML = '\
                    <div class="text-red align-center padding-all">\n\
                    <i class="icon-warning icn-4x"></i>\n\
                    <div class="font-medium"><?= $e->getMessage() ?></div>\n\
                    </div>';
                $erro.classList.remove('hide');
                $form.removeChild($form.querySelector('div.load-form'));
                document.getElementById('file-send').value = '';
                document.getElementById('file-name').innerText = '';
                setTimeout(function () {
                    $erro.classList.add('hide');
                    $erro.innerHTML = null;
                }, 3000);
            </script>
            <?php
            break;
    }
}
exit();
