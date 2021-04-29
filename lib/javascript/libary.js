/* 
 * sripts para serem adicionados ao sm-libary.js
 * estão aqui enquanto durar o desenvolvimento
 */

// Accordion V2.2 (2021)
var Accordion=function(){for(var t=document.getElementsByClassName("acc-button"),e={target:null,next:null,last:null,copy:null,height:null},n=0;n<t.length;n++)t[n].addEventListener("click",l,!1);function l(t){e.target=t.target,e.next=e.target.nextElementSibling,e.last==e.next?a():(e.last&&a(),i(),s())}function i(){e.copy=e.next.cloneNode(!0),e.copy.setAttribute("style","height:auto; visibility:visible"),e.next.parentNode.appendChild(e.copy),e.height=e.copy.offsetHeight,e.next.parentNode.removeChild(e.copy)}function a(){e.last.previousElementSibling.classList.remove("active"),e.last.style.height="0px",e.last=null,e.height=0}function s(){e.target.classList.add("active"),e.next.style.height=e.height+"px",e.last=e.next}this.forceOpen=function(t){var n;n=t?parseInt(t-1):0,e.next=document.getElementsByClassName("acc-container")[n],e.last=e.next,e.target=e.next.previousElementSibling,i(),s()}};
// AjaxRequest V4.1 (2021)
var AjaxRequest=function(){var e={http:null,loadID:null,file:null,response:null,url:null,form:null,head:null,loading:null,vetor:[null],exception:null};function t(){n(),e.http.addEventListener("readystatechange",r,!1),e.http.open("GET",e.file,!0),e.http.send()}function o(){n(),e.http.addEventListener("readystatechange",r,!1),e.http.open("POST",e.file,!0),e.http.setRequestHeader("Content-type","application/x-www-form-urlencoded"),e.http.send(e.head)}function n(){return e.http=new XMLHttpRequest,e.http.overrideMimeType&&e.http.overrideMimeType("text/html"),e.http}function r(){e.vetor&&1===e.http.readyState?function(){var t='<svg class="load-pre" viewBox="25 25 50 50"><circle class="load-path" cx="50" cy="50" r="20" fill="none" stroke="#'+e.vetor[1]+'" stroke-width="4" stroke-miterlimit="10" /></svg>';switch(e.vetor[0]){case"send":e.loadID.innerHTML='<div class="load-local">'+t+"</div>";break;case"pop":e.loading=document.createElement("div"),e.loading.classList.add("load-pop"),e.loading.innerHTML='<div class="progress-text">Carregando...</div>'+t,document.body.appendChild(e.loading);break;case"form":e.loading=document.createElement("div"),e.form.classList.add("form-conter"),e.loading.classList.add("load-form"),e.loading.innerHTML='<div class="fade-progress">'+t+"</div>",e.form.appendChild(e.loading);break;case"formSend":e.loading=document.createElement("div"),e.form.classList.add("form-conter"),e.loading.classList.add("load-form"),e.loadID.innerHTML='<div class="load-local">'+t+"</div>",e.form.appendChild(e.loading)}}():404===e.http.status?console.warn("Arquivo ["+e.file+"] não encontrado!"):500===e.http.status?console.warn("Erro na resposta do servidor"):4===e.http.readyState&&200===e.http.status&&(e.response=e.http.responseText,e.vetor[0]?setTimeout(function(){if("pop"===e.vetor[0])document.body.removeChild(e.loading);else if("form"===e.vetor[0]||"formSend"===e.vetor[0]){for(var t=0;t<e.form.elements.length;t++)e.form.elements[t].disabled=!1;e.form.removeChild(e.loading)}e.loadID.innerHTML=e.response,e.vetor=[null],e.http=null,i(),e.url&&(window.history.replaceState(null,null,e.url),e.url=null)},1e3):(e.loadID.innerHTML=e.response,i(),e.http=null))}function i(){var t,o,n=e.loadID.getElementsByTagName("script");for(t=n.length-1;t>=0;t--)o=document.createElement("script"),n[t].src?o.src=n[t].src:o.text=n[t].text,e.loadID.appendChild(o),n[t].parentNode.removeChild(n[t])}function a(){var t,o,n,r;for(t=0;t<e.form.elements.length;t++)e.form.elements[t].disabled=!0,"checkbox"===e.form.elements[t].type?e.form.elements[t].checked&&(o=e.form.elements[t].value,e.head+="&"+e.form.elements[t].name+"="+o):"radio"===e.form.elements[t].type?e.form.elements[t].checked&&(n=e.form.elements[t].value,e.head+="&"+e.form.elements[t].name+"="+n):e.head+="&"+e.form.elements[t].name+"="+(r=e.form.elements[t].value,encodeURIComponent(r).replace(/['()]/g,escape).replace(/\*/g,"%2A").replace(/%(?:7C|60|5E)/g,unescape))}function l(e){return null!=e}function d(){console.warn(e.exception),e.http=null,e.exception=null,e.vetor=[null]}this.open=function(o,n){e.loadID=document.getElementById(o),e.file=n;try{if(!o)throw'Parâmetro "inId" não expecificado';if(!n)throw'Parâmetro "file" não expecificado';if(!l(e.loadID))throw'Elemento "#'+o+'" não encontrado';if(e.http instanceof XMLHttpRequest)throw"Já existe uma requisição de protocolo em andamento.";t()}catch(t){e.exception=t,d()}return!1},this.send=function(o,n,r){e.loadID=document.getElementById(o),e.url=r||null,e.file=n,e.vetor=["send",555];try{if(!o)throw'Parâmetro "inId" não expecificado';if(!n)throw'Parâmetro "file" não expecificado';if(!l(e.loadID))throw'Elemento "#'+o+'" não encontrado';if(e.http instanceof XMLHttpRequest)throw"Já existe uma requisição de protocolo em andamento.";t()}catch(t){e.exception=t,d()}return!1},this.pop=function(o,n,r){e.loadID=document.getElementById(o),e.url=r||null,e.file=n,e.vetor=["pop","ccc"];try{if(!o)throw'Parâmetro "inId" não expecificado';if(!n)throw'Parâmetro "file" não expecificado';if(!l(e.loadID))throw'Elemento "#'+o+'" não encontrado';if(e.http instanceof XMLHttpRequest)throw"Já existe uma requisição de protocolo em andamento.";t()}catch(t){e.exception=t,d()}return!1},this.form=function(t,n,r){e.form=document.getElementById(t),e.loadID=document.getElementById(n),e.file=r,e.head="form_id="+t,e.vetor=["form",555];try{if(!t)throw'Parâmetro "form" não expecificado.';if(!n)throw'Parâmetro "inId" não expecificado';if(!r)throw'Parâmetro "file" não expecificado';if(!l(e.form))throw'Elemento "#'+t+'" não encontrado';if(!l(e.loadID))throw'Elemento "#'+n+'" não encontrado';if(e.http instanceof XMLHttpRequest)throw"Já existe uma requisição de protocolo em andamento.";a(),o()}catch(t){e.exception=t,d()}return!1},this.formSend=function(t,n,r){e.form=document.getElementById(t),e.loadID=document.getElementById(n),e.file=r,e.head="form_id="+t,e.vetor=["formSend",555];try{if(!t)throw'Parâmetro "form" não expecificado.';if(!n)throw'Parâmetro "inId" não expecificado';if(!r)throw'Parâmetro "file" não expecificado';if(!l(e.form))throw'Elemento "#'+t+'" não encontrado';if(!l(e.loadID))throw'Elemento "#'+n+'" não encontrado';if(e.http instanceof XMLHttpRequest)throw"Já existe uma requisição de protocolo em andamento.";e.loadID.innerHTML=null,a(),o()}catch(t){e.exception=t,d()}return!1}};
// FileTransfer V2.0 (2021)
var FileTransfer=function(){var e={request:null,method:null,file:null,name:null,progress:null,percent:null,cancel:null,bar:null},n={form:null,result:null},r={url:null,blob:null,link:null};function t(){e.request instanceof XMLHttpRequest&&(e.request.abort(),a())}function l(){e.progress=document.createElement("div"),e.progress.id="transfer-progress",e.progress.innerHTML='<div class="progress-text"></div><div class="progress-file"></div><div class="progress-percent"></div><div class="progress-line"><div class="progress-bar"></div></div>',document.body.appendChild(e.progress),e.cancel&&(e.cancel.classList.add("progress-cancel"),e.cancel.innerText="Cancelar",e.cancel.addEventListener("click",t,!1),e.progress.appendChild(e.cancel))}function s(){"up"===e.method?e.progress.querySelector(".progress-text").innerText="Enviando Arquivo":"do"===e.method&&(e.progress.querySelector(".progress-text").innerText="Recebendo Arquivo"),e.progress.querySelector(".progress-file").innerText=e.name,e.percent=e.progress.querySelector(".progress-percent"),e.bar=e.progress.querySelector(".progress-bar")}function o(n){var r;n.lengthComputable&&(r=Math.round(n.loaded/n.total*100),e.percent.innerText=r+"% completado",e.bar.style.width=r+"%")}function u(){404===e.request.status?(console.warn("Não foi possível localizar o arquivo ("+e.name+")"),t()):4===e.request.readyState&&200===e.request.status&&(e.cancel&&(e.cancel.style.display="none"),n.result&&(n.result.innerHTML=e.request.responseText),function(){if("up"===e.method){var t,l,s=n.result.getElementsByTagName("script");for(t=s.length-1;t>=0;t--)l=document.createElement("script"),s[t].src?l.src=s[t].src:l.text=s[t].text,n.result.appendChild(l),s[t].parentNode.removeChild(s[t])}else r.blob?(r.url=window.URL.createObjectURL(e.request.response),r.link.href=r.url):r.link.href=e.file,r.link.download=e.name,document.body.appendChild(r.link),r.link.click()}(),setTimeout(a,1e3))}function a(){e.cancel&&e.cancel.removeEventListener("click",t),"do"===e.method&&(r.blob&&window.URL.revokeObjectURL(r.url),document.body.removeChild(r.link)),document.body.removeChild(e.progress),function(){"up"==e.method?(n.form=null,n.result=null):r.link=null;r.blob&&(r.blob=null,r.url=null);e.request=null,e.method=null,e.file=null,e.name=null,e.progress=null,e.percent=null,e.cancel=null,e.bar=null}()}this.upload=function(r,t,a,c){var i=document.getElementById(r).querySelector('input[type="file"]');i.value?t?e.request instanceof XMLHttpRequest?console.warn("Já existe um processo em andamento"):(e.request=new XMLHttpRequest,n.form=new FormData,e.cancel=c?document.createElement("button"):null,n.result=a?document.getElementById(a):null,e.method="up",e.file=i.files[0],e.name=e.file.name,n.form.append(i.name,e.file),l(),s(),e.request.upload.addEventListener("progress",o,!1),e.request.addEventListener("readystatechange",u,!1),e.request.responseType="text",e.request.open("POST",t,!0),e.request.send(n.form)):console.warn("Destino de recebimento não expecificado"):console.warn("Nenhum arquivo selecionado");return!1},this.download=function(n,t,a){n?e.request instanceof XMLHttpRequest?console.warn("Já existe um processo em andamento"):(e.request=new XMLHttpRequest,e.file=n,e.name=e.file.split("/").reverse()[0],e.cancel=t?document.createElement("button"):null,r.blob=a||null,e.method="do",r.link=document.createElement("a"),l(),s(),e.request.addEventListener("progress",o,!1),e.request.addEventListener("readystatechange",u,!1),e.request.responseType="blob",e.request.open("GET",n,!0),e.request.send()):console.warn("Arquivo de envio não expecificado");return!1},this.stop=t};
// ImageCut V2.1 (2021)
var ImageCut=function(t){var e,i,o,n,s=document.getElementById(t),h=!1,d=!1,a={ratio:1,left:0,top:0},g={imgLeft:0,imgTop:0,imgWidth:0,imgHeight:0,boxLeft:0,boxTop:0,posX:0,posY:0},r={boxWidth:0,boxHeight:0,imgWidth:0,imgHeight:0,left:0,top:0,right:0,bottom:0},m={w:0,h:0};function f(t){return Math.ceil("w"===t?s.offsetWidth:s.offsetHeight)}function c(t,e){a.left=-t*a.ratio,a.top=-e*a.ratio,o.style.top=-e+"px",o.style.left=-t+"px"}function u(t){var e;t.preventDefault(),t.stopPropagation(),e=t,g.boxLeft=i.offsetLeft,g.boxTop=i.offsetTop,g.posX=(e.clientX||e.pageX||e.touches&&e.touches[0].clientX)+window.scrollX,g.posY=(e.clientY||e.pageY||e.touches&&e.touches[0].clientY)+window.scrollY,document.addEventListener("mousemove",l),document.addEventListener("touchmove",l),document.addEventListener("mouseup",p),document.addEventListener("touchend",p)}function p(t){t.preventDefault(),document.removeEventListener("mouseup",p),document.removeEventListener("touchend",p),document.removeEventListener("mousemove",l),document.removeEventListener("touchmove",l)}function l(t){t.preventDefault(),t.stopPropagation(),g.imgLeft=(t.pageX||t.touches&&t.touches[0].pageX)-(g.posX-g.boxLeft),g.imgTop=(t.pageY||t.touches&&t.touches[0].pageY)-(g.posY-g.boxTop),g.imgWidth=i.offsetWidth+3,g.imgHeight=i.offsetHeight+3,g.imgLeft<0?g.imgLeft=1:g.imgLeft>o.offsetWidth-g.imgWidth&&(g.imgLeft=o.offsetWidth-g.imgWidth),g.imgTop<0?g.imgTop=1:g.imgTop>o.offsetHeight-g.imgHeight&&(g.imgTop=o.offsetHeight-g.imgHeight),c(g.imgLeft,g.imgTop),v(g.imgLeft,g.imgTop)}function v(t,e){i.style.top=e+100+"px",i.style.left=t+100+"px"}function x(t){t.preventDefault(),L(t.deltaY)}function L(t){if(h){if(r.boxWidth=Math.floor(i.clientWidth+t),r.boxHeight=Math.floor(i.clientHeight+t),r.imgWidth=o.clientWidth,r.imgHeight=o.clientHeight,r.boxWidth<50)return;if(r.boxWidth>r.imgWidth)return;if(r.left=i.offsetLeft-t/2,r.top=i.offsetTop-t/2,r.right=r.left+r.boxWidth,r.bottom=r.top+r.boxHeight,r.left<0&&(r.left=0),r.top<0&&(r.top=0),r.right>r.imgWidth)return;if(r.bottom>r.imgHeight)return;a.ratio=200/r.boxWidth,e=r.boxWidth,n=r.boxWidth,i.style.width=e+"px",i.style.height=n+"px",c(r.left,r.top),v(r.left,r.top),b()}var e,n}function b(){a.width=o.width*a.ratio,a.height=o.height*a.ratio,n.width=200,n.height=200,n.getContext("2d").drawImage(o,a.left,a.top,a.width,a.height)}function w(t){switch(t.preventDefault(),String.fromCharCode(t.charCode)){case"+":L(10);break;case"-":L(-10)}}void 0!==s||null!==s?(s.addEventListener("load",function(t){m.w=f("w"),m.h=f("h"),m.w<201?console.warn("A imagem de corte deve possuir pelo menos 210 pixel's de largura"):m.h<201?console.warn("A imagem de corte deve possuir pelo menos 210 pixel's de altura"):(e=document.createElement("div"),i=document.createElement("div"),o=new Image,n=document.createElement("canvas"),s.classList.add("cut-focus"),s.draggable=!1,e.classList.add("cut-limiter"),e.setAttribute("style","max-width:"+m.w+"px; max-height:"+m.h+"px"),i.classList.add("cut-box"),o.src=s.src,o.draggable=!1,o.setAttribute("style","width:auto; height:auto; max-width:"+m.w+"px; max-height:"+m.h+"px"),s.parentNode.insertBefore(e,s.nextSibling),e.appendChild(i),i.appendChild(o),e.appendChild(s),c(m.w/2-100,m.h/2-100),i.addEventListener("mousedown",u,!1),i.addEventListener("touchstart",u,!1),i.addEventListener("wheel",x,!1),document.addEventListener("keypress",w,!1),h=!0)},!1),this.sizePlus=function(){L(10)},this.sizeMinus=function(){L(-10)},this.setCut=function(){b(),$getImage=n.toDataURL("image/png",1),d=!0},this.getImage=function(){if(d)return $getImage}):console.error("Não foi possível identificar a imagem para corte")};

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

/*
 * 
 * NOTA!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * PARA O CORRETO FUNCIONAMENTO ESSA CLASSE TEM QUE FICAR DEPOIS DA
 *  INSTÂNCIA DO OBJETO "sml"
 * FAZER ISSO DEPOIS DA PRODUÇÃO
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * 
 * 
 * 
 * Mostra ou oculta elementos.
 * @example 
 * Adicionar a um botão (data-open="exemplo")
 * O elemento ao qual deve ser exibido pelo click recebe a class do data do botão
 * <div class="exemplo">
 * Adicionar então no CSS
 * .exemplo.opened { display: block }
 */
var ItemOpen = function () {
    var $itemClick, $lastOpen, $i, $close, $open, $click, $lastButton, $fix, $nodeList;

    document.addEventListener('click', openItens, false);

    function openItens(e) {
        if (e.which === 1) {
            $click = e.target;
            $itemClick = (sml.isReady($click.dataset.open) ? $click.dataset.open : false);
            $nodeList = nodeParents();
            $fix = null;
            for ($i = 0; $i < $nodeList.length; $i++) {
                if ($nodeList[$i].dataset.open == 'fix') {
                    $fix = true;
                    break;
                }
            }
            if ($fix) {
                return false;
            } else if ($itemClick) {
                if ($itemClick === $lastOpen) {
                    toggleItem();
                    clearVar();
                } else {
                    if ($lastOpen) {
                        closeItem();
                    }
                    toggleItem();
                    $lastOpen = $itemClick;
                    $lastButton = $click;
                }
            } else {
                if ($lastOpen) {
                    closeItem();
                    clearVar();
                }
            }
        }
    }

    function nodeParents() {
        var $node = $click, $parentList = [];
        while ($node.parentNode != null && $node.parentNode != document.documentElement) {
            $parentList.push($node.parentNode);
            $node = $node.parentNode;
        }
        return ($parentList);
    }

    function toggleItem() {
        $open = document.getElementsByClassName($itemClick);
        for ($i = 0; $i < $open.length; $i++) {
            $click.classList.toggle('opened');
            $open[$i].classList.toggle('opened');
        }
    }

    function closeItem() {
        $lastButton.classList.remove('opened');
        $close = document.getElementsByClassName($lastOpen);
        for ($i = 0; $i < $close.length; $i++) {
            $close[$i].classList.remove('opened');
        }
        clearVar();
    }

    function clearVar() {
        $lastButton = null;
        $lastOpen = null;
        $fix = null;
        $nodeList = null;
    }

    function closeAll() {
        var $dataOpen = document.querySelectorAll('.opened');
        $dataOpen.forEach(function (e) {
            e.classList.remove('opened');
        });
        clearVar();
    }

    this.forceClose = function () {
        closeAll();
    };
};
