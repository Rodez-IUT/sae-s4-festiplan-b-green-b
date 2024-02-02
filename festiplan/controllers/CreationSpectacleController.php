<?php

namespace controllers;

use PDO;
use services\CreationSpectacleServices;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur responsable de la création et gestion des spectacles.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class CreationSpectacleController
{
    /** @var CreationSpectacleServices $creationSpectacleServices Le service de création de spectacle. */
    private CreationSpectacleServices $creationSpectacleServices;

    /**
     * Constructeur du contrôleur de création de spectacle.
     *
     * @param CreationSpectacleServices $param Le service de création de spectacle.
     */
    public function __construct(CreationSpectacleServices $param)
    {
        $this->creationSpectacleServices = $param;
    }

    /**
     * Affiche la vue de création de spectacle avec les listes nécessaires pour le formulaire.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de création de spectacle.
     */
    public function index(PDO $pdo): View
    {
        // Initialisation des listes à vide.
        $listeCategorieSpectacle = $listeSceneSpectacle = array();
        $listeIntervenantHors = $listeIntervenantScene = array();

        try {
            // Récupération des listes nécessaires pour le formulaire.
            $listeCategorieSpectacle = $this->creationSpectacleServices->getCategoriesSpectacle($pdo);
            $listeIntervenantHors = $this->creationSpectacleServices->getIntervenantHors($pdo);
            $listeIntervenantScene = $this->creationSpectacleServices->getIntervenantScene($pdo);

        } catch (\PDOException $e) {
            // En cas d'erreur PDO, redirige vers une page d'erreur.
            $message_erreur = "Erreur lors de la récupération des données";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        // Récupération des autres listes nécessaires pour le formulaire.
        $liste_classes = $this->creationSpectacleServices->getListeClasses($pdo, $_POST);
        $liste_valeurs = $this->creationSpectacleServices->getListeValeurs($pdo, $_POST);

        // Initialisation de la vue avec les données nécessaires.
        $view = new View("view/creationSpectacle");
        $view->setVar("liste_categories", $listeCategorieSpectacle);
        $view->setVar("listeIntervenantHors", $listeIntervenantHors);
        $view->setVar("listeIntervenantScene", $listeIntervenantScene);
        $view->setVar("liste_valeurs", $liste_valeurs);
        $view->setVar("liste_classes", $liste_classes);
        $view->setVar("titre", "Création d'un spectacle");
        $view->setVar("controller", "CreationSpectacle");
        $view->setVar("action_validation", "creerSpectacle");
        $view->setVar("texte_bouton", "Valider");
        $view->setVar("open", "");
        return $view;
    }

    /**
     * Crée un spectacle avec les données du formulaire.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de création de spectacle ou redirection vers la liste des spectacles.
     */
    public function creerSpectacle(PDO $pdo): View
    {
        // Vérifie si toutes les données nécessaires sont présentes.
        $everything_ok = $this->creationSpectacleServices->getEverythingOK($pdo);

        if (!$everything_ok) {
            return $this->index($pdo);
        } else {
            try {
                // Crée un spectacle avec les valeurs du formulaire.
                $liste_valeurs = $this->creationSpectacleServices->getListeValeurs($pdo, $_POST);
                $spectacle = $this->creationSpectacleServices->create_Spectacle($liste_valeurs);
                $this->creationSpectacleServices->insert_Spectacle($pdo, $spectacle);

            } catch (\PDOException $e) {
                // En cas d'erreur PDO lors de la création, redirige vers une page d'erreur.
                $message_erreur = "Erreur lors de la création du festival des données";
                header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
                exit();
            }

            // Réinitialise les données du formulaire et redirige vers la liste des spectacles.
            $_POST = array();
            header("Location: ?controller=ListeSpectacle");
            exit();
        }
    }

    /**
     * Affiche la vue de modification d'un spectacle avec les données du spectacle sélectionné.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de modification de spectacle.
     */
    public function modifier(PDO $pdo): View
    {
        // Initialise la vue avec les données du formulaire.
        $view = $this->index($pdo);

        // Récupère l'identifiant du spectacle à modifier.
        $id = HttpHelper::getParam("idSpectacle");

        // Récupère les valeurs du spectacle sélectionné.
        $liste_valeurs = $this->creationSpectacleServices->getSpectacle($pdo, $id);

        // Initialise la vue avec les données nécessaires pour la modification.
        $view->setVar("liste_valeurs", $liste_valeurs);
        $view->setVar("idSpectacle", $id);
        $view->setVar("titre", "Modification d'un spectacle");
        $view->setVar("controller", "CreationSpectacle");
        $view->setVar("action", "modifier");
        $view->setVar("open", '');
        $view->setVar("texte_bouton", "Modifier");
        $view->setVar("action_validation", "validationModification");

        return $view;
    }

    /**
     * Valide la modification d'un spectacle avec les nouvelles valeurs du formulaire.
     *
     * @param PDO $pdo Connexion à la base de données.
     */
    public function validationModification(PDO $pdo)
    {
        // Récupère l'identifiant du spectacle à modifier.
        $id = HttpHelper::getParam("idSpectacle");

        // Récupère les nouvelles valeurs du formulaire.
        $nouvelles_valeurs = $this->creationSpectacleServices->getListeValeurs($pdo, $_POST);

        // Met à jour les informations du spectacle.
        $this->creationSpectacleServices->update_Spectacle($pdo, $id, $nouvelles_valeurs);

        // Redirige vers la liste des spectacles après la modification.
        header("Location: ?controller=ListeSpectacle");
        exit();
    }

    /**
     * Affiche le menu de création de spectacle avec l'option correspondante ouverte.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de création de spectacle avec menu ouvert.
     */
    public function showMenu($pdo): View
    {
        // Initialise la vue avec le menu ouvert.
        $view = $this->index($pdo);
        $view->setVar('open', 'open');
        return $view;
    }
}
