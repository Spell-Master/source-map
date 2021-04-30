
var sm_a = sm_a || {};
sml = sml || {};

(function () {
    'use strict';
    sm_a = (function () {

        var changeOption = function (e) {
            switch (e.target.value) {
                case 'edi':
                    MEMORY.appTitle = encodeURIComponent(document.querySelector('[data-apptitle]').innerText);
                    MEMORY.appContent = encodeURIComponent(document.querySelector('[data-appcontent]').innerHTML);
                    MEMORY.appModel = encodeURIComponent(document.querySelector('[data-appmodel]').innerText);
                    document.getElementById('page-base').classList.add('hide');
                    sml.scrollTop();
                    sml.ajax.send('page-action', 'modules/app/edit.php?app=' + MEMORY.appModel, false);
                    break;
                case 'del':
                    console.log('apagar')
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
            document.getElementById('new-page').classList.remove('hide');
            document.getElementById('preview-page').innerHTML = null;
            sml.scrollTop();
        };

        var preview = function (type) {
            sm_e.save('editor-page');
            document.getElementById('new-page').classList.add('hide');
            sml.scrollTop();
            sml.ajax.formSend(type + '-app', 'preview-page', 'modules/actions/app/preview.php');
        };

        var cancelNew = function () {
            document.getElementById('page-base').classList.remove('hide');
            document.getElementById('page-action').innerHTML = null;
            sml.scrollTop();
        };

        var newPage = function (app) {
            document.getElementById('page-base').classList.add('hide');
            sml.scrollTop();
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