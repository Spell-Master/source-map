/**
 * *****************************************************
 * @Copyright (c) Spell Master.
 * *****************************************************
 * @class Controle motor de interatividades com website
 * *****************************************************
 **/

var smc = smc || {};
sml = sml || {};
//CKEDITOR = CKEDITOR || {};

(function () {
    'use strict';
    smc = (function () {

        /**
         * **********************************************
         * * Ajax pelos links no menu principal direito
         * @param {STR} dir
         * Diretório para o page-load.php
         * **********************************************
         */
        var globalMenu = function (dir) {
            var $menu = document.getElementById('global-menu'), $i, $links, $target, $href;

            if (sml.isReady($menu)) {
                $links = $menu.querySelectorAll('a');
                for ($i = 0; $i < $links.length; $i++) {
                    $links[$i].addEventListener('click', openLink, false);
                }
            }
            function openLink(e) {
                e.preventDefault();
                $target = e.target;
                if (!$target.classList.contains('active')) {
                    sml.scrollTop();
                    linksActive();
                    $target.classList.add('active');
                    $href = $target.getAttribute('href');
                    sml.ajax.pop('page-load', 'modules/' + dir + '/page-load.php?url=' + $href, $href);
                }
                return (false);
            }
            function linksActive() {
                for ($i = 0; $i < $links.length; $i++) {
                    if ($links[$i].classList.contains('active')) {
                        $links[$i].classList.remove('active');
                    }
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
            $topButton.addEventListener('click', sml.scrollTop, false);
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
                sml.modal.open(title, x);
                return(sml.ajax.send('modal-load', file, false));
            },
            saveform: function (form, file, windowTitle, open) {
                if (open) {
                    sml.modal.open(windowTitle, false);
                } else {
                    sml.modal.hiddenX();
                    sml.modal.title(windowTitle);
                }
                return (sml.ajax.formSend(form, 'modal-load', 'modules/actions/' + file));
            },
            error: function (textError, open) {
                var $modal = document.getElementById('modal-load');
                $modal.innerHTML = null;
                if (open) {
                    sml.modal.open('Aviso', true);
                } else {
                    sml.modal.title('Aviso');
                    sml.modal.showX();
                }
                document.getElementById('modal-load').innerHTML = '<div class="text-red align-center padding-all"><i class="icon-warning icn-4x"></i><div class="font-medium">' + textError + '</div></div>';
            },
            alertAction: function (formID, file, title) {
                sml.modal.open('Cuidado!', true);
                document.getElementById('modal-load').innerHTML = '\
                    <div class="text-orange align-center padding-all">\n\
                        <i class="icon-spam icn-4x"></i>\n\
                        <p class="font-large">Você tem certeza que deseja prosseguir?</p>\n\
                    </div>\n\
                    <div class="row-pad">\n\
                        <div class="col-half col-fix">\n\
                            <button class="btn-warning button-block text-white" onclick="smc.modal.saveform(\'' + formID + '\', \'' + file + '\', \'' + title + '\', true);">\n\
                                <i class="icon-bell3"></i> SIM\n\
                            </button>\n\
                        </div>\n\
                        <div class="col-half col-fix">\n\
                            <button class="btn-default button-block" onclick="sml.modal.close();">\n\
                                NÃO <i class="icon-blocked"></i>\n\
                            </button>\n\
                        </div>\n\
                    </div>';
                return (false);
            },
            dangerAction: function (formID, file, title) {
                sml.modal.open('Atenção!', true);
                document.getElementById('modal-load').innerHTML = '\
                    <div class="text-red align-center padding-all">\n\
                        <i class="icon-warning2 icn-4x"></i>\n\
                        <p class="font-large">Você tem certeza que deseja prosseguir?</p>\n\
                        <p>Esta ação não poderá ser desfeita!</p>\n\
                    </div>\n\
                    <div class="row-pad">\n\
                        <div class="col-half col-fix">\n\
                            <button class="btn-danger button-block text-white" onclick="smc.modal.saveform(\'' + formID + '\', \'' + file + '\', \'' + title + '\', true);">\n\
                                <i class="icon-notification"></i> SIM\n\
                            </button>\n\
                        </div>\n\
                        <div class="col-half col-fix">\n\
                            <button class="btn-default button-block" onclick="sml.modal.close();">\n\
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
            save: function (form, div, file) {
                return (sml.ajax.form(form, div, 'modules/actions/' + file));
            }
        };

        return {
            globalMenu: globalMenu,
            topScroll: topScroll,
            go: reLocation,
            modal: modalControl,
            formItens: formItens
        };

    }());
}());
