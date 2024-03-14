<?php

namespace utils;

use PDO;
use other\classes\Festival;
use other\classes\Spectacle;
use yasmf\HttpHelper;

const MAX_IMAGE_WIDTH  = 800;
const MAX_IMAGE_HEIGHT = 600;

function get_image_size($file_path): ?array 
{

    if (empty($file_path)) {
        return null;
    }

    $informations = getimagesize($file_path);

    if (!$informations) {
        return null;
    }

    return array(
        "width" => $informations[0],
        "height" => $informations[1]
    );
}

function is_image_valid($size): bool 
{

    if (empty($size)) {
        return false;
    }

    if ($size["width"] > MAX_IMAGE_WIDTH
        || $size["height"] > MAX_IMAGE_HEIGHT) {
        return false;
    }

    return true;

}

function add_image_to_db(PDO $pdo, string $fileName, string $tmp_path): int
{
    $fileName = uniqid() . $fileName;

    $server_path = getenv("DOCUMENT_ROOT") . "/festiplan/stockage/images/" . $fileName;

    move_uploaded_file($tmp_path, $server_path);

    $sql = "INSERT INTO images (nomImage) VALUES (?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$fileName]);

    return $pdo->lastInsertId();
}

function verifInput($key, $value): bool
{
    switch ($key) { 
        case 'nom':
        case 'prenom':
        case 'identifiant':
        case 'motDePasse':
        case 'ville':
            return 0 < strlen($value) && strlen($value) < 100;
            
        case 'description':
            return 0 < strlen($value) && strlen($value) < 1000;

        case 'email':
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        
        case 'codePostal':
            return preg_match("/^[1-9][0-9]{4}$/", $value);

        case 'dateDebut':
        case 'dateFin':
            return preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $value);

        default:
            return true;
    }
}

function creer_festival(array $liste_valeurs): Festival
{
    if ($liste_valeurs["categories"] == '') {
        $liste_valeurs["categories"] = array();
    }

    return new Festival(
        $liste_valeurs["nom"],
        $liste_valeurs["description"],
        $liste_valeurs["image"],
        $liste_valeurs["dateDebut"],
        $liste_valeurs["dateFin"],
        $liste_valeurs["responsable"],
        $liste_valeurs["ville"],
        $liste_valeurs["codePostal"],
        $liste_valeurs["categories"],
        $liste_valeurs["scenes"],
        $liste_valeurs["debutGriJ"],
        $liste_valeurs["finGriJ"],
        $liste_valeurs["dureeGriJ"]
    );
}

function insertion_festival(PDO $pdo, Festival $festival): void
{
    $sqlGriJ = "INSERT INTO grilleJournaliere (heureDebut, heureFin, dureeMinimaleEntreDeuxSpectacles) 
                VALUES (:debut, :fin, :duree)";

    $debut = $festival->getDebutGriJ();
    $fin = $festival->getFinGriJ();
    $duree = $festival->getDureeGriJ();

    $stmtGriJ = $pdo->prepare($sqlGriJ);
    $stmtGriJ->bindParam(":debut", $debut);
    $stmtGriJ->bindParam(":fin", $fin);
    $stmtGriJ->bindParam(":duree", $duree);

    $stmtGriJ->execute();

    $idGriJ = $pdo->lastInsertId();


    $sql = "INSERT INTO festivals (nomFestival, descriptionFestival, idImage, dateDebutFestival, dateFinFestival, idGriJ, idResponsable, ville, codePostal) 
    VALUES (:nom, :description, :image_id, :date_debut, :date_fin, :grille, :idResponsable, :ville, :codePostal)";

    $stmt = $pdo->prepare($sql);

    $nom = $festival->getNom();
    $description = $festival->getDescription();
    $image_id = $festival->getIdImage();
    $date_debut = $festival->getDateDebut();
    $date_fin = $festival->getDateFin();
    $idResponsable = $festival->getIdResponsable();
    $ville = $festival->getVille();
    $codePostal = $festival->getCodePostal();


    $stmt->bindParam(":nom", $nom);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":image_id", $image_id);
    $stmt->bindParam(":date_debut", $date_debut);
    $stmt->bindParam(":date_fin", $date_fin);
    $stmt->bindParam(":grille", $idGriJ);
    $stmt->bindParam(":idResponsable", $idResponsable);
    $stmt->bindParam(":ville", $ville);
    $stmt->bindParam(":codePostal", $codePostal);

    $stmt->execute();

    $festival_id = $pdo->lastInsertId();


    foreach ($festival->getCategories() as $categorie) {
        $sql = "INSERT INTO categorieFestival (idFestival, idCategorie) VALUES (?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$festival_id, $categorie]);
    }

    foreach ($festival->getScenes() as $scene) {
        $sql = "INSERT INTO accueillir (idFestival, idScene) VALUES (?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$festival_id, $scene]);
    }

    
}

function create_visiteur(array $liste_valeurs): Visiteur
{
    return new Visiteur(
        $liste_valeurs["nom"],
        $liste_valeurs["prenom"],
        $liste_valeurs["email"],
        $liste_valeurs["identifiant"],
        $liste_valeurs["motDePasse"]
    );
}

function insert_visiteur(Visiteur $visiteur) 
{
    global $pdo;

    $sql = "INSERT INTO users (nomUser, prenomUser, emailUser, loginUser, passwordUser) 
    VALUES (:nom, :prenom, :email, :identifiant, :pass)";

    $stmt = $pdo->prepare($sql);

    $nom = $visiteur->getNom();
    $prenom = $visiteur->getPrenom();
    $identifiant = $visiteur->getIdentifiant();
    $email = $visiteur->getEmail();
    $password = $visiteur->getPassword();

    $stmt->bindParam(":nom", $nom);
    $stmt->bindParam(":prenom", $prenom);
    $stmt->bindParam(":identifiant", $identifiant);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":pass", $password);

    $stmt->execute();

    return $pdo->lastInsertId();
}

function is_client_valid ($identifiant, $password): bool
{
    global $pdo;

    $sql = "SELECT loginUser, passwordUser 
            FROM users 
            WHERE loginUser = :identifiant
            AND passwordUser = :pass";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":identifiant", $identifiant);
    $stmt->bindParam(":pass", $password);

    $stmt->execute();

    $result = $stmt->fetch();

    if ($result) {
        return true;
    }
    return false;
}


function creer_Spectacle(array $liste_valeurs): Spectacle
{
    if (gettype($liste_valeurs["intervenantScene"])!= array()) {
        $liste_valeurs["intervenantScene"] = array();
    }
    if (gettype($liste_valeurs["intervenantHors"])!= array()) {
        $liste_valeurs["intervenantHors"] = array();
    }
    $id = HttpHelper::getParam("user_id");

    return new Spectacle(
        $liste_valeurs["titre"],
        $liste_valeurs["description"],
        $liste_valeurs["tailleScene"],
        $liste_valeurs["image"],
        $id,
        $liste_valeurs["duree"],
        $liste_valeurs["categories"],
        $liste_valeurs["intervenantScene"],
        $liste_valeurs["intervenantHors"]
    );
}

function insertion_Spectacle(PDO $pdo, Spectacle $spectacle): void
{
    $sql = "INSERT INTO spectacles (titreSpectacle, descriptionSpectacle, idImage, dureeSpectacle, surfaceSceneRequise, idResponsableSpectacle) 
    VALUES (:titre, :description, :image_id, :duree, :tailleScene, :idResponsable)";

    $stmt = $pdo->prepare($sql);

    $nom = $spectacle->getTitre();
    $description = $spectacle->getDescription();
    $image_id = $spectacle->getIdImage();
    $duree = $spectacle->getduree();
    $tailleScene = $spectacle->getTailleScene();
    $idResponsable = $spectacle->getIdResponsable();


    $stmt->bindParam(":titre", $nom);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":image_id", $image_id);
    $stmt->bindParam(":duree",$duree);
    $stmt->bindParam(":tailleScene",$tailleScene);
    $stmt->bindParam(":idResponsable",$idResponsable);


    $stmt->execute();

    $spectacle_id = $pdo->lastInsertId();


    foreach ($spectacle->getCategories() as $categorie) {
        $sql = "INSERT INTO categorieSpectacle (idSpectacle, idCategorie) VALUES (?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$spectacle_id, $categorie]);
    }


}
