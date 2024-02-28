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

    

}