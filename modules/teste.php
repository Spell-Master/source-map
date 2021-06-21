<div id="page-load">
    <?= SeoData::breadCrumbs($url) ?>
    <div class="editor-area">
        <textarea id="editor-page" name="editor" class="hide"></textarea>
    </div>
    <div class="bg-light padding-all text-white">
        <div class="row">
            <div class="col-third col-fix padding-top-min bg-aqua">
                <button
                    class="btn-dark button-small shadow-on-hover"
                    title="Inserir um vídeo"
                    onclick="editorVideo();">
                    &nbsp;<i class="icon-youtube"></i>&nbsp;
                </button>
            </div>
            <div class="col-twothird col-fix align-right bg-orange">
                <button class="btn-success shadow-on-hover">
                    <i class="icon-file-plus2"></i>
                </button>
                <button class="btn-info shadow-on-hover">
                    <i class="icon-file-eye2"></i>
                </button>
                <button class="btn-warning shadow-on-hover">
                    <i class="icon-file-minus2"></i>
                </button>
            </div>

        </div>




    </div>
</div>

<script>
    smEditor.init('editor-page', 'admin');
</script>
