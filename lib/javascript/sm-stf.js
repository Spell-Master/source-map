/**
 * *****************************************************
 * @Copyright (c) Spell Master.
 * *****************************************************
 * Controle de ações administrativas
 * *****************************************************
 **/

var smStf = smStf || {};
smTools = smTools || {};
smCore = smCore || {};
smEditor = smEditor || {};
postData = postData || {};

(function () {
    'use strict';
    smStf = (function () {

        var pageAction = {
            cancel: function () {
                smTools.scroll.top();
                document.getElementById('page-action').innerHTML = null;
                document.getElementById('paginator').classList.remove('hide');
            }, preview: function (formID, folder) {
                smEditor.save('editor-page');
                var $editor = document.getElementById('editor-page').value;
                if (smTools.check.isReady($editor) && $editor.length >= 1) {
                    smTools.ajax.form(formID, 'page-preview', 'modules/actions/admin/' + folder + '/preview_v.php');
                } else {
                    smCore.modal.error('A página não possui conteúdo para gerar visualização', true);
                }
            }, exitPreview: function () {
                document.getElementById('page-preview').innerHTML = null;
                document.getElementById('page-action').classList.remove('hide');
            }
        };

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
                        smCore.modal.saveform('search-app', 'admin/app/search_v.php', 'Pesquisar', true);
                    }
                } catch (e) {
                    smCore.modal.error(e, true);
                }
                return (false);
            }, delPage: function (hashId) {
                smCore.modal.dangerAction('del-' + hashId, 'admin/app/del_v.php', 'Apagar Página');
            }, editPage: function (hash) {
                smTools.scroll.top();
                document.getElementById('paginator').classList.add('hide');
                smTools.ajax.send('page-action', 'modules/admin/app/edit.php?hash=' + hash, false);
            }, newPage: function (app) {
                smTools.scroll.top();
                document.getElementById('paginator').classList.add('hide');
                smTools.ajax.send('page-action', 'modules/admin/app/new.php?app=' + app, false);
            }, save: function (len, type) {
                smEditor.save('editor-page');
                var $title = document.getElementById('title').value.trim(),
                        $editor = postData.parseStr(document.getElementById('editor-page').value);
                try {
                    if (type !== 'new' && type !== 'edit') {
                        smCore.go.reload();
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
                        smCore.modal.saveform(type + '-app', 'admin/app/' + type + '_v.php', (type == 'new' ? 'Nova' : 'Editar') + ' Página', true);
                    }
                } catch (e) {
                    smCore.modal.error(e, true);
                }
                return (false);
            }
        };

        var doc = {
            addSector: function () {
                
            },
            addCateg: function () {
                smTools.scroll.top();
                document.getElementById('paginator').classList.add('hide');
                smTools.ajax.send('page-action', 'modules/admin/doc/categ-new.php', false);
            },
            editCateg: function (hash) {
                smTools.scroll.top();
                document.getElementById('paginator').classList.add('hide');
                smTools.ajax.send('page-action', 'modules/admin/doc/categ-edit.php?hash=' + hash, false);
            },
            editSector: function (hash) {
                
            },
            delCateg: function (hash) {
                smCore.modal.dangerAction('del-' + hash, 'admin/doc/categ-del_v.php', 'Apagar');
            },
            delSector: function () {
                
            },
            saveCateg: function (len, type) {
                var $title = document.getElementById('title').value.trim();
                try {
                    if (type !== 'new' && type !== 'edit') {
                        smCore.go.reload();
                    } else if (!$title) {
                        throw 'Informe o título da categoria';
                    } else if ($title.length < len[0]) {
                        throw 'O título da categoria deve possuir mais de ' + len[0] + ' caracteres';
                    } else if ($title.length > len[1]) {
                        throw 'O título da categoria deve possuir menos de ' + len[1] + ' caracteres';
                    } else if (!$title.match(/^([a-zA-Z À-ú 0-9 _ . -]+)$/gmi)) {
                        throw 'O título parece ser inválido';
                    } else {
                        smCore.modal.saveform(type + '-categ', 'admin/doc/categ-' + type + '_v.php', (type == 'new' ? 'Nova' : 'Editar') + ' Categoria', true);
                    }
                } catch (e) {
                    smCore.modal.error(e, true);
                }
                return (false);
            }
        };

        return {
            pageAction: pageAction,
            app: app,
            doc: doc
        };
    }());
}());
