/**
 * ****************************************************
 * @Copyright (c) 2021, Spell Master.
 * ****************************************************
 * Plugin CkEditor para adicionar anexos
 * ****************************************************
 */
var $attName = null, $path = 'lib/editor/plugins/sm_attach/';

CKEDITOR.plugins.add('sm_attach', {
    requires: 'widget',
    requiredContent: 'div(sm-attach)',
    beforeInit: function (editor) {
        editor.addContentsCss($path + 'css.css');
    },
    init: function (editor) {
        $attName = editor.name;
        editor.widgets.add('smAtt', {
            upcast: function (e) {
                return e.name == 'div' && e.hasClass('sm-attach');
            }
        });
    }
});

/**
 * ****************************************************
 * Abre o modal o carregando por ajax o arquivo que
 *  irá listar os anexos
 * ****************************************************
 */
function editorAtt() {
    smCore.modal.ajax($path + '/att.php', 'Anexos', true);
}

/**
 * ****************************************************
 * Insere um arquivo de imagem no editor
 * ****************************************************
 */
function insertEditorImg(img) {
    var template = '<p>&nbsp;</p>\n\
                    <div class="sm-attach">\n\
                        <img src="uploads/' + img + '" alt="" onerror="this.src=\'lib/image/image-broken.jpg\'" />\n\
                    </div>\n\
                    <p>&nbsp;</p>';
    CKEDITOR.instances[$attName].insertHtml(template);
    smTools.modal.close();
}

/**
 * ****************************************************
 * Insere um arquivo para download no editor
 * ****************************************************
 */
function insertEditorFile(file) {
    var template = '<p>&nbsp;</p>\n\
        <div class="sm-attach">\n\
            <a class="btn-default shadow-on-hover" onclick="smTools.file.download(\'uploads/' + file + '\', true)"><i class="icon-download7"></i> ' + file.split('/').reverse()[0] + '</a>\n\
        </div>\n\
    <p>&nbsp;</p>';
    CKEDITOR.instances[$attName].insertHtml(template);
    smTools.modal.close();
}
