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

$title = (isset($post->title) ? trim($post->title) : false);
$sector = (isset($post->sector) ? trim($post->sector) : false);
$editor = (isset($post->editor) ? PostData::parseStr($post->editor) : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admPage) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if (!isset($session->user->hash)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    }
    //
    else if (!$title) {
        throw new ConstException('Não recebido dados de $_POST[\'title\']', ConstException::SYSTEM_ERROR);
    } else if ($len->strLen($title, $config->length->minPageTitle, $config->length->maxPageTitle, '$_POST[\'title\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    } else if (!preg_match('/^([a-zA-Z À-ú 0-9 _ . -]+)$/i', $title)) {
        throw new ConstException('$_POST[\'title\'] não é válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$sector) {
        throw new ConstException('Não recebido dados de $_POST[\'sector\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($sector)) {
        throw new ConstException('$_POST[\'sector\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$editor) {
        throw new ConstException('Não recebido dados de $_POST[\'editor\']', ConstException::SYSTEM_ERROR);
    } else if ($len->strLen($editor, $config->length->minPageData, $config->length->maxPageData, '$_POST[\'editor\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    }
    //
    else {
        if ($session->admin < $config->admSource) {
            $content = PostData::savePost(preg_replace('/<script[^>]*>([\S\s]*?)<\/script>/', '', $post->editor));
        } else {
            $content = PostData::savePost($post->editor);
        }
        $save = [
            'p_status' => (isset($post->view) ? 1 : 0),
            'p_hash' => $code->defCode(20) . time(),
            'p_title' => htmlentities($title),
            'p_link' => $clear->formatStr($title),
            'p_sector' => $clear->formatStr($sector),
            'p_content' => $content,
            'p_text' => $editor,
            'p_created' => $session->user->hash,
            'p_date' => date('Y-m-d')
        ];

        $select->query(
            "doc_pages",
            "p_link = :pl AND p_sector = :ps",
            "pl={$save['p_link']}&ps={$save['p_sector']}"
        );
        if ($select->count()) {
            throw new ConstException('Já existe uma página com o mesmo título no setor selecionado', ConstException::INVALID_POST);
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            $insert->query("doc_pages", $save);
            if ($insert->count()) {
                ?>
                <script>
                    MEMORY.selectedIndex = 'all';
                    smStf.pageAction.cancel();
                    smTools.modal.close();
                    smTools.scroll.top();
                    smTools.ajax.send('paginator', 'modules/admin/doc/page-paginator.php', false);
                    smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Página Criada</p>', true);
                </script>
                <?php
            } else if ($insert->error()) {
                throw new ConstException($insert->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('No momento não foi possível salvar a página'
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
