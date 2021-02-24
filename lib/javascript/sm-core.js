/**
 * *****************************************************
 * @Copyright (c) Spell Master.
 * *****************************************************
 * @class Controle motor de interatividades com website
 * *****************************************************
 **/

var smcore = smcore || {};
smlib = smlib || {};

(function () {
    'use strict';
    smcore = (function () {


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
                smlib.modal.open(title, x);
                return(smlib.ajax.send('modal-load', file, false));
            },
            saveform: function (form, file, windowTitle, open) {
                if (open) {
                    smlib.modal.open(windowTitle, false);
                } else {
                    smlib.modal.hiddenX();
                    smlib.modal.title(windowTitle);
                }
                return (smlib.ajax.formSend(form, 'modal-load', 'actions/' + file));
            },
            error: function (textError, open) {
                var $modal = document.getElementById('modal-load');
                $modal.innerHTML = null;
                if (open) {
                    smlib.modal.open('Aviso', true);
                } else {
                    smlib.modal.title('Aviso');
                    smlib.modal.showX();
                }
                document.getElementById('modal-load').innerHTML = '<div class="text-red align-center padding-all"><i class="icon-warning icn-4x"></i><div class="font-medium">' + textError + '</div></div>';
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
            }
        };

        return {
            go: reLocation,
            modal: modalControl,
            formItens: formItens
        };

    }());
}());
