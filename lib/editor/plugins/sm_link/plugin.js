/**
 * ****************************************************
 * @Copyright (c) 2018, Spell Master.
 * @version 2 (2021)
 * ****************************************************
 * Plugin CkEditor para adicionar links.
 * 
 * @requires :
 * - Classe ModalShow
 * - Objeto global "smTools" para controle do modal
 * ****************************************************
 */

var globaltarget = null;

CKEDITOR.plugins.add('sm_link', {

    init: function (editor) {
        editor.ui.addButton('LinkAdd', {
            label: 'Inserir Link',
            command: 'insertLink'
        });

        editor.addCommand('insertLink', {
            exec: function () {
                globaltarget = null;
                smTools.modal.open('Adicionar Link', true);
                document.getElementById('modal-load').innerHTML = '<div class="padding-all"><p>Link</p><input id="text-link" type="text" class="input-default" /><p>Texto de exibição</p><input id="text-name" type="text" class="input-default" /><div class="margin-top"><button class="btn-default" onclick="editorLink(\'' + editor.name + '\', false);">Inserir Link</button></div></div>';
                linkReplace();
            },
            allowedContent: 'a[!href](!href-link)'
        });

        editor.on('doubleclick', function (e) {
            var dataE = (e.data).element.getParents(true);
            for (var i in dataE) {
                if (dataE[i].hasClass('href-link')) {
                    globaltarget = dataE[i].$;
                    editLink(editor.name);
                    break;
                }
            }
        });
    }
});

/**
 * Insere o link formatado na área do editor
 * @param {STR} eName : Nome da instância do editor
 * @param {BOOL} editL : Inserir em link já existente
 */
function editorLink(eName, editL) {
    var editor = CKEDITOR.instances[eName],
            link = document.getElementById('text-link').value,
            name = document.getElementById('text-name').value,
            nameOut;
    if (link) {
        nameOut = name.length >= 1 ? name : link;
        if (editL) {
            var editar = globaltarget;
            editar.innerText = name;
            editar.href = link;
            editar.setAttribute('data-cke-saved-href', name);
        } else {
            editor.insertHtml(' <a href="' + link + '" class="href-link" target="_blank">' + nameOut + '</a> ');
        }
    }
    smTools.modal.close();
}

/**
 * Altera o input do texto visível com base no link inserido
 */
function linkReplace() {
    var txtLink = document.getElementById('text-link');
    var txtName = document.getElementById('text-name');

    txtLink.addEventListener('keyup', function (e) {
        txtName.value = e.target.value;
    }, false);
}

/**
 * Edita o link existente
 * @param {STR} eName : Nome da instância do editor
 */
function editLink(eName) {
    smTools.modal.open('Editar Link', true);
    document.getElementById('modal-load').innerHTML = '<div class="padding-all"><p>Link</p><input id="text-link" type="text" class="input-default" value="' + globaltarget.href + '" /><p>Texto de exibição</p><input id="text-name" type="text" class="input-default" value="' + globaltarget.innerText + '" /><div class="margin-top"><button class="btn-default" onclick="editorLink(\'' + eName + '\', true);">Inserir Link</button></div></div>';
    linkReplace();
}
