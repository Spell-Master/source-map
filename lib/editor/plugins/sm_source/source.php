<div class="padding-lr-min">
    <div class="padding-tb text-white">
        <button class="btn-warning button-block shadow-on-hover" onclick="saveSource()">
            <i class="icon-warning"></i> Salvar Código Fonte <i class="icon-warning"></i>
        </button>
    </div>

    <div class="editor-area">
        <textarea id="font-code" class="textarea input-default" maxlength="1000"></textarea>
    </div>

</div>

<script>
    var $textArea = document.getElementById('font-code');
    $textArea.value = unBreak(SOURCE_CODE.string);

    var $codeEditor = CodeMirror.fromTextArea($textArea, {
        lineNumbers: false,
        theme: 'mdn-like',
        mode: 'htmlmixed'
    });
    $codeEditor.focus();

    function unBreak(str) {
        return (str.replace(/<br>/g, '\r').replace(/<br \/>/g, '\r'));
    }

    function saveSource() {
        $codeEditor.save();
        sm_e.replace(SOURCE_CODE.editor, $textArea.value);
        SOURCE_CODE.editor = null;
        SOURCE_CODE.string = null;

        sml.modal.close();
    }


</script>
