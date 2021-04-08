<div id="page-load">
    <div class="container padding-all">
        <div id="resultado"></div>
        <hr />
        <p class="bold">editorA</p>
        <div class="editor-area">
            <textarea id="editorA" class="input-default"></textarea>
        </div>

        <p class="bold">editorB</p>
        <textarea id="editorB"></textarea>

    </div>
</div>
<button class="btn-default" onclick="cirarA()"> criar A</button>
<button class="btn-info" onclick="salvarA()"> Salvar A</button>
<br />
<button class="btn-default" onclick="cirarB()"> criar B</button>
<button class="btn-info" onclick="salvarB()"> Salvar B</button>

<script>
    function cirarA() {
        editor.init('editorA', 'admin');
    }
    function cirarB() {
        editor.init('editorB', 'admin');
    }

    function salvarA() {
        editor.save('editorA');
        console.log(document.getElementById('editorA').value);
    }

    function salvarB() {
        editor.save('editorB');
        console.log(document.getElementById('editorB').value);
    }

</script>
