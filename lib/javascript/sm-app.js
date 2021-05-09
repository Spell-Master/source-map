/**
 * *****************************************************
 * @Copyright (c) Spell Master.
 * *****************************************************
 * Controle de ações nas aplicações padrão
 * *****************************************************
 **/

var sm_a = sm_a || {};
sml = sml || {};

(function () {
    'use strict';
    sm_a = (function () {

        /**
         * **********************************************
         * * Gerenciamento da página pelo seletor.
         * **********************************************
         * @param {OBJ} e
         * Evento de disparo.
         * 
         * - edi
         * Carrega o arquivo para editar a página.
         * - del
         * Solicita confirmação para apagar a página.
         * **********************************************
         */
        var changeOption = function (e) {
            switch (e.target.value) {
                case 'edi':
                    var $hash = encodeURIComponent(document.getElementById('page-hash').value);
                    var $app = encodeURIComponent(document.getElementById('page-app').value);
                    document.getElementById('page-base').classList.add('hide');
                    sml.scrollTop();
                    sml.ajax.send('page-action', 'modules/app/edit.php?hash=' + $hash + '&app=' + $app, false);
                    break;
                case 'del':
                    smc.modal.dangerAction('del-app', 'app.php', 'Apagar Página');
                    break;
                default:
                    return (false);
                    break;
            }
        };

        /**
         * **********************************************
         * * Aciona ações do seletor de administração da
         *  página.
         * **********************************************
         */
        var managerPage = function () {
            var $manager = document.getElementById('manager-page');
            if (sml.isReady($manager)) {
                sml.select.init();
                $manager.addEventListener('change', changeOption, false);
            }
        };

        /**
         * **********************************************
         * * Remove a pré visualização da página.
         * **********************************************
         */
        var cancelPreview = function () {
            sml.scrollTop();
            document.getElementById('preview-page').innerHTML = null;
            document.getElementById('page-action').classList.remove('hide');
        };

        /**
         * **********************************************
         * * Carrega o arquivo para pré visualizar a
         *  página.
         * **********************************************
         */
        var preview = function (type) {
            sm_e.save('editor-page');
            var $editor = document.getElementById('editor-page').value;
            if (sml.isReady($editor) && $editor.length >= 1) {
                sml.scrollTop();
                document.getElementById('page-action').classList.add('hide');
                sml.ajax.formSend(type + '-app', 'preview-page', 'modules/actions/app/preview.php');
            } else {
                smc.modal.error('A página não possui conteúdo para gerar visualização', true);
            }
        };

        /**
         * **********************************************
         * * Carrega o arquivo para criar novas páginas.
         * **********************************************
         * @param {STR} app
         * Modelo da aplicação a ser criada a página.
         * **********************************************
         */
        var cancelNew = function () {
            sml.scrollTop();
            document.getElementById('page-action').innerHTML = null;
            document.getElementById('page-base').classList.remove('hide');
        };

        /**
         * **********************************************
         * * Carrega o arquivo para criar novas páginas.
         * **********************************************
         * @param {STR} app
         * Modelo da aplicação a ser criada a página.
         * **********************************************
         */
        var newPage = function (app) {
            sml.scrollTop();
            document.getElementById('page-base').classList.add('hide');
            sml.ajax.send('page-action', 'modules/app/new.php?app=' + app, false);
        };

        return {
            managerPage: managerPage,
            cancelPreview: cancelPreview,
            preview: preview,
            cancelNew: cancelNew,
            newPage: newPage
        };

    }());
}());