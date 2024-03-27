<?php

namespace controllers;

use PDOException;
use services\CreationGriJServices;

use yasmf\View;
use PDO;

/**
 * Contrôleur responsable de la gestion des contraintes de grille journalière.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class GrijController {

    /** @var CreationGriJServices $creationGriJService Service de création des contraintes de grille journalière. */
    private CreationGriJServices $creationGriJService;

    /**
     * Constructeur de la classe.
     *
     * @param CreationGriJServices $creationGriJService Service de création des contraintes de grille journalière.
     */
    public function __construct(CreationGriJServices $creationGriJService)
    {
        $this->creationGriJService = $creationGriJService;
    }

    /**
     * Affiche la vue principale de gestion des contraintes de grille journalière.
     *
     * @return View Vue principale de gestion des contraintes de grille journalière.
     */
    public function index(): View
    {
        try {
            // Récupère la liste des valeurs et des classes nécessaires à la vue.
            $liste_valeurs = $this->creationGriJService->getListeValeurs($_POST);
            $liste_classes = $this->creationGriJService->getListeClasses($_POST);

        } catch(PDOException) {
            // En cas d'erreur, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur lors de la récupération des données";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        // Initialise la vue avec les données récupérées.
        $view = new View("view/planification/contraintesGrilleJournaliere");
        $view->setVar('liste_valeurs', $liste_valeurs);
        $view->setVar('liste_classes', $liste_classes);

        return $view;
    }

    /**
     * Insère une contrainte de grille journalière dans la base de données.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Redirige vers la page de création de festival après l'insertion.
     */
    public function insertGriJ(PDO $pdo): View
    {
        // Récupère la liste des classes nécessaires à la vue.
        $liste_classes = $this->creationGriJService->getListeClasses($_POST);

        // Vérifie les classes obtenues et redirige vers la page principale en cas d'erreur.
        foreach ($liste_classes as $key => $value) {
            if ($value != "ok") {
                $this->index();
            }
        }

        try {
            // Insère la contrainte de grille journalière dans la base de données.
            $this->creationGriJService->insertGriJ($pdo, $_POST);

        } catch (PDOException) {
            // En cas d'erreur lors de l'insertion, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur lors de l'insertion des données";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        // Redirige vers la page de création de festival après l'insertion réussie.
        header('Location: ?controller=CreationFestival');
        exit();
    }
}
