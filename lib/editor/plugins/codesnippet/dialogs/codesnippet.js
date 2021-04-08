/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

'use strict';

(function () {
    CKEDITOR.dialog.add('codeSnippet', function (editor) {
        var snippetLangs = editor._.codesnippet.langs,
                lang = editor.lang.codesnippet,
                langSelectItems = [],
                snippetLangId;

        langSelectItems.push([editor.lang.common.notSet, '']);

        for (snippetLangId in snippetLangs) {
            langSelectItems.push([snippetLangs[ snippetLangId ], snippetLangId]);
        }

        return {
            title: lang.title,
            minHeight: 200,
            resizable: CKEDITOR.DIALOG_RESIZE_NONE,
            contents: [{
                    id: 'info',
                    elements: [
                        {
                            id: 'lang',
                            type: 'select',
                            label: 'Linguagem',
                            items: langSelectItems,
                            setup: function (widget) {
                                if (widget.ready && widget.data.lang) {
                                    this.setValue(widget.data.lang);
                                } else {
                                    this.getInputElement().$.selectedIndex = 1;
                                }
                            },
                            commit: function (widget) {
                                widget.setData('lang', this.getValue());
                            },
                            inputStyle: 'min-width:240px'
                        },
                        {
                            id: 'code',
                            type: 'textarea',
                            label: lang.codeContents,
                            setup: function (widget) {
                                this.setValue(widget.data.code);
                            },
                            commit: function (widget) {
                                widget.setData('code', this.getValue());
                            },
                            required: true,
                            validate: CKEDITOR.dialog.validate.notEmpty(lang.emptySnippetError),
                            inputStyle: 'cursor:auto;' +
                                    'max-width:900px;' +
                                    'min-height:250px;' +
                                    'max-height:400px;' +
                                    'tab-size:4;' +
                                    'text-align:left;',
                            'class': 'cke_source'
                        }
                    ]
                }]
        };
    });
}());
