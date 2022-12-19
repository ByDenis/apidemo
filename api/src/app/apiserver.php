<?php

namespace app;

use routes\RouteList;
use routes\Route;
use routes\RouteAuthorized;

class ApiServer {
    private $db = null;

    private $method = null;
    private $uri = null;
    private $routes = null;

    public function __construct(RouteList $routList, \mysqli $db) 
    {
        $this->routes = $routList;
        $this->db = $db;

        $this->method = strtolower($_SERVER['REQUEST_METHOD']);

        if (($tmp = strpos($_SERVER['REQUEST_URI'],'?')) !== false) {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'],0,$tmp);
        }
        $uri = strtolower($_SERVER['REQUEST_URI']);
        $uri = urldecode($uri);
        $uri = parse_url($uri, PHP_URL_PATH) ?? '';
        $uri = str_replace(\Config::BASE_URI, '', $uri);
        $uri = trim($uri,'/');
        $this->uri = $uri;
    }

    public function run() : void
    {
        $this->routes->findRoute($this->method, $this->uri)->run($this->db);
    }
}