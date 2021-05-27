<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$valid = new StrValid();
$select = new Select();
$clear = new StrClean();
$update = new Update();

$hash = (isset($post->hash) ? trim($post->hash) : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docPage) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$hash) {
        throw new ConstException('Não recebido dados de $_POST[\'hash\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($hash)) {
        throw new ConstException('$_POST[\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $select->query("doc_pages", "p_hash = :ph", "ph={$clear->formatStr($hash)}");
        if ($select->count()) {
            $pageData = $select->result()[0];
            if ($pageData->p_status == '1') {
                $status = [
                    'lock' => '0',
                    'bg' => 'bg-light-red',
                    'color' => 'text-red',
                    'name' => 'OCULTADA',
                    'icon' => '<i class="icon-unlocked"></i> Desocultar'
                ];
            } else {
                $status = [
                    'lock' => '1',
                    'bg' => 'bg-light',
                    'color' => 'text-green',
                    'name' => 'VISÍVEL',
                    'icon' => '<i class="icon-lock4"></i> Ocultar'
                ];
            }

            $update->query(
                "doc_pages",
                ['p_status' => $status['lock']],
                "p_hash = :ph",
                "ph={$pageData->p_hash}"
            );
            if ($update->count()) {
                ?>
                <script>
                    var $row = document.getElementById('row-<?= $pageData->p_hash ?>');
                    var $span = $row.querySelector('span[data-lock="span"]');
                    var $button = $row.querySelector('a[data-lock="button"]');

                    $row.className = '<?= $status['bg'] ?>';
                    $span.className = '<?= $status['color'] ?>';
                    $span.innerHTML = '<?= $status['name'] ?>';
                    $button.innerHTML = '<?= $status['icon'] ?>';

                    smTools.modal.close();
                    smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p><?= $status['name'] ?></p>', true);
                </script>
                <?php
            } else if ($update->error()) {
                throw new ConstException($update->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('No momento não é possível modificar dados da página'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('Não encontrado dados da página alvo'
            . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo ("<script>smCore.go.reload();</script>");
            break;
        case ConstException::SYSTEM_ERROR:
            $log = new LogRegister();
            $log->registerError($e->getFile(), $e->getMessage(), 'Linha:' . $e->getLine());
            include (__DIR__ . '/../../../error/500.php');
            break;
        case ConstException::INVALID_POST:
            echo ("<script>smCore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
