<?php

namespace services;

use PDO;
use other\classes\Festival;
use function utils\add_image_to_db;
use function utils\creer_festival;
use function utils\get_image_size;
use function utils\insertion_festival;
use function utils\is_image_valid;

include "utils/fonctions.php";

/**
 * La classe GestionFestivalsServices fournit des méthodes 
 * pour la gestion des festivals.
 *
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class GestionFestivalsServices
{

    // Champs liés aux festivals
    const LISTE_CHAMPS = array(
        "nom",
        "description",
        "dateDebut",
        "dateFin",
        "responsable",
        "ville",
        "codePostal",
        "categories",
        "scenes",
        "debutGriJ",
        "finGriJ",
        "dureeGriJ",
    );

    /**
     * Récupère les informations d'un festival à partir de son identifiant.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $id Identifiant du festival à récupérer.
     * @return array Tableau contenant les informations du festival.
     */
    public function getFestival(PDO $pdo, string $id): array
    {
        $requetes = array(
            "SELECT nomFestival as nom, descriptionFestival as description, idImage as image, 
                       dateDebutFestival as dateDebut, dateFinFestival as dateFin, idGriJ as grille,
                       idResponsable as responsable, ville, codePostal FROM festivals WHERE idFestival = :id",
            "SELECT nomImage as image FROM images WHERE idImage = :id",
            "SELECT idUser as membres FROM organiser WHERE idFestival = :id",
            "SELECT idSpectacle as spectacles FROM composer WHERE idFestival = :id",
            "SELECT idCategorie as categories FROM categorieFestival WHERE idFestival = :id",
            "SELECT idScene as scenes FROM accueillir WHERE idFestival = :id",
        );


        $listeStatement = array();

        foreach ($requetes as $requete) {
            $stmt = $pdo->prepare($requete);
            $stmt->execute(array(":id" => $id));

            $resultat = array();
            while ($row = $stmt->fetch()) {
                $resultat[] = $row;
            }

            $listeStatement[] = $resultat;
        }

        foreach ($listeStatement[0] as $festival) {
            $idGriJ = $festival["grille"];

            $requete = "SELECT heureDebut as debutGriJ, heureFin as finGriJ, dureeMinimaleEntreDeuxSpectacles as dureeGriJ FROM grilleJournaliere WHERE idGriJ = :id";
            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(":id", $idGriJ);
            $stmt->execute();

            $grilles = array();
            while ($row = $stmt->fetch()) {
                $grilles[] = $row;
            }

            $temp[] = $grilles;

            $liste_valeurs = array(
                "nom" => $festival["nom"],
                "description" => $festival["description"],
                "image" => $festival["image"],
                "dateDebut" => $festival["dateDebut"],
                "dateFin" => $festival["dateFin"],
                "responsable" => $festival["responsable"],
                "ville" => $festival["ville"],
                "codePostal" => $festival["codePostal"],
                "debutGriJ" => $temp[0][0]["debutGriJ"],
                "finGriJ" => $temp[0][0]["finGriJ"],
                "dureeGriJ" => $temp[0][0]["dureeGriJ"],
            );
        }

        $membres = array();
        foreach ($listeStatement[2] as $membre) {
            $membres[] = $membre["membres"];
        }

        $spectacles = array();
        foreach ($listeStatement[3] as $spectacle) {
            $spectacles[] = $spectacle["spectacles"];
        }

        $categories = array();
        foreach ($listeStatement[4] as $categorie) {
            $categories[] = $categorie["categories"];
        }

        $scenes = array();
        foreach ($listeStatement[5] as $scene) {
            $scenes[] = $scene["scenes"];
        }

        $liste_valeurs["membres"] = $membres;
        $liste_valeurs["spectacles"] = $spectacles;
        $liste_valeurs["categories"] = $categories;
        $liste_valeurs["scenes"] = $scenes;

        return $liste_valeurs;
    }

    /**
     * Vérifie les entrées de données pour la création/modification d'un festival.
     *
     * @param array $liste Tableau associatif contenant les données du festival à vérifier.
     * @return array Tableau associatif contenant les classes CSS pour chaque champ vérifié.
     */
    private function verif_inputs(array $liste): array
    {
        $liste_classes = [];
        foreach ($this::LISTE_CHAMPS as $key) {
            if (!isset($liste[$key])) {
                if (in_array($key, ["categories", "scenes", "spectacles", "membres"])) {
                    $liste_classes[$key] = "invalide";
                } else {
                    $liste_classes[$key] = "";
                }
            } else {
                $liste_classes[$key] = $this->verif_input($key, $liste[$key]) ? "ok" : "invalide";
            }
        }
        return $liste_classes;

    }

    /**
     * Vérifie une entrée de données spécifique pour un champ donné.
     *
     * @param string $key Clé du champ à vérifier.
     * @param mixed $value Valeur du champ à vérifier.
     * @return bool Renvoie vrai si la valeur est valide, sinon faux.
     */
    private function verif_input(string $key, mixed $value): bool
    {

        switch ($key) {
        case 'nom':
        case 'prenom':
        case 'identifiant':
        case 'motDePasse':
        case 'ville':
            return !ctype_space($value) && 0 < strlen($value) && strlen($value) < 100;

        case 'description':
            return !ctype_space($value) && 0 < strlen($value) && strlen($value) < 1000;

        case 'email':
            return filter_var($value, FILTER_VALIDATE_EMAIL);

        case 'codePostal':
            return preg_match("/^[1-9][0-9]{4}$/", $value);

        case 'dateDebut':
        case 'dateFin':
            return preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $value);

        case 'image':
            $taille_nom_image = strlen($value);
            return substr($value, $taille_nom_image - 4, $taille_nom_image) == ".jpg"
                || substr($value, $taille_nom_image - 4, $taille_nom_image) == ".png"
                || substr($value, $taille_nom_image - 5, $taille_nom_image) == ".jpeg"
                || substr($value, $taille_nom_image - 4, $taille_nom_image) == ".gif";

        case 'categories':
        case 'scenes':
        case 'spectacles':
        case 'membres':
        case 'grilles':
            return true;

        case "debutGriJ":
        case "finGriJ":
            return preg_match("/^[0-9]{2}:[0-9]{2}$/", $value);

        case "dureeGriJ":
            return $value > 0;


        default:
            return true;
        }
    }

    /**
     * Vérifie et enregistre une image pour le festival dans la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return string Identifiant de l'image enregistrée.
     */
    private function verif_image(PDO $pdo): string
    {
        $image_id = 1;
        if (isset($_FILES["image"])) {
            $complete_file_path = $_FILES["image"]["tmp_name"];
            $file_name = $_FILES["image"]["name"];

            $size = get_image_size($complete_file_path);

            $valid_size = is_image_valid($size);

            if ($valid_size){
                $image_id = add_image_to_db($pdo, $file_name, $complete_file_path);
            }

        }
        return $image_id;
    }

    /**
     * Récupère les classes CSS pour chaque champ dans une liste de valeurs spécifiée.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param array $liste Tableau associatif contenant les valeurs du festival.
     * @return array Tableau associatif contenant les classes CSS pour chaque champ.
     */
    public function getListeClasses(PDO $pdo, array $liste): array
    {
        $liste_classes = $this->verif_inputs($liste);

        if ($liste_classes == null) {
            foreach (self::LISTE_CHAMPS as $key) {
                $liste_classes[$key] = "";
            }
        }

        if ($liste_classes["dateDebut"] == "ok"
            && $liste_classes["dateFin"] == "ok") {

            $dateDebut = $liste["dateDebut"];
            $dateFin = $liste["dateFin"];
            $liste_classes["dateDebut"] = $dateDebut <= $dateFin ? "ok" : "invalide";
            $liste_classes["dateFin"] = $dateDebut <= $dateFin ? "ok" : "invalide";
        }

        $liste_classes["image"] = "ok";

        return $liste_classes;
    }

    /**
     * Récupère les valeurs à utiliser pour la création/modification d'un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param array $liste Tableau associatif contenant les valeurs du festival.
     * @return array Tableau associatif contenant les valeurs du festival à utiliser.
     */
    public function getListeValeurs(PDO $pdo, array $liste): array
    {
        $liste_valeurs = [];
        foreach ($this::LISTE_CHAMPS as $key) {

            $value = $liste[$key] ?? null;

            if (isset($value)) {
                if (gettype($value) == "array" || in_array($key, ["categories", "scenes", "spectacles", "membres"])) {
                    $values = array();
                    foreach ($value as $v) {
                        $values[] = $v;
                    }
                    $liste_valeurs[$key] = $values;
                } else {
                    $liste_valeurs[$key] = $value;
                }
            } else {
                $liste_valeurs[$key] = "";
            }
        }

        if ($liste_valeurs == null) {
            foreach (self::LISTE_CHAMPS as $key) {
                $liste_valeurs[$key] = "";
            }
        }

        $liste_valeurs["image"] = $this->verif_image($pdo);

        return $liste_valeurs;
    }

    /**
     * Récupère la liste des catégories disponibles pour les festivals.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return array Tableau contenant la liste des catégories de festivals.
     */
    public function getCategories(PDO $pdo): array
    {
        $requete = "SELECT * FROM categories";
        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        $categories = array();
        while ($row = $stmt->fetch()) {
            $categories[] = $row;
        }

        return $categories;
    }

    /**
     * Récupère la liste des scènes disponibles pour les festivals.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return array Tableau contenant la liste des scènes de festivals.
     */
    public function getScenes(PDO $pdo): array
    {
        $requete = "SELECT * FROM scenes";
        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        $scenes = array();
        while ($row = $stmt->fetch()) {
            $scenes[] = $row;
        }

        return $scenes;
    }

    /**
     * Récupère la liste des grilles horaires disponibles pour les festivals.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return array Tableau contenant la liste des grilles horaires de festivals.
     */
    public function getGrilles(PDO $pdo): array
    {
        $requete = "SELECT * FROM grilleJournaliere";
        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        $grilles = array();
        while ($row = $stmt->fetch()) {
            $grilles[] = $row;
        }

        return $grilles;
    }

    /**
     * Récupère la liste des spectacles disponibles pour les festivals.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return array Tableau contenant la liste des spectacles de festivals.
     */
    public function getSpectacles(PDO $pdo): array
    {
        $requete = "SELECT * FROM spectacles";
        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        $spectacles = array();
        while ($row = $stmt->fetch()) {
            $spectacles[] = $row;
        }

        return $spectacles;
    }

    /**
     * Récupère la liste des utilisateurs disponibles pour les festivals.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return array Tableau contenant la liste des utilisateurs de festivals.
     */
    public function getUsers(PDO $pdo): array
    {
        $requete = "SELECT * FROM users";
        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        $users = array();
        while ($row = $stmt->fetch()) {
            $users[] = $row;
        }

        return $users;
    }

    /**
     * Crée un objet Festival à partir d'un tableau de valeurs.
     *
     * @param array $liste_valeurs Tableau associatif contenant les valeurs du festival.
     * @return Festival Objet Festival créé à partir des valeurs spécifiées.
     */
    public function create_festival(array $liste_valeurs): Festival
    {
        return creer_festival($liste_valeurs);
    }

    /**
     * Met à jour un festival dans la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $id Identifiant du festival à mettre à jour.
     * @param array $nouvelles Tableau associatif contenant les nouvelles valeurs du festival.
     */
    public function update_festival(PDO $pdo, string $id, array $nouvelles): void
    {
        $festival = creer_festival($nouvelles);

        $this->supprimerFestival($pdo, $id);
        insertion_festival($pdo, $festival);

    }

    /**
     * Supprime un festival de la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $idFestival Identifiant du festival à supprimer.
     */
    public function supprimerFestival(PDO $pdo, string $idFestival): void
    {
        $requetes = array(
            "DELETE FROM festivals WHERE idFestival = :id",
        );
        foreach ($requetes as $requete) {
            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(":id", $idFestival);
            $stmt->execute();
        }
    }

    /**
     * Insère un nouvel objet Festival dans la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param Festival $festival Objet Festival à insérer dans la base de données.
     */
    public function insert_festival(PDO $pdo, Festival $festival): void
    {
        insertion_festival($pdo, $festival);
    }

    /**
     * Vérifie si toutes les valeurs des champs du formulaire sont valides.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return bool Renvoie vrai si toutes les valeurs sont valides, sinon faux.
     */
    public function getEverythingOK(PDO $pdo): bool
    {
        $everything_ok = true;

        foreach ($this->getListeClasses($pdo, $_POST) as $key => $value) {
            if ($value == "invalide") {
                $everything_ok = false;
            }
        }

        return $everything_ok;
    }


}