<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$len = new LenMaxMin();
$code = new CreateCode();
$clear = new StrClean();
$select = new Select();
$insert = new Insert();

$title = (isset($post->title) ? trim($post->title) : false);
$editor = (isset($post->editor) ? PostData::parseStr($post->editor) : false);
$app = (isset($post->app) ? $post->app : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->admin) {
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
    else if (!$editor) {
        throw new ConstException('Não recebido dados de $_POST[\'editor\']', ConstException::SYSTEM_ERROR);
    } else if ($len->strLen($editor, $config->length->minPageData, $config->length->maxPageData, '$_POST[\'editor\']')) {
        throw new ConstException($len->getAnswer(), ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$app) {
        throw new ConstException('Não recebido dados de $_POST[\'app\']', ConstException::SYSTEM_ERROR);
    } else if ($app !== 'css' && $app !== 'js') {
        throw new ConstException('$_POST[\'app\'] não é um modelo válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $save = [
            'a_hash' => $code->defCode(20) . time(),
            'a_title' => htmlentities($title),
            'a_link' => mb_strtolower($clear->formatStr($title)),
            'a_key' => $app,
            'a_content' => PostData::savePost($post->editor),
            'a_text' => htmlentities($editor),
            'a_date' => date('Y-m-d')
        ];

        $select->query(
            "app_page",
            "a_link = :al AND a_key = :ak",
            "al={$save['a_link']}&ak={$save['a_key']}"
        );
        if ($select->count()) {
            throw new ConstException('Já existe uma página com o mesmo título em ' . $save['a_key'] . '-padrao', ConstException::INVALID_POST);
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            $insert->query("app_page", $save);
            if ($insert->count()) {
                ?>
                <script>
                    smStf.pageAction.cancel();
                    smTools.modal.close();
                    smTools.scroll.top();
                    smTools.ajax.send('paginator', 'modules/admin/app/paginator.php?reload=<?= $save['a_key'] ?>', false);
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
