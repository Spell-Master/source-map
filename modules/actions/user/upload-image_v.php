<?php
require_once (__DIR__ . '/../../../system/config.php');
require_once (__DIR__ . '/../../../system/function/Translate.php');
sleep((int) $config->length->colldown);

$valid = new StrValid();
$clear = new StrClean();
$select = new Select();
$code = new CreateCode();
$insert = new Insert();

$image = (isset($_FILES['upload']) ? $_FILES['upload'] : false);
$admin = (isset($session->admin) ? $session->admin : 0); // O administrador pode enviar quantos arquivos quiser

try {
    if (!isset($session->user->hash)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if (!$valid->strInt($session->user->hash)) {
        throw new ConstException('$_SESSION[\'user\'][\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($image)) {
        throw new ConstException('Não recebidos dados para $_FILES[\'image\']', ConstException::SYSTEM_ERROR);
    } else if ($image['error']) {
        throw new ConstException('Erro no armazenamento do arquivo pelo $_FILES[\'image\'][\'error\'] Código do erro: <p class="bold">' . $icon['error'] . '</p>', ConstException::SYSTEM_ERROR);
    } else if (empty($image['name'])) {
        throw new ConstException('$_FILES[\'image\'][\'name\'] está vazio', ConstException::SYSTEM_ERROR);
    } else if ($image['size'] > $config->store->uploadSize) {
        throw new ConstException('Arquivo enviado é maior que o limite de armazenamento permitido', ConstException::SYSTEM_ERROR);
    } else {
        switch ($image['type']) {
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/png':
            case 'image/x-png':
                break;
            default:
                throw new ConstException('Tipo de arquivo inválido: ' . $image['type'], ConstException::SYSTEM_ERROR);
        }

        $maxStore = 0;
        $count = 0;
        $filename = strtolower($code->intCode(30));
        $userHash = $clear->formatStr($session->user->hash);
        $fileDir = __DIR__ . '/../../../uploads/' . $userHash . '/';

        if ($admin < $config->admin) {
            $select->query("uploads", "up_user = :u", "u={$userHash}");
            $count = $select->count();

            if ($count) {
                foreach ($select->result() as $value) {
                    $maxStore += $value->up_size;
                }
            } else if ($select->error()) {
                throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
            }
        }

        if ($count > $config->store->maxFiles) {
            throw new ConstException('Você não pode enviar mais arquivos porque já atingiu o imite de'
            . ' <span class="bold">' . $config->store->maxFiles . '</span>'
            . ' arquivos no armazenamento'
            . '<p class="font-small">Apague um ou mais arquivos para poder enviar outro</p>', ConstException::INVALID_POST);
        } else if ($maxStore > $config->store->maxSize) {
            throw new ConstException('Você não pode enviar mais arquivos porque já atingiu o imite de'
            . ' <span class="bold">' . sizeName($config->store->maxSize) . '</span>'
            . ' espaço no armazenamento'
            . '<p class="font-small">Apague um ou mais arquivos para poder enviar outro</p>', ConstException::INVALID_POST);
        } else {
            $upload = new ImageUpload($fileDir);
            $upload->sendImage($image, $filename, 1024);
            if ($upload->getResult()) {
                $imgName = $upload->getImgName();

                $insert->query(
                    "uploads",
                    [
                        'up_name' => $imgName,
                        'up_size' => $image['size'],
                        'up_user' => $userHash,
                        'up_date' => date('Y-m-d'),
                        'up_type' => 'image'
                    ]
                );

                if ($insert->count()) {
                    ?>
                    <script>
                        var $form = document.getElementById('upload-file');
                        $form.removeChild($form.querySelector('div.load-form'));
                        $form.removeChild($form.querySelector('button'));
                        document.getElementById('file-send').value = '';
                        document.getElementById('file-name').innerText = '';
                        smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Arquivo Enviado</p>', true);
                    </script>
                    <?php
                } else if ($insert->error()) {
                    unlink($fileDir . $imgName); // Remove o arquivo pois ele não foi registrado no banco
                    throw new ConstException($insert->error(), ConstException::SYSTEM_ERROR);
                } else {
                    unlink($fileDir . $imgName); // Remove o arquivo pois ele não foi registrado no banco
                    throw new ConstException('Arquivo enviado foi salvo porém o registro do mesmo não foi concluído', ConstException::SYSTEM_ERROR);
                }
            } else {
                throw new ConstException('No momento não foi possível salvar o arquivo'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            include (__DIR__ . '/../../error/denied.php');
            break;
        case ConstException::SYSTEM_ERROR:
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
                $form.removeChild($form.querySelector('button'));
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
