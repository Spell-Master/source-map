<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$len = new LenMaxMin();
$code = new CreateCode();
$select = new Select();
$insert = new Insert();

$title = (isset($post->title) ? trim($post->title) : false);

try {
    if (!isset($session->admin)) {
        throw new ConstException(null, ConstException::INVALID_ACESS);
    } else if ($session->admin < $config->docCategory) {
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
    else {
        $save = [
            'hash' => $code->defCode(20) . time(),
            'title' => htmlentities($title)
        ];

        $select->query("doc_category");
        if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else {
            $count = $select->count();
            foreach ($select->result() as $value) {
                if (strtolower($value->c_title) == strtolower($save['title'])) {
                    throw new ConstException('No momento não é possível salvar a categoria'
                    . '<p class="font-small">Use um título diferente ou tente novamente mais tarde</p>', ConstException::INVALID_POST);
                }
            }
            $order = ($count += 1);
        }

        $insert->query("doc_category", [
            'c_hash' => $save['hash'],
            'c_title' => $save['title'],
            'c_order' => $order
        ]);
        if ($insert->count()) {
            ?>
            <script>
                smStf.pageAction.cancel();
                smTools.modal.close();
                smTools.scroll.top();
                smTools.ajax.send('paginator', 'modules/admin/doc/categ-paginator.php?reload=true', false);
                smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Categoria Criada</p>', true);
            </script>
            <?php
        } else if ($insert->error()) {
            throw new ConstException($insert->error(), ConstException::SYSTEM_ERROR);
        } else {
            throw new ConstException('No momento não foi possível salvar a categoria'
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
