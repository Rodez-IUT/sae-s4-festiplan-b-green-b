<?php

namespace controllers;

use PDO;
use yasmf\HttpHelper;
use yasmf\View;
use services\AuthentificationServices;

/**
 * Contrôleur gérant l'authentification et la gestion des utilisateurs.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class AuthentificationController {

    private AuthentificationServices $authentificationService;

    /**
     * Constructeur du contrôleur d'authentification.
     *
     * @param AuthentificationServices $authentificationService Le service d'authentification.
     */
    public function __construct(AuthentificationServices $authentificationService)
    {
        $this->authentificationService = $authentificationService;
    }

    /**
     * Affiche la vue d'authentification.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue d'authentification.
     */
    public function index($pdo): View
    {
        $view = new View("view/authentification");
        return $view;
    }

    /**
     * Gère le processus d'authentification.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue d'authentification.
     */
    public function auth($pdo): View
    {
        $identifiant = HttpHelper::getParam("identifiant");
        $password = HttpHelper::getParam("motDePasse");
        $est_organisateur = false;

        try {
            $result = $this->authentificationService->is_user_valid($pdo, $identifiant, $password);

        } catch (\PDOException $e) {
            // En cas d'erreur PDO, redirige vers une page d'erreur.
            $message_erreur = "Erreur lors de la recuperation des donnees";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        $view = new View("view/authentification");

        if (isset($result) && $result) {
            // L'utilisateur est valide, configure la vue avec les informations de l'utilisateur.
            $user_ok = true;
            $view->setVar("user_ok", $user_ok);
            $view->setVar("user_id", $result["idUser"]);
            $view->setVar("user_nom", $result["nomUser"]);
            $view->setVar("user_prenom", $result["prenomUser"]);
            $est_organisateur = $this->authentificationService->is_organisateur($pdo, $result["idUser"]);
            $est_organisateur = $est_organisateur || $this->authentificationService->is_responsable($pdo, $result["idUser"]);

        } else {
            // L'utilisateur n'est pas valide.
            $user_valid = false;
            $view->setVar("user_valid", $user_valid);
        }
        $view->setVar("organisateur", $est_organisateur);
        return $view;

    }

    /**
     * Gère la déconnexion de l'utilisateur.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue d'authentification.
     */
    public function deconnexion($pdo): View
    {
        $view = new View("view/authentification");
        return $view;
    }

    /**
     * Affiche la confirmation de suppression du compte utilisateur.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de confirmation de suppression.
     */
    public function confirmSuppression(PDO $pdo): View
    {
        $id = HttpHelper::getParam("user_id");

        $id = htmlspecialchars($id);

        $view = new View("view/confirmation");

        $view->setVar("message", "Etes-vous sur de vouloir supprimer votre compte ?");
        $view->setVar("controllerValider", "Authentification");
        $view->setVar("actionValider", "suppression");
        $view->setVar("controllerRetour", "Home");
        $view->setVar("actionRetour", "index");
        $view->setVar("id", $id);

        return $view;
    }

    /**
     * Gère la suppression du compte utilisateur.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue d'authentification.
     */
    public function suppression(PDO $pdo): View
    {

        // On récupère l'id de l'utilisateur dans $_SESSION
        $id = HttpHelper::getParam("id");

        $id = htmlspecialchars($id);

        if (!empty($id)) {
            $reussite_suppression = $this->authentificationService->delete_account($pdo, $id);
        }

        $view = new View("view/authentification");
        return $view;

    }
}
