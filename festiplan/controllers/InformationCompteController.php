<?php

namespace controllers;

use PDO;
use yasmf\HttpHelper;
use yasmf\View;
use services\InformationCompteService;

/**
 * Contrôleur responsable de la gestion des informations du compte utilisateur.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class InformationCompteController 
{

    /** @var InformationCompteService $informationsCompteService Service pour les informations du compte utilisateur. */
    private InformationCompteService $informationsCompteService;

    /**
     * Constructeur de la classe.
     *
     * @param InformationCompteService $service Service pour les informations du compte utilisateur.
     */
    public function __construct(InformationCompteService $service) {
        $this->informationsCompteService = $service;
    }

    /**
     * Affiche les informations du compte utilisateur.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue des informations du compte utilisateur.
     */
    public function index(PDO $pdo): View {

        $data = null;        
        try {
            // Récupère les informations du compte utilisateur.
            $data = $this->informationsCompteService->GetInfoFromAccount($pdo, HttpHelper::getParam("user_id"));

        } catch (\PDOException $e) {
            // En cas d'erreur PDO, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur lors de la récupération de vos informations";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        } catch (\TypeError $e) {
            // En cas d'erreur de type, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur inattendue";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        if (is_null($data)) {
            // Si les données sont nulles, il y a eu une erreur. Redirige vers la page d'erreur.
            $message_erreur = "Erreur lors de la récupération des données";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        // Initialise la vue avec les informations du compte utilisateur.
        $view = new View("view/compte_utilisateur/informationsCompte");
        $view->setVar("identifiant", $data["loginUser"]);
        $view->setVar("nom", $data["nomUser"]);
        $view->setVar("prenom", $data["prenomUser"]);
        $view->setVar("email", $data["emailUser"]);
        $view->setVar("controller", "InformationCompte");
        $view->setVar("titre", "Mes informations");
        $view->setVar("open", "");
        return $view;
    }

    /**
     * Affiche les informations du compte utilisateur avec le menu déroulant ouvert.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue des informations du compte utilisateur avec le menu déroulant ouvert.
     */
    public function showMenu(PDO $pdo) {
        // Affiche les informations du compte utilisateur avec le menu déroulant ouvert.
        $view = $this->index($pdo);
        $view->setVar("open", "open");
        return $view;
    }

}
