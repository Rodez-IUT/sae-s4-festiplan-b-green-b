<?php

namespace controllers;

use PDO;
use PDOException;
use services\ListeFestivalServices;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur responsable de l'affichage de la liste des festivals pour un utilisateur.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class ListeFestivalController
{
    /** @var ListeFestivalServices $listeFestivalService Service pour la gestion de la liste des festivals. */
    private ListeFestivalServices $listeFestivalService;

    /**
     * Constructeur de la classe.
     *
     * @param ListeFestivalServices $param Service pour la gestion de la liste des festivals.
     */
    public function __construct(ListeFestivalServices $param)
    {
        $this->listeFestivalService = $param;
    }

    /**
     * Affiche la liste des festivals pour un utilisateur.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la liste des festivals pour un utilisateur.
     */
    public function index(PDO $pdo): View
    {
        $idUtilisateur = HttpHelper::getParam('user_id');

        if (!isset($idUtilisateur) || empty($idUtilisateur)) {
            header("Location: ?controller=Authentification");
            exit();
        }

        try {
            // Récupère les festivals pour l'utilisateur.
            $festivals = $this->listeFestivalService->getFestivals($pdo, $idUtilisateur);
            // Récupère la liste des spectacles pour chaque festival.
            $festivals = $this->listeFestivalService->getListeSpectaclesFestivals($pdo, $festivals);
            // Vérifie si l'utilisateur est organisateur ou responsable.
            $est_organisateur = $this->listeFestivalService->is_organisateur($pdo, $idUtilisateur);
            $est_organisateur = $est_organisateur || $this->listeFestivalService->is_responsable($pdo, $idUtilisateur);
        } catch (PDOException) {
            // En cas d'erreur PDO, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur lors de la récupération des données";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }
        $cree = HttpHelper::getParam('cree');

        unset($_GET['cree']);
        // Initialise la vue avec la liste des festivals et d'autres variables nécessaires.
        $view = new View("view/festival/listeFestivalsUtilisateur");
        $view->setVar('cree', $cree);
        $view->setVar("liste_festivals", $festivals);
        $view->setVar("controller", "ListeFestival");
        $view->setVar("titre", "Liste des Festivals");
        $view->setVar("open", "");
        $view->setVar("organisateur", $est_organisateur);
        return $view;
    }

    /**
     * Affiche la liste des festivals avec le menu déroulant ouvert.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la liste des festivals avec le menu déroulant ouvert.
     */
    public function showMenu(PDO $pdo): View
    {
        // Affiche la liste des festivals avec le menu déroulant ouvert.
        $view = $this->index($pdo);
        $view->setVar("open", "open");
        return $view;
    }
}