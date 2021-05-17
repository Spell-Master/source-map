/**
 * ****************************************************
 * @Copyright (c) 2018, Spell Master.
 * @version 2 (2021)
 * ****************************************************
 * Plugin CkEditor para uso de spoilers
 * ****************************************************
 */

CKEDITOR.plugins.add('sm_spolier', {
    requires: 'widget',

    afterInit: function (editor) {
        editor.addContentsCss(this.path + 'spolier.css');
    },

    init: function (editor) {
        editor.ui.addButton('Spoiler', {
            label: 'Conteúdo Oculto',
            command: 'insertSpoiler'
        });

        editor.addCommand('insertSpoiler', {
            exec: function (editor) {
                var body = smEditor.position(editor, 'div');
                if (body && body.hasClass('spoiler-body')) {
                    return (false);
                } else {
                    editor.insertHtml('<p>&nbsp;</p><div class="spoiler"><div class="spoiler-read">Conteúdo oculto</div><div class="spoiler-body"><p>&nbsp;</p></div></div><p>&nbsp;</p>');
                }
            },
            allowedContent: 'div(spoiler-*);div(spoiler);p'
        });

        editor.widgets.add('spoilerBox', {
            editables: {
                content: {
                    selector: '.spoiler-body'
                }
            },
            requiredContent: 'div(spoiler)',
            upcast: function (element) {
                return element.name == 'div' && element.hasClass('spoiler');
            }
        });
    }

});

