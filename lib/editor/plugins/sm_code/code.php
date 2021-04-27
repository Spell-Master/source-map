<div class="row-pad">
    <div class="col-half">
        <p class="font-small">Inserir Código</p>
        <button class="btn-default button-block" style="height: 50px" onclick="insertCode();">Salvar Código</button>
    </div>
    <div class="col-half">
        <p class="font-small">Selecione a linguagem</p>
        <select class="select-options" id="select-lang">
            <option value="language-markup">HTML/XML</option>
            <option value="language-css">CSS</option>
            <option value="language-javascript">Javascript</option>
            <option value="language-php">PHP</option>
            <option value="language-sql">SQL</option>
        </select>
    </div>
</div>
<div class="padding-lr-min">
    <span class="font-small">Transcreva o código</span>
    <div class="editor-area"><textarea id="insert-code" class="textarea input-default" maxlength="1000"></textarea></div>
</div>


<script>
    var $selector = document.getElementById('select-lang');
    var $textArea = document.getElementById('insert-code');

    $selector.value = (sml.isReady(CODE_MEMORY.model) ? CODE_MEMORY.model : 'language-markup');
    $textArea.value = (sml.isReady(CODE_MEMORY.text) ? decodeURI(CODE_MEMORY.text) : '');

    var $codeEditor = CodeMirror.fromTextArea($textArea, {
        lineNumbers: false,
        theme: 'mdn-like',
        mode: getLangCode()
    });
    $codeEditor.focus();

    $selector.addEventListener('change', function (e) {
        if (e.target.value) {
            $codeEditor.setOption('mode', getLangCode(e.target.value));
        }
    }, false);
    sml.select.init();

    function getLangCode(changed) {
        switch (changed ? changed : $selector.value) {
            case 'language-css':
                return ('css');
                break;
            case 'language-javascript':
                return ('javascript');
                break;
            case 'language-php':
                return ('php');
                break;
            case 'language-sql':
                return ('sql');
                break;
            default:
                return ('htmlmixed');
                break;
        }
    }

    function insertCode() {
        $codeEditor.save();
        var $editor = CKEDITOR.instances[CODE_MEMORY.editor];
        var $codeText = ($textArea.value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        var $template = '<pre><code class="' + $selector.value + '">' + $codeText + '</code></pre><p>&nbsp;</p>';

        $editor.insertHtml($template);
        $editor.widgets.initOn($template, 'codeSnippet');

        CODE_MEMORY.model = null;
        CODE_MEMORY.text = null;

        sml.modal.close();
    }
</script>