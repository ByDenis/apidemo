<?php

namespace http;

class Response{
    public static function answer(int $code, \stdClass $json) {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET,POST,DELETE");
        header("Access-Control-Max-Age: 3600");
        
        $statusNsg = StatusMessage::get($code);
        header("HTTP/1.1 {$code} {$statusNsg}");

        echo json_encode($json);

        exit();
    }
}