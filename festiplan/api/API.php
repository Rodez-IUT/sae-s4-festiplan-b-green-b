<?php

namespace api;

use PDOException;
use services\api\FestivalService;
use yasmf\DataSource;

class API
{

    private DataSource $dataSource;

    public function __construct()
    {
        try {
            $this->dataSource = new DataSource('localhost', '3306', 'festiplan', 'root', '', 'utf8mb4');
        } catch (PDOException $e) {
            API::send_json($e, 500);
        }
    }

    public function getAllFestival(): array|PDOException
    {
        try {
            return FestivalService::getAllFestival($this->dataSource->getpdo());
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function getOrganizerFestival($id): array|PDOException
    {
        try {
            return FestivalService::getOrganizerFestival($this->dataSource->getpdo(), $id);
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function getScenesFestival($id): array|PDOException
    {
        try {
            return FestivalService::getScenesFestival($this->dataSource->getpdo(), $id);
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function getShowsFestival($id): array|PDOException
    {
        try {
            return FestivalService::getShowsFestival($this->dataSource->getpdo(), $id);
        } catch (PDOException $e) {
            return $e;
        }
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