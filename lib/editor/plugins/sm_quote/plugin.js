/**
 * ****************************************************
 * @Copyright (c) 2018, Spell Master.
 * ****************************************************
 * Plugin CkEditor para uso de bloco de citação
 * ****************************************************
 */
CKEDITOR.plugins.add('sm_quote', {
    init: function (editor) {
        editor.addCommand('insertQuote', {
            allowedContent: 'blockquote(*);p',
            exec: function (editor) {
                if (smEditor.position(editor, 'blockquote')) {
                    return (false);
                } else {
                    editor.insertHtml('<blockquote class="quote"><p></p></blockquote><p>&nbsp;</p>');
                }
            }
        });

        editor.ui.addButton('Quote', {
            label: 'Destacar',
            command: 'insertQuote'
        });
    }
});
