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
            $pass = 'root',
            $charset = 'utf8mb4'
        );
    }

    public function getDataSource(): DataSource
    {
        return $this->dataSource;
    }

    public function send_json($data, $status)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=UTF-8');
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");

        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

}