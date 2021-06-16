<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();
$len = new LenMaxMin();
$valid = new StrValid();
$select = new Select();
$clear = new StrClean();
$update = new Update();
$user = new SmUser();
$selectB = clone $select;
$updateB = clone $update;

$title = (isset($post->title) ? trim($post->title) : false);
$sector = (isset($post->sector) ? trim($post->sector) : false);
$editor = (isset($post->editor) ? PostData::parseStr($post->editor) : false);
$hash = (isset($post->hash) ? trim($post->hash) : false);

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
    else if (!$hash) {
        throw new ConstException('Não recebido dados de $_POST[\'hash\']', ConstException::SYSTEM_ERROR);
    } else if (!$valid->strInt($hash)) {
        throw new ConstException('$_POST[\'hash\'] não é um identificador válido', ConstException::SYSTEM_ERROR);
    }
    //
    else {
        $pageHash = $clear->formatStr($hash);

        if ($session->admin < $config->admSource) {
            $content = PostData::savePost(preg_replace('/<script[^>]*>([\S\s]*?)<\/script>/', '', $post->editor));
        } else {
            $content = PostData::savePost($post->editor);
        }

        $save = [
            'p_status' => (isset($post->view) ? 1 : 0),
            'p_title' => htmlentities($title),
            'p_link' => $clear->formatStr($title),
            'p_sector' => $clear->formatStr($sector),
            'p_content' => $content,
            'p_text' => $editor,
            'p_last_edit' => $session->user->hash,
            'p_last_date' => date('Y-m-d')
        ];

        $select->query(
            "doc_sectors",
            "s_hash = :sh",
            "sh={$save['p_sector']}"
        );

        $selectB->query(
            "doc_pages",
            "p_hash != :ph AND p_link = :pl AND p_sector = :ps",
            "ph={$pageHash}&pl={$save['p_link']}&ps={$save['p_sector']}"
        );

        if ($select->error() || $selectB->error()) {
            $error = "";
            $error .= (($select->error() !== null) ? '<p>' . $select->error() . '</p>' : null);
            $error .= (($selectB->error() !== null) ? '<p>' . $selectB->error() . '</p>' : null);
            throw new ConstException($error, ConstException::SYSTEM_ERROR);
        } else if (!$select->count()) {
            throw new ConstException('Não foi possível localizar o setor para edição'
            . '<p class="font-small">Tente selecionar outro setor</p>', ConstException::INVALID_POST);
        } else if ($selectB->count()) {
            throw new ConstException('No momento não é possível salvar a página'
            . '<p class="font-small">Use um título diferente ou selecione outro setor</p>', ConstException::INVALID_POST);
        } else {
            $sectorData = $select->result()[0];
            $update->query("doc_pages", $save, "p_hash = :ph", "ph={$pageHash}");

            if ($update->count()) {
                /////////////////////////
                // Registrar atividade
                /////////////////////////
                // Atualizar os links das atividades vinculadas a página
                $updateB->query(
                    "users_activity",
                    ['ua_link' => $sectorData->s_link . '/' . $save['p_link']],
                    "ua_bound = :ub",
                    "ub={$pageHash}"
                );
                // Definir nova atividade
                $user->setActivity(
                    $clear->formatStr($session->user->hash),
                    $pageHash,
                    htmlentities('Editou a página ' . $title . ' em <span class="bold">' . $sectorData->s_title) . '</span>',
                    $sectorData->s_link . '/' . $save['p_link'],
                    SeoData::longText($editor, $config->length->longStr)
                );
                ?>
                <script>
                    MEMORY.selectedIndex = 'all';
                    smStf.pageAction.cancel();
                    smTools.modal.close();
                    smTools.scroll.top();
                    smTools.ajax.send('paginator', 'modules/admin/doc/page-paginator.php', false);
                    smCore.notify('<i class="icon-bubble-notification icn-2x"></i><p>Página Editada</p>', true);
                </script>
                <?php
            } else if ($update->error()) {
                throw new ConstException($update->error(), ConstException::SYSTEM_ERROR);
            } else {
                throw new ConstException('Não foi possível editar a página'
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
