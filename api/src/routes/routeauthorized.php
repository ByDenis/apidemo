<?php

namespace routes;

use actions\Action;
use actions\CheckAuth;
use database\DB;

class RouteAuthorized extends Route
{
    public function getAction() : Action
    {
        return new CheckAuth($this->action);
    }
}