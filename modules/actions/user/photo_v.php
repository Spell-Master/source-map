<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$clear = new StrClean();
$update = new Update();

$base64 = (isset($post->base64) ? $post->base64 : false);

try {
    if (!isset($session->user->hash)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if (!isset($session->user->photo)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$base64) {
        throw new ConstException('Não recebido dados de $_POST[\'base64\']', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $userHash = $clear->formatStr($session->user->hash);

        $fileName = time() . strtolower($userHash) . '.png';
        $imageData = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $base64));
        $decode = base64_decode($imageData);

        $saveDir = __DIR__ . '/../../../uploads/photos/';
        $save = file_put_contents($saveDir . $fileName, $decode);
        if ($save) {
            $update->query(
                "users",
                ['u_photo' => $fileName],
                "u_hash = :uh",
                "uh={$userHash}"
            );
            if ($update->count()) {
                if (file_exists($saveDir . $session->user->photo)) {
                    unlink($saveDir . $session->user->photo);
                }
                $session->user->photo = $fileName;
                ?>
                <script>
                    var $photo = document.querySelectorAll('[data-photo=""]');
                    $photo.forEach(function (e) {
                        e.src = 'uploads/photos/<?= $fileName ?>';
                    });
                    smTools.modal.close();
                    smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Foto do perfil modificada</p>', true);
                </script>
                <?php
            } else if ($update->error()) {
                throw new ConstException($update->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('No momento não é possível salvar a foto do perfil'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        } else {
            throw new ConstException('No momento não é possível salvar a foto do perfil'
            . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
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
            echo ("<script>smCore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
