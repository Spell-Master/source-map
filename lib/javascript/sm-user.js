/**
 * *****************************************************
 * @Copyright (c) Spell Master.
 * *****************************************************
 * Controle de ações administrativas
 * *****************************************************
 **/

var smUser = smUser || {};
smTools = smTools || {};
smCore = smCore || {};

(function () {
    'use strict';
    smUser = (function () {

        /*
         * Logar
         */
        var login = function (len) {
            var $link = new SocialLink(),
                    $mail = document.getElementById('mail').value.trim(),
                    $pass = document.getElementById('pass').value.trim();
            try {
                if (!$mail) {
                    throw 'Informe o endereço de e-mail';
                } else if ($mail.length < len[2]) {
                    throw 'O endereço de e-mail deve possuir mais de ' + len[2] + ' caracteres';
                } else if ($mail.length > len[3]) {
                    throw 'O endereço de e-mail deve possuir menos de ' + len[3] + ' caracteres';
                } else if (!$link.eMail($mail)) {
                    throw 'O endereço de e-mail não é válido';
                } else if (!$pass) {
                    throw 'Informe a senha de acesso';
                } else if ($pass.length < len[4]) {
                    throw 'A senha de acesso deve possuir mais de ' + len[4] + ' caracteres';
                } else if ($pass.length > len[5]) {
                    throw 'A senha de acesso deve possuir menos de ' + len[5] + ' caracteres';
                } else {
                    smCore.formItens.save('user-login', 'modal-load', 'user/login_v.php');
                }
            } catch (e) {
                smCore.modal.error(e, true);
            }
            return (false);
        };

        /*
         * Deslogar
         */
        var logOut = function () {
            smCore.modal.ajax('modules/actions/user/logout_v.php', 'Saíndo', false);
        };

        /*
         * Redefinir senha
         */
        var pass = function (len) {
            var $link = new SocialLink(),
                    $mail = document.getElementById('mail').value.trim(),
                    $captcha = document.getElementById('captcha').value.trim();
            try {
                if (!$mail) {
                    throw 'Informe o e-mail cadastrado';
                } else if ($mail.length < len[0]) {
                    throw 'O e-mail deve possuir mais de ' + len[0] + ' caracteres';
                } else if ($mail.length > len[1]) {
                    throw 'O e-mail deve possuir menos de ' + len[1] + ' caracteres';
                } else if (!$link.eMail($mail)) {
                    throw 'O e-mail não é válido';
                } else if (!$captcha) {
                    throw 'Informe o código de verificação';
                } else if ($captcha.length !== 6 || !$captcha.match(/^([a-z0-9]+)$/i)) {
                    throw 'O código de verificação não está transcrito corretamente';
                } else {
                    smCore.modal.alertAction('recover-pass', 'user/pass_v.php', 'Redefinir Senha');
                }
            } catch (e) {
                smCore.modal.error(e, true);
            }
            return (false);
        };

        /*
         * Confirmar cadastro
         */
        var confirm = function (len) {
            var $link = new SocialLink(),
                    $mail = document.getElementById('mail').value.trim(),
                    $captcha = document.getElementById('captcha').value.trim();
            try {
                if (!$mail) {
                    throw 'Informe o endereço de e-mail no cadastro';
                } else if ($mail.length < len[0]) {
                    throw 'O endereço de e-mail deve possuir mais de ' + len[0] + ' caracteres';
                } else if ($mail.length > len[1]) {
                    throw 'O endereço de e-mail deve possuir menos de ' + len[1] + ' caracteres';
                } else if (!$link.eMail($mail)) {
                    throw 'O endereço de e-mail não é válido';
                } else if (!$captcha) {
                    throw 'Informe o código de verificação';
                } else if ($captcha.length !== 6 || !$captcha.match(/^([a-z0-9]+)$/i)) {
                    throw 'O código de verificação não está transcrito corretamente';
                } else {
                    smCore.modal.saveform('confirm-new', 'user/confirm_v.php', 'Validar Cadastro', true);
                }
            } catch (e) {
                smCore.modal.error(e, true);
            }
            return (false);
        };

        /*
         * Re-enviar e-mail de confirmação
         */
        var reMail = function (len) {
            var $link = new SocialLink(),
                    $mail = document.getElementById('mail').value.trim(),
                    $captcha = document.getElementById('captcha').value.trim();
            try {
                if (!$mail) {
                    throw 'Informe o endereço de e-mail que solicitou no cadastro';
                } else if ($mail.length < len[0]) {
                    throw 'O endereço de e-mail deve possuir mais de ' + len[0] + ' caracteres';
                } else if ($mail.length > len[1]) {
                    throw 'O endereço de e-mail deve possuir menos de ' + len[1] + ' caracteres';
                } else if (!$link.eMail($mail)) {
                    throw 'O endereço de e-mail não é válido';
                } else if (!$captcha) {
                    throw 'Informe o código de verificação';
                } else if ($captcha.length !== 6 || !$captcha.match(/^([a-z0-9]+)$/i)) {
                    throw 'O código de verificação não está transcrito corretamente';
                } else {
                    smCore.modal.saveform('re-mail', 'user/remail_v.php', 'Enviar E-Mail', true);
                }
            } catch (e) {
                smCore.modal.error(e, true);
            }
            return (false);
        };

        /*
         * Registrar
         */
        function register(len) {
            var $link = new SocialLink(),
                    $name = document.getElementById('name').value.trim(),
                    $mail = document.getElementById('mail').value.trim(),
                    $pass = document.getElementById('pass').value.trim(),
                    $passb = document.getElementById('passb').value.trim(),
                    $terms = document.getElementById('terms-a').checked,
                    $captcha = document.getElementById('captcha').value.trim();
            try {
                if (!$name) {
                    throw 'Informe o seu nome';
                } else if ($name.length < len[0]) {
                    throw 'O seu nome deve possuir mais de ' + len[0] + ' caracteres';
                } else if ($name.length > len[1]) {
                    throw 'O seu nome deve possuir menos de ' + len[1] + ' caracteres';
                } else if (!$name.match(/^([a-zA-Z À-ú 0-9 _ . -]+)$/i)) {
                    throw 'O nome fornecido parece não ser válido <p class="font-small">Você só pode usar letras, números e alguns tipos de caracteres como traços e pontos</p>';
                } else if (!$mail) {
                    throw 'Informe o seu endereço de e-mail';
                } else if ($mail.length < len[2]) {
                    throw 'O seu endereço de e-mail deve possuir mais de ' + len[2] + ' caracteres';
                } else if ($mail.length > len[3]) {
                    throw 'O seu endereço de e-mail deve possuir menos de ' + len[3] + ' caracteres';
                } else if (!$link.eMail($mail)) {
                    throw 'Seu endereço de e-mail não é válido';
                } else if (!$pass) {
                    throw 'Informe sua senha de acesso';
                } else if ($pass.length < len[4]) {
                    throw 'A sua senha de acesso deve possuir mais de ' + len[4] + ' caracteres';
                } else if ($pass.length > len[5]) {
                    throw 'A sua senha de acesso deve possuir menos de ' + len[5] + ' caracteres';
                } else if (!$passb) {
                    throw 'Confirme sua senha';
                } else if ($pass !== $passb) {
                    throw 'A senha de confirmação não é igual a senha de acesso';
                } else if (!$terms) {
                    throw 'Por não concordar com os termos de uso não podemos prosseguir com o cadastro';
                } else if (!$captcha) {
                    throw 'Informe o código de verificação';
                } else if ($captcha.length !== 6 || !$captcha.match(/^([a-z0-9]+)$/i)) {
                    throw 'O código de verificação não está transcrito corretamente';
                } else {
                    smCore.modal.saveform('new-user', 'user/new_v.php', 'Novo Cadastro', true);
                }
            } catch (e) {
                smCore.modal.error(e, true);
            }
            return (false);
        }

        var openEdit = function (file) {
            if (file && (file == 'data' || file == 'info' || file == 'img' || file == 'content')) {
                smTools.scroll.top();
                document.getElementById('profile-display').classList.add('hide');
                smTools.ajax.send('profile-manager', 'modules/user/profile/edit-' + file + '.php');
            }
        };

        var cancelEdit = function () {
            smTools.scroll.top();
            document.getElementById('profile-display').classList.remove('hide');
            document.getElementById('profile-manager').innerHTML = null;
        };

        var editData = function (len) {
            var $link = new SocialLink(),
                    $name = document.getElementById('name').value.trim(),
                    $mail = document.getElementById('mail').value.trim(),
                    $pass = document.getElementById('pass').value.trim(),
                    $passb = document.getElementById('passb').value.trim();
            try {
                if ($name.length >= 1 && $name.length < len[0]) {
                    throw 'O seu nome deve possuir mais de ' + len[0] + ' caracteres';
                } else if ($name.length >= 1 && $name.length > len[1]) {
                    throw 'O seu nome deve possuir menos de ' + len[1] + ' caracteres';
                } else if ($name.length >= 1 && !$name.match(/^([a-zA-Z À-ú 0-9 _ . -]+)$/i)) {
                    throw 'O nome fornecido parece não ser válido <p class="font-small">Você só pode usar letras, números e alguns tipos de caracteres como traços e pontos</p>';
                } else if ($mail.length >= 1 && $mail.length < len[2]) {
                    throw 'O seu endereço de e-mail deve possuir mais de ' + len[2] + ' caracteres';
                } else if ($mail.length >= 1 && $mail.length > len[3]) {
                    throw 'O seu endereço de e-mail deve possuir menos de ' + len[3] + ' caracteres';
                } else if ($mail.length >= 1 && !$link.eMail($mail)) {
                    throw 'Seu endereço de e-mail não é válido';
                } else if ($pass.length >= 1 && $pass.length < len[4]) {
                    throw 'A sua senha de acesso deve possuir mais de ' + len[4] + ' caracteres';
                } else if ($pass.length >= 1 && $pass.length > len[5]) {
                    throw 'A sua senha de acesso deve possuir menos de ' + len[5] + ' caracteres';
                } else if ($pass.length >= 1 && !$passb) {
                    throw 'Confirme sua senha';
                } else if ($pass.length >= 1 && $pass !== $passb) {
                    throw 'A senha de confirmação não é igual a senha de acesso';
                } else {
                    smCore.modal.alertAction('edit-data', 'user/edit-data_v.php', 'Editar Dados');
                }
            } catch (e) {
                smCore.modal.error(e, true);
            }
            return (false);
        };



        return {
            login: login,
            logOut: logOut,
            pass: pass,
            confirm: confirm,
            reMail: reMail,
            register: register,
            openEdit: openEdit,
            cancelEdit: cancelEdit,
            editData: editData
        };

    }());
}());

