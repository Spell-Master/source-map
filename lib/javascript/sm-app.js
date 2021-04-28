
var sm_a = sm_a || {};
sml = sml || {};

(function () {
    'use strict';
    sm_a = (function () {

        var cancelPreview = function () {
            document.getElementById('new-page').classList.remove('hide');
            document.getElementById('preview-page').innerHTML = null;
            sml.scrollTop();
        };

        var preview = function () {
            sm_e.save('editor-page');
            document.getElementById('new-page').classList.add('hide');
            sml.scrollTop();
            sml.ajax.formSend('new-app', 'preview-page', 'modules/actions/app/preview.php');
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
            cancelPreview: cancelPreview,
            preview: preview,
            cancelNew: cancelNew,
            newPage: newPage
        };

    }());
}());