<?php
$last = SeoData::parseUrl();
$count = count($last);
$loop = 0;
$goBack = "./";
foreach ($last as $value) {
    $loop ++;
    if ($loop != $count) {
        $goBack .= $value . '/';
    }
}
?>
<div id="error">
    <div class="fixed bg-white" style="top:0; left: 0; height: 100vh; width: 100vw; z-index:999">
        <div class="absolute pos-center padding-all" style="top:40%">
            <div class="align-center font-medium">
                <div class="text-red">
                    <i class="icon-drawer3 icn-5x"></i>
                </div>
                <h2 class="text-red">Error 500</h2>
                Ocorreu um erro interno no sitema
                <div class="margin-top text-white">
                    <a href="<?= $goBack; ?>" title="voltar" class="btn-danger button-block">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var html = document.getElementById('error').innerHTML;
    document.body.innerHTML = html;
</script>