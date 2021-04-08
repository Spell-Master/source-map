/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */
//const BASE_URI = location.pathname.substring(0, location.pathname.lastIndexOf('/'));

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
        //'emoji',
        'autocomplete', 'textmatch', 'textwatcher', 'ajax', 'xml',
        'codemirror',
        'codesnippet'
    ];
    config.contentsCss = [
        'textarea:focus {border: none}',
        BASE_URI + '/lib/stylesheet/sm-default.css',
        //'lib/stylesheet/sm-default.css',
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

    config.modalID = 'default-modal'; /* Source Map (Identificador do modal) */

    /*
     "dialogui, dialog, basicstyles, button, toolbar, clipboard,notification 'indent', 'indentlist', floatpanel, menu,
     
     enterkey,
     entities,
     popup,
     floatingspace,
     listblock,
     htmlwriter,
     wysiwygarea,
     fakeobjects,
     list,
     maximize,
     showborders,
     sourcearea,
     menubutton,
     tab,
     lineutils,
     widgetselection,
     widget,
     notificationaggregator,
     */
};

CKEDITOR.dom.element.prototype.disableContextMenu = function () {
    this.on('contextmenu', function (e) {
        return(false);
    });
};
