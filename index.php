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

$url = SeoData::parseUrl();
?>
<html lang="pt-BR">
    <head>
        <base href="<?= BaseURI(); ?>">
        <meta charset="UTF-8">
        <title><?= NAME ?></title>
        <meta name="ROBOTS" content="INDEX, FOLLOW" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="image/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link href="image/favicon.ico" rel="icon" type="image/x-icon" />

        <link href="lib/stylesheet/sm-default.css" rel="stylesheet" type="text/css" />
        <link href="lib/stylesheet/icomoon.css" rel="stylesheet" type="text/css" />
        <link href="lib/stylesheet/sm-libary.css" rel="stylesheet" type="text/css" />
        <link href="lib/stylesheet/core.css" rel="stylesheet" type="text/css" />

        <script src="lib/javascript/sm-libary.js" type="text/javascript"></script>
        <script src="lib/javascript/core.js" type="text/javascript"></script>

    </head>
    <body>
        <header><?php include ('modules/header.php'); ?></header>
        <main>
            <?php include (LoadModule($url[0])); ?>
        </main>
        <footer>

            <div class="modal" id="default-modal">
                <div class="modal-box zoom-in" style="max-width: 600px">
                    <div class="modal-header"></div>
                    <div class="modal-content over-y" id="modal-load"></div>
                </div>
            </div>
        </footer>

        <div id="resolucao" style="position: fixed; bottom: 0; left: 10px; padding: 10px 20px; background: black; color: white;">
            W: <div class="line-block"></div> <span class="text-red">|</span>
            <?php
            foreach ($url as $key => $value) {
                echo ("url[{$key}] = {$value} <span class=\"text-red\">|</span> ");
            }
            ?>
        </div>
        <script>
            var res = document.getElementById('resolucao').children[0];
            res.innerText = window.innerWidth;
            window.onresize = function () {
                res.innerText = window.innerWidth;
            };
        </script>
    </body>
</html>
