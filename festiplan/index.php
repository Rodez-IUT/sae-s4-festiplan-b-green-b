<?php

const PREFIX_TO_RELATIVE_PATH = "sae-s4-festiplan-b-green-b/festiplan";

require 'autoload.php';

use application\DefaultComponentFactory;
use yasmf\DataSource;
use yasmf\Router;


$dataSource = new DataSource(
    $host = 'localhost',
    $port = '3306',
    $db = 'festiplan',
    $user = 'root',
    $pass = '',
    $charset = 'utf8mb4'
);


$router = new Router(new DefaultComponentFactory(), $dataSource) ;
$router->route(PREFIX_TO_RELATIVE_PATH, $dataSource);


