CKEDITOR.editorConfig = function (config) {
    config.removePlugins = [
        'a11yhelp',
        'about',
        'blockquote',
        'contextmenu',
        'elementspath',
        'filetools',
        'filebrowser',
        'format',
        'horizontalrule',
        'image',
        'link',
        'liststyle',
        //'magicline',
        'pastetext',
        'pastefromword',
        'removeformat',
        //'richcombo',
        'resize',
        'scayt',
        'specialchar',
        'stylescombo',
        'table',
        'tabletools',
        'tableselection',
        'undo',
        'uploadimage',
        'uploadwidget',
        'wsc'
    ];
    config.extraPlugins = [
        'font', 'colorbutton', 'panelbutton', 'floatpanel', 'justify',
        'autocomplete', 'textmatch', 'textwatcher', 'ajax', 'xml',
        'codesnippet',
        // Souce Map =>
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
    config.disableNativeSpellChecker = false;
    config.baseFloatZIndex = 1;
    config.height = 280;
    config.pasteFilter = "plain-text";
    config.fontSize_sizes = "Minúscula/11px; Pequena/16px; Ampla/24px; Grande/30px;";
    config.removeButtons = "Cut,Copy,Paste,Subscript,Superscript,NumberedList,Outdent,Indent";
    config.allowedContent = true;
};

CKEDITOR.dom.element.prototype.disableContextMenu = function () {
    this.on('contextmenu', function (e) {
        return(false);
    });
};
