<?php

namespace routes;

use actions\Action;

class RouteList {
    private $post = [];
    private $get = [];
    private $delete = [];

    public function __construct() {

    }

    public function addRoute(Route $route) : void
    {
        $method = $route->getMethod();
        $this->$method[$route->getPath()] = $route->getAction();
    }

    public function findRoute($method,$uri) : \actions\Action
    {
        if (isset($this->$method[$uri])) {
            return $this->$method[$uri];
        } else {
            throw new \Exception('Path not found', 404); 
        }
    }
}