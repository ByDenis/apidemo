<?php

namespace validator;

class Validation {
    public static function string(string $str, int $length=255) : string
    {
        $str = trim(preg_replace('/\s{1,}/',' ',$str));
		$str = strip_tags($str);
		$str = htmlspecialchars($str,ENT_QUOTES);
        $str = mb_substr($str,0,$length);
	    return $str;
    }

    public static function post(string $str, int $length=255) {
        if (!isset($_POST[$str])) return null;
        return self::string($_POST[$str], $length);
    } 
    public static function cookie(string $str, int $length=255) {
        if (!isset($_COOKIE[$str])) return null;
        return self::string($_COOKIE[$str], $length);
    }
    public static function get(string $str, int $length=255) {
        if (!isset($_GET[$str])) return null;
        return self::string($_GET[$str], $length);
    }
}