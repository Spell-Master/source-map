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
postdata = postdata || {};

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
            },
            save: function (len, type) {
                sm_e.save('editor-page');
                var $title = document.getElementById('title').value.trim(),
                        $editor = postdata.parseStr(document.getElementById('editor-page').value);
                try {
                    if (type !== 'new' && type !== 'edit') {
                        smc.go.reload();
                    } else if (!$title) {
                        throw 'Informe o título da página';
                    } else if ($title.length < len[0]) {
                        throw 'O título da página deve possuir mais de ' + len[0] + ' caracteres';
                    } else if ($title.length > len[1]) {
                        throw 'O título da página deve possuir menos de ' + len[1] + ' caracteres';
                    } else if (!$title.match(/^([a-zA-Z À-ú 0-9 _ . -]+)$/gmi)) {
                        throw 'O título parece ser inválido';
                    } else if (!$editor) {
                        throw 'Transcreva o conteúdo da página';
                    } else if ($editor.length < len[2]) {
                        throw 'O conteúdo é muito curto para ser uma página válida';
                    } else if ($editor.length > len[3]) {
                        throw 'O conteúdo é muito extenso para ser uma página válida';
                    } else {
                        smc.modal.saveform(type + '-app', 'admin.php', (type == 'new' ? 'Nova' : 'Editar') + ' Página', true);
                    }
                } catch (e) {
                    smc.modal.error(e, true);
                }
                return (false);
            }
        };

        return {
            app: app
        };
    }());
}());
