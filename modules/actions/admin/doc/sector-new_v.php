<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$len = new LenMaxMin();
$valid = new StrValid();
$code = new CreateCode();
$clear = new StrClean();
$select = new Select();
$insert = new Insert();
$selectB = clone $select;

$title = (isset($post->title) ? trim($post->title) : false);
$category = (isset($post->category) ? trim($post->category) : false);
$editor = (isset($post->editor) ? PostData::parseStr($post->editor) : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docCategory) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$title) {
        throw new ConstException('Não recebido dados de $_POST[\'title\']', ConstException::SYSTEM_ERROR);
    } else if ($len->strLen($title, $config->length->minSectorTitle, $config->length->maxSectorTitle, '$_POST[\'title\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (!preg_match('/^([a-zA-Z À-ú 0-9 _ . -]+)$/i', $title)) {
        throw new ConstException('$_POST[\'title\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$category) {
        throw new ConstException('Não recebido dados de $_POST[\'category\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($category)) {
        throw new ConstException('$_POST[\'category\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$editor) {
        throw new ConstException('Não recebido dados de $_POST[\'editor\']', ConstException::SYSTEM_ERROR);
    } else if ($len->strLen($editor, $config->length->minSectorDesc, $config->length->maxSectorDesc, '$_POST[\'editor\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $scripts = preg_replace('/<script[^>]*>([\S\s]*?)<\/script>/', '', $post->editor);
        $save = [
            's_status' => (isset($post->view) ? 1 : 0),
            's_date' => date('Y-m-d'),
            's_hash' => $code->defCode(20) . time(),
            's_title' => htmlentities($title),
            's_link' => mb_strtolower($clear->formatStr($title)),
            's_category' => $clear->formatStr($category),
            's_info' => PostData::savePost($scripts)
        ];

        $select->query("doc_category", "c_hash = :ch", "ch={$save['s_category']}");
        $selectB->query("doc_sectors", "s_link = :sl", "sl={$save['s_link']}");

        if ($select->error() || $selectB->error()) {
            $error = "";
            $error .= (($select->error() !== null) ? '<p>' . $select->error() . '</p>' : null);
            $error .= (($selectB->error() !== null) ? '<p>' . $selectB->error() . '</p>' : null);
            throw new ConstException($error, ConstException::SYSTEM_ERROR);
        } else if (!$select->count()) {
            throw new ConstException('No momento não é possível salvar o setor na categoria selecionada', ConstException::INVALID_POST);
        } else if ($selectB->count()) {
            throw new ConstException('No momento não é possível salvar o setor'
            . '<p class="font-small">Use um título diferente ou tente novamente mais tarde</p>', ConstException::INVALID_POST);
        } else {
            $insert->query("doc_sectors", $save);
            if ($insert->count()) {
                ?>
                <script>
                    MEMORY.selectedIndex = 'all';
                    smStf.pageAction.cancel();
                    smTools.modal.close();
                    smTools.scroll.top();
                    smTools.ajax.send('paginator', 'modules/admin/doc/sector-paginator.php', false);
                    smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Setor Criado</p>', true);
                </script>
                <?php
            } else if ($insert->error()) {
                throw new ConstException($insert->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('No momento não foi possível salvar o setor'
                . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
            }
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
