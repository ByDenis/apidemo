<?php

namespace http;

abstract class StatusMessage {
    const HTTPSTATUS = array(
        200 => 'OK',
        202 => 'Accepted',  
        204 => 'No Content',  
        400 => 'Bad Request',  
        401 => 'Unauthorized',  
        403 => 'Forbidden',  
        404 => 'Not Found',  
        500 => 'Internal Server Error',
    ); 

    public static function get(int $statusCode) : string
    {
        return (self::HTTPSTATUS[$statusCode]) ? self::HTTPSTATUS[$statusCode] : self::HTTPSTATUS[500];
    }
}