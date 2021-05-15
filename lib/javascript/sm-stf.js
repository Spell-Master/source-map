/**
 * *****************************************************
 * @Copyright (c) Spell Master.
 * *****************************************************
 * Controle de ações administrativas
 * *****************************************************
 **/

var sm_stf = sm_stf || {};
sml = sml || {};
smc = smc || {};
sm_e = sm_e || {};

(function () {
    'use strict';
    sm_stf = (function () {

        var app = {
            searchPage: function (len) {
                var $search = document.getElementById('search').value.trim();
                try {
                    if (!$search) {
                        throw 'Informe o que pesquisar';
                    } else if ($search.length < len[0]) {
                        throw 'A pesquisa deve possuir mais de ' + len[0] + ' caracteres';
                    } else if ($search.length > len[1]) {
                        throw 'A pesquisa deve possuir menos de ' + len[1] + ' caracteres';
                    } else if (!$search.match(/^([a-zà-ú0-9]+)$/gmi)) {
                        throw 'Você só pode usar letras e números em uma pesquisa';
                    } else {
                        smc.modal.saveform('search-app', 'admin.php', 'Pesquisar', true);
                    }
                } catch (e) {
                    smc.modal.error(e, true);
                }
                return (false);
            },
            delPage: function (hashId) {
                smc.modal.dangerAction('del-' + hashId, 'admin/app-del.php', 'Apagar Página');
            },
            editPage: function (hash) {
                sml.scrollTop();
                document.getElementById('paginator').classList.add('hide');
                sml.ajax.send('page-action', 'modules/admin/app/edit.php?hash=' + hash, false);
            },
            cancelPreview: function () {
                document.getElementById('page-preview').innerHTML = null;
                document.getElementById('page-action').classList.remove('hide');
            },
            previewPage: function (type) {
                sm_e.save('editor-page');
                var $editor = document.getElementById('editor-page').value;
                if (sml.isReady($editor) && $editor.length >= 1) {
                    sml.scrollTop();
                    document.getElementById('page-action').classList.add('hide');
                    sml.ajax.formSend(type + '-app', 'page-preview', 'modules/actions/admin/app-preview.php');
                } else {
                    smc.modal.error('A página não possui conteúdo para gerar visualização', true);
                }
            },
            cancelAction: function () {
                sml.scrollTop();
                document.getElementById('page-action').innerHTML = null;
                document.getElementById('paginator').classList.remove('hide');
            },
            newPage: function (app) {
                sml.scrollTop();
                document.getElementById('paginator').classList.add('hide');
                sml.ajax.send('page-action', 'modules/admin/app/new.php?app=' + app, false);
            }
        };

        return {
            app: app
        };
    }());
}());
