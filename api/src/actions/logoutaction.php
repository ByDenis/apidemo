<?php

namespace actions;

class LogoutAction extends Action {
    private $userToken = null;
    private $userName = null;

    public function __construct(?string $name, ?string $token) 
    {
        if ($name !== null) $this->userName = \validator\Validation::string($name, 100);
        if ($token !== null) $this->userToken = \validator\Validation::string($token, 100);
    }

    public function run(\mysqli $db) : void
    {
        if ($this->userName === null OR $this->userToken === null) {
            throw new \Exception('Authorisation Error', 401); 
        }

        $sql="SELECT `id` FROM `api_users` WHERE `name`='{$this->userName}'";
        $res = $db->query($sql);
        if (!list($id) = $res->fetch_row()) {
            throw new \Exception('Authorisation Error', 401);
        }

        $sql="DELETE FROM `api_sessions` WHERE `userid`='{$id}' AND `token`='{$this->userToken}'";
        if ($db->query($sql) === false) {
            throw new \Exception('Authorisation Error', 401);
        }

        if ($db->affected_rows===0) {
            throw new \Exception('Authorisation Error', 401);
        }

        $json = new \stdClass();
        $json->msg = 'Logout successful';

        $lifetime = time()-86400;
        setcookie("token", $token, $lifetime, '/');
        setcookie("user", $this->userName, $lifetime, '/');

        \http\Response::answer(200, $json);
        
    }
}