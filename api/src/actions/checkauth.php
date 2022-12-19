<?php

namespace Actions;

class CheckAuth extends Action {
    private $action = null;

    public function __construct(Action $action) {
        $this->action = $action;
    }

    private function logout() {
        setcookie("token", $token, time()-1, '/');
        setcookie("user", $this->userName, time()-1, '/');

        throw new \Exception('Authorisation Error', 401);
    }

    public function run(\mysqli $db) : void
    {
        $userName  = \validator\Validation::string($_COOKIE['user'], 100);
        $userToken = \validator\Validation::string($_COOKIE['token'], 100);

        $sql="SELECT `id` FROM `api_users` WHERE `name`='{$userName}'";
        $res = $db->query($sql);
        if (!list($id) = $res->fetch_row()) {
            $this->logout();
        }
        $sql="SELECT COUNT(*) FROM `api_sessions` WHERE `userid`='{$id}' AND `token`='{$userToken}'";
        $res = $db->query($sql);
        if (!list($count) = $res->fetch_row()) {
            $this->logout();
        }

        if ($count<1) {
            $this->logout();
        }
        
        if ($this->action !== null) $this->action->run($db);
    }
}