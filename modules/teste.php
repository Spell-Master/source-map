<div id="page-load">
    <div class="container padding-all">
        <div class="padding-all">
            <div id="resultado"></div>
        </div>
        <div class="editor-area">
            <textarea id="editorA" class="input-default"></textarea>
        </div>

        <button class="btn-default" onclick="salvar()">Salvar</button>
        <button class="btn-default" onclick="limpar()">Limpar</button>
    </div>
    
    <textarea id="editorB" class="input-default"></textarea>
    <button class="btn-default" onclick="criarB()">criarB</button>
</div>


<script>

    
    
    sm_e.init('editorA', 'all');

    var resultado = document.getElementById('resultado');
    var editorA = document.getElementById('editorA');
    function salvar() {
        sm_e.save('editorA');
        resultado.innerHTML = editorA.value;
        smc.spoiler();
        Prism.highlightAll();
    }

    function limpar() {
        sm_e.replace('editorA', '');
        resultado.innerHTML = null;
        editorA.value = null;
    }
    
    function criarB() {
        sm_e.init('editorB', 'advance');
    }
</script>
