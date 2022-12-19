<?php

namespace actions;

class LoginAction extends Action {
    private $userName    = null;
    private $userPass    = null;
    private $is_remember = false;

    public function __construct(?string $name, ?string $pass, bool $is_remember=false) 
    {
        if ($name !== null) $this->userName = \validator\Validation::string($name, 100);
        if ($pass !== null)  $this->userPass = \validator\Validation::string($pass, 100);
        if ($is_remember === true) $this->is_remember = true;
    }

    public function run(\mysqli $db) : void
    {
        if ($this->userName === null OR $this->userPass === null) {
            throw new \Exception('Authorisation Error', 401);
        }

        $sql="SELECT `id`,`pass` FROM `api_users` WHERE `name`='{$this->userName}'";
        $res = $db->query($sql);
        if (!list($id,$pass) = $res->fetch_row()) {
            throw new \Exception('Incorrect password or username', 401);
        }

        if ($pass !== sha1($this->userPass)) {
            throw new \Exception('Authorisation Error', 401);
        }

        $token = md5(rand(1000,9999));
        $sql="INSERT INTO `api_sessions` SET `userid`='{$id}', `token`='{$token}'";
        if ($db->query($sql) === false) {
            throw new \Exception('Authorisation Error', 401);
        }
        
        $json = new \stdClass();
        $json->msg = 'Login successful';
        $json->token = $token;

        $lifetime = $this->is_remember === true ? time()+86400*\Config::AUTH_LIFETIME : null;
        
        setcookie("token", $token, $lifetime, '/');
        setcookie("user", $this->userName, $lifetime, '/');

        \http\Response::answer(200, $json);
    }
}