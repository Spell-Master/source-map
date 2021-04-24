/**
 * *****************************************************
 * @Copyright (c) Spell Master.
 * *****************************************************
 * Controle dobre o ckeditor
 * *****************************************************
 **/

var sm_e = sm_e || {};
sml = sml || {};

(function () {
    'use strict';
    sm_e = (function () {

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
            var $ckInit = (ckId ? ckId : 'editor');
            CKEDITOR.replace($ckInit, {toolbar: [setOptions(options)]});
            CKEDITOR.instances[$ckInit].on('contentDom', function () {
                this.document.on('click', function (e) {
                    window.$itemOpen.forceClose();
                });
            });
        };

        /**
         * **********************************************
         * * Define as opções na barra de utilitários.
         * **********************************************
         * @param {STR} options
         *  Opções da barra de utilitários.
         * **********************************************
         */
        var setOptions = function (options) {
            var $options = [], $newOptions = [];
            if (sml.isMobile()) {
                $options = ['Bold', 'Italic', 'Underline', 'Strike'];
            } else {
                $options = ['FontSize', 'TextColor', 'Bold', 'Italic', 'Underline', 'Strike'];
                switch (options) {
                    case 'admin':
                        $newOptions = ['BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'BlockQuote', 'LinkAdd', 'Spoiler', 'SmCode', 'SmSource'];
                        CKEDITOR.config.allowedContent = {
                            script: true,
                            $a: {elements: CKEDITOR.dtd, attributes: true, styles: true, classes: true}
                        };
                        break;
                    case 'advance':
                        $newOptions = ['BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'Quote', 'LinkAdd', 'Spoiler', 'SmCode'];
                        break;
                    case 'basic':
                        $newOptions = ['BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'];
                        break;
                    case 'all':
                        $newOptions = ['BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'Quote', 'BlockQuote', 'LinkAdd',
                            'Spoiler',
                            'SmCode', 
                            'SmSource'];
                        break;
                    default:
                        break;
                }
            }
            return ($options.concat($newOptions));
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
            if (sml.isReady($active)) {
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

        /**
         * **********************************************
         * * Verifica se o cursor está dentro de uma
         * tag expecífica no editor.
         * **********************************************
         * @param {OBJ} edt 
         * Objeto da instância do editor.
         * @param {STR} tgt 
         * Tag de busca.
         * @return {BOOL/OBJ}
         * * false: o cursor não está no elemento
         * * true: "dom objet" alvo
         * **********************************************
         */
        var targetPosition = function (edt, tgt) {
            var $selection = edt.getSelection().getRanges()[0];
            var $tag = $selection.startContainer.getAscendant(tgt);
            return ($tag);
        };

        return {
            init: initEditor,
            kill: killEditor,
            save: saveEditor,
            replace: replaceEditor,
            insert: insertEditor,
            position: targetPosition
        };

    }());
}());

