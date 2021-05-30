<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$get = GlobalFilter::filterGet();
$valid = new StrValid();
$select = new Select();
$clear = new StrClean();
$code = new CreateCode();
$update = new Update();
$log = new LogRegister();

$hash = (isset($get->hash) ? $get->hash : false);
$icon = (isset($_FILES['icon']) ? $_FILES['icon'] : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docSector) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$hash) {
        throw new ConstException('Não recebido dados de $_GET[\'hash\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($hash)) {
        throw new ConstException('$_GET[\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!isset($icon)) {
        throw new ConstException('Não recebidos dados para $_FILES[\'icon\']', ConstException::SYSTEM_ERROR);
    } else if ($icon['error']) {
        throw new ConstException('Erro no armazenamento do arquivo pelo $_FILES[\'icon\'][\'error\'] Código do erro: <p class="bold">' . $icon['error'] . '</p>', ConstException::SYSTEM_ERROR);
    } else if (empty($icon['name'])) {
        throw new ConstException('$_FILES[\'icon\'][\'name\'] está vazio', ConstException::SYSTEM_ERROR);
    } else if ($icon['size'] > $config->store->iconSize) {
        throw new ConstException('Arquivo enviado é maior que o limite de armazenamento para ícones', ConstException::SYSTEM_ERROR);
    } else {
        switch ($icon['type']) {
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/png':
            case 'image/x-png':
                break;
            default:
                throw new ConstException('Tipo de arquivo inválido: ' . $icon['type'], ConstException::SYSTEM_ERROR);
        }

        $select->query("doc_sectors", "s_hash = :sh", "sh={$clear->formatStr($hash)}");
        if ($select->count()) {
            $sectorData = $select->result()[0];
            $fileDir = __DIR__ . '/../../../../uploads/icons/';
            $fileName = $sectorData->s_hash . $code->defCode(9) . md5(time());

            $upload = new ImageUpload($fileDir);
            $upload->sendImage($icon, $fileName, 250);
            if ($upload->getResult()) {
                $imgSrc = $upload->getImgName();
                $update->query(
                    "doc_sectors",
                    ['s_icon' => $imgSrc],
                    "s_hash = :sh",
                    "sh={$sectorData->s_hash}"
                );

                if ($update->count()) {
                    // Apagar o antigo ícone do diretório
                    if (!empty($sectorData->s_icon) && file_exists($fileDir . $sectorData->s_icon)) {
                        unlink($fileDir . $sectorData->s_icon);
                    }
                    $newIcon = 'uploads/icons/' . $imgSrc . '?r=' . time();
                    ?>
                    <script>
                        var $icon = document.getElementById('icon-<?= $sectorData->s_hash ?>');
                        if (smTools.check.isReady($icon)) {
                            $icon.src = '<?= $newIcon ?>';
                        }
                        smTools.modal.close();
                        smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Ícone do setor modificado</p>', true);
                    </script>
                    <?php
                } else if ($update->error()) {
                    throw new ConstException($update->error(), ConstException::SYSTEM_ERROR);
                } else {
                    // Registrar log de erro porque a imagem foi salva, mas o setor não foi atualizado
                    $log->registerError(__FILE__, 'A imagem foi salva porém não houve o registro na coluna do setor');
                    throw new ConstException('No momento não foi possível salvar o ícone'
                    . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
                }
            } else {
                throw new ConstException('No momento não foi possível salvar o ícone'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('No momento não é possível localizar dados do setor alvo'
            . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo ("<script>smCore.go.reload();</script>");
            break;
        case ConstException::SYSTEM_ERROR:
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../../error/500.php');
            break;
        case ConstException::INVALID_POST:
            echo ("<script>smCore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
