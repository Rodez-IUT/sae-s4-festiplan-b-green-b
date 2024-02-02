<?php

namespace controllers;

use PDO;
use services\ListeSpectacleServices;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur responsable de l'affichage de la liste des spectacles pour un utilisateur.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class ListeSpectacleController
{

    /** @var ListeSpectacleServices $spectacleServices Service pour la gestion de la liste des spectacles. */
    private ListeSpectacleServices $spectacleServices;

    /**
     * Constructeur de la classe.
     *
     * @param ListeSpectacleServices $param Service pour la gestion de la liste des spectacles.
     */
    public function __construct(ListeSpectacleServices $param)
    {
        $this->spectacleServices = $param;
    }

    /**
     * Affiche la liste des spectacles pour un utilisateur.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la liste des spectacles pour un utilisateur.
     */
    public function index(PDO $pdo): View
    {
        $idUtilisateur = HttpHelper::getParam("user_id");

        // Redirige vers la page d'authentification si l'identifiant d'utilisateur n'est pas défini ou est vide.
        if (!isset($idUtilisateur) || empty($idUtilisateur)) {
            header("Location: ?controller=Authentification");
            exit();
        }

        $liste_spectacles = array();

        try {
            // Récupère la liste des spectacles pour l'utilisateur.
            $liste_spectacles = $this->spectacleServices->getListeSpectacles($pdo, $idUtilisateur);
            // Vérifie si l'utilisateur est organisateur ou responsable.
            $est_organisateur = $this->spectacleServices->is_organisateur($pdo, $idUtilisateur);
            $est_organisateur = $est_organisateur || $this->spectacleServices->is_responsable($pdo, $idUtilisateur);
        } catch (\PDOException $e) {
            // En cas d'erreur PDO, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur lors de la récupération de la liste des spectacles";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        // Initialise la vue avec la liste des spectacles et d'autres variables nécessaires.
        $view = new View("view/listeSpectaclesUtilisateur");
        $view->setVar("organisateur", $est_organisateur);
        $view->setVar("liste_spectacles", $liste_spectacles);
        $view->setVar("titre", "Liste des spectacles");
        $view->setVar("controller", "ListeSpectacle");
        $view->setVar("open", "");

        return $view;
    }

    /**
     * Affiche la liste des spectacles avec le menu déroulant ouvert.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la liste des spectacles avec le menu déroulant ouvert.
     */
    function showMenu($pdo): View
    {
        // Affiche la liste des spectacles avec le menu déroulant ouvert.
        $view = $this->index($pdo);
        $view->setVar("open", "open");
        return $view;
    }
}