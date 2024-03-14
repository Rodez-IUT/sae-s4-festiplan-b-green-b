<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Spectacles ajouts</title>

    <link rel="stylesheet" href="../festiplan/other/css/style.css">

</head>
<body>
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/header.php");
SetupHeadersAndDialog($titre, $controller, $open);
?>



<div class="container">
    <div class="row">

        <div class="col-12">&nbsp;</div>
        <div class="col-12">&nbsp;</div>
        <div class="col-12 fond-primary">
            <div class="col-12">&nbsp;</div>
            <div class="col-12">&nbsp;</div>

            <div class="col-12">
                <h2 class="text-center">
                    Créer un intervenant
                </h2>
            </div>

            <div class="col-12">&nbsp;</div>

            <div class="col-10 offset-1 row liste">
                <form action="?" method="post" class="col-6 row">
                    <input type="hidden" name="controller" value="SpectacleAjouts">
                    <input type="hidden" name="action" value="<?php echo $action ?>">
                    <h4 class="col-12 text-center">
                        Ajouter manuellement
                    </h4>
                    <div class="col-12">&nbsp;</div>
                    <input type="hidden" name="controller" value="SpectacleAjouts">
                    <input type="hidden" name="action" value="<?php echo $action ?>">
                    <input type="hidden" name="idCreateur" value="<?php echo $_SESSION["user_id"] ?>">
                    <input type="hidden" name="idSpectacle" value="<?php echo $idSpectacle ?>">
                    <input type="hidden" name="idIntervenant" value="<?php echo isset($valeurIntervenant) ? $valeurIntervenant["idIntervenant"] : 0 ?>">

                    <label class="col-12" for="nom">
                        Nom :
                    </label>
                    <input type="text" name="nom" class="col-12" <?php if ($valeurIntervenant!=null) {
                        echo 'value="'.$valeurIntervenant["nomIntervenant"] .'"';
                    } ?>>
                    <div class="col-12">&nbsp;</div>
                    <label class="col-12" for="prenom">
                        Prénom :
                    </label>
                    <input type="text" name="prenom" class="col-12" <?php if ($valeurIntervenant!=null) {
                        echo 'value="'.$valeurIntervenant["prenomIntervenant"] .'"';
                    } ?>>
                    <div class="col-12">&nbsp;</div>
                    <label class="col-12" for="mail">
                        Mail :
                    </label>
                    <input type="email" name="mail" class="col-12" <?php if ($valeurIntervenant!=null) {
                        echo 'value="'.$valeurIntervenant["emailIntervenant"] .'"';
                    } ?>>

                    <div class="col-12">&nbsp;</div>

                    <div class="inputs">
                        <label for="estSurScene">
                            Est sur scène :
                        </label>
                        <input type="checkbox" name="estSurScene" value="1"
                            <?php if($valeurIntervenant!=null) {
                                if ($valeurIntervenant["estSurScene"] == 1) {
                                    echo "checked";
                                }
                            }
                        ?>>
                    </div>


                    <div class="col-12">&nbsp;</div>
                    <button type="submit" class="btn-creer col-3">
                        <?php echo $texte_button ?>
                    </button>
                </form>

                <form class="col-6" enctype="multipart/form-data" method="post">
                    <h4 class="col-12 text-center">
                        Ajouter à partir d'un fichier .csv
                    </h4>
                    <div class="col-12">&nbsp;</div>
                    <div class="col-12">&nbsp;</div>
                    <input type="hidden" name="controller" value="SpectacleAjouts">
                    <input type="hidden" name="action" value="fromCSVFile">
                    <input type="hidden" name="idCreateur" value="<?php echo $_SESSION["user_id"] ?>">
                    <input type="hidden" name="idSpectacle" value="<?php echo $idSpectacle ?>">


                    <label class="col-6 offset-2" for="fichier">
                        Fichier :
                    </label>
                    <div class="col-12">&nbsp;</div>
                    <div class="col-10 offset-2">
                        les noms des colonnes doivent être :
                        <br> nom | prenom | mail | estSurScene(0 ou 1)
                    </div>
                    <?php
                    if (isset($file_erreor)) {
                        echo "<div class='col-12'>&nbsp;</div>";
                        echo "<div class='col-10 offset-2'>";
                        echo $file_erreor;
                        echo "</div>";
                    } else {
                        echo "<div class='col-12'>&nbsp;</div>";
                        echo "<div class='col-12'>&nbsp;</div>";
                    }


                    ?>

                    <div class="col-12">&nbsp;</div>
                    <input type="file" name="fichier" class="col-10 offset-2" accept="text/csv">
                    <div class="col-12">&nbsp;</div>
                    <div class="col-12">&nbsp;</div>
                    <button type="submit" class="btn-creer col-3 offset-2">
                        Créer
                    </button>
                </form>

            </div>
            <div class="col-12">&nbsp;</div>
            <div class="col-12">&nbsp;</div>

            <div class="col-12">
                <h2 class="text-center">
                    Ajouter un intervenant existant
                </h2>
            </div>

            <div class="col-8 offset-2">
                <div class="col-12">&nbsp;</div>
                <form action="">
                    <input type="hidden" name="controller" value="SpectacleAjouts">
                    <input type="hidden" name="action" value="recherche">
                    <input type="hidden" name="idSpectacle" value="<?php echo $idSpectacle ?>">
                    <input type="hidden" name="idCreateur" value="<?php echo $_SESSION["user_id"] ?>">
                    <input type="hidden" name="idSpectacle" value="<?php echo $idSpectacle ?>">

                    <input type="text" name="recherche" class="col-7 offset-1"
                            value="<?php echo isset($recherche) ? $recherche : "" ?>">

                    <button type="submit" class="btn btn-light col-2 offset-1">
                        Rechercher
                    </button>
                </form>
                <div class="col-12">&nbsp;</div>
                <div class="col-12">&nbsp;</div>

            </div>



            <div class="col-10 offset-1 row liste ajout-container">
                <?php

                echo "<div class='col-12'>&nbsp;</div>";

                echo "<br><br>";
                foreach ($liste_intervenant as $intervenant) {

                    ?>

                    <div class="col-8 liste-item  fond-background">
                        <div class="col-12 row ">
                            <div class="col-8 color-primary">
                                <?php echo $intervenant["nomIntervenant"] . " " . $intervenant["prenomIntervenant"];
                                if ($intervenant["estSurScene"]== 1){
                                    echo "  (sur scène)";
                                } else {
                                    echo "  (hors scène)";
                                }?>
                            </div>
                            <form action="?" method="post" class="col-2 liste-item">
                                <?php
                                if ($intervenant["idCreateur"] == $_SESSION["user_id"]) {
                                    ?>
                                    <input type="hidden" name="controller" value="SpectacleAjouts">
                                    <input type="hidden" name="action" value="modifierIntervenant">
                                    <input type="hidden" name="idIntervenant" value="<?php echo $intervenant["idIntervenant"] ?>">
                                    <input type="hidden" name="idSpectacle" value="<?php echo $idSpectacle ?>">
                                    <button type="submit" class="btn-light col-12">
                                        <span class="fa fa-pen-to-square"></span>
                                    </button>
                                    <?php
                                }
                                ?>
                        </form>
                            <form action="?" method="post" class="col-2 liste-item">
                            <?php
                            if ($intervenant["idCreateur"] == $_SESSION["user_id"]) {
                                ?>
                                <input type="hidden" name="controller" value="SpectacleAjouts">
                                <input type="hidden" name="action" value="supprimerIntervenant">
                                <input type="hidden" name="idIntervenant" value="<?php echo $intervenant["idIntervenant"] ?>">
                                <input type="hidden" name="idSpectacle" value="<?php echo $idSpectacle ?>">
                                <button type="submit" class="btn-supprimer col-12">
                                    <span class="fa fa-trash-can"></span>
                                </button>
                                <?php
                            }
                            ?>
                            </div>
                    </form>
                    </div>

                    <?php
                    if (!in_array($intervenant["idIntervenant"], $intervenants_present)) {
                    ?>

                    <form action="?" method="post" class="col-2 liste-item">
                        <input type="hidden" name="controller" value="SpectacleAjouts">
                        <input type="hidden" name="action" value="ajouterIntervenant">
                        <input type="hidden" name="idSpectacle" value="<?php echo $idSpectacle ?>">
                        <input type="hidden" name="idIntervenant" value="<?php echo $intervenant["idIntervenant"] ?>">
                        <button type="submit" class="btn-valide col-12">
                            Ajouter
                        </button>
                    </form>

                    <?php
                    } else {
                    ?>
                    <form action="?" method="post" class="col-2 offset-2 liste-item">
                        <input type="hidden" name="controller" value="SpectacleAjouts">
                        <input type="hidden" name="action" value="retirerIntervenant">
                        <input type="hidden" name="idSpectacle" value="<?php echo $idSpectacle ?>">
                        <input type="hidden" name="idIntervenant" value="<?php echo $intervenant["idIntervenant"] ?>">
                        <button type="submit" class="btn-invalide col-12">
                            Retirer
                        </button>
                    </form>
                    <?php
                    }
                    ?>


                    <div class="col-12">&nbsp;</div>

                <?php
                }
                ?>
            </div>
            <div class="col-12">&nbsp;</div>

        </div>

    </div>
</div>

<br><br><br>

<?php
require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/footer.php");
?>

</body>
</html>