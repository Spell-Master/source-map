/**
 * ****************************************************
 * @Copyright (c) 2021, Spell Master.
 * ****************************************************
 * Plugin CkEditor para adicionar marcações de código.
 * ****************************************************
 */

const CODE_MEMORY = {};

CKEDITOR.plugins.add('sm_code', {
    requires: 'codesnippet',

    init: function (editor) {
        CODE_MEMORY.editor = editor.name;
        var path = this.path;

        if (editor.addContentsCss) {
            editor.addContentsCss('lib/prism/prism.css');
        }
        var prismCode = new CKEDITOR.plugins.codesnippet.highlighter({
            init: function (ready) {
                CKEDITOR.scriptLoader.load('lib/prism/prism.js', function () {
                    ready();
                });
            },
            languages: {css: 'CSS', markup: 'HTML', javascript: 'JavaScript', markdown: 'Markdown', php: 'PHP', sql: 'SQL'},
            highlighter: function (code, language, callback) {
                var _prism = _self.Prism;
                var highlightedCode = _prism.highlight(code, _prism.languages[language], language);
                callback(highlightedCode);
            }
        });
        editor.plugins.codesnippet.setHighlighter(prismCode);

        editor.ui.addButton('SmCode', {
            label: 'Marcação de código',
            command: 'insertCode'
        });

        editor.addCommand('insertCode', {
            exec: function () {
                CODE_MEMORY.model = null;
                CODE_MEMORY.text = null;
                smCore.modal.ajax(path + 'code.php', 'Inserir Código', true);
            },
            allowedContent: 'pre; code(language-*)'
        });

        editor.on('doubleclick', function (e) {
            var dataE = (e.data).element.getParents(true), i, tgt;
            for (i in dataE) {
                if (dataE[i].hasClass('cke_widget_codeSnippet')) {
                    tgt = (dataE[i].$).childNodes[0].firstChild;
                    CODE_MEMORY.text = encodeURI(tgt.innerText);
                    CODE_MEMORY.model = tgt.classList[0];
                    if (CODE_MEMORY.text.length >= 1 && CODE_MEMORY.model) {
                        smCore.modal.ajax(path + 'code.php', 'Editar Código', true);
                    }
                    break;
                }
            }
        });
    }
});
