/*
 * Controle dos scripts padrões desenvolvidos por mim
 */

var smlib = smlib || {};

(function () {
    'use strict';
    smlib = (function () {

        /*
         * Verifica se algum dado está pronto para uso
         */
        var isReady = function (find) {
            return ((typeof find !== 'undefined' && find !== null) ? true : false);
        };

        /*
         * Accordion.js
         */
        var accordion = {
            init: function () {
                accordion.prototype = new Accordion();
            }, forceOpen: function (p) {
                accordion.prototype.forceOpen(p);
            }
        };

        /*
         * AjaxRequest.js
         */
        var ajaxRequest = {
            open: function (div, file) {
                ajaxRequest.prototype = new AjaxRequest();
                return (ajaxRequest.prototype.open(div, file));
            }, send: function (div, file, url) {
                ajaxRequest.prototype = new AjaxRequest();
                return (ajaxRequest.prototype.send(div, file, url));
            }, pop: function (div, file, url) {
                ajaxRequest.prototype = new AjaxRequest();
                return (ajaxRequest.prototype.pop(div, file, url));
            }, form: function (form, div, file) {
                ajaxRequest.prototype = new AjaxRequest();
                return (ajaxRequest.prototype.form(form, div, file));
            }, formSend: function (form, div, file) {
                ajaxRequest.prototype = new AjaxRequest();
                return (ajaxRequest.prototype.formSend(form, div, file));
            }
        };

        /*
         * FileTransfer.js
         */
        var fileTransfer = {
            upload: function (form, sendTo, result, cancel) {
                fileTransfer.prototype = new FileTransfer();
                return (fileTransfer.prototype.upload(form, sendTo, result, cancel));
            }, download: function (file, cancel) {
                fileTransfer.prototype = new FileTransfer();
                return (fileTransfer.prototype.download(file, cancel));
            }
        };

        /*
         * ImageCut.js
         */
        var imageCut = {
            init: function (img) {
                imageCut.prototype = new ImageCut(img);
            }, set: function () {
                imageCut.prototype.setCut();
            }, get: function () {
                return (imageCut.prototype.getImage());
            }
        };

        /*
         * ImageGalery.js
         */
        var imageGalery = {
            init: function (targetID) {
                var $targeyID = document.getElementById(targetID);
                if (isReady($targeyID)) {
                    imageGalery.prototype = new ImageGalery(targetID);
                }
            }
        };

        /*
         * ModalShow.js
         */
        var modalShow = {
            open: function (title, x) {
                modalShow.prototype = new ModalShow('default-modal'); // Source Map
                modalShow.prototype.open(title, x);
                if (x) {
                    this.clearModal();
                }
                this.istance = modalShow.prototype;
                return;
            }, close: function () {
                document.getElementById('modal-load').innerHTML = null;
                if (isReady(this.istance)) {
                    this.istance.close();
                }
                this.istance = null;
            }, showX: function () {
                if (isReady(this.istance)) {
                    this.istance.showX();
                    this.clearModal();
                }
                return;
            }, hiddenX: function () {
                if (isReady(this.istance)) {
                    this.istance.hiddenX();
                }
                return;
            }, title: function (text) {
                if (isReady(this.istance)) {
                    this.istance.title(text);
                }
                return;
            }, clearModal: function () { // Source Map
                var $mID = document.getElementById('default-modal');
                var $closeX = $mID.querySelector('.modal-close');
                if (isReady($closeX)) {
                    $closeX.addEventListener('click', this.close, true);
                }
            },
            istance: null
        };

        /*
         * Paginator.js
         */
        var paginator = {
            set: function (target, itens) {
                paginator.prototype = new Paginator(target, itens);
            }, init: function (row) {
                paginator.prototype.init(row);
            }
        };

        /*
         * SelectOption.js
         */
        var selectOption = {
            init: function () {
                selectOption.prototype = new SelectOption();
            }, restart: function () {
                if (selectOption.prototype instanceof SelectOption) {
                    selectOption.prototype.restart();
                } else {
                    selectOption.prototype = new SelectOption();
                }
            }
        };

        /*
         * TabPaginator.js
         */
        var tabPaginator = {
            init: function (divID) {
                tabPaginator.prototype = new TabPaginator((divID ? divID : null));
            }, open: function (tabNum) {
                tabPaginator.prototype.openTab(tabNum);
            }
        };

        return {
            isReady: isReady,
            acc: accordion,
            ajax: ajaxRequest,
            transfer: fileTransfer,
            imgCut: imageCut,
            imgGal: imageGalery,
            modal: modalShow,
            paginator: paginator,
            select: selectOption,
            tab: tabPaginator
        };
    }());
}());