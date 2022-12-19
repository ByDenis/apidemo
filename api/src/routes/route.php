<?php

namespace routes;

use actions\Action;

class Route {
    protected $method = null;
    protected $uri = null;
    protected $action = null;

    public function __construct(string $method, string $uri, Action $action) {
        $this->method = strtolower($method);
        $this->uri = strtolower($uri);
        $this->action = $action;
    }

    public function getMethod() : string
    {
        return $this->method;
    }

    public function getPath() : string
    {
        return $this->uri;
    }

    public function getAction() : Action
    {
        return $this->action;
    }
}