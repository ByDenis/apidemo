<?php

declare(strict_types=1);
error_reporting(E_ERROR | E_PARSE);

require_once 'src/autoloader.php';

use app\ApiServer;
use database\DB;

use routes\RouteList;
use routes\Route;
use routes\RouteAuthorized;

try {
    $routList = new RouteList();
    $db = DB::getInstance();
   
    $routList->addRoute(
        new Route('post', 'auth', 
            new \actions\LoginAction( 
                \validator\Validation::post('user'), 
                \validator\Validation::post('pass'),
                $_POST['remember']==='true'?true:false 
            ) 
        ) 
    );
    $routList->addRoute(
        new Route('delete', 'auth', 
            new \actions\LogoutAction( 
                \validator\Validation::cookie('user'), 
                \validator\Validation::cookie('token')
            ) 
        )
    );
    $routList->addRoute(
        new RouteAuthorized('get', 'users', 
            new \actions\UsersAction( 
                (int)\validator\Validation::get('offset'), 
                (int)\validator\Validation::get('limit')
            ) 
        ) 
    );
    
    $server = new ApiServer($routList, $db->getConnection());

    $server->run();
} catch (Exception $ex) {
    $json = new \stdClass();
    $json->msg = $ex->getMessage();
    \http\Response::answer($ex->getCode(), $json);
} catch (Throwable $ex) {
    $json = new \stdClass();
    $json->msg = $ex->getMessage();
    \http\Response::answer(404, $json);
} finally {
    \http\Response::answer(404, new \stdClass());
}
