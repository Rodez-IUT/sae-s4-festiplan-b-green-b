<?php

namespace api;

use PDOException;
use services\api\FestivalService;
use yasmf\DataSource;

/**
 * Classe API
 *
 * Cette classe est responsable de la gestion des points de terminaison de l'API liés aux festivals.
 * Elle utilise les services : FestivalService, FavorisService et LoginService.
 */
class API
{

    /**
     * @var DataSource L'objet source de données utilisé pour interagir avec la base de données.
     */
    private DataSource $dataSource;

    /**
     * Constructeur de l'API.
     *
     * Initialise l'objet source de données. S'il y a une erreur lors de la connexion, il envoie une réponse d'erreur.
     */
    public function __construct()
    {
        try {
            $this->dataSource = new DataSource('localhost', '3306', 'festiplan', 'root', '', 'utf8mb4');
        } catch (PDOException $e) {
            API::sendError("Erreur lors de la connexion à la base de données", 500);
        }
    }

    /**
     * Obtenir tous les festivals.
     *
     * @return array|PDOException Renvoie un tableau de tous les festivals ou une PDOException s'il y a une erreur.
     */
    public function getAllFestival(): array|PDOException
    {
        try {
            return FestivalService::getAllFestival($this->dataSource->getpdo());
        } catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * Obtenir les festivals organisés par un organisateur spécifique.
     *
     * @param int $id L'ID de l'organisateur.
     * @return array|PDOException Renvoie un tableau de festivals ou une PDOException s'il y a une erreur.
     */
    public function getOrganizerFestival($id): array|PDOException
    {
        try {
            return FestivalService::getOrganizerFestival($this->dataSource->getpdo(), $id);
        } catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * Obtenir les scènes d'un festival spécifique.
     *
     * @param int $id L'ID du festival.
     * @return array|PDOException Renvoie un tableau de scènes ou une PDOException s'il y a une erreur.
     */
    public function getScenesFestival($id): array|PDOException
    {
        try {
            return FestivalService::getScenesFestival($this->dataSource->getpdo(), $id);
        } catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * Obtenir les spectacles d'un festival spécifique.
     *
     * @param int $id L'ID du festival.
     * @return array|PDOException Renvoie un tableau de spectacles ou une PDOException s'il y a une erreur.
     */
    public function getShowsFestival($id): array|PDOException
    {
        try {
            return FestivalService::getShowsFestival($this->dataSource->getpdo(), $id);
        } catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * Envoyer une réponse d'erreur.
     *
     * @param string $message Le message d'erreur.
     * @param int $status Le code de statut HTTP.
     */
    public static  function sendError($message, $status): void
    {
        $info['status'] = "KO";
        $info['message'] = $message;
        API::send_json($info, $status);
    }

    /**
     * Envoyer une réponse JSON.
     *
     * @param mixed $data Les données à envoyer dans la réponse.
     * @param int $status Le code de statut HTTP.
     */
    public static function send_json($data, $status)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=UTF-8');
        header("Access-Control-Allow-Methods: GET, PUT");

        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

}