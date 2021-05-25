<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$len = new LenMaxMin();
$valid = new StrValid();
$clear = new StrClean();
$select = new Select();
$update = new Update();

$title = (isset($post->title) ? trim($post->title) : false);
$editor = (isset($post->editor) ? PostData::parseStr($post->editor) : false);
$hash = (isset($post->hash) ? $post->hash : false);
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
    else if (!$hash) {
        throw new ConstException('Não recebido dados de $_POST[\'hash\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($hash)) {
        throw new ConstException('$_POST[\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else if (!$app) {
        throw new ConstException('Não recebido dados de $_POST[\'app\']', ConstException::SYSTEM_ERROR);
    } else if ($app !== 'css' && $app !== 'js') {
        throw new ConstException('$_POST[\'app\'] não é um modelo válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $pageHash = $clear->formatStr($hash);
        $appKey = $clear->formatStr($app);
        $pTitle = 0; // Contagem de títulos duplicados
        $save = [
            'a_title' => PostData::savePost($title),
            'a_link' => mb_strtolower($clear->formatStr($title)),
            'a_content' => PostData::savePost($post->editor),
            'a_text' => htmlentities($editor),
            'a_version' => date('Y-m-d')
        ];

        $select->query(
            "app_page",
            "a_key = :ak",
            "ak={$appKey}"
        );
        if ($select->count()) {
            foreach ($select->result() as $key => $value) {
                if ($value->a_hash == $pageHash) {
                    $pageData = $select->result()[$key];
                }
                if ($value->a_link == $save['a_link'] && $value->a_hash != $pageHash) {
                    $pTitle++;
                }
            }

            if (!isset($pageData)) {
                throw new ConstException('Não foi possível localizar a página para edição'
                . '<p class="font-small">Redirecionando...</p>', ConstException::NOT_FOUND);
            } else if ($pTitle >= 1) {
                throw new ConstException('Já existe uma página com o mesmo título em:</p>'
                . $appKey . '-padrao', ConstException::INVALID_POST);
            } else {
                $update->query("app_page", $save, "a_hash = :ah", "ah={$pageData->a_hash}");
                if ($update->count()) {
                    ?>
                    <script>
                        smStf.pageAction.cancel();
                        smTools.modal.close();
                        smTools.scroll.top();
                        smTools.ajax.send('paginator', 'modules/admin/app/paginator.php?reload=<?= $appKey ?>', false);
                        smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Página Editada</p>', true);
                    </script>
                    <?php
                } else if ($update->error()) {
                    throw new ConstException($update->error(), ConstException::SYSTEM_ERROR);
                } else {
                    throw new ConstException('No momento não foi possível salvar a página'
                    . '<p class="font-small">Tente novamente mais tarde</p>', ConstException::INVALID_POST);
                }
            }
        } else if ($select->error()) {
            throw new ConstException($select->error(), ConstException::SYSTEM_ERROR);
        } else { // NÃO EXISTE MAIS NENHUMA PÁGINA... RECARREGAR TUDO ENTÃO
            throw new ConstException(null, ConstException::INVALID_ACESS);
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
        case ConstException::NOT_FOUND:
            ?>
            <script>
                smCore.modal.error('<?= $e->getMessage() ?>', false);
                smTools.modal.hiddenX();
                setTimeout(function () {
                    smCore.go.reload();
                }, <?= (int) $config->length->reload ?>000);
            </script>
            <?php
            break;
        case ConstException::INVALID_POST:
            echo ("<script>smCore.modal.error('{$e->getMessage()}', false);</script>");
            break;
    }
}
exit();
