/**
 * *****************************************************
 * @Copyright (c) Spell Master.
 * *****************************************************
 * Controle motor de interatividades com website
 * *****************************************************
 **/

var smCore = smCore || {};
smTools = smTools || {};

(function () {
    'use strict';
    smCore = (function () {
        /**
         * **********************************************
         * * Aciona a caixa de notificação global
         * @param {STR} msg
         * Informar o conteúdo da notificação
         * @param {BOOL} sound
         * Um som deve ser tocado?
         * **********************************************
         */
        var notifyBox = function (msg, sound) {
            var $box = document.createElement('div');
            $box.id = 'box-notify';
            $box.innerHTML = msg;
            document.body.appendChild($box);
            if (sound) {
                document.getElementById('notify-wav').play();
            }
            setTimeout(function () {
                $box.parentNode.removeChild($box);
            }, 5000);
        };

        /**
         * **********************************************
         * * Atualiza o breadCrumb
         * **********************************************
         * 
         * @param {ARR} crumb
         * Informar um array com os índices.
         * 
         * @see
         * Não precisa informar o índice inicial
         * 
         * @example
         * updateCrumbs('<?= json_encode(['a','b']) ?>')
         * **********************************************
         */
        var updateCrumbs = function (crumb) {
            var $crumbs = document.getElementById('crumbs');
            var $array = (typeof crumb === 'string' ? JSON.parse(crumb) : crumb);
            var $loop = 0;
            var $count = $array.length;
            var $str = ('<a href="./" title="Página Inicial" class="href-link margin-left" style="font-weight:0"><i class="icon-home5"></i></a> / ');
            $array.forEach(function (e) {
                $loop++;
                if ($loop != $count) {
                    $str += ('<a href="' + e + '" class="href-link">' + e + '</a> / ');
                } else {
                    $str += (e);
                }
            });
            $crumbs.innerHTML = $str;
        };

        /**
         * **********************************************
         * * Controle de exibição de spoilers
         * **********************************************
         */
        var spoilerToggle = function () {
            var $spolier = document.getElementsByClassName('spoiler-read');
            var $sl = $spolier.length;
            if ($sl >= 1) {
                var $curret, $i;
                for ($i = 0; $i < $sl; $i++) {
                    $spolier[$i].addEventListener('click', function (e) {
                        $curret = e.target;
                        $curret.classList.toggle('active');
                        ($curret.nextElementSibling).classList.toggle('active');
                    }, false);
                }
            }
        };

        /**
         * **********************************************
         * * Botão de rolagem da tela até o topo
         * **********************************************
         */
        var topScroll = function () {
            var $scrollY = 0;
            var $topButton = document.getElementById('scoll-top');
            $topButton.addEventListener('click', smTools.scroll.top, false);
            document.addEventListener('scroll', function (e) {
                $scrollY = window.scrollY;
                if ($scrollY > 200) {
                    $topButton.classList.add('show');
                } else {
                    $topButton.classList.remove('show');
                }
            }, false);
        };

        /**
         * **********************************************
         * * Controle de redirecionamento da janela
         * **********************************************
         * 
         * @function reload
         * Atualiza a janela
         * **********************************************
         * 
         * @function href
         * Redireciona para o local expecíficado
         * 
         * @param {STR} moveTo
         * Para onde deve ir
         * **********************************************
         */
        var reLocation = {
            reload: function () {
                window.location.reload();
            }, href: function (move) {
                window.location.href = move;
            }
        };

        /**
         * **********************************************
         * * Controle do modal
         * **********************************************
         * 
         * @function ajax
         * Abre a janela modal e excuta ajax em arquivo
         * dentro dela.
         * 
         * @param {STR} file
         * Local onde se encontra o arquivo
         * 
         * @param {STR} title
         * Título para janela
         * 
         * @param {BOOL} x
         * Botão de fechar mostrar/ocultar
         * **********************************************
         * 
         * @function saveform
         * Salva formulários na janela modal
         * 
         * @param {STR} form 
         * #ID do formulário
         * 
         * @param {STR} file 
         * Arquivo para enviar os dados
         * 
         * @param {STR} windowTitle
         * Título para a janela
         * 
         * @param {BOOL} open
         * A janela deve ser aberta?
         * **********************************************
         * 
         * @funtion error
         * Exibe retornos de erros de execução geral
         * 
         * @param {STR} textError
         * Texto a exibir como erro
         * 
         * @param {BOOL} open
         * Nova janela deve ser aberta ou usar a atual
         * **********************************************
         */
        var modalControl = {
            ajax: function (file, title, x) {
                smTools.modal.open(title, x);
                return(smTools.ajax.send('modal-load', file, false));
            },
            saveform: function (form, file, windowTitle, open) {
                if (open) {
                    smTools.modal.open(windowTitle, false);
                } else {
                    smTools.modal.hiddenX();
                    smTools.modal.title(windowTitle);
                }
                return (smTools.ajax.formSend(form, 'modal-load', 'modules/actions/' + file));
            },
            error: function (textError, open) {
                var $modal = document.getElementById('modal-load');
                $modal.innerHTML = null;
                if (open) {
                    smTools.modal.open('Aviso', true);
                } else {
                    smTools.modal.title('Aviso');
                    smTools.modal.showX();
                }
                document.getElementById('modal-load').innerHTML = '<div class="text-red align-center padding-all"><i class="icon-warning icn-4x"></i><div class="font-medium">' + textError + '</div></div>';
            },
            alertAction: function (formID, file, title) {
                smTools.modal.open('Cuidado!', true);
                document.getElementById('modal-load').innerHTML = '\
                    <div class="text-orange align-center padding-all">\n\
                        <i class="icon-spam icn-4x"></i>\n\
                        <p class="font-large">Você tem certeza que deseja prosseguir?</p>\n\
                    </div>\n\
                    <div class="row-pad">\n\
                        <div class="col-half col-fix">\n\
                            <button class="btn-warning button-block text-white" onclick="smCore.modal.saveform(\'' + formID + '\', \'' + file + '\', \'' + title + '\', true);">\n\
                                <i class="icon-bell3"></i> SIM\n\
                            </button>\n\
                        </div>\n\
                        <div class="col-half col-fix">\n\
                            <button class="btn-default button-block" onclick="smTools.modal.close();">\n\
                                NÃO <i class="icon-blocked"></i>\n\
                            </button>\n\
                        </div>\n\
                    </div>';
                return (false);
            },
            dangerAction: function (formID, file, title) {
                smTools.modal.open('Atenção!', true);
                document.getElementById('modal-load').innerHTML = '\
                    <div class="text-red align-center padding-all">\n\
                        <i class="icon-warning2 icn-4x"></i>\n\
                        <p class="font-large">Você tem certeza que deseja prosseguir?</p>\n\
                        <p>Esta ação não poderá ser desfeita!</p>\n\
                    </div>\n\
                    <div class="row-pad">\n\
                        <div class="col-half col-fix">\n\
                            <button class="btn-danger button-block text-white" onclick="smCore.modal.saveform(\'' + formID + '\', \'' + file + '\', \'' + title + '\', true);">\n\
                                <i class="icon-notification"></i> SIM\n\
                            </button>\n\
                        </div>\n\
                        <div class="col-half col-fix">\n\
                            <button class="btn-default button-block" onclick="smTools.modal.close();">\n\
                                NÃO <i class="icon-blocked"></i>\n\
                            </button>\n\
                        </div>\n\
                    </div>';
                return (false);
            }
        };


        /**
         * **********************************************
         * * Interatividade com itens de formulário
         * **********************************************
         * 
         * @function capctha
         * troca o código capctha
         * **********************************************
         * 
         * @function showPass
         * Mostra o oculta caracteres de campo password
         * 
         * @param {DOM} btn
         * Elemento HTML que aciona a função
         * **********************************************
         * 
         * @function stripTags
         * Remove caracteres expeciais de uma string
         * 
         * @param {DOM} btn
         * Elemento HTML que aciona a função
         * 
         * **********************************************
         * @function save
         * Salva formulários
         * 
         * @param {STR} form
         * #ID do formulário
         * 
         * @param {STR} div
         * #ID do local de resultado
         * 
         * @param {STR} file
         * Arquivo para enviar dados
         * 
         * **********************************************
         * @function progress
         * Cria animação de progresso no formulário igual
         *  ao AjaxRequest.
         * 
         * @param {OBJ} formTgt
         * Formulário alvo.
         * **********************************************
         */
        var formItens = {
            capctha: function () {
                var $img = new Image(),
                        $id = document.getElementById('captchaimg');
                $img.src = 'lib/image/captcha.php?rand=' + Math.random();
                $id.src = $img.src;
            },
            showPass: function () {
                var $input = document.getElementById('pass'),
                        $icon = document.getElementById('pass-icon');
                if ($input.type === 'password') {
                    $input.type = 'text';
                    $icon.title = 'Ocultar Senha';
                    $icon.className = 'icon-eye-blocked font-large';
                } else {
                    $input.type = 'password';
                    $icon.title = 'Mostrar Senha';
                    $icon.className = 'icon-eye font-large';
                }
            },
            progress: function (formTgt) {
                var $loading = document.createElement('div'),
                        $svg = '<svg class="load-pre" viewBox="25 25 50 50"><circle class="load-path" cx="50" cy="50" r="20" fill="none" stroke="#555" stroke-width="4" stroke-miterlimit="10"/></svg>';
                $loading.classList.add('load-form');
                $loading.innerHTML = '<div class="fade-progress">' + $svg + '</div>';
                formTgt.classList.add('form-conter');
                formTgt.appendChild($loading);
            },
            save: function (form, div, file) {
                return (smTools.ajax.form(form, div, 'modules/actions/' + file));
            }
        };


        return {
            notify: notifyBox,
            crumbs: updateCrumbs,
            spoiler: spoilerToggle,
            topScroll: topScroll,
            go: reLocation,
            modal: modalControl,
            formItens: formItens
        };

    }());
}());
