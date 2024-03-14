<?php

namespace services;

use PDO;
use PDOException;

/**
 * Classe fournissant des services de planification pour les spectacles d'un festival.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class PlanificationServices
{

    /**
     * Obtient la planification des spectacles pour un festival donné.
     *
     * @param PDO    $pdo         Instance PDO pour la connexion à la base de données.
     * @param string $idFestival  Identifiant du festival pour lequel on souhaite récupérer la planification.
     * @return array
     */
    public function getPlanification(PDO $pdo, string $idFestival) : array
    {
        $infoSpectacle = $this->getSpectacles($pdo, $idFestival);

        if(empty($infoSpectacle)) {
            $message_erreur = "Erreur aucun spectacle attribue pour ce festival";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        $nbJour = $this->calculeNbJourFestival($pdo, $idFestival);

        
        
        $compteurJour = 0;
        $compteurNumeroSpectacle = 0;
        $dureeJourneeGriJ = $this->soustraireDuree($pdo, $infoSpectacle[0]["heureDebut"], $infoSpectacle[0]["heureFin"]);
        $dureeJournee = $dureeJourneeGriJ;
        $dureeEntreSpectacles = $this->intToTime($infoSpectacle[0]["dureeMinimaleEntreDeuxSpectacles"]);
        $heureActuelle = $infoSpectacle[0]["heureDebut"];
        $jour = [];

        foreach ($infoSpectacle as $data) {
            $data["dureeSpectacle"] = $this->intToTime($data["dureeSpectacle"]); // Convertir la durée du spectacle en TIME

            if ($data["dureeSpectacle"] <= $dureeJournee) {
                $heureActuelle = $this->gererSpectacle($pdo, $data, $idFestival, $heureActuelle, $dureeEntreSpectacles, $jour, $compteurJour, $compteurNumeroSpectacle);
                $dureeJournee = $this->soustraireDuree($pdo, $heureActuelle, $data["heureFin"]);
                $compteurNumeroSpectacle++;
            } else {
                $compteurNumeroSpectacle = 0;
                $compteurJour++;
                $dureeJournee = $dureeJourneeGriJ;
                $heureActuelle = $this->initialiserNouveauJour($pdo, $data, $idFestival, $dureeEntreSpectacles, $jour, $compteurJour, $compteurNumeroSpectacle);
                $dureeJournee = $this->soustraireDuree($pdo, $heureActuelle, $data["heureFin"]);
                $compteurNumeroSpectacle++;
            }
        }
        if($compteurJour >= $nbJour) {
            $message_erreur = "Erreur trop de spectacles, la planification depasse le nombre de jours du festival";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }
        return $jour;
    }

    private function gererSpectacle(PDO $pdo, $data, $idFestival, $heureActuelle, $dureeEntreSpectacles, &$jour, $compteurJour, $compteurNumeroSpectacle): ?string
    {

        $data["heureDebutSpectacle"] = $heureActuelle; // Création d'une valeur heureDebut du spectacle
        $heureActuelle = $this->ajouter_duree($pdo, $heureActuelle, $data["dureeSpectacle"]);
        $data["heureFinSpectacle"] = $heureActuelle; // Création d'une valeur heure de fin du spectacle
        $heureActuelle = $this->ajouter_duree($pdo, $heureActuelle, $dureeEntreSpectacles);
        $data["scene"] = $this->attribuerScene($pdo, $data, $idFestival);
        $jour[$compteurJour][$compteurNumeroSpectacle] = $data; // Affectation du spectacle correspondant au jour correspondant

        return $heureActuelle;
    }

    private function initialiserNouveauJour(PDO $pdo, $data, $idFestival, $dureeEntreSpectacles, &$jour, $compteurJour, $compteurNumeroSpectacle): ?string
    {
        $heureActuelle = $data["heureDebut"]; // Affectation à heure actuelle l'heure de début d'une journée du festival
        $data["heureDebutSpectacle"] = $heureActuelle;
        $heureActuelle = $this->ajouter_duree($pdo, $heureActuelle, $data["dureeSpectacle"]);
        $data["heureFinSpectacle"] = $heureActuelle; // Création d'une valeur heure de fin du spectacle
        $heureActuelle = $this->ajouter_duree($pdo, $heureActuelle, $dureeEntreSpectacles);
        $data["scene"] = $this->attribuerScene($pdo, $data, $idFestival);
        $jour[$compteurJour][$compteurNumeroSpectacle] = $data; // Affectation du spectacle correspondant au jour correspondant

        return $heureActuelle;
    }

    private function attribuerScene(PDO $pdo, $data, $idFestival) {
        $listeScenes = $this->getScenes($pdo, $idFestival);
        if(empty($listeScenes)) {
            return null;
        }
        foreach($listeScenes as $scene) {
            switch($scene["tailleScene"]) {
                case "petite":
                    $scene["tailleScene"] = 1;
                    break;
                case "moyenne":
                    $scene["tailleScene"] = 2;
                    break;
                case "grande":
                    $scene["tailleScene"] = 3;
                    break;
            }
        }
        

        // on vérifie que la scène a la taille requise pour le spectacle
        $tailleSceneOk = false;
        $sceneAleatoire = [];
        while(!$tailleSceneOk) {
            $cleAleatoire = array_rand($listeScenes);
            $sceneAleatoire = $listeScenes[$cleAleatoire];
            if($sceneAleatoire["tailleScene"] < $data["surfaceSceneRequise"]) {
                unset($listeScenes[$cleAleatoire]);
            } else {
                $tailleSceneOk = true;
            }
        }
        return $sceneAleatoire["nomScene"];
    }


    public function getSpectacles(PDO $pdo, $idFestival) : array
    {
        $retour = array();
        $sql = "SELECT titreSpectacle, descriptionSpectacle, dureeSpectacle, surfaceSceneRequise, nomImage, dateDebutFestival, dateFinFestival, heureDebut, heureFin, dureeMinimaleEntreDeuxSpectacles FROM spectacles
                INNER JOIN composer 
                ON composer.idSpectacle = spectacles.idSpectacle 
                INNER JOIN images
                ON spectacles.idImage = images.idImage
                INNER JOIN festivals
                ON festivals.idFestival = composer.idFestival
                INNER JOIN grilleJournaliere
                ON grilleJournaliere.idGriJ = festivals.idGriJ
                WHERE festivals.idFestival = :idFestival";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":idFestival", $idFestival);
        if($stmt->execute()) {
            foreach($stmt as $row) {
                $retour[] = $row;
            }
        }
        return $retour;
    }

    private function soustraireDuree(PDO $pdo, $duree1, $duree2) : ?string {
        $sql = "SELECT soustraireDuree(:duree1, :duree2)";
        $stmt =$pdo->prepare($sql);
        $stmt->bindParam(':duree1', $duree1);
        $stmt->bindParam(':duree2', $duree2);
        $stmt->execute();
        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result === false) {
                echo "Aucun résultat retourné après l'exécution de la requête.";
                return null;
            }
            return $result["soustraireDuree(?, ?)"];
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return null;
        }
    }

    private function ajouter_duree(PDO $pdo, $duree1, $duree2) : ?string {
        $sql = "SELECT ajouterDuree(:duree1, :duree2)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':duree1', $duree1);
        $stmt->bindParam(':duree2', $duree2);
    
        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result === false) {
                echo "Aucun résultat retourné après l'exécution de la requête.";
                return null;
            }
            return $result["ajouterDuree(?, ?)"];
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return null;
        }
    }    

    private function intToTime(int $valeurATransformer): string {
        $hours = floor($valeurATransformer / 60);
        $minutesLeft = $valeurATransformer % 60;

        // Ajout du zéro de remplissage si les heures ou les minutes sont inférieures à 10
        $hoursFormatted = ($hours < 10) ? '0' . $hours : $hours;
        $minutesFormatted = ($minutesLeft < 10) ? '0' . $minutesLeft : $minutesLeft;


        return $hoursFormatted . ":" . $minutesFormatted . ":00";
    }

    private function getScenes(PDO $pdo,  $idFestival) : array {
        $retour = array();
        $sql = "SELECT nomScene, tailleScene FROM accueillir
                INNER JOIN scenes
                ON scenes.idScene = accueillir.idScene
                WHERE accueillir.idFestival = :idFestival";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idFestival', $idFestival);
        if($stmt->execute()) {
            foreach($stmt as $row) {
                $retour[] = $row;
            }
        }
        return $retour;
    }

    private function calculeNbJourFestival(PDO $pdo, $idFestival)  {
        $retour = [];
        $sql = 'SELECT DATEDIFF(dateFinFestival, dateDebutFestival) FROM festivals WHERE idFestival=:idFestival';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam('idFestival', $idFestival);
        if($stmt->execute()) {
            foreach($stmt as $row) {
                $retour = $row['DATEDIFF(dateFinFestival, dateDebutFestival)'];
            }
        }
        return $retour;
    }

}
