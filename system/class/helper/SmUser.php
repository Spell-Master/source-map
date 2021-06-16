<?php

/*
 * Classe para gerenciamento de dados de usuários
 */

class SmUser {

    private $serve;
    private $userAcess;

    /**
     * obten dados do servidor
     */
    private function getServe() {
        $this->serve = GlobalFilter::filterServe();
    }

    /**
     * obtem dados da máquina que está acessando
     */
    private function getAgent() {
        $agent = new UserAgent();
        $this->userAcess = str_replace(' ', null, $agent->getUserMachine() . $agent->requestIP());
    }

    /**
     * Loga o usuário no sistema
     */
    public function setLogin($loginData = []) {
        $this->getServe();
        $this->delLoginErr();
        @$session = Session::startSession(NAME);
        $session->user = GlobalFilter::StdArray([
                    'hash' => $loginData['hash'],
                    'mail' => $loginData['mail'],
                    'name' => $loginData['name'],
                    'link' => $loginData['link'],
                    'level' => $loginData['level'],
                    'photo' => $loginData['photo']
        ]);
        if ($loginData['level'] >= 1) {
            $session->admin = $loginData['level'];
        }
        setcookie('clienthash', $loginData['hash'], time() + 3600 * 24 * 365, '/', $this->serve->HTTP_HOST, false);
    }

    /**
     * Desloga
     */
    public function logOut() {
        Session::destroy();
        $this->getServe();
        if (isset($this->serve->HTTP_COOKIE)) {
            $cookies = explode(';', $this->serve->HTTP_COOKIE);
            foreach ($cookies as $cookie) {
                $name = trim(explode('=', $cookie)[0]);
                setcookie($name, null, time() - 1000, '/', $this->serve->HTTP_HOST, false);
            }
        }
    }

    /**
     * En caso de errar senha por
     *  $config->length->loginError bloquear o login por 1 dia
     */
    public function loginError($quant) {
        $this->getAgent();
        $this->getServe();
        $select = new Select();
        $update = new Update();
        $insert = new Insert();

        $select->query("users_error", "ue_bound=:e", "e={$this->userAcess}");
        $count = (int) $select->result()[0]->ue_count;
        if (!$count) {
            $insert->query("users_error", ['ue_count' => 1, 'ue_bound' => $this->userAcess]);
        } else if ($count >= (int) ($quant - 1)) {
            setcookie('loginerror', true, time() + 86400, '/', $this->serve->HTTP_HOST, false);
            $update->query("users_error", ['ue_time' => date('Y-m-d', strtotime('+1 day'))], "ue_bound=:b", "b={$this->userAcess}");
        } else {
            $update->query("users_error", ['ue_count' => $count += 1], "ue_bound=:b", "b={$this->userAcess}");
        }
    }

    /**
     * Em caso de bloqueio da máquina.
     * Verifica se o tempo de espera até desbloquear a máquina
     */
    public function loginCheck() {
        $this->getAgent();
        $select = new Select();
        $select->query("user_error", "ue_bound=:e", "e={$this->userAcess}");
        $block = strtotime(($select->count() ? $select->result()[0]->ue_time : '0'));
        if ($block > strtotime(date('Y-m-d'))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Apaga o bloqueiro de login da máquina
     */
    public function delLoginErr() {
        $this->getAgent();
        $delete = new Delete();
        $delete->query("user_error", "ue_bound=:e", "e={$this->userAcess}");
        setcookie('loginerror', null, time() - 1000, '/', $this->serve->HTTP_HOST, false);
    }

    /**
     * Registrar atividade
     * @param {STR} $user
     * Hash identificador do usuário
     * @param {STR} $bound
     * Hash identificador do vínculo da atividade
     * @param {STR} $title
     * Título da atividade
     * @param {STR} $link
     * Link para a postagem
     * @param {STR} $info
     * Conteúdo simplificado da postagem
     */
    public function setActivity($user, $bound, $title, $link, $info) {
        $query = new Insert();
        $query->query("users_activity", [
            'ua_user' => $user,
            'ua_bound' => $bound,
            'ua_title' => $title,
            'ua_link' => $link,
            'ua_info' => $info,
            'ua_date' => date('Y-m-d H:i:s')
        ]);
        if ($query->count()) {
            $this->oldActivity($user); // Apagar antigas atividades
        }
    }

    /**
     * Apagar atividades antigas com mais de 1 mês
     * @param {STR} $user
     * Hash identificador do usuário
     */
    public function oldActivity($user) {
        $month = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
        $sel = new Select();
        $del = new Delete();
        $sel->setQuery("SELECT ua_id, ua_user, ua_date FROM users_activity WHERE ua_user = '{$user}' AND ua_date < '{$month}'");
        if ($sel->count()) {
            foreach ($sel->result() as $key => $value) {
                if ($key < 10) {
                    $del->query("users_activity", "ua_id = :hash ", "hash={$value->ua_id}");
                }
            }
        }
    }

}
