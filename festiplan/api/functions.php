<?php

function send_json($data, $status)
{
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');
    header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");

    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
}