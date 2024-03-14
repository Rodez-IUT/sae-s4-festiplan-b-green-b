<?php

namespace controllers;

use PDO;
use PDOException;
use services\ListeTousSpectaclesServices;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur responsable de l'affichage de la liste de tous les spectacles.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class ListeTousSpectaclesController
{

    /** @var ListeTousSpectaclesServices $tousSpectacleServices Service pour la gestion de la liste de tous les spectacles. */
    private ListeTousSpectaclesServices $tousSpectacleServices;

    /**
     * Constructeur de la classe.
     *
     * @param ListeTousSpectaclesServices $param Service pour la gestion de la liste de tous les spectacles.
     */
    public function __construct(ListeTousSpectaclesServices $param)
    {
        $this->tousSpectacleServices = $param;
    }

    /**
     * Affiche la liste de tous les spectacles.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la liste de tous les spectacles.
     */
    public function index(PDO $pdo): View
    {
        // Récupère l'identifiant de l'utilisateur depuis les paramètres de la requête.
        $idUtilisateur = HttpHelper::getParam("user_id");

        try {
            // Récupère la liste de tous les spectacles.
            $liste_spectacles = $this->tousSpectacleServices->getListeSpectacles($pdo);

        } catch (PDOException) {
            // En cas d'erreur PDO, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur lors de la récupération de la liste des spectacles";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        // Initialise la vue avec la liste des spectacles et d'autres variables nécessaires.
        $view = new View("view/spectacle/listeTousSpectacles");
        $view->setVar("liste_spectacles", $liste_spectacles);
        $view->setVar("idUtilisateur", $idUtilisateur);
        $view->setVar("titre", "Liste des spectacles");
        $view->setVar("controller", "ListeTousSpectacles");
        $view->setVar("open", "");

        return $view;
    }

    /**
     * Affiche la liste de tous les spectacles avec le menu déroulant ouvert.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la liste de tous les spectacles avec le menu déroulant ouvert.
     */
    function showMenu(PDO $pdo): View
    {
        // Affiche la liste de tous les spectacles avec le menu déroulant ouvert.
        $view = $this->index($pdo);
        $view->setVar("open", "open");
        return $view;
    }
}
