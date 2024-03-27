<?php

namespace controllers;

use PDO;
use PDOException;
use services\GestionFestivalsServices;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur responsable de la gestion des festivals.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class GestionFestivalsController
{
    /** @var GestionFestivalsServices $gestionFestivalsServices Le service de gestion des festivals. */
    private GestionFestivalsServices $gestionFestivalsServices;

    /**
     * Constructeur de la classe.
     *
     * @param GestionFestivalsServices $gestionFestivalsServices Service de gestion des festivals.
     */
    public function __construct(GestionFestivalsServices $gestionFestivalsServices)
    {
        $this->gestionFestivalsServices = $gestionFestivalsServices;
    }

    /**
     * Affiche la vue principale pour la création d'un festival.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de création de festival.
     */
    public function index(PDO $pdo): View
    {
        // Initialisation des listes avec des valeurs par défaut.
        $liste_categories = $liste_scenes = $liste_grilles = $liste_spectacles = $liste_membres = $liste_responsables = array();

        // Tentative de récupération des données nécessaires depuis la base de données.
        try {
            $liste_categories = $this->gestionFestivalsServices->getCategories($pdo);
            $liste_scenes = $this->gestionFestivalsServices->getScenes($pdo);
            $liste_grilles = $this->gestionFestivalsServices->getGrilles($pdo);
            $liste_spectacles = $this->gestionFestivalsServices->getSpectacles($pdo);
            $liste_membres = $liste_responsables = $this->gestionFestivalsServices->getUsers($pdo);
        } catch (PDOException $e) {
            $message_erreur = "Erreur lors de la récupération des données";
        }

        // Redirection vers la page d'erreur si une erreur s'est produite pendant la récupération des données.
        if (isset($message_erreur)) {
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
        }

        // Récupération des listes de classes et de valeurs.
        $liste_classes = $this->gestionFestivalsServices->getListeClasses($pdo, $_POST);
        $liste_valeurs = $this->gestionFestivalsServices->getListeValeurs($pdo, $_POST);

        // Initialisation de la vue avec les données récupérées.
        $view = new View("view/festival/creationFestival");
        $view->setVar("liste_categories", $liste_categories);
        $view->setVar("liste_scenes", $liste_scenes);
        $view->setVar("liste_grilles", $liste_grilles);
        $view->setVar("liste_spectacles", $liste_spectacles);
        $view->setVar("liste_membres", $liste_membres);
        $view->setVar("liste_responsables", $liste_responsables);
        $view->setVar("liste_classes", $liste_classes);
        $view->setVar("liste_valeurs", $liste_valeurs);

        $view->setVar("titre", "Création d'un festival");
        $view->setVar("controller", "CreationFestival");
        $view->setVar("open", "");
        $view->setVar("action_validation", "creerFestival");
        $view->setVar("texte_bouton", "Valider");

        return $view;
    }

    /**
     * Crée un festival en fonction des données du formulaire.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View|null Vue de la liste des festivals ou null en cas d'échec.
     */
    public function creerFestival(PDO $pdo): ?View
    {
        // Vérifie si toutes les conditions pour la création du festival sont remplies.
        $everything_ok = $this->gestionFestivalsServices->getEverythingOK($pdo);

        if (!$everything_ok) {
            return $this->index($pdo);
        } else {
            try {
                // Crée le festival en fonction des valeurs fournies.
                $liste_valeurs = $this->gestionFestivalsServices->getListeValeurs($pdo, $_POST);
                $festival = $this->gestionFestivalsServices->create_festival($liste_valeurs);

                // Insère le festival dans la base de données.
                $this->gestionFestivalsServices->insert_festival($pdo, $festival);
            } catch (PDOException $e) {
                // En cas d'erreur, redirige vers la page d'erreur avec le message d'erreur.
                $message_erreur = "Erreur lors de la création du festival\n\n message erreur :" . $e->getMessage() . "\n";
                header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
                exit();
            }

            // Redirige vers la page de la liste des festivals avec un indicateur de création réussie.
            header("Location: ?controller=ListeFestival&cree=true");
            exit();
        }
    }

    /**
     * Redirige vers la page d'ajouts avec l'identifiant du festival.
     *
     * @param PDO $pdo Connexion à la base de données.
     */
    public function ajouts(PDO $pdo): void
    {
        header("Location: ?controller=Ajouts&idFestival=" . HttpHelper::getParam("idFestival"));
        exit();
    }

    /**
     * Affiche la vue de modification d'un festival.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de modification d'un festival.
     */
    public function modifier(PDO $pdo): View
    {
        $view = $this->index($pdo);

        // Récupère l'identifiant du festival à modifier depuis les paramètres de la requête.
        $id = HttpHelper::getParam("idFestival");

        // Récupère les valeurs du festival à modifier.
        $liste_valeurs = $this->gestionFestivalsServices->getFestival($pdo, $id);
        $view->setVar("liste_valeurs", $liste_valeurs);

        // Définit les variables nécessaires à la vue.
        $view->setVar("idFestival", $id);
        $view->setVar("titre", "Modification d'un festival");
        $view->setVar("controller", "GestionFestivals");
        $view->setVar("action", "modifier");
        $view->setVar("open", '');
        $view->setVar("texte_bouton", "Modifier");
        $view->setVar("action_validation", "validationModification");

        return $view;
    }

    /**
     * Valide la modification d'un festival en mettant à jour les valeurs dans la base de données.
     *
     * @param PDO $pdo Connexion à la base de données.
     */
    public function validationModification(PDO $pdo): void
    {
        // Récupère l'identifiant du festival à modifier depuis les paramètres de la requête.
        $id = HttpHelper::getParam("idFestival");

        // Récupère les nouvelles valeurs depuis les données du formulaire.
        $nouvelles_valeurs = $this->gestionFestivalsServices->getListeValeurs($pdo, $_POST);

        // Met à jour les valeurs du festival dans la base de données.
        $this->gestionFestivalsServices->update_festival($pdo, $id, $nouvelles_valeurs);

        // Redirige vers la page de la liste des festivals.
        header("Location: ?controller=ListeFestival");
    }

    /**
     * Affiche la vue de confirmation de suppression d'un festival.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de confirmation de suppression d'un festival.
     */
    public function confirmationSupressionFestival(PDO $pdo): View
    {
        $view = new View("view/confirmation");

        // Récupère l'identifiant du festival à supprimer depuis les paramètres de la requête.
        $id = HttpHelper::getParam("idFestival");

        // Définit les variables nécessaires à la vue.
        $view->setVar("message", "Etes-vous sûr de vouloir supprimer ce festival ?");
        $view->setVar("controllerValider", "GestionFestivals");
        $view->setVar("actionValider", "suppression");
        $view->setVar("controllerRetour", "ListeFestival");
        $view->setVar("actionRetour", "index");
        $view->setVar("id", $id);

        return $view;
    }

    /**
     * Supprime un festival de la base de données.
     *
     * @param PDO $pdo Connexion à la base de données.
     */
    public function suppression(PDO $pdo): void
    {
        // Récupère l'identifiant du festival à supprimer depuis les paramètres de la requête.
        $id = HttpHelper::getParam("id");

        // Supprime le festival de la base de données.
        $this->gestionFestivalsServices->supprimerFestival($pdo, $id);

        // Redirige vers la page de la liste des festivals.
        header("Location: ?controller=ListeFestival");
    }

    /**
     * Affiche le menu avec les différentes options pour la gestion des festivals.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue du menu de gestion des festivals.
     */
    public function showMenu(PDO $pdo): View
    {
        $view = $this->index($pdo);
        $view->setVar('open', 'open');
        return $view;
    }
}
