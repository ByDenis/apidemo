<?php 

abstract class Config {
    const DB_HOST = 'localhost';
    const DB_PORT = 3306;
    const DB_NAME = 'demo';
    const DB_USER = 'root';
    const DB_PASS = '';

    const APP_NAME      = 'Demo';
    const BASE_URI      = '/api/';
    const AUTH_LIFETIME = 7; //Days
}