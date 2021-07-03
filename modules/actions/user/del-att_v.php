<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$valid = new StrValid();
$clear = new StrClean();
$select = new Select();
$delete = new Delete();
        
$files = (isset($post->delfile) ? $post->delfile : false);

try {
    if (!isset($session->user->hash)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if (!$valid->strInt($session->user->hash)) {
        throw new ConstException('$_SESSION[\'user\'][\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$files) {
        throw new ConstException('Não recebido dados de $_POST[\'delfile\']', ConstException::SYSTEM_ERROR);
    } else if (!count($files)) {
        throw new ConstException('$_POST[\'delfile\'] não possui índices para manipulação', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $userHash = $clear->formatStr($session->user->hash);
        $fileID = [];
        $fileDir = __DIR__ . '/../../../uploads/' . $userHash . '/';
        $count = 0;

        $select->query("uploads", "up_user = :u", "u={$userHash}");

        if ($select->count()) {
            foreach ($select->result() as $sql) {
                foreach ($files as $file) {
                    if ($file == $sql->up_name && file_exists($fileDir . $sql->up_name)) {
                        $count++;
                        $fileID[] = $sql->up_id;
                        // Apagar registro
                        $delete->query("uploads", "up_id = :id", "id={$sql->up_id}");
                        // Apagar arquivo
                        unlink($fileDir . $sql->up_name);
                    }
                }
            }
            if ($delete->count()) {
                $json = json_encode($fileID);
                ?>
                <script>
                    var $json = JSON.parse(`<?= $json ?>`),
                        $attId = document.querySelectorAll('[data-att]'),
                        $imgAtt = document.getElementById('img-att'),
                        $fileAtt = document.getElementById('file-att');

                    $attId.forEach(function (e) {
                        if ($json.includes(e.dataset.att)) {
                            e.parentNode.removeChild(e);
                        }
                    });

                    if ($imgAtt.children.length < 1) {
                        $imgAtt.innerHTML = '<div style="opacity: .5" id="not-img"><i class="icon-images3 icn-5x"></i><p class="padding-top">Você não possui imagens</p></div>';
                    }
                    if ($fileAtt.children.length < 1) {
                        $fileAtt.innerHTML = '<div style="opacity: .5" id="not-file"><i class="icon-archive icn-5x"></i><p class="padding-top">Você não possui arquivos</p></div>';
                    }

                    smTools.cart.restart();
                    smTools.modal.close();
                    smCore.notify('<i class="icon-bubble-notification2 icn-2x"></i><p><?= ($count > 1 ? "Itens Apagados" : "Item Apagado") ?></p>', true);
                </script>
                <?php
            } else if ($delete->error()) {
                throw new ConstException($delete->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('Não foi possível completar a operação'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException(null, ConstException::MISC_RETURN);
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
        case ConstException::MISC_RETURN:
            SeoData::showProgress();
            echo ("<script>smCore.go.reload()</script>");
            break;
        case ConstException::INVALID_POST:
            echo ("<script>smCore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
