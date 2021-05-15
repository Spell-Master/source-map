/**
 * *****************************************************
 * @Copyright (c) Spell Master.
 * Funções comuns em páginas
 * *****************************************************
 **/

var page = page || {};
sml = sml || {};
smc = smc || {};

(function () {
    'use strict';
    page = (function () {

        /**
         * **********************************************
         * * Ajax pelos links no menu principal direito
         * @param {STR} dir
         * Diretório para o page-load.php
         * **********************************************
         */
        var menu = function (dir) {
            var $menu = document.getElementById('global-menu'), $i, $links, $target, $href;
            if (sml.isReady($menu)) {
                $links = $menu.querySelectorAll('a');
                for ($i = 0; $i < $links.length; $i++) {
                    $links[$i].addEventListener('click', openLink, false);
                }
            }
            function openLink(e) {
                e.preventDefault();
                $target = e.target;
                if (!$target.classList.contains('active')) {
                    sml.scrollTop();
                    linksActive();
                    $href = $target.getAttribute('href');
                    sml.ajax.pop('page-load', 'modules/' + dir + '/page-load.php?url=' + $href, $href);
                }
                return (false);
            }
            function linksActive() {
                for ($i = 0; $i < $links.length; $i++) {
                    if ($links[$i].classList.contains('active')) {
                        $links[$i].classList.remove('active');
                    }
                }
            }
        };

        /**
         * **********************************************
         * * Pesquisar páginas
         * @param {type} folder
         * Pasta onde contém o search.php no diretório
         *  action.
         * @param {type} len
         * Caracteres limites dos campos.
         * **********************************************
         */
        var search = function (folder, len) {
            var $search = document.getElementById('search').value.trim();
            try {
                if (!$search) {
                    throw 'Informe o que pesquisar';
                } else if ($search.length < len[0]) {
                    throw 'A pesquisa deve possuir mais de ' + len[0] + ' caracteres';
                } else if ($search.length > len[1]) {
                    throw 'A pesquisa deve possuir menos de ' + len[1] + ' caracteres';
                } else if (!$search.match(/^([a-zà-ú0-9]+)$/gmi)) {
                    throw 'Você só pode usar letras e números em uma pesquisa';
                } else {
                    smc.modal.saveform('search-page', folder + '/search.php', 'Pesquisar', true);
                }
            } catch (e) {
                smc.modal.error(e, true);
            }
            return (false);
        };

        return {
            menu: menu,
            search: search
        };
    }());
}());
