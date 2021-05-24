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
                var $paginator = document.getElementById('paginator');
                if ($paginator.classList.contains('hide')) {
                    document.getElementById('page-action').innerHTML = null;
                    document.getElementById('page-preview').innerHTML = null;
                    document.getElementById('page-tools').classList.remove('hide');
                    $paginator.classList.remove('hide');
                    smTools.scroll.top();
                }
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
            openNew: function (file) {
                if (file && (file == 'page' || file == 'sector' || file == 'categ')) {
                    smTools.scroll.top();
                    document.getElementById('paginator').classList.add('hide');
                    smTools.ajax.send('page-action', 'modules/admin/doc/' + file + '-new.php', false);
                }
            },
            openEdit: function (file, hash) {
                if ((file && hash) && (file == 'page' || file == 'sector' || file == 'categ')) {
                    smTools.scroll.top();
                    document.getElementById('paginator').classList.add('hide');
                    smTools.ajax.send('page-action', 'modules/admin/doc/' + file + '-edit.php?hash=' + hash, false);
                }
            },
            openDel: function (file, hash) {
                if ((file && hash) && (file == 'page' || file == 'sector' || file == 'categ')) {
                    smCore.modal.dangerAction('del-' + hash, 'admin/doc/' + file + '-del_v.php', 'Apagar');
                }
            },
            filterView: function (file, filter) {
                if ((file && filter) && (file == 'page' || file == 'sector')) {
                    pageAction.cancel();
                    smTools.ajax.send('paginator', 'modules/admin/doc/' + file + '-paginator.php?filter=' + filter);
                }
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
            },
            saveSector: function (len, type) {
                smEditor.save('editor-sector');
                var $title = document.getElementById('title').value.trim(),
                        $category = document.getElementById('category').value,
                        $editor = postData.parseStr(document.getElementById('editor-sector').value);
                try {
                    if (type !== 'new' && type !== 'edit') {
                        smCore.go.reload();
                    } else if (!$title) {
                        throw 'Informe o título do setor';
                    } else if ($title.length < len[0]) {
                        throw 'O título do setor deve possuir mais de ' + len[0] + ' caracteres';
                    } else if ($title.length > len[1]) {
                        throw 'O título do setor deve possuir menos de ' + len[1] + ' caracteres';
                    } else if (!$title.match(/^([a-zA-Z À-ú 0-9 _ . -]+)$/gmi)) {
                        throw 'O título parece ser inválido';
                    } else if (!$category) {
                        throw 'Selecione a categoria para o setor';
                    } else if (!$editor) {
                        throw 'Transcreva a descrição do setor';
                    } else if ($editor.length < len[2]) {
                        throw 'A descrição é muito curta para ser válida';
                    } else if ($editor.length > len[3]) {
                        throw 'A descrição é muito extensa para ser válida';
                    } else {
                        smCore.modal.saveform(type + '-sector', 'admin/doc/sector-' + type + '_v.php', (type == 'new' ? 'Novo' : 'Editar') + ' Setor', true);
                    }
                } catch (e) {
                    smCore.modal.error(e, true);
                }
                return (false);
            },
            savePage: function (len, type) {
                smEditor.save('editor-page');
                var $title = document.getElementById('title').value.trim(),
                        $sector = document.getElementById('sector').value,
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
                    } else if (!$sector) {
                        throw 'Selecione o setor para a página';
                    } else if (!$editor) {
                        throw 'Transcreva o conteúdo da página';
                    } else if ($editor.length < len[2]) {
                        throw 'O conteúdo é muito curto para ser válido';
                    } else if ($editor.length > len[3]) {
                        throw 'O conteúdo é muito extenso para ser válido';
                    } else {
                        smCore.modal.saveform(type + '-page', 'admin/doc/page-' + type + '_v.php', (type == 'new' ? 'Novo' : 'Editar') + ' Setor', true);
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
