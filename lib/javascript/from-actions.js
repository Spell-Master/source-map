smc = smc || {};
postdata = postdata || {};

/*
 * **************************************************
 * Salvar formulários
 * **************************************************
 */


/*
 * Salva nova e edição de página em aplicações padrão
 */
function saveApp(len, type) {
    sm_e.save('editor-page');
    var $title = document.getElementById('title').value.trim(),
            $editor = postdata.parseStr(document.getElementById('editor-page').value);
    try {
        if (type !== 'new' && type !== 'edit') {
            smc.go.reload();
        } else if (!$title) {
            throw 'Informe o título da página';
        } else if ($title.length < len[0]) {
            throw 'O título da página deve possuir mais de ' + len[0] + ' caracteres';
        } else if ($title.length > len[1]) {
            throw 'O título da página deve possuir menos de ' + len[1] + ' caracteres';
        } else if (!$title.match(/^([a-zA-Z À-ú 0-9 _ . -]+)$/gmi)) {
            throw 'O título parece ser inválido';
        } else if (!$editor) {
            throw 'Transcreva o conteúdo da página';
        } else if ($editor.length < len[2]) {
            throw 'O conteúdo é muito curto para ser uma página válida';
        } else if ($editor.length > len[3]) {
            throw 'O conteúdo é muito extenso para ser uma página válida';
        } else {
            smc.modal.saveform(type + '-app', 'admin.php', (type == 'new' ? 'Nova' : 'Editar') + ' Página', true);
        }
    } catch (e) {
        smc.modal.error(e, true);
    }
    return (false);
}

/*
 * Deslogar
 * @param {ARR} len
 */

function logOut() {
    smc.modal.ajax('modules/actions/user/logout.php', 'Saíndo', false);
}


/*
 * Logar
 * @param {ARR} len
 */
function userLogin(len) {
    userLogin.prototype = new SocialLink();
    var $mail = document.getElementById('mail').value.trim(),
            $pass = document.getElementById('pass').value.trim();
    try {
        if (!$mail) {
            throw 'Informe o endereço de e-mail';
        } else if ($mail.length < len[2]) {
            throw 'O endereço de e-mail deve possuir mais de ' + len[2] + ' caracteres';
        } else if ($mail.length > len[3]) {
            throw 'O endereço de e-mail deve possuir menos de ' + len[3] + ' caracteres';
        } else if (!userLogin.prototype.eMail($mail)) {
            throw 'O endereço de e-mail não é válido';
        } else if (!$pass) {
            throw 'Informe a senha de acesso';
        } else if ($pass.length < len[4]) {
            throw 'A senha de acesso deve possuir mais de ' + len[4] + ' caracteres';
        } else if ($pass.length > len[5]) {
            throw 'A senha de acesso deve possuir menos de ' + len[5] + ' caracteres';
        } else {
            smc.formItens.save('user-login', 'modal-load', 'user.php');
        }
    } catch (e) {
        smc.modal.error(e, true);
    }
    return (false);
}

/*
 * Recuperar senha
 * @param {ARR} len
 */
function recoverPass(len) {
    recoverPass.prototype = new SocialLink();
    var $mail = document.getElementById('mail').value.trim(),
            $captcha = document.getElementById('captcha').value.trim();
    try {
        if (!$mail) {
            throw 'Informe o e-mail cadastrado';
        } else if ($mail.length < len[0]) {
            throw 'O e-mail deve possuir mais de ' + len[0] + ' caracteres';
        } else if ($mail.length > len[1]) {
            throw 'O e-mail deve possuir menos de ' + len[1] + ' caracteres';
        } else if (!recoverPass.prototype.eMail($mail)) {
            throw 'O e-mail não é válido';
        } else if (!$captcha) {
            throw 'Informe o código de verificação';
        } else if ($captcha.length !== 6 || !$captcha.match(/^([a-z0-9]+)$/i)) {
            throw 'O código de verificação não está transcrito corretamente';
        } else {
            smc.modal.alertAction('recover-pass', 'user.php', 'Redefinir Senha');
        }
    } catch (e) {
        smc.modal.error(e, true);
    }
    return (false);
}

/*
 * Envia confirmação de validação de cadastro por e-mail
 * @param {ARR} len
 */
function confirmNew(len) {
    reMail.prototype = new SocialLink();
    var $mail = document.getElementById('mail').value.trim(),
            $captcha = document.getElementById('captcha').value.trim();
    try {
        if (!$mail) {
            throw 'Informe o endereço de e-mail no cadastro';
        } else if ($mail.length < len[0]) {
            throw 'O endereço de e-mail deve possuir mais de ' + len[0] + ' caracteres';
        } else if ($mail.length > len[1]) {
            throw 'O endereço de e-mail deve possuir menos de ' + len[1] + ' caracteres';
        } else if (!reMail.prototype.eMail($mail)) {
            throw 'O endereço de e-mail não é válido';
        } else if (!$captcha) {
            throw 'Informe o código de verificação';
        } else if ($captcha.length !== 6 || !$captcha.match(/^([a-z0-9]+)$/i)) {
            throw 'O código de verificação não está transcrito corretamente';
        } else {
            smc.modal.saveform('confirm-new', 'user.php', 'Validar Cadastro', true);
        }
    } catch (e) {
        smc.modal.error(e, true);
    }
    return (false);
}

/*
 * Envia novamente o e-mail de confirmação de cadastro
 * @param {ARR} len
 */
function reMail(len) {
    reMail.prototype = new SocialLink();
    var $mail = document.getElementById('mail').value.trim(),
            $captcha = document.getElementById('captcha').value.trim();
    try {
        if (!$mail) {
            throw 'Informe o endereço de e-mail que solicitou no cadastro';
        } else if ($mail.length < len[0]) {
            throw 'O endereço de e-mail deve possuir mais de ' + len[0] + ' caracteres';
        } else if ($mail.length > len[1]) {
            throw 'O endereço de e-mail deve possuir menos de ' + len[1] + ' caracteres';
        } else if (!reMail.prototype.eMail($mail)) {
            throw 'O endereço de e-mail não é válido';
        } else if (!$captcha) {
            throw 'Informe o código de verificação';
        } else if ($captcha.length !== 6 || !$captcha.match(/^([a-z0-9]+)$/i)) {
            throw 'O código de verificação não está transcrito corretamente';
        } else {
            smc.modal.saveform('re-mail', 'user.php', 'Enviar E-Mail', true);
        }
    } catch (e) {
        smc.modal.error(e, true);
    }
    return (false);
}


/**
 * Salva novo usuário
 * @param {ARR} len
 */
function newUser(len) {
    newUser.prototype = new SocialLink();
    var $name = document.getElementById('name').value.trim(),
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
        } else if (!newUser.prototype.eMail($mail)) {
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
            smc.modal.saveform('new-user', 'user.php', 'Novo Cadastro', true);
        }
    } catch (e) {
        smc.modal.error(e, true);
    }
    return (false);
}
