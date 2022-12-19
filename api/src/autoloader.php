<?php
if (PHP_VERSION_ID < 70400) {
    echo 'Support on PHP <7.4+ and you are running '.PHP_VERSION.', please upgrade PHP';
    exit(1);
}

spl_autoload_register(function($className) 
{
    $path = str_replace('\\','/',$className).'.php';
    $path = strtolower($path);
    require_once __DIR__ . '/' . $path;
});