/**
 * ****************************************************
 * @Copyright (c) 2021, Spell Master.
 * ****************************************************
 * Plugin CkEditor para uso de blocos de citação
 * ****************************************************
 */
CKEDITOR.plugins.add('sm_blockquote', {
    init: function (editor) {
        var quote;
        editor.ui.addRichCombo('BlockQuote', {
            label: '',
            title: 'Detacar Trecho',
            allowedContent: 'blockquote(*);p',

            panel: {
                css: [editor.config.contentsCss, CKEDITOR.skin.getPath('editor')],
                multiSelect: false,
                attributes: {'aria-label': 'quote'}
            },

            init: function () {
                this.startGroup('Tipo de Bloco');
                this.add('default', 'Padrão');
                this.add('grey', 'Escuro');
                this.add('red', 'Vermelho');
                this.add('blue', 'Azul');
                this.add('green', 'Verde');
                this.add('yellow', 'Amarelo');
            },

            onClick: function (value) {
                if (value == 'default') {
                    quote = '<blockquote class="quote"><p></p></blockquote><p>&nbsp;</p>';
                } else {
                    quote = '<blockquote class="quote-' + value + '"><p></p></blockquote><p>&nbsp;</p>';
                }
                editor.insertHtml(quote);
            }
        });
    }
});
