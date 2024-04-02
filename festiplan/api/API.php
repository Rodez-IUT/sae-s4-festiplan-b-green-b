<?php

namespace api;

use PDOException;
use services\api\FavorisService;
use services\api\FestivalService;
use services\api\LoginService;
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

    public function __construct()
    {
        try {
            $this->dataSource = new DataSource('localhost', '3306', 'festiplan', 'root', '', 'utf8mb4');
        } catch (PDOException $e) {
            API::send_error("Erreur lors de la connexion à la base de données", 500);
        }
    }


    /**
     * Obtenir tous les festivals.
     *
     * @return string Renvoie un tableau de tous les festivals ou une PDOException s'il y a une erreur.
     */
    public function getAllFestival(): string | array
    {
        try {;
            $result = FestivalService::getAllFestival($this->dataSource->getpdo());
            foreach ($result as $key => $value) {
                $result[$key]['imagePath'] = "/sae-s4-festiplan-b-green-b/festiplan/stockage/images/" . $value['nomImage'];
            }
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Obtenir les festivals organisés par un organisateur spécifique.
     *
     * @param int $id L'ID de l'organisateur.
     * @return string Renvoie un tableau de festivals ou une PDOException s'il y a une erreur.
     */
    public function getOrganizerFestival($id): string | array
    {
        try {
            return FestivalService::getOrganizerFestival($this->dataSource->getpdo(), $id);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Obtenir les scènes d'un festival spécifique.
     *
     * @param int $id L'ID du festival.
     * @return array|PDOException Renvoie un tableau de scènes ou une PDOException s'il y a une erreur.
     */
    public function getScenesFestival($id): array | string
    {
        try {
            return FestivalService::getScenesFestival($this->dataSource->getpdo(), $id);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Obtenir les spectacles d'un festival spécifique.
     *
     * @param int $id L'ID du festival.
     * @return array|PDOException Renvoie un tableau de spectacles ou une PDOException s'il y a une erreur.
     */
    public function getShowsFestival($id): array | string
    {
        try {
            $result = FestivalService::getShowsFestival($this->dataSource->getpdo(), $id);
            // on modifi le chemin pour accéder à l'image
            foreach ($result as $key => $value) {
                $result[$key]['imagePath'] = "/sae-s4-festiplan-b-green-b/festiplan/stockage/images/" . $value['nomImage'];
            }
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Obtenir les détails d'un festival spécifique.
     * @return array | string Renvoie un tableau de détails sur le festival ou une string si une erreur s'est produite.
     */
    public function getDetailsFestival($id)
    {
        try {
            $result = FestivalService::getDetailsFestival($this->dataSource->getpdo(), $id);
            // on modifi le chemin pour accéder à l'image
            $result[0]['imagePath'] =  "/sae-s4-festiplan-b-green-b/festiplan/stockage/images/" . $result[0]['nomImage'];
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Connexion d'un utilisateur.
     *
     * @param string $login
     * @param string $mdp
     * @return string|array Renvoie un tableau d'informations sur l'utilisateur ou une string si une erreur s'est produite.
     */
    public function login(string $login, string $mdp): string | bool | array
    {
        try {
            return LoginService::login($this->dataSource->getpdo(), $login, $mdp);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Ajouter un favori à un utilisateur spécifique.
     *
     * @param int $idU L'ID de l'utilisateur.
     * @param int $idF L'ID du festival.
     * @param string $mdp Le mot de passe de l'utilisateur.
     * @return string|bool Renvoie un message de confirmation ou une PDOException s'il y a une erreur.
     */
    public function ajouterFavori($idU, $mdp): string | bool
    {
        try {
            $this->auth($idU);
            return FavorisService::addFavori($this->dataSource->getpdo(), $idU, $mdp);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Supprimer un favori.
     *
     * @param int $idU L'ID de l'utilisateur.
     * @param string $mdp Le mot de passe de l'utilisateur.
     * @return string|bool Renvoie un message de succès ou une PDOException s'il y a une erreur.
     */
    public function supprimerFavori($idU, $mdp): string | bool
    {
        try {
            $this->auth($idU);
            return FavorisService::deleteFavori($this->dataSource->getpdo(), $idU, $mdp);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Obtenir tous les favoris d'un utilisateur spécifique.
     *
     * @param int $idU L'ID de l'utilisateur.
     * @return string|array Renvoie un tableau de favoris ou une PDOException s'il y a une erreur.
     */
    public function getAllFavorites($idU): string | array
    {
        try {
            $this->auth($idU);
            return FavorisService::getFavoris($this->dataSource->getpdo(), $idU);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Envoyer une réponse d'erreur.
     *
     * @param string $message Le message d'erreur.
     * @param int $status Le code de statut HTTP.
     */
    public static  function send_error(string $message, int $status): void
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
    public static function send_json(mixed $data, int $status): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=UTF-8');
        header("Access-Control-Allow-Methods: GET, PUT");

        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /**
     * Teste si la clé API fournie est valide.
     * Si invalide renvoie un message d'erreur et arrete le processus
     * @return void
     */
    public function auth($idUser)
    {
        // Sécurisation avec clé API
        if (isset($_SERVER["HTTP_APIKEY"])) {
            $key = $_SERVER["HTTP_APIKEY"];

            if ($key == (new LoginService())->get_API_key($this->dataSource->getpdo(), $idUser, false)) {
                return;
            } else {
                API::send_error("Clé API invalide", 401);
                die();
            }
        } else {
            API::send_error("Clé API absente", 401);
            die();
        }

    }



}