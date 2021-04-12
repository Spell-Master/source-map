<?php
require_once (__DIR__ . '/../../../system/config.php');
$user = new SmUser();
$user->logOut();
?>
<div class="align-center">
    <?= SeoData::showProgress() ?>
    <p>Removendo dados de acesso</p>
    <p class="font-small">Aguarde...</p>
</div>
<script>
    setTimeout(function () {
       smc.go.reload();
    }, <?= (int) $config->length->reload ?>000);
</script>
