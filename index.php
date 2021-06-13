<!DOCTYPE html>
<?php
/*
  if (version_compare(PHP_VERSION, '8.0.0', '<')) {
  die('A versão do PHP do servidor não é compatível.'
  . '<p>Versão atual: ' . PHP_VERSION . '</p>'
  . '<p>Versão mínima necessária: 8.0.0</p>');
  } else if (!extension_loaded('pdo') || !extension_loaded('pdo_mysql')) {
  die('Driver\'s de extensão pdo e pdo_mysql não estão instalados no servidor');
  } else if (function_exists('imagecreatefromjpeg') == false) {
  die('Biblioteca nativa GD não habilitada');
  }
 */
include ('system/config.php');
require_once ('system/function/LoadModule.php');
require_once ('system/function/BaseURI.php');

$baseUri = BaseURI();
$url = SeoData::parseUrl();

// Logar o usuário pelo cookie
$cookie = GlobalFilter::filterCookie();
$select = new Select();
$clear = new StrClean();
$user = new SmUser();
if (isset($cookie->clienthash) && $config->enable->users == 'y' && !isset($session->user)) {
    $select->query("users", "u_hash = :uh", "uh={$clear->formatStr($cookie->clienthash)}");
    if ($select->count()) {
        $userData = $select->result()[0];
        $user->setLogin([
            'hash' => $userData->u_hash,
            'mail' => $userData->u_mail,
            'name' => $userData->u_name,
            'link' => $userData->u_link,
            'level' => $userData->u_level,
            'photo' => $userData->u_photo
        ]);
    }
}
?>
<html lang="pt-BR">
    <head>
        <base href="<?= $baseUri ?>">
        <meta charset="UTF-8">
        <title><?= NAME ?></title>
        <meta name="ROBOTS" content="INDEX, FOLLOW" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="image/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link href="image/favicon.ico" rel="icon" type="image/x-icon" />

        <link href="lib/stylesheet/sm-default.css" rel="stylesheet" type="text/css" />
        <link href="lib/stylesheet/sm-libary.css" rel="stylesheet" type="text/css" />
        <link href="lib/stylesheet/icomoon.css" rel="stylesheet" type="text/css" />
        <link href="lib/stylesheet/sm-core.css" rel="stylesheet" type="text/css" />
        <link href="lib/stylesheet/media.css" rel="stylesheet" type="text/css" />
        <link href="lib/codemirror/codemirror.css" rel="stylesheet" type="text/css" />
        <link href="lib/prism/prism.css" rel="stylesheet" type="text/css" />

        <script src="lib/javascript/libary.js" type="text/javascript"></script>
        <script src="lib/javascript/post-data.js" type="text/javascript"></script>
        <script src="lib/javascript/sm-tools.js" type="text/javascript"></script>
        <script src="lib/javascript/sm-core.js" type="text/javascript"></script>
        <script src="lib/javascript/sm-user.js" type="text/javascript"></script>
        <script src="lib/javascript/sm-page.js" type="text/javascript"></script>
        <script src="lib/javascript/sm-editor.js" type="text/javascript"></script>
        <script src="lib/javascript/sm-stf.js" type="text/javascript"></script>

        <script src="lib/codemirror/codemirror.js"></script>
        <script src="lib/codemirror/mode.js" type="text/javascript"></script>
        <script src="lib/codemirror/hint.js" type="text/javascript"></script>
        <script src="lib/prism/prism.js" type="text/javascript"></script>
        <script src="lib/editor/ckeditor.js" type="text/javascript"></script>
        <script>
            const BASE_URI = '<?= $baseUri ?>';
            const MEMORY = {};
        </script>
    </head>
    <body>
        <header>
            <div class="modal" id="default-modal">
                <div class="modal-box zoom-in" style="max-width: 600px">
                    <div class="modal-header"></div>
                    <div class="modal-content over-y" id="modal-load"></div>
                </div>
            </div>
            <?php include ('modules/default/header.php'); ?>
        </header>
        <main><?php include (LoadModule($url[0])); ?></main>
        <footer>
            <?php include ('modules/default/footer.php'); ?>
            <a id="scoll-top" class="fixed cursor-pointer">
                <i class="icon-circle-up4 icn-3x text-dark-blue text-blue-hover"></i>
            </a>
            <audio id="notify-wav">
                <source src="lib/wav/notify.wav" type="audio/x-wav">
            </audio>
        </footer>

        <div id="resolucao" style="position: fixed; bottom: 0; left: 10px; padding: 10px 20px; background: black; color: white;">
            W: <div class="line-block"></div> <span class="text-red">|</span>
            <?php
            foreach ($url as $key => $value) {
                echo ("url[{$key}] = {$value} <span class=\"text-red\">|</span> ");
            }
            ?>
            <a href="teste" class="href-link">TESTES</a>
        </div>
        <script>
            var $itemOpen = new ItemOpen();
            smCore.topScroll();

            var res = document.getElementById('resolucao').children[0];
            res.innerText = window.innerWidth;
            window.onresize = function () {
                res.innerText = window.innerWidth;
            };
        </script>
    </body>
</html>
