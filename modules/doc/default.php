<?php

?>

<div id="header-bottom" class="bg-dark-black">
    <div class="bottom-bar">
        <div class="container">
            <div class="row">
                <div class="col-third col-fix over-text padding-lr">
                    <div class="line-block vertical-middle">
                        <a href="###" class="font-large">TITULO</a>
                    </div>
                </div>
                <div class="col-twothird col-fix">
                    <ul class="bar-menu">
                        <li class="session-add" onclick="sm_a.newPage('<?= $app['key'] ?>');" title="Adicionar Conteúdo"></li>
                        <li data-open="session-folder" title="Sessões"></li>
                        <li data-open="session-search" title="Pesquisa"></li> 
                        <li data-open="session-menu" title="Páginas"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="session-folder">
        session-folder
    </div>

    <div class="session-search" data-open="fix">
        <div class="container padding-all-prop">
            <p class="font-large align-center gunship">Pesquisar</p>
            <hr class="border-dark-black" />
            <div class="box-x-900 margin-auto">
                <form method="POST" action="" id="search-page" onsubmit="return searchPage('app', [<?= $config->length->minSearch ?>, <?= $config->length->maxSearch ?>])">
                    <div class="row">
                        <div class="float-left">
                            <button class="btn-info box-y-50 text-white">
                                <i class="icon-search3 font-medium"></i>
                            </button>
                        </div>
                        <div class="over-not">
                            <input type="text" name="search" id="search" class="input-default" />
                            <input type="hidden" name="app" value="<?= $app['key'] ?>" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="session-menu">
        <ul id="global-menu">
            <li>
                <a
                    href="###"
                    title="TITULO"
                    id="link-">
                    title
                </a>
            </li>

        </ul>
    </div>

</div>

<div id="page-load">
<?php
var_dump($url);
?>
</div>
<script>
    //smc.globalMenu('app');
</script>
