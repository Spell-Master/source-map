<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);
$post = GlobalFilter::filterPost();

$valid = new StrValid();
$select = new Select();
$delete = new Delete();

$hash = (isset($post->hash) ? $post->hash : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docSector) {
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
        $sectorHash = htmlentities($hash);

        $select->query("doc_pages", "p_sector = :ps", "ps={$sectorHash}");
        if ($select->count()) {
            throw new ConstException('Não é possível apagar o setor enquanto houver páginas anexadas a ele'
            . '<p class="font-small">As páginas devem ser removidas do setor para poder apagar-lo</p>', ConstException::INVALID_POST);
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            $delete->query("doc_sectors", "s_hash = :sh", "sh={$sectorHash}");
            if ($delete->count()) {
                ?>
                <script>
                    smStf.pageAction.cancel();
                    smTools.modal.close();
                    smTools.scroll.top();
                    smTools.ajax.send('paginator', 'modules/admin/doc/sector-paginator.php', false);
                    smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Setor Apagado</p>', true);
                </script>
                <?php
            } else if ($delete->error()) {
                throw new ConstException($delete->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('Não é possível apagar o setor'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
        }
    }
} catch (ConstException $e) {
    switch ($e->getCode()) {
        case ConstException::INVALID_ACESS:
            echo("<script>smCore.go.reload();</script>");
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
