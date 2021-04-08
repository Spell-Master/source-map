/**
 * *****************************************************
 * @Copyright (c) Spell Master.
 * *****************************************************
 * Controle dobre o ckeditor
 * *****************************************************
 **/

var editor = editor || {};
smlib = smlib || {};

(function () {
    'use strict';
    editor = (function () {

        /**
         * **********************************************
         * * Inicia o editor.
         * **********************************************
         * @param {STR} target (Opcional)
         *  #id da textarea alvo.
         *  Não informando o alvo será "textarea#editor"
         * @param {STR} options
         *  Opções da barra de utilitários.
         * **********************************************
         */
        var initEditor = function (ckId, options) {
            var $ckInit = (ckId ? ckId : 'editor'), $options = [], $newOptions = [];
            setOptions();
            CKEDITOR.replace($ckInit, {toolbar: [$options.concat($newOptions)]});

            function setOptions() {
                if (smlib.isMobile()) {
                    $options = ['Bold', 'Italic', 'Underline', 'Strike'];
                } else {
                    $options = ['FontSize', 'TextColor', 'Bold', 'Italic', 'Underline', 'Strike'];
                    switch (options) {
                        case 'admin':
                            $newOptions = ['BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'];
                            CKEDITOR.config.allowedContent = {
                                script: true,
                                $a: {elements: CKEDITOR.dtd, attributes: true, styles: true, classes: true}
                            };
                            break;
                        case 'basic':
                            $newOptions = ['BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'];
                            break;
                        default:
                            break;
                    }
                }
            }
        };

        /**
         * **********************************************
         * * Destroi o editor iniciado.
         * **********************************************
         * @param {STR} ckId 
         *  #id da textarea alvo.
         * **********************************************
         */
        var killEditor = function (ckId) {
            var $active = document.getElementById('cke_' + ckId);
            var $instance = CKEDITOR.instances[ckId];
            if (smlib.isReady($active)) {
                CKEDITOR.remove($instance);
                $instance.destroy(true);
                $active.parentNode.removeChild($active);
            }
        };

        /**
         * **********************************************
         * * Salva o conteúdo no editor para texarea.
         * **********************************************
         * @param {STR} ckId 
         *  #id da textarea alvo.
         * **********************************************
         */
        var saveEditor = function (ckId) {
            document.getElementById(ckId).value = CKEDITOR.instances[ckId].getData();
        };

        /**
         * **********************************************
         * * Atera todo conteúdo do editor na textarea.
         * **********************************************
         * @param {STR} ckId 
         *  #id da textarea alvo.
         * **********************************************
         */
        var replaceEditor = function (ckId, content) {
            CKEDITOR.instances[ckId].setData(content);
        };

        /**
         * **********************************************
         * * Insere conteúdo dentro do editor na
         * textarea.
         * **********************************************
         * @param {STR} ckId 
         *  #id da textarea alvo.
         * **********************************************
         */
        var insertEditor = function (ckId, content) {
            CKEDITOR.instances[ckId].insertHtml(content);
        };

        return {
            init: initEditor,
            kill: killEditor,
            save: saveEditor,
            replace: replaceEditor,
            insert: insertEditor
        };

    }());
}());

