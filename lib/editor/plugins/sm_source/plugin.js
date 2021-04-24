/**
 * ****************************************************
 * @Copyright (c) 2021, Spell Master.
 * ****************************************************
 * Plugin CkEditor para editar código fonte.
 * ****************************************************
 */

const SOURCE_CODE = {};

CKEDITOR.plugins.add('sm_source', {
    init: function (editor) {
        var path = this.path;

        editor.ui.addButton('SmSource', {
            label: 'Código Fonte',
            command: 'toSource'
        });

        editor.addCommand('toSource', {
            exec: function () {
                SOURCE_CODE.editor = editor.name;
                SOURCE_CODE.string = editor.getData();
                if (SOURCE_CODE.string.length >= 1) {
                    smc.modal.ajax(path + 'source.php', 'Código Fonte', true);
                }
            }
        });
    }
});