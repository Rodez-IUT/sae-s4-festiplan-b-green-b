<?php

namespace api;

use yasmf\DataSource;

class API
{

    private $dataSource;

    public function __construct()
    {
        $this->dataSource = new DataSource(
            $host = 'localhost',
            $port = '3306',
            $db = 'festiplan',
            $user = 'root',
            $pass = '',
            $charset = 'utf8mb4'
        );
        // TODO: gerer plusieurs utilisateurs
    }

    public static function send_json($data, $status)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=UTF-8');
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");

        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

}