
var sm_a = sm_a || {};
sml = sml || {};

(function () {
    'use strict';
    sm_a = (function () {

        var changeOption = function (e) {
            switch (e.target.value) {
                case 'edi':
                    var $hash = encodeURIComponent(document.getElementById('page-hash').value);
                    var $app = encodeURIComponent(document.getElementById('page-app').value);
                    document.getElementById('page-base').classList.add('hide');
                    sml.scrollTop();
                    sml.ajax.send('page-action', 'modules/app/edit.php?hash=' + $hash + '&app=' + $app, false);
                    /*
                     MEMORY.appTitle = encodeURIComponent(document.querySelector('[data-apptitle]').innerText);
                     MEMORY.appContent = encodeURIComponent(document.querySelector('[data-appcontent]').innerHTML);
                     MEMORY.appModel = encodeURIComponent(document.querySelector('[data-appmodel]').innerText);
                     document.getElementById('page-base').classList.add('hide');
                     sml.scrollTop();
                     sml.ajax.send('page-action', 'modules/app/edit.php?app=' + MEMORY.appModel, false);
                     * 
                     */
                    break;
                case 'del':
                    smc.modal.dangerAction('del-app', 'app.php', 'Apagar Página');
                    break;
                default:
                    return (false);
                    break;
            }
        };

        var managerPage = function () {
            var $manager = document.getElementById('manager-page');
            if (sml.isReady($manager)) {
                sml.select.init();
                $manager.addEventListener('change', changeOption, false);
            }
        };

        var cancelPreview = function () {
            sml.scrollTop();
            document.getElementById('preview-page').innerHTML = null;
            document.getElementById('page-action').classList.remove('hide');
        };

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

        var cancelNew = function () {
            sml.scrollTop();
            document.getElementById('page-action').innerHTML = null;
            document.getElementById('page-base').classList.remove('hide');
        };

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