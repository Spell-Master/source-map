/* 
 * sripts para serem adicionados ao sm-libary.js
 * estão aqui enquanto durar o desenvolvimento
 */

// Accordion V2.2 (2021)
var Accordion=function(){for(var t=document.getElementsByClassName("acc-button"),e={target:null,next:null,last:null,copy:null,height:null},n=0;n<t.length;n++)t[n].addEventListener("click",l,!1);function l(t){e.target=t.target,e.next=e.target.nextElementSibling,e.last==e.next?a():(e.last&&a(),i(),s())}function i(){e.copy=e.next.cloneNode(!0),e.copy.setAttribute("style","height:auto; visibility:visible"),e.next.parentNode.appendChild(e.copy),e.height=e.copy.offsetHeight,e.next.parentNode.removeChild(e.copy)}function a(){e.last.previousElementSibling.classList.remove("active"),e.last.style.height="0px",e.last=null,e.height=0}function s(){e.target.classList.add("active"),e.next.style.height=e.height+"px",e.last=e.next}this.forceOpen=function(t){var n;n=t?parseInt(t-1):0,e.next=document.getElementsByClassName("acc-container")[n],e.last=e.next,e.target=e.next.previousElementSibling,i(),s()}};
// AjaxRequest V4.2 (2021)
var AjaxRequest=function(){var e={http:null,loadID:null,file:null,response:null,url:null,form:null,head:null,loading:null,vetor:[null],exception:null};function o(){n(),e.http.addEventListener("readystatechange",r,!1),e.http.open("GET",e.file,!0),e.http.send()}function t(){n(),e.http.addEventListener("readystatechange",r,!1),e.http.open("POST",e.file,!0),e.http.setRequestHeader("Content-type","application/x-www-form-urlencoded"),e.http.send(e.head)}function n(){return e.http=new XMLHttpRequest,e.http.overrideMimeType&&e.http.overrideMimeType("text/html"),e.http}function r(){e.vetor&&1===e.http.readyState?function(){var o='<svg class="load-pre" viewBox="25 25 50 50"><circle class="load-path" cx="50" cy="50" r="20" fill="none" stroke="#'+e.vetor[1]+'" stroke-width="4" stroke-miterlimit="10" /></svg>';switch(e.vetor[0]){case"send":e.loadID.innerHTML='<div class="load-local">'+o+"</div>";break;case"pop":e.loading=document.createElement("div"),e.loading.classList.add("load-pop"),e.loading.innerHTML='<div class="progress-text">Carregando...</div>'+o,document.body.appendChild(e.loading);break;case"form":e.loading=document.createElement("div"),e.form.classList.add("form-conter"),e.loading.classList.add("load-form"),e.loading.innerHTML='<div class="fade-progress">'+o+"</div>",e.form.appendChild(e.loading);break;case"formSend":e.loading=document.createElement("div"),e.form.classList.add("form-conter"),e.loading.classList.add("load-form"),e.loadID.innerHTML='<div class="load-local">'+o+"</div>",e.form.appendChild(e.loading)}}():404===e.http.status?console.warn("Arquivo ["+e.file+"] não encontrado!"):500===e.http.status?console.warn("Erro na resposta do servidor"):4===e.http.readyState&&200===e.http.status&&(e.response=e.http.responseText,e.vetor[0]?setTimeout(function(){if("pop"===e.vetor[0])document.body.removeChild(e.loading);else if("form"===e.vetor[0]||"formSend"===e.vetor[0]){for(var o=0;o<e.form.elements.length;o++)e.form.elements[o].disabled=!1;e.form.removeChild(e.loading)}e.loadID.innerHTML=e.response,e.vetor=[null],e.http=null,i(),e.url&&(window.history.replaceState(null,null,e.url),e.url=null)},1e3):(e.loadID.innerHTML=e.response,i(),e.http=null))}function i(){var o,t,n,r,i=e.response.indexOf("<script",0);for(!function(){var o,t=e.loadID.getElementsByTagName("script");for(o=t.length-1;o>=0;o--)t[o].parentNode.removeChild(t[o])}();-1!=i;)o=document.createElement("script"),(t=e.response.indexOf(" src",i))<(i=e.response.indexOf(">",i)+1)&&t>=0?(i=t+4,n=e.response.indexOf(".js",i)+3,r=(r=e.response.substring(i,n)).replace("=","").replace(" ","").replace('"',"").replace('"',"").replace("'","").replace("'","").replace(">",""),o.src=r):(n=e.response.indexOf("<\/script>",i),r=e.response.substring(i,n),o.text=r),e.loadID.appendChild(o),i=e.response.indexOf("<script",n),o=null}function a(){var o,t,n,r;for(o=0;o<e.form.elements.length;o++)e.form.elements[o].disabled=!0,"checkbox"===e.form.elements[o].type?e.form.elements[o].checked&&(t=e.form.elements[o].value,e.head+="&"+e.form.elements[o].name+"="+t):"radio"===e.form.elements[o].type?e.form.elements[o].checked&&(n=e.form.elements[o].value,e.head+="&"+e.form.elements[o].name+"="+n):e.head+="&"+e.form.elements[o].name+"="+(r=e.form.elements[o].value,encodeURIComponent(r).replace(/['()]/g,escape).replace(/\*/g,"%2A").replace(/%(?:7C|60|5E)/g,unescape))}function l(e){return null!=e}function d(){console.warn(e.exception),e.http=null,e.exception=null,e.vetor=[null]}this.open=function(t,n){e.loadID=document.getElementById(t),e.file=n;try{if(!t)throw'Parâmetro "inId" não expecificado';if(!n)throw'Parâmetro "file" não expecificado';if(!l(e.loadID))throw'Elemento "#'+t+'" não encontrado';if(e.http instanceof XMLHttpRequest)throw"Já existe uma requisição de protocolo em andamento.";o()}catch(o){e.exception=o,d()}return!1},this.send=function(t,n,r){e.loadID=document.getElementById(t),e.url=r||null,e.file=n,e.vetor=["send",555];try{if(!t)throw'Parâmetro "inId" não expecificado';if(!n)throw'Parâmetro "file" não expecificado';if(!l(e.loadID))throw'Elemento "#'+t+'" não encontrado';if(e.http instanceof XMLHttpRequest)throw"Já existe uma requisição de protocolo em andamento.";o()}catch(o){e.exception=o,d()}return!1},this.pop=function(t,n,r){e.loadID=document.getElementById(t),e.url=r||null,e.file=n,e.vetor=["pop","ccc"];try{if(!t)throw'Parâmetro "inId" não expecificado';if(!n)throw'Parâmetro "file" não expecificado';if(!l(e.loadID))throw'Elemento "#'+t+'" não encontrado';if(e.http instanceof XMLHttpRequest)throw"Já existe uma requisição de protocolo em andamento.";o()}catch(o){e.exception=o,d()}return!1},this.form=function(o,n,r){e.form=document.getElementById(o),e.loadID=document.getElementById(n),e.file=r,e.head="form_id="+o,e.vetor=["form",555];try{if(!o)throw'Parâmetro "form" não expecificado.';if(!n)throw'Parâmetro "inId" não expecificado';if(!r)throw'Parâmetro "file" não expecificado';if(!l(e.form))throw'Elemento "#'+o+'" não encontrado';if(!l(e.loadID))throw'Elemento "#'+n+'" não encontrado';if(e.http instanceof XMLHttpRequest)throw"Já existe uma requisição de protocolo em andamento.";a(),t()}catch(o){e.exception=o,d()}return!1},this.formSend=function(o,n,r){e.form=document.getElementById(o),e.loadID=document.getElementById(n),e.file=r,e.head="form_id="+o,e.vetor=["formSend",555];try{if(!o)throw'Parâmetro "form" não expecificado.';if(!n)throw'Parâmetro "inId" não expecificado';if(!r)throw'Parâmetro "file" não expecificado';if(!l(e.form))throw'Elemento "#'+o+'" não encontrado';if(!l(e.loadID))throw'Elemento "#'+n+'" não encontrado';if(e.http instanceof XMLHttpRequest)throw"Já existe uma requisição de protocolo em andamento.";e.loadID.innerHTML=null,a(),t()}catch(o){e.exception=o,d()}return!1}};
// FileTransfer V2.1 (2021)
var FileTransfer=function(){var e={request:null,method:null,file:null,name:null,progress:null,percent:null,cancel:null,bar:null},n={form:null,result:null},r={url:null,blob:null,link:null};function t(){e.request instanceof XMLHttpRequest&&(e.request.abort(),c())}function l(){e.progress=document.createElement("div"),e.progress.id="transfer-progress",e.progress.innerHTML='<div class="progress-text"></div><div class="progress-file"></div><div class="progress-percent"></div><div class="progress-line"><div class="progress-bar"></div></div>',document.body.appendChild(e.progress),e.cancel&&(e.cancel.classList.add("progress-cancel"),e.cancel.innerText="Cancelar",e.cancel.addEventListener("click",t,!1),e.progress.appendChild(e.cancel))}function s(){"up"===e.method?e.progress.querySelector(".progress-text").innerText="Enviando Arquivo":"do"===e.method&&(e.progress.querySelector(".progress-text").innerText="Recebendo Arquivo"),e.progress.querySelector(".progress-file").innerText=e.name,e.percent=e.progress.querySelector(".progress-percent"),e.bar=e.progress.querySelector(".progress-bar")}function o(n){var r;n.lengthComputable&&(r=Math.round(n.loaded/n.total*100),e.percent.innerText=r+"% completado",e.bar.style.width=r+"%")}function u(){404===e.request.status?(console.warn("Não foi possível localizar o arquivo ("+e.name+")"),t()):4===e.request.readyState&&200===e.request.status&&(e.cancel&&(e.cancel.style.display="none"),n.result&&(n.result.innerHTML=e.request.responseText),"up"===e.method?function(){if(n.result){var r,t,l,s,o,u=e.request.responseText,c=u.indexOf("<script",0),a=n.result.getElementsByTagName("script");for(o=a.length-1;o>=0;o--)a[o].parentNode.removeChild(a[o]);for(;-1!=c;)r=document.createElement("script"),t=u.indexOf(" src",c),c=u.indexOf(">",c)+1,t<c&&t>=0?(c=t+4,l=u.indexOf(".js",c)+3,s=(s=u.substring(c,l)).replace("=","").replace(" ","").replace('"',"").replace('"',"").replace("'","").replace("'","").replace(">",""),r.src=s):(l=u.indexOf("<\/script>",c),s=u.substring(c,l),r.text=s),n.result.appendChild(r),c=u.indexOf("<script",l),r=null}else console.warn("Local de checagem do envio não expecíficado")}():(r.blob?(r.url=window.URL.createObjectURL(e.request.response),r.link.href=r.url):r.link.href=e.file,r.link.download=e.name,document.body.appendChild(r.link),r.link.click()),setTimeout(c,1e3))}function c(){e.cancel&&e.cancel.removeEventListener("click",t),"do"===e.method&&(r.blob&&window.URL.revokeObjectURL(r.url),document.body.removeChild(r.link)),document.body.removeChild(e.progress),function(){"up"==e.method?(n.form=null,n.result=null):r.link=null;r.blob&&(r.blob=null,r.url=null);e.request=null,e.method=null,e.file=null,e.name=null,e.progress=null,e.percent=null,e.cancel=null,e.bar=null}()}this.upload=function(r,t,c,a){var i=document.getElementById(r).querySelector('input[type="file"]');i.value?t?e.request instanceof XMLHttpRequest?console.warn("Já existe um processo em andamento"):(e.request=new XMLHttpRequest,n.form=new FormData,e.cancel=a?document.createElement("button"):null,n.result=c?document.getElementById(c):null,e.method="up",e.file=i.files[0],e.name=e.file.name,n.form.append(i.name,e.file),l(),s(),e.request.upload.addEventListener("progress",o,!1),e.request.addEventListener("readystatechange",u,!1),e.request.responseType="text",e.request.open("POST",t,!0),e.request.send(n.form)):console.warn("Destino de recebimento não expecificado"):console.warn("Nenhum arquivo selecionado");return!1},this.download=function(n,t,c){n?e.request instanceof XMLHttpRequest?console.warn("Já existe um processo em andamento"):(e.request=new XMLHttpRequest,e.file=n,e.name=e.file.split("/").reverse()[0],e.cancel=t?document.createElement("button"):null,r.blob=c||null,e.method="do",r.link=document.createElement("a"),l(),s(),e.request.addEventListener("progress",o,!1),e.request.addEventListener("readystatechange",u,!1),e.request.responseType="blob",e.request.open("GET",n,!0),e.request.send()):console.warn("Arquivo de envio não expecificado");return!1},this.stop=t};
// ImageCut V2.1 (2021)
var ImageCut=function(t){var e,i,o,n,s=document.getElementById(t),h=!1,d=!1,a={ratio:1,left:0,top:0},g={imgLeft:0,imgTop:0,imgWidth:0,imgHeight:0,boxLeft:0,boxTop:0,posX:0,posY:0},r={boxWidth:0,boxHeight:0,imgWidth:0,imgHeight:0,left:0,top:0,right:0,bottom:0},m={w:0,h:0};function f(t){return Math.ceil("w"===t?s.offsetWidth:s.offsetHeight)}function c(t,e){a.left=-t*a.ratio,a.top=-e*a.ratio,o.style.top=-e+"px",o.style.left=-t+"px"}function u(t){var e;t.preventDefault(),t.stopPropagation(),e=t,g.boxLeft=i.offsetLeft,g.boxTop=i.offsetTop,g.posX=(e.clientX||e.pageX||e.touches&&e.touches[0].clientX)+window.scrollX,g.posY=(e.clientY||e.pageY||e.touches&&e.touches[0].clientY)+window.scrollY,document.addEventListener("mousemove",l),document.addEventListener("touchmove",l),document.addEventListener("mouseup",p),document.addEventListener("touchend",p)}function p(t){t.preventDefault(),document.removeEventListener("mouseup",p),document.removeEventListener("touchend",p),document.removeEventListener("mousemove",l),document.removeEventListener("touchmove",l)}function l(t){t.preventDefault(),t.stopPropagation(),g.imgLeft=(t.pageX||t.touches&&t.touches[0].pageX)-(g.posX-g.boxLeft),g.imgTop=(t.pageY||t.touches&&t.touches[0].pageY)-(g.posY-g.boxTop),g.imgWidth=i.offsetWidth+3,g.imgHeight=i.offsetHeight+3,g.imgLeft<0?g.imgLeft=1:g.imgLeft>o.offsetWidth-g.imgWidth&&(g.imgLeft=o.offsetWidth-g.imgWidth),g.imgTop<0?g.imgTop=1:g.imgTop>o.offsetHeight-g.imgHeight&&(g.imgTop=o.offsetHeight-g.imgHeight),c(g.imgLeft,g.imgTop),v(g.imgLeft,g.imgTop)}function v(t,e){i.style.top=e+100+"px",i.style.left=t+100+"px"}function x(t){t.preventDefault(),L(t.deltaY)}function L(t){if(h){if(r.boxWidth=Math.floor(i.clientWidth+t),r.boxHeight=Math.floor(i.clientHeight+t),r.imgWidth=o.clientWidth,r.imgHeight=o.clientHeight,r.boxWidth<50)return;if(r.boxWidth>r.imgWidth)return;if(r.left=i.offsetLeft-t/2,r.top=i.offsetTop-t/2,r.right=r.left+r.boxWidth,r.bottom=r.top+r.boxHeight,r.left<0&&(r.left=0),r.top<0&&(r.top=0),r.right>r.imgWidth)return;if(r.bottom>r.imgHeight)return;a.ratio=200/r.boxWidth,e=r.boxWidth,n=r.boxWidth,i.style.width=e+"px",i.style.height=n+"px",c(r.left,r.top),v(r.left,r.top),b()}var e,n}function b(){a.width=o.width*a.ratio,a.height=o.height*a.ratio,n.width=200,n.height=200,n.getContext("2d").drawImage(o,a.left,a.top,a.width,a.height)}function w(t){switch(t.preventDefault(),String.fromCharCode(t.charCode)){case"+":L(10);break;case"-":L(-10)}}void 0!==s||null!==s?(s.addEventListener("load",function(t){m.w=f("w"),m.h=f("h"),m.w<201?console.warn("A imagem de corte deve possuir pelo menos 210 pixel's de largura"):m.h<201?console.warn("A imagem de corte deve possuir pelo menos 210 pixel's de altura"):(e=document.createElement("div"),i=document.createElement("div"),o=new Image,n=document.createElement("canvas"),s.classList.add("cut-focus"),s.draggable=!1,e.classList.add("cut-limiter"),e.setAttribute("style","max-width:"+m.w+"px; max-height:"+m.h+"px"),i.classList.add("cut-box"),o.src=s.src,o.draggable=!1,o.setAttribute("style","width:auto; height:auto; max-width:"+m.w+"px; max-height:"+m.h+"px"),s.parentNode.insertBefore(e,s.nextSibling),e.appendChild(i),i.appendChild(o),e.appendChild(s),c(m.w/2-100,m.h/2-100),i.addEventListener("mousedown",u,!1),i.addEventListener("touchstart",u,!1),i.addEventListener("wheel",x,!1),document.addEventListener("keypress",w,!1),h=!0)},!1),this.sizePlus=function(){L(10)},this.sizeMinus=function(){L(-10)},this.setCut=function(){b(),$getImage=n.toDataURL("image/png",1),d=!0},this.getImage=function(){if(d)return $getImage}):console.error("Não foi possível identificar a imagem para corte")};
// ImageGalery V1.0 (2019)
var ImageGalery=function(e){var t=document.getElementById(e),n={backGround:null,galeryBox:null,boxImg:null,thumb:null,close:null};function c(e){!function(){for(var e,c=t.getElementsByTagName("img"),a=0;a<c.length;a++)(e=document.createElement("img")).src=c[a].src,e.classList.add("thumb"),n.thumb.appendChild(e),e.addEventListener("click",l,!1)}(),a()}function a(){n.backGround.classList.toggle("active"),n.galeryBox.classList.toggle("active"),n.thumb.classList.toggle("active"),n.close.classList.toggle("active")}function l(e){n.backGround.src=e.target.src,n.boxImg.src=e.target.src}function d(){n.backGround.src=null,n.boxImg.src=null,n.thumb.innerHTML=null;var e,t=n.thumb.getElementsByTagName("img");for(e=0;e<t.length;e++)t[e].parentNode&&t[e].parentNode.removeChild(t[e]);a()}function o(e){27==e.keyCode&&d()}n.backGround=document.createElement("img"),n.backGround.classList.add("galery-background"),document.body.appendChild(n.backGround),n.galeryBox=document.createElement("div"),n.boxImg=document.createElement("img"),n.galeryBox.classList.add("galery-box"),n.boxImg.classList.add("galery-image"),document.body.appendChild(n.galeryBox),n.galeryBox.appendChild(n.boxImg),n.thumb=document.createElement("div"),n.thumb.classList.add("thumb-background"),document.body.appendChild(n.thumb),n.close=document.createElement("a"),n.close.classList.add("galery-x"),n.close.title="Fechar",document.body.appendChild(n.close),n.close.addEventListener("click",d,!1),t.querySelectorAll("img").forEach(function(e){e.addEventListener("click",c,!1),e.addEventListener("click",l,!1),document.addEventListener("keypress",o,!1)}),this.openLight=c,this.setBg=l};
// ModalShow V3.0 (2020)
var ModalShow=function(e){var l={modal:document.getElementById(e),close:null,content:null};function o(){l.close&&(l.close.removeEventListener("click",o),l.close.classList.remove("active"),l.close=null),l.modal.classList.remove("active")}function c(e){l.modal.querySelector(".modal-title").innerText=e}function t(){l.close||(l.close=l.modal.querySelector(".modal-close"),l.close.classList.add("active"),l.close.addEventListener("click",o,!0))}this.open=function(e,o){l.modal.querySelector(".modal-header").innerHTML='<div class="modal-close"></div><div class="modal-title"></div>',l.content=l.modal.querySelector(".modal-content"),e&&c(e),o&&t(),l.modal.classList.add("active")},this.close=o,this.title=c,this.showX=t,this.hiddenX=function(){l.close&&(l.close.classList.remove("active"),l.close.removeEventListener("click",close,!0),l.close=null)}};
// Paginator V1.1 (2021)
var Paginator=function(t,a,l=null){var i=null!=l?document.getElementById(l):document,n={targetItem:i.getElementsByClassName(t),limit:parseInt(a),offset:0,amount:0,linksHtml:null};function e(t){n.rows=t?parseInt(t):1,n.offset=n.rows*n.limit-n.limit,function(){for(var t=0;t<n.targetItem.length;t++)n.targetItem[t].style.display="none"}(),function(){var t=n.rows-1,a=n.rows+1,l=n.targetItem.length;if(l>n.limit){for(n.amount=Math.ceil(l/n.limit),n.linksHtml='<ul class="paginator">',1!=n.rows&&(n.linksHtml+='<li><a title="Primeira Página" data-link-paginator="1" class="paginator-link"> &lt; </a></li>');t<=n.rows-1;t++)t>=1&&(n.linksHtml+='<li><a title="Página'+t+'" data-link-paginator="'+t+'" class="paginator-link">'+t+"</a></li>");for(n.linksHtml+='<li class="current"><a>'+n.rows+"</a></li>";a<=n.rows+1;a++)a<=n.amount&&(n.linksHtml+='<li><a title="Página '+a+'" data-link-paginator="'+a+'" class="paginator-link">'+a+"</a></li>");n.amount!=t&&(n.linksHtml+='<li><a title="Última Página" data-link-paginator="'+n.amount+'" class="paginator-link"> &gt; </a></li>'),n.linksHtml+='<li><a class="amount">'+n.rows+"/ "+n.amount+"</a></li>",n.linksHtml+="</ul>",i.querySelectorAll("[data-paginator]").forEach(o)}}(),function(){for(var t=n.offset,a=n.offset+n.limit,l=t;l<a;l++)void 0!==n.targetItem[l]&&null!==n.targetItem[l]&&(n.targetItem[l].style.display="block"),t++}()}function o(t){t.innerHTML=n.linksHtml,i.querySelectorAll("[data-link-paginator]").forEach(r)}function r(t){t.addEventListener("click",s,!1)}function s(t){var a=t.target.dataset.linkPaginator;"undefined"!==a&&e(a)}this.init=e};
// SelectOption V3.5 (2020)
var SelectOption=function(){var e,t=document.getElementsByClassName("select-options"),n={index:0,current:null,base:null,ul:null,active:null};function i(){n.button=document.createElement("div"),n.button.classList.add("select-button"),n.base.appendChild(n.button),n.ul=document.createElement("ul"),n.ul.classList.add("select-list"),n.button.parentNode.insertBefore(n.ul,n.button.nextSibling),function(){n.head=document.getElementsByClassName("select-button");for(var e,t=n.current.querySelectorAll("option"),i=0;i<t.length;i++)(e=document.createElement("li")).setAttribute("data-select",t[i].value),e.setAttribute("data-parent",n.index),e.innerText=t[i].innerText.substring(0,20),e.addEventListener("click",a,!1),n.ul.appendChild(e);for(var s=0;s<t.length&&!t[s].selected;s++);var c=n.current.selectedIndex;n.head[n.index].innerText=c>=1?t[c].innerText.substring(0,20):t[0].innerText.substring(0,20)}()}function a(i){var a=i.target,s=[a.dataset.parent,a.dataset.select];n.head[s[0]].innerText=a.innerText.substring(0,20),t[s[0]].value=s[1],t[s[0]].dispatchEvent(e)}!function(){for(e=new Event("change"),n.index=0;n.index<t.length;n.index++)n.current=t[n.index],n.current.classList.contains("init")||(n.current.classList.add("init"),n.current.setAttribute("style","opacity:0; height:0; width:0; overflow:hidden"),n.base=document.createElement("div"),n.base.classList.add("select-base"),n.current.parentNode.insertBefore(n.base,n.current),i())}(),document.addEventListener("click",function(e){if(1===e.which){var t=e.target;n.active===t?(n.active.classList.remove("active"),t.nextElementSibling.classList.remove("active"),n.active=null):"select-button"===t.className?(n.active&&(n.active.classList.remove("active"),n.active.nextElementSibling.classList.remove("active")),t.classList.add("active"),t.nextElementSibling.classList.add("active"),n.active=t):null==n.active&&null==n.active||(n.active.classList.remove("active"),n.active.nextElementSibling.classList.remove("active"),n.active=null)}},!1)};
// ShoppingCart V1.1 (2021)
var ShoppingCart=function(e){var t,n=document.getElementById("shopping-cart"),r={text:n.querySelector('[data-cart=""]'),form:n.getElementsByTagName("form")[0],target:null,count:0,memory:[]};function o(){for(var e=0;e<r.form.children.length;e++)r.memory.push(e)}function a(e){var t;r.target=e.target,r.target.checked?(r.count++,(t=document.createElement("input")).type="hidden",t.name=r.target.name,t.value=r.target.value,r.form.appendChild(t),r.memory.push(t)):(r.count--,function(){for(var e=0;e<r.memory.length;e++)r.memory[e].value==r.target.value&&r.memory.splice(e,1);var t=r.form.querySelector('input[value="'+r.target.value+'"]');r.form.removeChild(t)}()),function(){r.count>=1?n.classList.add("opened"):n.classList.remove("opened");r.text.innerText=r.count>=1?"Selecionados ("+r.count+")":null}()}function c(e){e.checked&&(e.checked=null)}if(o(),this.addInput=function(e){"checkbox"!==e.type||e.classList.contains("cart-add")||(e.checked&&(e.checked=null),e.classList.add("cart-add"),e.addEventListener("change",a,!1))},this.restart=function(){for(var e=r.form.querySelectorAll("input"),t=0;t<e.length;t++)r.memory.includes(e[t])&&r.form.removeChild(e[t]);document.querySelectorAll("input.cart-add").forEach(c),r.memory.splice(0,r.memory.length),r.text.innerText=null,r.target=null,r.count=0,n.classList.remove("opened"),o()},e){t=document.getElementsByClassName(e);for(var l=0;l<t.length;l++)this.addInput(t[l])}};
// TabPaginator V4.1 (2020)
var TabPaginator=function(n){var e=n?document.getElementById(n):document,t={link:e.getElementsByClassName("tab-link"),body:e.getElementsByClassName("tab-body"),index:0};function i(n){return function(){a(n)}}function a(n){!function(){var n;for(n=0;n<t.link.length;n++)t.link[n].classList.remove("active"),t.body[n].classList.remove("active")}(),t.index=n?parseInt(n-1):0,t.link[t.index].classList.add("active"),t.body[t.index].classList.add("active")}!function(){for(var n=0;n<t.link.length;n++)t.link[n].addEventListener("click",i(n+1),!1)}(),a(),this.openTab=a};

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
            $itemClick = ($click.dataset.open ? $click.dataset.open : false);
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
                    if ($click.offsetParent.className == 'sub-menu') { // Quando clicado para abrir um sub-menu
                        // Alterar o click para o ícone na barra superior
                        $click = document.querySelector('li[data-open="session-menu"]');
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

/**
 * **************************************************
 * * @Class UserPhoto
 * * @author Spell-Master (Omar Pautz)
 * * @copyright 2021
 * * @version 1.0
 * **************************************************
 * * Classe para adicionar de imagem de perfil sem
 *  necessidade de upload processando pela propria
 *  máquina do usuário.
 * **************************************************
 */

/**
 * **************************************************
 * @param {OBJ} prevImg
 * Elemento onde deve ser mostrada a imagem e os
 *  componetes do corte.
 * @param {OBJ} input
 * Elemento type="file" para busca de arquivo.
 * @param {OBJ} status
 * Elemento para exibir erros de envio.
 * **************************************************
 */
var UserPhoto = function (prevImg, input, status) {
    var $forms = {
        prevImg: prevImg,
        input: input,
        status: status
    }, $process = {
        file: null,
        reader: null
    }, $progress = {
        base: null,
        bar: null,
        barWidth: 0
    }, $image = {
        img: null,
        load: null,
        ext: null,
        w: 0,
        h: 0
    };

    /**
     * **********************************************
     * @private
     * Define o primeiro arquivo enviado pelo input
     *  e inicia o processo de ouvintes de eventos.
     * @param {OBJ} e 
     * **********************************************
     */
    function getFile(e) {
        $process.file = e.target.files[0];
        readListener();
    }

    /**
     * **********************************************
     * @private
     * Define os processos de ouvintes de eventos.
     * **********************************************
     */
    function readListener() {
        createProgress();
        $process.reader = new FileReader();
        $process.reader.addEventListener('progress', computableProgress, true);
        $process.reader.addEventListener('load', createImage, true);
        $process.reader.readAsDataURL($process.file);
    }

    /**
     * **********************************************
     * @private
     * Cria os elementos de progresso.
     * **********************************************
     */
    function createProgress() {
        $progress.base = document.createElement('div');
        $progress.bar = document.createElement('div');
        $progress.base.id = 'photo-progress';
        $progress.bar.classList.add('progress-bar');
        $progress.bar.style.width = 0;
        $progress.base.appendChild($progress.bar);
        document.body.appendChild($progress.base);
    }

    /**
     * **********************************************
     * @private
     * Calcula a leitura do arquivo computando e
     *  tempo de resposta do mesmo atualizando o
     *   elemento de progresso com essa relação.
     * @param {OBJ} e
     * **********************************************
     */
    function computableProgress(e) {
        $progress.barWidth = 0;
        if (e.lengthComputable) {
            $progress.barWidth = Math.ceil(e.loaded / e.total) * 100;
            $progress.bar.style.width = $progress.barWidth + '%';
        }
    }

    /**
     * **********************************************
     * @private
     * Cria a imagem com base64 a partir do arquivo
     *  enviado.
     * @param {OBJ} e
     * **********************************************
     */
    function createImage(e) {
        $image.img = document.createElement('img');
        $image.img.id = 'cut-image';
        $image.img.src = $process.reader.result;
        $image.img.addEventListener('load', checkImage, false);
    }

    /**
     * **********************************************
     * @private
     * Verifica os parâmetros da imagem
     * @param {OBJ} e
     * **********************************************
     */
    function checkImage(e) {
        $progress.base.classList.add('hide-progress');
        setTimeout(function () {
            document.body.removeChild($progress.base);
        }, 3000);
        $image.load = e.target;
        $image.ext = ($process.file.name).substr(($process.file.name).lastIndexOf('.') + 1).toLowerCase();
        $image.w = parseFloat($image.load.width);
        $image.h = parseFloat($image.load.height);
        if ($image.ext !== 'jpg' && $image.ext !== 'jpeg' && $image.ext !== 'pjpeg' && $image.ext !== 'png' && $image.ext != 'x-png') {
            photoError('Você só pode enviar uma foto nos formatos: jpg, jpeg ou png');
        } else if ($image.w < 300) {
            photoError('A foto precisa ter pelo menos 300 de largura');
        } else if ($image.h < 300) {
            photoError('A foto precisa ter pelo menos 300 de altura');
        } else if ((Math.ceil($image.w * 2) < $image.h) || (Math.ceil($image.h * 2) < $image.w)) {
            photoError('A foto possui dimensões muito desproporcionais');
        } else {
            smCore.formItens.progress($forms.prevImg);
            fileCut();
        }
    }

    /**
     * **********************************************
     * @private
     * Mostra erros de envio requeridos por
     *   checkImage()
     * @param {STR} msg
     * Mensagem de erro
     * **********************************************
     */
    function photoError(msg) {
        $forms.status.innerHTML = '<div class="alert-danger fade-in">' + msg + '</div>';
        setTimeout(function () {
            $forms.status.innerHTML = null;
        }, 3000);
    }

    /**
     * **********************************************
     * @private
     * Armazena o base64 da imagem no localStorage
     *  requisitando por ajax o arquivo para iniciar
     *  o corte.
     * **********************************************
     */
    function fileCut() {
        setTimeout(function () {
            localStorage.setItem('user-photo', $process.reader.result);
            smTools.modal.hiddenX();
            smTools.ajax.send('modal-load', 'modules/user/profile/photo.php', false);
            resetVar();
        }, 1000);
    }

    /**
     * **********************************************
     * @private
     * Reinicia as variáveis.
     * **********************************************
     */
    function resetVar() {
        $forms.prevImg = null;
        $forms.input = null;
        $forms.status = null;
        $process.file = null;
        $process.reader = null;
        //$progress.base = null;
        //$progress.bar = null;
        $progress.barWidth = 0;
        $image.img = null;
        $image.load = null;
        $image.ext = null;
        $image.w = 0;
        $image.h = 0;
    }
    
    $forms.input.addEventListener('change', getFile, false);
};

/*
 * Funções para ajuste e corte da imagem depois de enviada
 */
var photoCut = function () {
    var $model = null,
            $interval = 0;

    this.scalePress = function (e) {
        $model = e.target.dataset.model;
        $interval = setInterval(scaleSize, 100);
    };

    this.scaleDrop = function (e) {
        clearInterval($interval);
        $model = null;
    };

    this.savePhoto = function () {
        smTools.imgCut.set();
        document.getElementById('base64').value = smTools.imgCut.get();
        smCore.modal.saveform('cut-photo', 'user/photo_v.php', 'Salvar Foto', false);
    };

    scaleSize = function () {
        if ($model === 'plus') {
            smTools.imgCut.plus();
        } else {
            smTools.imgCut.minus();
        }
    };
};
