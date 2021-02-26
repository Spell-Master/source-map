<?php

/*
 * Classe para gerenciamento de dados de usuários
 */

class SmUser {
    /*
     * Loga o usuário no sistema
     */

    public function setLogin($loginData = []) {
        $serve = GlobalFilter::filterServe();
        @$session = Session::startSession(NAME);
        $session->user = GlobalFilter::StdArray([
                    'hash' => $loginData['hash'],
                    'mail' => $loginData['mail'],
                    'name' => $loginData['name'],
                    'link' => $loginData['link'],
                    'level' => $loginData['level']
        ]);
        if ($loginData['level'] >= 1) {
            $session->admin = $loginData['level'];
        }
        setcookie('clienthash', $loginData['hash'], time() + 3600 * 24 * 365, '/', $serve->HTTP_HOST, false);
    }

    public function logOut() {
        Session::destroy();
        $serve = GlobalFilter::filterServe();
        if (isset($serve->HTTP_COOKIE)) {
            $cookies = explode(';', $serve->HTTP_COOKIE);
            foreach ($cookies as $cookie) {
                $name = trim(explode('=', $cookie)[0]);
                setcookie($name, '', time() - 1000, '/', $serve->HTTP_HOST, false);
            }
        }
    }

}
