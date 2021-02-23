/* 
 * sripts para serem adicionados ao sm-libary.js
 * estão aqui enquanto durar o desenvolvimento
 */
/**
 * ****************************************************
 * * @Class Accordion
 * * @author Spell-Master (Omar Pautz)
 * * @copyright 2018
 * * @version 2.1 (2020)
 * ****************************************************
 * * Executa efeito sanfona em elementos
 * ****************************************************
 */

var Accordion = function () {

    var $button = document.getElementsByClassName('acc-button');
    var $this = {
        'target': null,
        'next': null,
        'last': null,
        'copy': null,
        'height': null
    };

    queryButtons();

    /**
     * ************************************************
     * @Method: Obtem os botões do cabeçalho
     *  e adiciona o evento click em cada.
     * ************************************************
     */
    function queryButtons() {
        for (var $i = 0; $i < $button.length; $i++) {
            $button[$i].addEventListener('click', togleContainer, false);
        }
    }

    /**
     * ************************************************
     * @Method: Aciona as funções para expandir ou
     *  contrair os elementos.
     * @param {OBJ} e 
     * Referencia ao evento do elemento
     *  clicado.
     * ************************************************
     */
    function togleContainer(e) {
        $this.target = e.target;
        $this.next = $this.target.nextElementSibling;
        if ($this.last == $this.next) {
            closeOpen();
        } else {
            if ($this.last) {
                closeOpen();
            }
            cloneNode();
            openTarget();
        }
    }

    /**
     * ************************************************
     * @Method: Cria temporariamente um elemento
     *  cópia do alvo na expansão para obter sua altura
     * ************************************************
     */
    function cloneNode() {
        $this.copy = $this.next.cloneNode(true);
        $this.copy.setAttribute('style', 'height:auto; visibility:visible');
        $this.next.parentNode.appendChild($this.copy);
        $this.height = $this.copy.offsetHeight;
        $this.next.parentNode.removeChild($this.copy);
    }

    /**
     * ************************************************
     * @Method: Contrai o elemento que estiver
     *  expandido.
     * ************************************************
     */
    function closeOpen() {
        $this.last.previousElementSibling.classList.remove('active');
        $this.last.style.height = 0 + 'px';
        $this.last = null;
        $this.height = 0;
    }

    /**
     * ************************************************
     * @Method: Expande o elemento alvo
     * ************************************************
     */
    function openTarget() {
        $this.target.classList.add('active');
        $this.next.style.height = $this.height + 'px';
        $this.last = $this.next;
    }

    /**
     * ************************************************
     * @Method: Forca a expansão de um elemento.
     * @param {INT} p
     *  Índice do elemento para expandir
     * ************************************************
     */
    this.forceOpen = function (p) {
        var $n;
        if (p) {
            $n = parseInt(p - 1);
        } else {
            $n = 0;
        }
        $this.next = document.getElementsByClassName('acc-container')[$n];
        $this.last = $this.next;
        $this.target = $this.next.previousElementSibling;
        cloneNode();
        openTarget();
    };
};

/**
 * ****************************************************
 * * @Class AjaxRequest
 * * @author Spell-Master (Omar Pautz)
 * * @copyright 2018
 * * @version 4.1 (2020)
 * ****************************************************
 * * Executa Asynchronous Javascript and Xml
 * ****************************************************
 */

var AjaxRequest = function () {
    var $this = {
        'http': null,
        'loadID': null,
        'file': null,
        'response': null,
        'url': null,
        'form': null,
        'head': null,
        'loading': null,
        'vetor': [null]
    };

    /**
     * ************************************************
     * @Method: Requisita um arquivo e o exibe o
     *  mesmo em um local expecífico.
     *  
     * @public
     * ************************************************
     * 
     * @param {STR} inId
     *  Elemento#ID onde o arquivo deve ser aberto.
     * @param {STR} file
     *  Arquivo que será aberto.
     * ************************************************
     */
    function open(inId, file) {
        if (!inId) {
            console.warn('Parâmetro "inId" não expecificado');
        } else if (!file) {
            console.warn('Parâmetro "file" não expecificado');
        } else {
            $this.loadID = document.getElementById(inId);
            $this.file = file;
            requestGet();
        }
        return (false);
    }

    /**
     * ************************************************
     * @Method: Requisita um arquivo e o exibe o mesmo
     *   em um local expecífico.
     *  Animação de progresso no local onde o arquivo
     *  será aberto.
     *  
     * @public
     * ************************************************
     *  
     * @param {STR} inId
     *  Elemento#ID onde o arquivo deve ser aberto.
     *  
     * @param {STR} file
     *  Arquivo que será aberto.
     *  
     * @param {STR} url (opcional)
     *  Quando informado adicionará a string a barra
     *  de navegação.
     * ************************************************
     */
    function send(inId, file, url) {
        if (!inId) {
            console.warn('Parâmetro "inId" não expecificado.');
        } else if (!file) {
            console.warn('Parâmetro "file" não expecificado.');
        } else if ($this.http instanceof XMLHttpRequest) {
            console.warn('Já existe uma requisição de protocolo em andamento.');
        } else {
            $this.loadID = document.getElementById(inId);
            $this.url = (url ? url : null);
            $this.file = file;
            $this.vetor = ['send', 555];
            requestGet();
        }
        return (false);
    }

    /**
     * ************************************************
     * @Method: Requisita um arquivo e o exibe o mesmo
     *  em um local expecífico.
     *  Animação suspensa no canto inferior esquerdo
     *  da página.
     *  
     * @public
     * ************************************************
     * 
     * @param {STR} inId
     *  Elemento#ID onde o arquivo deve ser aberto.
     * @param {STR} file
     *  Arquivo que será aberto.
     *  
     * @param {STR} url (opcional)
     *  Quando informado adicionará a string a barra
     *  de navegação.
     * ************************************************
     */
    function pop(inId, file, url) {
        if (!inId) {
            console.warn('Parâmetro "div" não expecificado.');
        } else if (!file) {
            console.warn('Parâmetro "file" não expecificado.');
        } else if ($this.http instanceof XMLHttpRequest) {
            console.warn('Já existe uma requisição de protocolo em andamento.');
        } else {
            $this.loadID = document.getElementById(inId);
            $this.url = (url ? url : null);
            $this.file = file;
            $this.vetor = ['pop', 'ccc'];
            requestGet();
        }
        return (false);
    }

    /**
     * ************************************************
     * @Method: Envia os dados de um formulário para
     *  outro arquivo.
     *  Animação cobre o formulário.
     *  
     *  @public
     * ************************************************
     * @param {STR} form
     *  Elemento#ID do formuário.
     *  
     * @param {STR} inId
     *  Elemento#ID onde o arquivo deve ser aberto.
     *  
     * @param {STR} file
     *  Arquivo que será aberto e os dados devem ser
     *  enviados.
     * ************************************************
     */
    function form(form, inId, file) {
        if (!form) {
            console.warn('Parâmetro "form" não expecificado.');
        } else if (!inId) {
            console.warn('Parâmetro "inId" não expecificado.');
        } else if (!file) {
            console.warn('Parâmetro "file" não expecificado.');
        } else if ($this.http instanceof XMLHttpRequest) {
            console.warn('Já existe uma requisição de protocolo em andamento.');
        } else {
            $this.form = document.getElementById(form);
            $this.loadID = document.getElementById(inId);
            $this.file = file;
            $this.head = 'form_id=' + form;
            $this.vetor = ['form', 555];
            formElements();
            requestForm();
        }
        return (false);
    }

    /**
     * ************************************************
     * @Method: Envia os dados de um formulário para
     *  outro arquivo.
     *  Animação no local onde o arquivo será aberto.
     *  
     * @public
     * ************************************************
     * 
     * @param {STR} form
     *  Elemento#ID do formuário.
     *  
     * @param {STR} inId
     *  Elemento#ID onde o arquivo deve ser aberto.
     *  
     * @param {STR} file
     *  Arquivo que será aberto e os dados devem ser
     *  enviados.
     * ************************************************
     */
    function formSend(form, inId, file) {
        if (!form) {
            console.warn('Parâmetro "form" não expecificado.');
        } else if (!inId) {
            console.warn('Parâmetro "inId" não expecificado.');
        } else if (!file) {
            console.warn('Parâmetro "file" não expecificado.');
        } else if ($this.http instanceof XMLHttpRequest) {
            console.warn('Já existe uma requisição de protocolo em andamento.');
        } else {
            $this.form = document.getElementById(form);
            $this.loadID = document.getElementById(inId);
            $this.loadID.innerHTML = null;
            $this.file = file;
            $this.head = 'form_id=' + form;
            $this.vetor = ['formSend', 555];
            formElements();
            requestForm();
        }
        return (false);
    }

    /**
     * ************************************************
     * @Method: Codifica o identificador de recurso
     *  uniforme em sequências de escape que
     *  representam a codificação UTF-8.
     *  Escapa todos os caracteres que não são
     *  alfabéticos, dígitos ou decimais.
     * 
     * @public
     * ************************************************
     * 
     * @param {STR} str
     * Valor de entrada do parâmetro URI.
     * ************************************************
     */
    function encodeURI(str) {
        var $encode = encodeURIComponent(str);
        return ($encode.replace(/['()]/g, escape).replace(/\*/g, '%2A').replace(/%(?:7C|60|5E)/g, unescape));
    }

    /**
     * ************************************************
     * @Method: Requisita os processos para os
     *  métodos de execução padrão via GET.
     *  
     * @private
     * ************************************************
     */
    function requestGet() {
        initXMLHR();
        $this.http.addEventListener('readystatechange', responseStatus, false);
        $this.http.open('GET', $this.file, true);
        $this.http.send();
    }

    /**
     * ************************************************
     * @Method: Requisita os processos para os
     *  métodos de execução de formulários via POST.
     *  
     * @private
     * ************************************************
     */
    function requestForm() {
        initXMLHR();
        $this.http.addEventListener('readystatechange', responseStatus, false);
        $this.http.open('POST', $this.file, true);
        $this.http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        $this.http.send($this.head);
    }

    /**
     * ************************************************
     * @Method: Inicia o protocolo HttpRequest e cria
     *  a base de tipo de exibição quando disponível.
     *  
     * @private
     * ************************************************
     */
    function initXMLHR() {
        $this.http = new XMLHttpRequest;
        if ($this.http.overrideMimeType) {
            $this.http.overrideMimeType('text/html');
        }
        return ($this.http);
    }

    /**
     * ************************************************
     * @Method: Solicita funções de acordo com o
     *  status da requisição.
     * 
     *  - Carregando -> Solicita animação de processo. 
     *  - Completado -> Armazena a resposta e solicita
     *    o completo processamento.
     *  
     * @private
     * ************************************************
     */
    function responseStatus() {
        if ($this.vetor && ($this.http.readyState === 1)) {
            setProgress();
        } else if ($this.http.status === 404) {
            console.warn('Arquivo [' + $this.file + '] não encontrado!');
        } else if ($this.http.status === 500) {
            console.warn('Erro na resposta do servidor');
        } else if (($this.http.readyState === 4) && ($this.http.status === 200)) {
            $this.response = $this.http.responseText;
            completeProcess();
        }
    }

    /**
     * ************************************************
     * @Method: Cria diferentes tipos de animações
     *  conforme cada método.
     * 
     * @private
     * ************************************************
     */
    function setProgress() {
        var $svg = '<svg class="load-pre" viewBox="25 25 50 50"><circle class="load-path" cx="50" cy="50" r="20" fill="none" stroke="#' + $this.vetor[1] + '" stroke-width="4" stroke-miterlimit="10" /></svg>';
        switch ($this.vetor[0]) {
            case 'send':
                $this.loadID.innerHTML = '<div class="load-local">' + $svg + '</div>';
                break;
            case 'pop':
                $this.loading = document.createElement('div');
                document.body.appendChild($this.loading);
                $this.loading.classList.add('load-pop');
                $this.loading.innerHTML = '<div class="progress-text">Carregando...</div>' + $svg;
                break;
            case 'form':
                $this.form.classList.add('form-conter');
                $this.loading = document.createElement('div');
                $this.loading.classList.add('load-form');
                $this.form.appendChild($this.loading);
                $this.loading.innerHTML = '<div class="fade-progress">' + $svg + '</div>';
                break;
            case 'formSend':
                $this.form.classList.add('form-conter');
                $this.loading = document.createElement('div');
                $this.loading.classList.add('load-form');
                $this.form.appendChild($this.loading);
                $this.loadID.innerHTML = '<div class="load-local">' + $svg + '</div>';
                break;
        }
    }

    /**
     * ************************************************
     * @Method: Exibe o conteúdo da requisição.
     *  Quando existem animações de processo remove
     *  primeiro essas animações só então exibe o
     *  conteúdo.
     * 
     * @private
     * ************************************************
     */
    function completeProcess() {
        if ($this.vetor[0]) {
            setTimeout(function () {
                if ($this.vetor[0] === 'pop') {
                    document.body.removeChild($this.loading);
                } else if ($this.vetor[0] === 'form' || $this.vetor[0] === 'formSend') {
                    for (var $i = 0; $i < $this.form.elements.length; $i++) {
                        $this.form.elements[$i].disabled = false;
                    }
                    $this.form.removeChild($this.loading);
                }
                $this.loadID.innerHTML = $this.response;
                $this.vetor = [null];
                $this.http = null;
                loadScripts();
                if ($this.url) {
                    window.history.replaceState(null, null, $this.url);
                    $this.url = null;
                }
            }, 1000);
        } else {
            $this.loadID.innerHTML = $this.response;
            loadScripts();
            $this.http = null;
        }
    }

    /**
     * ************************************************
     * @Method: Procura elementos javascript no
     *  arquivo aberto pela requisição e realoca os
     *  mesmos para correto funcionamento.
     * 
     * @private
     * ************************************************
     */
    function loadScripts() {
        var $j = $this.response.indexOf('<script', 0), $src, $idxSrc, $endSrc, $strSrc;
        oldScripts();
        while ($j != -1) {
            $src = document.createElement('script');
            $idxSrc = $this.response.indexOf(' src', $j);
            $j = $this.response.indexOf('>', $j) + 1;
            if ($idxSrc < $j && $idxSrc >= 0) {
                $j = $idxSrc + 4;
                $endSrc = $this.response.indexOf('.js', $j) + 3;
                $strSrc = $this.response.substring($j, $endSrc);
                $strSrc = $strSrc.replace('=', '')
                        .replace(' ', '')
                        .replace('"', '')
                        .replace('"', '')
                        .replace("'", '')
                        .replace("'", '')
                        .replace('>', '');
                $src.src = $strSrc;
            } else {
                $endSrc = $this.response.indexOf('</script>', $j);
                $strSrc = $this.response.substring($j, $endSrc);
                $src.text = $strSrc;
            }
            $this.loadID.appendChild($src);
            $j = $this.response.indexOf('<script', $endSrc);
            $src = null;
        }
    }

    /**
     * ************************************************
     * @Method: Localiza os antigos elementos
     *  javascript não funcionais da requisição e limpa
     *  eles para melhor leitura de dados pelo
     *  navegador.
     * 
     * @private
     * ************************************************
     */
    function oldScripts() {
        var $os = $this.loadID.getElementsByTagName('script'), $k;
        for ($k = $os.length - 1; $k >= 0; $k--) {
            $os[$k].parentNode.removeChild($os[$k]);
        }
    }

    /**
     * ************************************************
     * @Method: Procura elementos input em formulários
     *  e adiciona eles no cabeçalho da requisição.
     * 
     * @augment: No caso de {input type="checkbox"} ou
     *  {input type="radio"}
     *  quando não marcados seu valor não será enviado
     *  pela função.
     * 
     * @private
     * ************************************************
     */
    function formElements() {
        var $i, $checkbox, $radio;
        for ($i = 0; $i < $this.form.elements.length; $i++) {
            $this.form.elements[$i].disabled = true;
            if ($this.form.elements[$i].type === 'checkbox') {
                if ($this.form.elements[$i].checked) {
                    $checkbox = $this.form.elements[$i].value;
                    $this.head += '&' + $this.form.elements[$i].name + '=' + $checkbox;
                }
            } else if ($this.form.elements[$i].type === 'radio') {
                if ($this.form.elements[$i].checked) {
                    $radio = $this.form.elements[$i].value;
                    $this.head += '&' + $this.form.elements[$i].name + '=' + $radio;
                }
            } else {
                $this.head += '&' + $this.form.elements[$i].name + '=' + encodeURI($this.form.elements[$i].value);
            }
        }
    }

    /**
     * ************************************************
     * @return {OBJ}
     * Métodos públicos
     * ************************************************
     */
    this.open = open;
    this.send = send;
    this.pop = pop;
    this.form = form;
    this.formSend = formSend;
    this.encodeURI = encodeURI;
};

/**
 * ****************************************************
 * * @Class FileTransfer
 * * @author Spell-Master (Omar Pautz)
 * * @copyright 2018
 * * @version 1.1 (2020)
 * ****************************************************
 * * Executa transferência de arquivos.
 * Servidor X usuário (download)
 * Usuário X servidor (upload)
 * ****************************************************
 */

var FileTransfer = function () {

    var $data = {
        request: null,
        file: null,
        name: null,
        cancel: null,
        type: null,
        div: null,
        percent: null,
        bar: null
    }, $upload = {
        form: null,
        result: null,
        input: null,
        response: null
    }, $download = {
        url: null,
        link: null
    };


    /**
     * ************************************************
     * @function: Envia arquivos do hadware do usuário
     *  para o servidor.
     *  
     * @public
     * ************************************************
     * 
     * @param {STR} form
     *  Elemento#ID do formulário de envio.
     *  
     * @param {STR} sendTo
     *  Arquivo que será processará os dados do envio
     *  pelo lado do servidor.
     *  
     * @param {BOLL} cancel (true/false/null)
     *  Durante o processo de envio um botão para
     *  cancelar deve ser ativo?
     *  
     * @param {STR/BOLL} result
     *  Elemento#ID do local do html onde o arquivo
     *  de processamento deve ser mostrado.
     * ************************************************
     */
    this.upload = function (form, sendTo, result, cancel) {
        if (!form) {
            console.warn('ID do formulário não expecificado');
        } else if (!sendTo) {
            console.warn('Arquivo de recebimento não expecificado');
        } else if (!($data.request instanceof XMLHttpRequest)) {
            $data.request = new XMLHttpRequest();
            $upload.form = new FormData();
            $data.cancel = (cancel ? true : null);
            $upload.result = (result ? result : null);
            $data.type = 'upload';
            $upload.input = document.getElementById(form).querySelector('input[type="file"]');
            if (!$upload.input.value) {
                clearVar();
                console.warn('Nenhum arquivo selecionado');
            } else {
                $data.file = $upload.input.files[0];
                $data.name = $data.file.name;
                $upload.form.append($upload.input.name, $data.file);
                createProgress();
                queryDOM();
                $data.request.upload.addEventListener('progress', transferProgress, false);
                $data.request.addEventListener('readystatechange', transferComplete, false);

                $data.request.responseType = 'text';
                $data.request.open('POST', sendTo, true);
                $data.request.send($upload.form);
            }
        } else {
            console.warn('Já existe um processo em andamento');
        }
        return (false);
    };

    /**
     * ************************************************
     * @function: Envia arquivos do servidor remoto
     *  para hadware do usuário.
     *  
     * @public
     * ************************************************
     * 
     * @param {STR} file
     *  Arquivo para ser enviado.
     *  - Informar extensão.
     *  - Informar diretórios (se houver)
     *  - Expl.: 'pasta/arquivos/envio.zip'
     * 
     * @param {BOLL} cancel (true/false/null)
     *  Durante o processo de envio um botão para
     *  cancelar deve ser ativo?
     * ************************************************
     */
    this.download = function (file, cancel) {
        if (!file) {
            console.warn('Arquivo de envio não expecificado');
        } else if (!($data.request instanceof XMLHttpRequest)) {
            $data.request = new XMLHttpRequest();
            var $fileArr = file.split('/').reverse();
            $data.file = file;
            $data.name = $fileArr[0];
            $data.cancel = (cancel ? cancel : false);
            $data.type = 'download';
            createProgress();
            queryDOM();
            $data.request.addEventListener('progress', transferProgress, false);
            $data.request.addEventListener('readystatechange', transferComplete, false);
            $data.request.responseType = 'blob';
            $data.request.open('GET', file, true);
            $data.request.send();
        } else {
            console.warn('Já existe um processo em andamento');
        }
        return (false);
    };

    /**
     * ************************************************
     * @function: Cria um elemento para mostrar status
     *  da transferência em progresso.
     *  Adiciona o botão de interrupção quando definido
     *  no upload ou download.
     *  
     * @private
     * ************************************************
     */
    function createProgress() {
        $data.div = document.createElement('div');
        $data.div.id = 'transfer-progress';
        $data.div.innerHTML = '<div class="progress-text"></div><div class="progress-file"></div><div class="progress-percent"></div><div class="progress-line"><div class="progress-bar"></div></div>';
        if ($data.cancel) {
            $data.div.innerHTML += '<button class="progress-cancel">Cancelar</button>';
            $data.div.querySelector('.progress-cancel').addEventListener('click', stopTrasnfer, false);
        }
        document.body.appendChild($data.div);
    }

    /**
     * ************************************************
     * @function: Obtem dados do monitor de progresso.
     *  
     * @private
     * ************************************************
     */
    function queryDOM() {
        if ($data.type === 'upload') {
            $data.div.querySelector('.progress-text').innerText = 'Enviando Arquivo';
        } else if ($data.type === 'download') {
            $data.div.querySelector('.progress-text').innerText = 'Recebendo Arquivo';
        }
        $data.div.querySelector('.progress-file').innerText = $data.name;
        $data.percent = $data.div.querySelector('.progress-percent');
        $data.bar = $data.div.querySelector('.progress-bar');
    }

    /**
     * ************************************************
     * @function: Mostra o percentual da transferência
     *  no monitor.
     *  
     * @private
     * ************************************************
     * @param {OBJ} e
     *  Dados do evento "progress" 
     * ************************************************
     */
    function transferProgress(e) {
        var $upProgress;
        if (e.lengthComputable) {
            $upProgress = Math.round((e.loaded / e.total) * 100);
            $data.percent.innerText = $upProgress + '% completado';
            $data.bar.style.width = $upProgress + '%';
        }
    }

    /**
     * ************************************************
     * @function: Quando completado o processo de
     *  leitura dos dados toma as ações baseadas no
     *  status da requisição em relação as definições
     *  de variáveis.
     *  
     * @private
     * ************************************************
     */
    function transferComplete() {
        if ($data.request.status === 404) {
            console.warn('Não foi possível localizar o arquivo (' + $data.name + ')');
            stopTrasnfer();
        } else if (($data.request.readyState === 4) && ($data.request.status === 200)) {
            if ($data.cancel) {
                $data.div.querySelector('.progress-cancel').style.display = 'none';
            }
            setTimeout(function () {
                if ($upload.result) {
                    $upload.response = $data.request.responseText;
                    sincHtml();
                }
                if ($data.type === 'upload') {
                    removeProgress();
                } else if ($data.type === 'download') {
                    $download.url = window.URL.createObjectURL($data.request.response);
                    sendFile();
                }
            }, 1000);
        }
    }

    /**
     * ************************************************
     * @function: Para o processo de download cria
     *  um novo elemento que requisita o arquivo como
     * link html, forçando um click falso no elemento.
     *  
     * @private
     * ************************************************
     */
    function sendFile() {
        $download.link = document.createElement('a');
        $download.link.href = $data.file;
        $download.link.download = $data.name;
        document.body.appendChild($download.link);
        $download.link.click();
        removeProgress();
    }

    /**
     * ************************************************
     * @function: Sincorniza a abertura do arquivo
     *  via parâmentro do upload, exibindo o mesmo
     *  como como html limpo.
     * 
     * @augments : Essas ações são as mesmas usadas
     *  em outro arquivo javascripr de manipulação
     *  assíncrona (AjaxRequest.js)
     *  
     * @private
     * ************************************************
     */
    function sincHtml() {
        var $load = document.getElementById($upload.result);
        if ($load !== null) {
            $load.innerHTML = $data.request.responseText;
            var $j = $upload.response.indexOf('<script', 0), $src, $idxSrc, $endSrc, $strSrc;
            var $os = $load.getElementsByTagName('script'), $k;
            for ($k = $os.length - 1; $k >= 0; $k--) {
                $os[$k].parentNode.removeChild($os[$k]);
            }
            while ($j != -1) {
                $src = document.createElement('script');
                $idxSrc = $upload.response.indexOf(' src', $j);
                $j = $upload.response.indexOf('>', $j) + 1;
                if ($idxSrc < $j && $idxSrc >= 0) {
                    $j = $idxSrc + 4;
                    $endSrc = $upload.response.indexOf('.js', $j) + 3;
                    $strSrc = $upload.response.substring($j, $endSrc);
                    $strSrc = $strSrc.replace('=', '')
                            .replace(' ', '')
                            .replace('"', '')
                            .replace('"', '')
                            .replace("'", '')
                            .replace("'", '')
                            .replace('>', '');
                    $src.src = $strSrc;
                } else {
                    $endSrc = $upload.response.indexOf('</script>', $j);
                    $strSrc = $upload.response.substring($j, $endSrc);
                    $src.text = $strSrc;
                }
                $load.appendChild($src);
                $j = $upload.response.indexOf('<script', $endSrc);
                $src = null;
            }
        } else {
            console.warn('Não é possível determinar sucesso do envio, elemento de #ID de validação é "null"');
        }
    }

    /**
     * ************************************************
     * @function: Elimina elementos criados pelos
     *  métodos.
     *  
     * @private
     * ************************************************
     */
    function removeProgress() {
        if ($data.cancel) {
            $data.div.querySelector('.progress-cancel').removeEventListener('click', stopTrasnfer);
        }
        if ($data.type === 'download') {
            if ($download.url) {
                window.URL.revokeObjectURL($download.url);
                document.body.removeChild($download.link);
            }
        }
        document.body.removeChild($data.div);
        clearVar();
    }

    /**
     * ************************************************
     * @function: Define todas dados usadas para nada.
     *  
     * @private
     * ************************************************
     */
    function clearVar() {
        if ($data.type === 'upload') {
            $upload.form = null;
            $upload.result = null;
            $upload.input = null;
        } else if ($data.type === 'download') {
            $download.url = null;
            $download.link = null;
        }
        $data.request = null;
        $data.file = null;
        $data.name = null;
        $data.cancel = null;
        $data.type = null;
        $data.div = null;
        $data.bar = null;
        $data.percent = null;
        $data.bar = null;
    }

    /**
     * ************************************************
     * @function: Cancela/ para a aquisição dos
     *  arquivos.
     *  
     * @private
     * ************************************************
     */
    function stopTrasnfer() {
        $data.request.abort();
        removeProgress();
    }
};

/**
 * ****************************************************
 * * @Class ImageCut
 * * @author Spell-Master (Omar Pautz)
 * * @copyright 2019
 * * @version 2.0 (2020)
 * ****************************************************
 * * Executa corte de imagens.
 * 
 * 
 * @param {STR} img
 * * #ID da imagem para trabalho.
 * ****************************************************
 */
var ImageCut = function (img) {

    var $imgTarget = document.getElementById(img);
    var $isReady = false;
    var $isCut = false;
    var $limiter;
    var $box;
    var $img;
    var $canvas;
    var $data = {
        ratio: 1.0,
        left: 0,
        top: 0
    };
    var $status = {
        imgLeft: 0,
        imgTop: 0,
        imgWidth: 0,
        imgHeight: 0,
        boxLeft: 0,
        boxTop: 0,
        posX: 0,
        posY: 0
    };
    var $zoom = {
        boxWidth: 0,
        boxHeight: 0,
        imgWidth: 0,
        imgHeight: 0,
        left: 0,
        top: 0,
        right: 0,
        bottom: 0
    };

    if (typeof $imgTarget === 'undefined' && $imgTarget === null) {
        console.error('Não foi possível identificar a imagem para corte');
        return;
    } else {
        $imgTarget.addEventListener('load', initCut, false);
    }

    /**
     * ************************************************
     * @function: Inicia as funções necessárias
     *  
     * @private
     * ************************************************
     * 
     * @param {OBJ} e (Não utilizável)
     * Imagem vinda do elemento #id requisitado
     * ************************************************
     */
    function initCut(e) {
        if (targetSize('w') < 210) {
            console.warn('A imagem de corte deve possuir pelo menos 210 pixel\'s de largura');
        } else if (targetSize('h') < 210) {
            console.warn('A imagem de corte deve possuir pelo menos 210 pixel\'s de altura');
        } else {
            $isReady = true;
            newComponents();
            setProperties();
            insertComponents();
            imgPos(targetSize('w') / 2 - (200 / 2), targetSize('h') / 2 - (200 / 2));
            getEvents();
        }
    }

    /**
     * ************************************************
     * @function: Obtem as dimenções da imagem.
     * 
     * @private
     * ************************************************
     * 
     * @param {STR} size (w ? h)
     * Retorna a largura ou altura da imagem
     * ************************************************
     */
    function targetSize(size) {
        return Math.ceil((size === 'w' ? $imgTarget.offsetWidth : $imgTarget.offsetHeight));
    }

    /**
     * ************************************************
     * @function: Cria os elementos no documento
     * para usos.
     * 
     * @private
     * ************************************************
     */
    function newComponents() {
        $limiter = document.createElement('div');
        $box = document.createElement('div');
        $img = new Image();
        $canvas = document.createElement('canvas');
    }

    /**
     * ************************************************
     * @function: Define as características dos
     * elementos criados.
     * 
     * @private
     * ************************************************
     */
    function setProperties() {
        var $this = {
            w: targetSize('w'),
            h: targetSize('h')
        };
        $imgTarget.classList.add('cut-focus');
        $imgTarget.draggable = false;
        $limiter.classList.add('cut-limiter');
        $limiter.setAttribute('style', 'max-width:' + $this.w + 'px; max-height:' + $this.h + 'px');
        $box.classList.add('cut-box');
        $img.src = $imgTarget.src;
        $img.draggable = false;
        $img.setAttribute('style', 'width:auto; height:auto; max-width:' + $this.w + 'px; max-height:' + $this.h + 'px');
    }

    /**
     * ************************************************
     * @function: Anexa os elementos criados ao
     * documento.
     * 
     * @private
     * ************************************************
     */
    function insertComponents() {
        $imgTarget.parentNode.insertBefore($limiter, $imgTarget.nextSibling);
        $limiter.appendChild($box);
        $box.appendChild($img);
        $limiter.appendChild($imgTarget);
    }

    /**
     * ************************************************
     * @function: Posiciona a imagem criada dentro do
     * limitador.
     * 
     * @private
     * ************************************************
     * 
     * @param {INT} left
     *  Distância a esqueda
     * @param {INT} top
     *  Distância ao topo
     * ************************************************
     */
    function imgPos(left, top) {
        $data.left = -left * $data.ratio;
        $data.top = -top * $data.ratio;
        $img.style.top = -top + 'px';
        $img.style.left = -left + 'px';
    }

    /**
     * ************************************************
     * @function: Obtem os eventos de arraste e
     *  alteração no tamanho da caixa de corte.
     * 
     * @private
     * ************************************************
     */
    function getEvents() {
        $box.addEventListener('mousedown', startEvents, false);
        $box.addEventListener('touchstart', startEvents, false);
        $box.addEventListener('wheel', scrollResize, false);
        document.addEventListener('keypress', keyResize, false);
    }

    /**
     * ************************************************
     * @function: Inicia os eventos de movimento
     *  quando segurada a caixa de corte.
     * 
     * @private
     * ************************************************
     * 
     * @param {OBJ} e
     * Evento disparado
     * ************************************************
     */
    function startEvents(e) {
        e.preventDefault();
        e.stopPropagation();
        currentPos(e);
        document.addEventListener('mousemove', dragMoving);
        document.addEventListener('touchmove', dragMoving);
        document.addEventListener('mouseup', stopEvents);
        document.addEventListener('touchend', stopEvents);
    }

    /**
     * ************************************************
     * @function: Remove os eventos de movimento
     *  quando solta a caixa de corte.
     * 
     * @private
     * ************************************************
     * 
     * @param {OBJ} e
     * Evento disparado
     * ************************************************
     */
    function stopEvents(e) {
        e.preventDefault();
        document.removeEventListener('mouseup', stopEvents);
        document.removeEventListener('touchend', stopEvents);
        document.removeEventListener('mousemove', dragMoving);
        document.removeEventListener('touchmove', dragMoving);
    }

    /**
     * ************************************************
     * @function: Obtem a posição do arraste da caixa
     * de corte.
     * 
     * @private
     * ************************************************
     * 
     * @param {OBJ} e
     * Evento disparado
     * ************************************************
     */
    function dragMoving(e) {
        e.preventDefault();
        e.stopPropagation();
        $status.imgLeft = (e.pageX || e.touches && e.touches[0].pageX) - ($status.posX - $status.boxLeft);
        $status.imgTop = (e.pageY || e.touches && e.touches[0].pageY) - ($status.posY - $status.boxTop);
        $status.imgWidth = ($box.offsetWidth + 3);
        $status.imgHeight = ($box.offsetHeight + 3);
        if ($status.imgLeft < 0) {
            $status.imgLeft = 1;
        } else if ($status.imgLeft > $img.offsetWidth - $status.imgWidth) {
            $status.imgLeft = $img.offsetWidth - $status.imgWidth;
        }
        if ($status.imgTop < 0) {
            $status.imgTop = 1;
        } else if ($status.imgTop > $img.offsetHeight - $status.imgHeight) {
            $status.imgTop = $img.offsetHeight - $status.imgHeight;
        }
        imgPos($status.imgLeft, $status.imgTop);
        boxPos($status.imgLeft, $status.imgTop);
    }

    /**
     * ************************************************
     * @function: Obtem a posição inicial da caixa
     * de corte.
     * 
     * @private
     * ************************************************
     * 
     * @param {OBJ} pos
     * Evento disparado
     * ************************************************
     */
    function currentPos(pos) {
        $status.boxLeft = $box.offsetLeft;
        $status.boxTop = $box.offsetTop;
        $status.posX = (pos.clientX || pos.pageX || pos.touches && pos.touches[0].clientX) + window.scrollX;
        $status.posY = (pos.clientY || pos.pageY || pos.touches && pos.touches[0].clientY) + window.scrollY;
    }

    /**
     * ************************************************
     * @function: Obtem a posição atual da caixa
     * de corte.
     * 
     * @private
     * ************************************************
     * 
     * @param {INT} left
     *  Distância a esqueda
     * @param {INT} top
     *  Distância ao topo
     * ************************************************
     */
    function boxPos(left, top) {
        $box.style.top = top + (200 / 2) + 'px';
        $box.style.left = left + (200 / 2) + 'px';
    }

    /**
     * ************************************************
     * @function: Requisita alteração no tamanho da
     * caixa de corte quando rolado a roda do mouse.
     * 
     * @private
     * ************************************************
     * 
     * @param {OBJ} e
     *  Evento
     * ************************************************
     */
    function scrollResize(e) {
        e.preventDefault();
        imgZoom(e.deltaY);
    }

    /**
     * ************************************************
     * @function: Altera o tamanho da caixa de corte.
     * 
     * @private
     * ************************************************
     * 
     * @param {INT} zoom
     *  Quantidade de fluxo na escala.
     * ************************************************
     */
    function imgZoom(zoom) {
        if ($isReady) {
            $zoom.boxWidth = Math.floor($box.clientWidth + zoom);
            $zoom.boxHeight = Math.floor($box.clientHeight + zoom);
            $zoom.imgWidth = $img.clientWidth;
            $zoom.imgHeight = $img.clientHeight;
            if ($zoom.boxWidth < 50) {
                return;
            } else if ($zoom.boxWidth > $zoom.imgWidth) {
                return;
            }
            $zoom.left = $box.offsetLeft - (zoom / 2);
            $zoom.top = $box.offsetTop - (zoom / 2);
            $zoom.right = $zoom.left + $zoom.boxWidth;
            $zoom.bottom = $zoom.top + $zoom.boxHeight;
            if ($zoom.left < 0) {
                $zoom.left = 0;
            }
            if ($zoom.top < 0) {
                $zoom.top = 0;
            }
            if ($zoom.right > $zoom.imgWidth) {
                return;
            }
            if ($zoom.bottom > $zoom.imgHeight) {
                return;
            }
            $data.ratio = 200 / $zoom.boxWidth;
            boxSize($zoom.boxWidth, $zoom.boxWidth);
            imgPos($zoom.left, $zoom.top);
            boxPos($zoom.left, $zoom.top);
            cutBox();
        }
    }

    /**
     * ************************************************
     * @function: Define o tamanho da caixa de corte.
     * 
     * @private
     * ************************************************
     * 
     * @param {INT} width
     *  Largura.
     * @param {INT} height
     *  Altura.
     * ************************************************
     */
    function boxSize(width, height) {
        $box.style.width = width + 'px';
        $box.style.height = height + 'px';
    }

    /**
     * ************************************************
     * @function: Faz o corte da imagem.
     * 
     * @private
     * ************************************************
     */
    function cutBox() {
        $data.width = $img.width * $data.ratio;
        $data.height = $img.height * $data.ratio;
        $canvas.width = 200;
        $canvas.height = 200;
        $canvas.getContext('2d').drawImage($img, $data.left, $data.top, $data.width, $data.height);
    }

    /**
     * ************************************************
     * @function: Requisita alteração no tamanho da
     * caixa de corte quando pressionado as teclas
     * "+" ou "-".
     * 
     * @private
     * ************************************************
     * 
     * @param {OBJ} e
     *  Evento
     * ************************************************
     */
    function keyResize(e) {
        e.preventDefault();
        switch (String.fromCharCode(e.charCode)) {
            case '+' :
                imgZoom(10);
                break;
            case '-' :
                imgZoom(-10);
                break;
        }
    }

    /**
     * ************************************************
     * @function: Requisita almento no tamanho da
     * caixa de corte.
     * 
     * @public
     * ************************************************
     */
    this.sizePlus = function() {
        imgZoom(10);
    };

    /**
     * ************************************************
     * @function: Requisita redução no tamanho da
     * caixa de corte.
     * 
     * @public
     * ************************************************
     */
    this.sizeMinus = function() {
        imgZoom(-10);
    };

    /**
     * ************************************************
     * @function: Define a imagem de cortada
     * 
     * @public
     * ************************************************
     */
    this.setCut = function() {
        cutBox();
        $getImage = $canvas.toDataURL('image/png', 1.0);
        $isCut = true;
    };

    /**
     * ************************************************
     * @function: Obtem o corte
     * 
     * @public
     * ************************************************
     */
    this.getImage = function() {
        if ($isCut) {
            return ($getImage);
        }
    };
};


/**
 * ****************************************************
 * * @Class ImageGalery
 * * @author Spell-Master (Omar Pautz)
 * * @copyright 2019
 * * @version 1.0
 * ****************************************************
 * * Exibe imagens como forma de galeria.
 * 
 * @param {STR/DOM} box
 * #ID do elemento que contenha a galeria de imagens.
 * ****************************************************
 */

var ImageGalery = function (box) {

    var $box = document.getElementById(box), $img;
    var $this = {
        backGround: null,
        galeryBox: null,
        boxImg: null,
        thumb: null,
        close: null
    };

    createBg();
    createBox();
    createThumb();
    createX();

    $img = $box.querySelectorAll('img');
    $img.forEach(setMod);

    /**
     * ************************************************
     * * Cria o plano de fundo
     * ************************************************
     */
    function createBg() {
        $this.backGround = document.createElement('img');
        $this.backGround.classList.add('galery-background');
        document.body.appendChild($this.backGround);
    }

    /**
     * ************************************************
     * * Cria a caixa central da imagem
     * ************************************************
     */
    function createBox() {
        $this.galeryBox = document.createElement('div');
        $this.boxImg = document.createElement('img');
        $this.galeryBox.classList.add('galery-box');
        $this.boxImg.classList.add('galery-image');
        document.body.appendChild($this.galeryBox);
        $this.galeryBox.appendChild($this.boxImg);
    }

    /**
     * ************************************************
     * * Cria a caixa de minituras da galeria
     * ************************************************
     */
    function createThumb() {
        $this.thumb = document.createElement('div');
        $this.thumb.classList.add('thumb-background');
        document.body.appendChild($this.thumb);
    }

    /**
     * ************************************************
     * * Cria o botão de fechar
     * ************************************************
     */
    function createX() {
        $this.close = document.createElement('a');
        $this.close.classList.add('galery-x');
        $this.close.title = 'Fechar';
        document.body.appendChild($this.close);
        $this.close.addEventListener('click', closeGalery, false);
    }

    /**
     * ************************************************
     * * Define eventos de click em todas as imagens
     * para abertua da galeria.
     * * Adiciona evento de click ao documento para
     * fechar a galeria.
     * * @param {OBJ} e
     * ************************************************
     */
    function setMod(e) {
        e.addEventListener('click', openLight, false);
        e.addEventListener('click', setBg, false);
        document.addEventListener('keypress', keyboard, false);
    }

    /**
     * ************************************************
     * * Abre a galeria
     * * @param {OBJ} e
     * ************************************************
     */
    function openLight(e) {
        setTumbs();
        showBoxes();
    }

    /**
     * ************************************************
     * * Mostra os elementos
     * ************************************************
     */
    function showBoxes() {
        $this.backGround.classList.toggle('active');
        $this.galeryBox.classList.toggle('active');
        $this.thumb.classList.toggle('active');
        $this.close.classList.toggle('active');
    }

    /**
     * ************************************************
     * * Cria as miniaturas da galeria
     * ************************************************
     */
    function setTumbs() {
        var $tumb = $box.getElementsByTagName('img'), $tImg;
        for (var $i = 0; $i < $tumb.length; $i++) {
            $tImg = document.createElement('img');
            $tImg.src = $tumb[$i].src;
            $tImg.classList.add('thumb');
            $this.thumb.appendChild($tImg);
            $tImg.addEventListener('click', setBg, false);
        }
    }

    /**
     * ************************************************
     * * Define a atual imagem clicada
     * @param {OBJ} e
     * ************************************************
     */
    function setBg(e) {
        $this.backGround.src = e.target.src;
        $this.boxImg.src = e.target.src;
    }

    /**
     * ************************************************
     * * Remove todos elementos fechando a galeria
     * ************************************************
     */
    function closeGalery() {
        $this.backGround.src = null;
        $this.boxImg.src = null;
        $this.thumb.innerHTML = null;
        var $clearT = $this.thumb.getElementsByTagName('img'), $i;
        for ($i = 0; $i < $clearT.length; $i++) {
            if ($clearT[$i].parentNode) {
                $clearT[$i].parentNode.removeChild($clearT[$i]);
            }
        }
        showBoxes();
    }

    /**
     * ************************************************
     * * Fecha a galeria caso a tela "escape" é
     * pressionada.
     * @param {OBJ} e
     * ************************************************
     */
    function keyboard(e) {
        if (e.keyCode == 27) {
            closeGalery();
        }
    }

    this.openLight = openLight;
    this.setBg = setBg;
};

/**
 * ****************************************************
 * * ModalShow
 * * @author Spell-Master (Omar Pautz)
 * * @copyright 2018
 * * @version 3.0 (2020)
 * ****************************************************
 * * Gerencia aplicação modal.
 * 
 * ****************************************************
 * @requires
 * Estrutura HTML
 * <div class="modal" id="identificador">
 *     <div class="modal-box">
 *         <div class="modal-header"></div>
 *         <div class="modal-content">
 *             Conteúdo...
 *         </div>
 *     </div>
 * </div>
 * ****************************************************
 */

/**
 * ****************************************************
 * @param {STR} modal
 * * #ID do elemento modal
 * ****************************************************
 */
var ModalShow = function (modal) {

    var $this = {
        modal: document.getElementById(modal),
        close: null,
        content: null
    };

    /**
     * ************************************************
     * Exibe o bloco modal designado pela instância.
     *  
     * @public
     * ************************************************
     * 
     * @param {STR} title (opcional)
     * Informar o título do cabeçalho do bloco modal
     * designado pela instância.
     * 
     * @param {BOOL} close (opcional) true/false
     * Se verdadeiro um botão para fechar será exibido
     * no bloco modal designado pela instância.
     * ************************************************
     */
    function openModal(title, close) {
        $this.modal.querySelector('.modal-header').innerHTML = '<div class="modal-close"></div><div class="modal-title"></div>';
        $this.content = $this.modal.querySelector('.modal-content');
        if (title) {
            setTitle(title);
        }
        if (close) {
            setClose();
        }
        $this.modal.classList.add('active');
    }

    /**
     * ************************************************
     * Oculta o bloco modal designado pela instância.
     *  
     * @public
     * ************************************************
     */
    function closeModal() {
        if ($this.close) {
            $this.close.removeEventListener('click', closeModal);
            $this.close.classList.remove('active');
            $this.close = null;
        }
        $this.modal.classList.remove('active');
    }

    /**
     * ************************************************
     * Exibe o bloco modal designado pela instância.
     *  
     * @public
     * ************************************************
     * 
     * @param {STR} title (opcional)
     * Altera ou adiciona o título do cabeçalho do
     * bloco designado pela instância.
     * ************************************************
     */
    function setTitle(title) {
        $this.modal.querySelector('.modal-title').innerText = title;
    }

    /**
     * ************************************************
     * Mostra um botão de fechar se ele não estiver
     * visível no bloco modal designado pela instância.
     *  
     * @public
     * ************************************************
     */
    function setClose() {
        if (!$this.close) {
            $this.close = $this.modal.querySelector('.modal-close');
            $this.close.classList.add('active');
            $this.close.addEventListener('click', closeModal, true);
        }
    }

    /**
     * ************************************************
     * Oculta o botão de fechar se ele estiver visível
     * no bloco modal designado pela instância.
     *  
     * @public
     * ************************************************
     */
    function unsetClose() {
        if ($this.close) {
            $this.close.classList.remove('active');
            $this.close.removeEventListener('click', close, true);
            $this.close = null;
        }
    }

    /**
     * ************************************************
     * * Acesso público aos métodos
     * ************************************************
     */
    this.open = openModal;
    this.close = closeModal;
    this.title = setTitle;
    this.showX = setClose;
    this.hiddenX = unsetClose;

};

/**
 * ****************************************************
 * * Paginator
 * * @author Spell-Master (Omar Pautz)
 * * @copyright 2020
 * * @version 1.0
 * ****************************************************
 * * Realiza paginação de elementos.
 * ****************************************************
 */

/**
 * ****************************************************
 * @param {STR} targetItem
 * Informar quais são os alvos da paginação
 * @param {INT} maxItens
 * Informar qual a quantidade máxima de itens a ser
 * exibida por vez
 * ****************************************************
 */
var Paginator = function (targetItem, maxItens) {
    if (!targetItem || !maxItens) {
        console.error('Parâmetros de iniciação necessários para paginação não definidos');
    } else {
        var $this = {
            itens: document.getElementsByClassName(targetItem),
            limit: parseInt(maxItens),
            offset: 0,
            rows: 0,
            amount: 0
        };

        /**
         * ************************************************
         * Define os dados de quais elementos são os alvos
         * da paginação.
         *  
         * @public
         * ************************************************
         * 
         * @param {INT} rows (opcional)
         * Informar numero de linha inicial.
         * ************************************************
         */
        function setData(rows) {
            $this.rows = (rows ? parseInt(rows) : 1);
            $this.offset = ($this.rows * $this.limit) - $this.limit;
            hidenItens();
            navLinks();
            showItens();
        }

        /**
         * ************************************************
         * Cria o HTML para o links de navegação entre os
         * elementos.
         *  
         * @private
         * ************************************************
         */
        function navLinks() {
            var $below = $this.rows - 1;
            var $above = $this.rows + 1;
            var $length = $this.itens.length;
            if ($length > $this.limit) {
                $this.amount = Math.ceil($length / $this.limit);
                $this.linksHtml = '<ul class="paginator">';
                if ($this.rows != 1) {
                    $this.linksHtml += '<li><a title="Primeira Página" data-link-paginator="1" class="paginator-link"> &lt; </a></li>';
                }
                for ($below; $below <= $this.rows - 1; $below++) {
                    if ($below >= 1) {
                        $this.linksHtml += '<li><a title="Página' + $below + '" data-link-paginator="' + $below + '" class="paginator-link">' + $below + '</a></li>';
                    }
                }
                $this.linksHtml += '<li class="current"><a>' + $this.rows + '</a></li>';
                for ($above; $above <= $this.rows + 1; $above++) {
                    if ($above <= $this.amount) {
                        $this.linksHtml += '<li><a title="Página ' + $above + '" data-link-paginator="' + $above + '" class="paginator-link">' + $above + '</a></li>';
                    }
                }
                if ($this.amount != $below) {
                    $this.linksHtml += '<li><a title="Última Página" data-link-paginator="' + $this.amount + '" class="paginator-link"> &gt; </a></li>';
                }
                $this.linksHtml += '<li><a class="amount">' + $this.rows + '/ ' + $this.amount + '</a></li>';
                $this.linksHtml += "</ul>";
                document.querySelectorAll('[data-paginator]').forEach(attachLinks);
            }
        }

        /**
         * ************************************************
         * Oculta os elmentos que não fazem parte da
         * coluna de itens atual.
         *  
         * @private
         * ************************************************
         */
        function hidenItens() {
            for (var i = 0; i < $this.itens.length; i++) {
                $this.itens[i].style.display = 'none';
            }
        }

        /**
         * ************************************************
         * Mostra os elementos que fazem parte da coluna
         * de itens atual.
         *  
         * @private
         * ************************************************
         */
        function showItens() {
            var $count = $this.offset;
            var $delimiter = $this.offset + $this.limit;
            for (var i = $count; i < $delimiter; i++) {
                if (typeof $this.itens[i] !== 'undefined' && $this.itens[i] !== null) {
                    $this.itens[i].style.display = 'block';
                }
                $count++;
            }
        }

        /**
         * ************************************************
         * Anexa o links de navegação aos elementos
         * responsável por abrigar-los.
         * 
         * Adiciona evento de click a cada link
         *  
         * @private
         * ************************************************
         * 
         * @param {OBJ} e 
         * ************************************************
         */
        function attachLinks(e) {
            e.innerHTML = $this.linksHtml;
            document.querySelectorAll('[data-link-paginator]').forEach(setClick);
        }

        /**
         * ************************************************
         * Inicia o evento de click em cada link da
         * navegação.
         *  
         * @private
         * ************************************************
         * 
         * @param {OBJ} e 
         * ************************************************
         */
        function setClick(e) {
            e.addEventListener('click', changePage, false);
        }

        /**
         * ************************************************
         * Altera a coluna de itens atual pelo propriedade
         * data de cada link de navegação.
         *  
         * @private
         * ************************************************
         * 
         * @param {OBJ} e 
         * ************************************************
         */
        function changePage(e) {
            var $key = (e.target).dataset.linkPaginator;
            if ($key !== 'undefined') {
                setData($key);
            }
        }

        /**
         * ************************************************
         * Inicia o processo de paginação.
         * ************************************************
         */
        this.init = setData;
    }
};

/**
 * ****************************************************
 * * SelectOption
 * * @author Spell-Master (Omar Pautz)
 * * @copyright 2017
 * * @version 3.5 (2020)
 * ****************************************************
 * * Personaliza menu suspenso.
 * 
 * ****************************************************
 * @requires
 * Estrutura HTML
 * <select class="select-options">
 *     <option value="">Opção</option>
 *     <option value="">Opção</option>
 *     <option value="">Opção</option>
 * </select>
 * ****************************************************
 */

var SelectOption = function () {

    var $select = document.getElementsByClassName('select-options'),
        $event,
        $this = {
            index: 0,
            current: null,
            base: null,
            ul: null,
            active: null
        };

    createNew();

    /**
     * ************************************************
     * Cria um novo elemento para abrigar o seletor.
     *  
     * @private
     * ************************************************
     */
    function createNew() {
        $event = new Event('change');
        for ($this.index = 0; $this.index < $select.length; $this.index++) {
            $this.current = $select[$this.index];
            if (!$this.current.classList.contains('init')) {
                $this.current.classList.add('init');
                $this.current.setAttribute('style', 'opacity:0; height:0; width:0; overflow:hidden');
                $this.base = document.createElement('div');
                $this.base.classList.add('select-base');
                $this.current.parentNode.insertBefore($this.base, $this.current);
                createButton();
            }
        }
    }

    /**
     * ************************************************
     * Cria o botão de controle que ao clicado
     * mostrará as opções.
     *  
     * @private
     * ************************************************
     */
    function createButton() {
        $this.button = document.createElement('div');
        $this.button.classList.add('select-button');
        $this.base.appendChild($this.button);
        createList();
    }

    /**
     * ************************************************
     * Cria o o elemento lista que abrigará as opções.
     *  
     * @private
     * ************************************************
     */
    function createList() {
        $this.ul = document.createElement('ul');
        $this.ul.classList.add('select-list');
        $this.button.parentNode.insertBefore($this.ul, $this.button.nextSibling);
        queryOptions();
    }

    /**
     * ************************************************
     * Obtem os valores do select e aplica os mesmos
     * ao novo seletor personalizado.
     *  
     * @private
     * ************************************************
     */
    function queryOptions() {
        $this.head = document.getElementsByClassName('select-button');
        var $opt = $this.current.querySelectorAll('option'), $li;
        for (var $i = 0; $i < $opt.length; $i++) {
            $li = document.createElement('li');
            $li.setAttribute('data-select', $opt[$i].value);
            $li.setAttribute('data-parent', $this.index);
            $li.innerText = $opt[$i].innerText.substring(0, 20);
            $li.addEventListener('click', clickItem, false);
            $this.ul.appendChild($li);
        }
        for (var $j = 0; $j < $opt.length; $j++) {
            if ($opt[$j].selected) {
                break;
            }
        }
        var $selected = $this.current.selectedIndex;
        if ($selected >= 1) {
            $this.head[$this.index].innerText = $opt[$selected].innerText.substring(0, 20);
        } else {
            $this.head[$this.index].innerText = $opt[0].innerText.substring(0, 20);
        }
    }

    /**
     * ************************************************
     * Modifica o seletor quando uma das opções é 
     * clicada.
     *  
     * @private
     * ************************************************
     * 
     * @param {OBJ} e
     * Evento de click
     * ************************************************
     */
    function clickItem(e) {
        var $cT = e.target;
        var $dataSet = [$cT.dataset.parent, $cT.dataset.select];
        $this.head[$dataSet[0]].innerText = $cT.innerText.substring(0, 20);
        $select[$dataSet[0]].value = $dataSet[1];
        $select[$dataSet[0]].dispatchEvent($event);
    }

    /**
     * ************************************************
     * Controle de exibição para os seletores
     * personalizados.
     *  
     * @private
     * ************************************************
     * 
     * @param {OBJ} e
     * Evento de click
     * ************************************************
     */
    function clickDoc(e) {
        if (e.which === 1) {
            var $target = e.target;
            if ($this.active === $target) {
                $this.active.classList.remove('active');
                $target.nextElementSibling.classList.remove('active');
                $this.active = null;
            } else if ($target.className === 'select-button') {
                if ($this.active) {
                    $this.active.classList.remove('active');
                    $this.active.nextElementSibling.classList.remove('active');
                }
                $target.classList.add('active');
                $target.nextElementSibling.classList.add('active');
                $this.active = $target;
            } else if ($this.active != undefined || $this.active != null) {
                $this.active.classList.remove('active');
                $this.active.nextElementSibling.classList.remove('active');
                $this.active = null;
            }
        }
    }
    
    document.addEventListener('click', clickDoc, false);
};

/**
 * ****************************************************
 * * TabPaginator
 * * @author Spell-Master (Omar Pautz)
 * * @copyright 2017
 * * @version 4.1 (2020)
 * ****************************************************
 * * Executa paginação de conteúdo por blocos.
 * 
 * ****************************************************
 * @requires
 * Estrutura HTML
 * <div id="identificador">
 *    <ul class="tab-menu">
 *        <li><a class="tab-link">A</a></li>
 *        <li><a class="tab-link">B</a></li>
 *    </ul>
 *    <div class="tab-body">Conteúdo A</div>
 *    <div class="tab-body">Conteúdo B</div>
 * </div>
 * ****************************************************
 */

/**
 * ****************************************************
 * * @param {STR} propriety (opcional)
 * Elemento #ID para determinar o conjunto de ativação.
 * ****************************************************
 */
var TabPaginator = function (propriety) {

    var $node = propriety ? document.getElementById(propriety) : document;

    var $this = {
        link: $node.getElementsByClassName('tab-link'),
        body: $node.getElementsByClassName('tab-body'),
        index: 0
    };

    tabButtons();
    openTab();
    this.openTab = openTab;

    /**
     * ************************************************
     * Adiciona evento de click e cada botão do menu.
     *  
     * @private
     * ************************************************
     */
    function tabButtons() {
        for (var $i = 0; $i < $this.link.length; $i++) {
            $this.link[$i].addEventListener('click', getTab($i + 1), false);
        }
    }

    /**
     * ************************************************
     * Solicita a abertura da tab solicitada por click
     * nos botões do menu.
     * ************************************************
     * 
     * @param {INT} tb
     * Índice da tab para abrir.
     * ************************************************
     */
    function getTab(tb) {
        return function () {
            openTab(tb);
        };
    }

    /**
     * ************************************************
     * Abre a tab solicitada.
     * ************************************************
     * 
     * @param {INT} index
     * Índice da tab para abrir.
     * ************************************************
     */
    function openTab(index) {
        closeTabs();
        $this.index = (index ? parseInt(index - 1) : 0);
        $this.link[$this.index].classList.add('active');
        $this.body[$this.index].classList.add('active');
    }

    /**
     * ************************************************
     * Fecha todas as tabs para correta abertura da
     * atual.
     * ************************************************
     */
    function closeTabs() {
        var $i;
        for ($i = 0; $i < $this.link.length; $i++) {
            $this.link[$i].classList.remove('active');
            $this.body[$i].classList.remove('active');
        }
    }
};

/**
 * @copyright Spell Master 2020
 */
var SocialLink = function () {

    this.eMail = function (text) {
        return (text.match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{3})$/) ? true : false);
    };

    this.webSite = function (text) {
        return (text.match(/^http(?:s)?:\/\/(www\.)?([a-zA-Z0-9 -_.\/:=&"?%+@#$!])+$/) ? true : false);
    };

    this.youTube = function (text) {
        return (text.match(/^https\:\/\/www\.youtube\.com\/(?:channel|(?:c))\/[a-z0-9\-]+?(\/)?$/gmi) ? true : false);
    };

    this.faceBook = function (text) {
        return (text.match(/^https\:\/\/www\.facebook\.com\/(?:([a-z0-9\-\_]+)|(?:profile\.php\?id\=([0-9]+?)))$/gmi) ? true : false);
    };

    this.instagram = function (text) {
        return (text.match(/^(https?\:\/\/(?:www\.)?instagram\.com\/)([a-z0-9\-\_]+)?(\/)?$/gmi) ? true : false);
    };

    this.twitter = function (text) {
        return (text.match(/^(https?\:\/\/(?:www\.)?twitter\.com\/)([a-z0-9\-\_]+)?(\/)?$/gmi) ? true : false);
    };

    this.gitHub = function (text) {
        return (text.match(/^(https?\:\/\/(?:www\.)?github\.com\/)([a-z0-9\-\_]+)?(\/)?$/gmi) ? true : false);
    };

    this.whatsApp = function (text) {
        return (text.match(/^(?:(?:\+|00)?(55)\s?)?(?:\(?([1-9][0-9])\)?\s?)?(?:((?:9\d|[2-9])\d{3})\-?(\d{4}))$/) ? true : false);
    };

};