CKEDITOR.editorConfig = function (config) {
    config.removePlugins = [
        'blockquote',
        'elementspath',
        'contextmenu',
        'filebrowser',
        'format',
        'image'
    ];
    config.extraPlugins = [
        'sm_blockquote',
        'sm_quote',
        'sm_link',
        'sm_spolier',
        'sm_code',
        'sm_source'
    ];
    config.contentsCss = [
        'textarea:focus {border: none}',
        BASE_URI + '/lib/stylesheet/sm-default.css',
        'body {font-size:16px; font-family:\'roboto\', Helvetica, Arial, sans-serif; padding:5px 10px; color:#777777}',
        'ul,ol {padding:0; margin:15px}',
        'body.cke_editable{background-color:#fafafa;overflow:auto}',
        'body:focus.cke_editable{background-color:#ffffff}'
    ];

    config.skin = 'sm';
    config.language = "pt-br";
    config.removeButtons = "Cut,Copy,Paste,Subscript,Superscript,NumberedList,Outdent,Indent";
    config.disableNativeSpellChecker = false;
    config.baseFloatZIndex = 1;
    config.height = 280;
    config.pasteFilter = "plain-text";
    config.fontSize_sizes = "Minúscula/11px; Pequena/16px; Ampla/24px; Grande/30px;";
    config.colorButton_colors ='404040,000000,f44336,c40700,3b9900,2f7a00,3b7fe7,0051b4,ff01ff,b100b1,8e28a3,6b1e7a,324299,263172,00ffff,009999,f4cca4,efb275,ffd400,ccaa00,ff7f00,cc6600,9f705f,5f4339';
    config.fontSize_defaultLabel = '';
    config.allowedContent = true;
};

CKEDITOR.dom.element.prototype.disableContextMenu = function () {
    this.on('contextmenu', function (e) {
        return(false);
    });
};

