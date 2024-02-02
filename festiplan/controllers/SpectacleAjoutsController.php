<?php

namespace controllers;

use PDO;
use services\SpectacleAjoutsService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur responsable des ajouts d'intervenants pour un spectacle.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class SpectacleAjoutsController
{

    /** @var SpectacleAjoutsService $spectacleAjoutsService Service pour les ajouts d'intervenants. */
    private SpectacleAjoutsService $spectacleAjoutsService;

    /**
     * Constructeur de la classe.
     *
     * @param SpectacleAjoutsService $param Service pour les ajouts d'intervenants.
     */
    public function __construct(SpectacleAjoutsService $param)
    {
        $this->spectacleAjoutsService = $param;
    }

    /**
     * Affiche la page d'ajouts d'intervenants pour un spectacle.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la page d'ajouts d'intervenants.
     */
    public function index(PDO $pdo): View
    {
        $id = HttpHelper::getParam("idSpectacle");

        try {
            // Récupération des intervenants disponibles et présents pour le spectacle.
            $liste_intervenant = $this->spectacleAjoutsService->getIntervenants($pdo);
            $intervenants_present = $this->spectacleAjoutsService->getIntervenantsPresent($pdo, $id);
        } catch (\PDOException $e) {
            echo $e;
            $liste_intervenant = [];
            $intervenants_present = [];
        }

        // Initialisation de la vue avec les résultats de la recherche et d'autres variables nécessaires.
        $view = new View("view/spectaclesAjouts");

        $view->setVar("liste_intervenant", $liste_intervenant);
        $view->setVar("intervenants_present", $intervenants_present);

        $view->setVar("idSpectacle", $id);

        $view->setVar("titre", "Ajouts");
        $view->setVar("controller", "SpectacleAjouts");
        $view->setVar("open", "");

        $view->setVar("texte_button", "Créer");
        $view->setVar("action", "creerIntervenant");

        $view->setVar("valeurIntervenant", null);

        return $view;
    }

    /**
     * Crée un nouvel intervenant et le lie au spectacle.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return void Redirige vers la page d'ajouts d'intervenants pour le spectacle.
     */
    public function creerIntervenant(PDO $pdo): void
    {
        // Récupération des paramètres du formulaire.
        $nom = HttpHelper::getParam("nom");
        $prenom = HttpHelper::getParam("prenom");
        $mail = HttpHelper::getParam("mail");
        $estSurSceneValue = HttpHelper::getParam("estSurScene");
        $idCreateur = HttpHelper::getParam("idCreateur");

        $idSpec = HttpHelper::getParam("idSpectacle");

        // Conversion de la valeur de estSurScene en entier.
        $estSurScene = ($estSurSceneValue == "1") ? 1 : 0;

        try {
            // Appel du service pour créer l'intervenant.
            $this->spectacleAjoutsService->creerIntervenant($pdo, $nom, $prenom, $mail, $estSurScene, $idCreateur, $idSpec);
        } catch (\PDOException $e) {
            echo $e;
            die();
        }

        // Redirection vers la page d'ajouts d'intervenants pour le spectacle.
        header("Location: ?controller=SpectacleAjouts&idSpectacle=$idSpec");
        exit();
    }

    /**
     * Charge les intervenants depuis un fichier CSV et les lie au spectacle.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return void Redirige vers la page d'ajouts d'intervenants pour le spectacle.
     */
    public function fromCSVFile(PDO $pdo): void
    {
        // Vérification de la présence et de l'absence d'erreurs liées au fichier.
        if (!isset($_FILES["fichier"]) || $_FILES["fichier"]["error"] != 0) {
            // En cas d'erreur, retourne à la page d'ajouts avec un message d'erreur.
            $view = $this->index($pdo);
            $view->setVar("file_erreor", "Aucun fichier sélectionné");
            return;
        }

        // Récupération du nom et du chemin du fichier.
        $file_name = $_FILES["fichier"]["name"];
        $file_path = $_FILES["fichier"]["tmp_name"];

        // Récupération d'autres paramètres du formulaire.
        $idCreateur = HttpHelper::getParam("idCreateur");
        $idSpec = HttpHelper::getParam("idSpectacle");

        try {
            // Appel du service pour charger les intervenants depuis le fichier CSV.
            $this->spectacleAjoutsService->fromCSVFile($pdo, $file_name, $file_path, $idCreateur, $idSpec);
        } catch (\PDOException $e) {
            // En cas d'erreur PDO, retourne à la page d'ajouts avec un message d'erreur.
            $view = $this->index($pdo);
            $view->setVar("file_erreor", $e->getMessage());
            return;
        }

        // Redirection vers la page d'ajouts d'intervenants pour le spectacle.
        header("Location: ?controller=SpectacleAjouts&idSpectacle=$idSpec");
        exit();
    }

    /**
     * Ajoute un intervenant au spectacle.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return void Redirige vers la page d'ajouts d'intervenants pour le spectacle.
     */
    public function ajouterIntervenant(PDO $pdo): void
    {
        // Récupération des paramètres du formulaire.
        $idSpectacle = HttpHelper::getParam("idSpectacle");
        $idIntervenant = HttpHelper::getParam("idIntervenant");

        try {
            // Appel du service pour ajouter l'intervenant au spectacle.
            $this->spectacleAjoutsService->ajouterIntervenant($pdo, $idSpectacle, $idIntervenant);
        } catch (\PDOException $e) {
            echo $e;
        }

        // Redirection vers la page d'ajouts d'intervenants pour le spectacle.
        header("Location: ?controller=SpectacleAjouts&idSpectacle=$idSpectacle");
        exit();
    }

    /**
     * Retire un intervenant du spectacle.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return void Redirige vers la page d'ajouts d'intervenants pour le spectacle.
     */
    public function retirerIntervenant(PDO $pdo): void
    {
        // Récupération des paramètres du formulaire.
        $idSpectacle = HttpHelper::getParam("idSpectacle");
        $idIntervenant = HttpHelper::getParam("idIntervenant");

        try {
            // Appel du service pour retirer l'intervenant du spectacle.
            $this->spectacleAjoutsService->retirerIntervenant($pdo, $idSpectacle, $idIntervenant);
        } catch (\PDOException $e) {
            echo $e;
        }

        // Redirection vers la page d'ajouts d'intervenants pour le spectacle.
        header("Location: ?controller=SpectacleAjouts&idSpectacle=$idSpectacle");
        exit();
    }

    /**
     * Effectue une recherche d'intervenants.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la page d'ajouts d'intervenants avec les résultats de la recherche.
     */
    public function recherche(PDO $pdo): View
    {
        // Récupération du terme de recherche et conversion des caractères spéciaux.
        $recherche = HttpHelper::getParam("recherche");
        $recherche = htmlspecialchars($recherche);

        // Liste des intervenants résultant de la recherche.
        $liste_intervenants = [];

        try {
            // Appel du service pour effectuer la recherche.
            $liste_intervenants = $this->spectacleAjoutsService->recherche($pdo, $recherche);
        } catch (\PDOException $e) {
            echo $e;
        }

        // Initialisation de la vue avec les résultats de la recherche.
        $view = $this->index($pdo);
        $view->setVar("liste_intervenant", $liste_intervenants);
        $view->setVar("recherche", $recherche);

        return $view;
    }

    /**
     * Supprime un intervenant de la base de données.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return void Redirige vers la page d'ajouts d'intervenants pour le spectacle.
     */
    public function supprimerIntervenant(PDO $pdo): void
    {
        // Récupération des paramètres du formulaire.
        $idSpectacle = HttpHelper::getParam("idSpectacle");
        $idIntervenant = HttpHelper::getParam("idIntervenant");

        try {
            // Appel du service pour supprimer l'intervenant de la base de données.
            $this->spectacleAjoutsService->supprimerIntervenant($pdo, $idIntervenant);
        } catch (\PDOException $e) {
            echo $e;
            die();
        }

        // Redirection vers la page d'ajouts d'intervenants pour le spectacle.
        header("Location: ?controller=SpectacleAjouts&idSpectacle=$idSpectacle");
        exit();
    }

    /**
     * Modifie les informations d'un intervenant.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la page d'ajouts d'intervenants avec les modifications prises en compte.
     */
    public function modifierIntervenant(PDO $pdo): View
    {
            // Récupération des paramètres du formulaire.
            $idIntervenant = HttpHelper::getParam("idIntervenant");
            $idSpectacle = HttpHelper::getParam("idSpectacle");

            try {
                // Récupération de la liste des intervenants, des intervenants présents, et des informations de l'intervenant.
                $liste_intervenant = $this->spectacleAjoutsService->getIntervenants($pdo);
                $intervenants_present = $this->spectacleAjoutsService->getIntervenantsPresent($pdo, $idSpectacle);
                $valeurIntervenant = $this->spectacleAjoutsService->getIntervenant($pdo, $idIntervenant);
            } catch (\PDOException $e) {
                echo $e;
                $liste_intervenant = [];
                $intervenants_present = [];
            }

            // Initialisation de la vue avec les données nécessaires pour la modification.
            $view = $this->index($pdo);
            $view->setVar("controller", "SpectacleAjouts");
            $view->setVar("valeurIntervenant", $valeurIntervenant);
            $view->setVar("texte_button", 'modifier');
            $view->setVar("action", "modifier");
            $view->setVar("liste_intervenant", $liste_intervenant);
            $view->setVar("intervenants_present", $intervenants_present);
            return $view;
    }

    /**
     * Effectue la modification des informations d'un intervenant.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return void Redirige vers la page d'ajouts d'intervenants pour le spectacle.
     */
    public function modifier(PDO $pdo): void
    {
       // Récupération des paramètres du formulaire.
       $idSpectacle = HttpHelper::getParam("idSpectacle");
       $idIntervenant = HttpHelper::getParam("idIntervenant");
       $nom = HttpHelper::getParam("nom");
       $prenom = HttpHelper::getParam("prenom");
       $mail = HttpHelper::getParam("mail");
       $estSurSceneValue = HttpHelper::getParam("estSurScene");
       $idCreateur = HttpHelper::getParam("idCreateur");

       // Conversion de la valeur de estSurScene en entier.
       $estSurScene = ($estSurSceneValue == "1") ? 1 : 0;

       try {
           // Appel du service pour effectuer la modification de l'intervenant.
           $this->spectacleAjoutsService->modifierIntervenant($pdo, $idIntervenant, $nom, $prenom, $mail, $estSurScene);
       } catch (\PDOException $e) {
           echo $e;
           die();
       }

       // Redirection vers la page d'ajouts d'intervenants pour le spectacle.
       header("Location: ?controller=SpectacleAjouts&idSpectacle=$idSpectacle");
       exit();
    }

    /**
     * Affiche la page d'ajouts d'intervenants avec le menu déroulant ouvert.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la page d'ajouts d'intervenants avec le menu déroulant ouvert.
     */
    public function showMenu(PDO $pdo): View
    {
        // Affiche la page d'ajouts d'intervenants avec le menu déroulant ouvert.
        $view = $this->index($pdo);
        $view->setVar("open", "open");
        return $view;
    }
}