<?php

namespace database;

class DB
{
    private static $_instance = null;
    private static $_connection;

    private function __construct()
    {
        static::$_connection = new \mysqli(\Config::DB_HOST, \Config::DB_USER, \Config::DB_PASS, \Config::DB_NAME);
        if (static::$_connection->connect_error) {
            throw new \Exception('Error database connection', 404);
        }

        static::$_connection->query("set character_set_client='utf8mb4'");
        static::$_connection->query("set character_set_results='utf8mb4'");
        static::$_connection->query("set collation_connection='utf8mb4_general_ci'");

    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance(): self
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    public function getConnection(): \mysqli
    {
        return static::$_connection;
    }
}