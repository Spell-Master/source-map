/**
 * ****************************************************
 * @Copyright (c) 2021, Spell Master.
 * ****************************************************
 * Plugin CkEditor para adicionar vídeo do youtub
 * ****************************************************
 */

var instanceName = null;

CKEDITOR.plugins.add('sm_video', {
    requires: 'widget',
    requiredContent: 'div(smvideo)',
    beforeInit: function (editor) {
        editor.addContentsCss(this.path + 'css.css');
    },
    init: function (editor) {
        instanceName = editor.name;
        editor.widgets.add('smVideo', {
            upcast: function (e) {
                return e.name == 'div' && e.hasClass('smvideo');
            }
        });
    }
});

/**
 * ****************************************************
 * Abre o modal o adiciona elementos de input
 * para escrita da url do video
 * ****************************************************
 */
function editorVideo() {
    smTools.modal.open('You-Tub', true);
    var padding = document.createElement('div'),
            title = document.createElement('p'),
            row = document.createElement('div'),
            right = document.createElement('div'),
            button = document.createElement('button'),
            over = document.createElement('div'),
            input = document.createElement('input');

    padding.classList.add('padding-all');
    title.classList.add('italic');
    title.innerText = 'Url do Vídeo:';
    row.classList.add('row');
    right.classList.add('float-right');
    button.className = 'btn-danger box-y-50 text-white';
    button.innerHTML = '<i class="icon-clapboard-play"></i> Anexar';
    over.classList.add('over-not');
    input.id = 'yt-video';
    input.classList.add('input-default');
    input.setAttribute('placeholder', 'https://youtu.be/...?');
    padding.appendChild(title);
    padding.appendChild(row);
    row.appendChild(right);
    row.appendChild(over);
    right.appendChild(button);
    over.appendChild(input);
    document.getElementById('modal-load').appendChild(padding);
    button.addEventListener('click', checkYube, false);
}

/**
 * ****************************************************
 * Verifica o que foi escrito no input é mesmo um
 * video compatível com a url do youtub
 * ****************************************************
 */
function checkYube(e) {
    var linkValid = /^https\:\/\/(?:(www\.youtube\.com\/watch\?v\=)|(?:youtu\.be\/))[a-z0-9\-]+?$/i,
            inputV = document.getElementById('yt-video').value;
    if (inputV && inputV.match(linkValid)) {
        insertEditorVideo(inputV);
    }
}

/**
 * ****************************************************
 * Insere o iframe de video no editor
 * ****************************************************
 */
function insertEditorVideo(url) {
    var editor = CKEDITOR.instances[instanceName], watch, template;
    if (url.indexOf('watch?v=') != -1) {
        watch = url.substring(url.lastIndexOf('watch?v=') + 8);
    } else {
        watch = url.substring(url.lastIndexOf('/') + 1);
    }
    template = '<p>&nbsp;</p>\n\
                <div class="smvideo">\n\
                    <iframe\n\
                        src="https://www.youtube.com/embed/' + watch + '"\n\
                        frameborder="0"\n\
                        allowfullscreen>\n\
                    </iframe>\n\
                </div>\n\
                <p>&nbsp;</p>';
    editor.insertHtml(template);
    smTools.modal.close();
}
