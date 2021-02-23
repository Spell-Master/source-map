smcore = smcore || {};
/*
 * Salvar formulários
 */
function newUser(len) {
    newUser.prototype = new SocialLink();
    var $name = document.getElementById('name').value.trim();
    var $mail = document.getElementById('mail').value.trim();
    var $pass = document.getElementById('pass').value.trim();
    var $passb = document.getElementById('passb').value.trim();
    var $terms = document.getElementById('terms-a').checked;
    var $captcha = document.getElementById('captcha').value.trim();

    try {
        if (!$name) {
            throw 'Informe o seu nome';
        } else if ($name.length < len[0]) {
            throw 'O seu nome deve possuir mais de ' + len[0] + ' caracteres';
        } else if ($name.length > len[1]) {
            throw 'O seu nome deve possuir menos de ' + len[1] + ' caracteres';
        } else if (!$name.match(/^([a-zA-Z À-ú 0-9 _ . -]+)$/i)) {
            throw 'O nome fornecido parece não ser válido <p class="font-small">Você só pode usar letras, números e alguns tipos de caracteres como traços e pontos</p>';
        }
        else if (!$mail) {
            throw 'Informe o seu endereço de e-mail';
        } else if ($mail.length < len[2]) {
            throw 'O seu endereço de e-mail deve possuir mais de ' + len[2] + ' caracteres';
        } else if ($mail.length > len[3]) {
            throw 'O seu endereço de e-mail deve possuir menos de ' + len[3] + ' caracteres';
        } else if (!newUser.prototype.eMail($mail)) {
            throw 'Seu endereço de e-mail não é válido';
        }
        else if (!$pass) {
            throw 'Informe sua senha de acesso';
        } else if ($pass.length < len[4]) {
            throw 'A sua senha de acesso deve possuir mais de ' + len[4] + ' caracteres';
        } else if ($pass.length > len[5]) {
            throw 'A sua senha de acesso deve possuir menos de ' + len[5] + ' caracteres';
        } else if (!$passb) {
            throw 'Confirme sua senha';
        } else if ($pass !== $passb) {
            throw 'A senha de confirmação não é igual a senha de acesso';
        }
        else if (!$terms) {
            throw 'Por não concordar com os termos de uso não podemos prosseguir com o cadastro';
        }
        else if (!$captcha) {
            throw 'Informe o código de verificação';
        } else if ($captcha.length !== 6 || !$captcha.match(/^([a-z0-9]+)$/i)) {
            throw 'O código de verificação não está transcrito corretamente';
        } else {
            smcore.modal.saveform('new-user', 'user/new.php', 'Novo Cadastro', true);
        }
    } catch (e) {
        smcore.modal.error(e, true);
    }
    return (false);
}