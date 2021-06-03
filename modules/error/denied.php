<div id="error">
    <div class="fixed bg-white" style="top:0; left: 0; height: 100vh; width: 100vw; z-index:999">
        <div class="absolute pos-center padding-all" style="top:40%">
            <div class="align-center font-medium">
                <div class="text-red">
                    <i class="icon-user-lock2 icn-5x"></i>
                </div>
                <h2 class="text-red">Permissão Negada</h2>
                Você não tem autorização para acessar esse conteúdo
                <div class="margin-top text-white">
                    <a href="./" title="voltar" class="btn-danger button-block">Voltar ao Início</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var html = document.getElementById('error').innerHTML;
    document.body.innerHTML = html;
</script>