<?php

if (!isset($_SESSION["session_id"])
    || $_SESSION["session_id"] != session_id()) {
    header("Location: ?controller=Authentification");
    exit();
}
$_SESSION["organisateur"] = $organisateur;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des spectacles</title>

    <link rel="stylesheet" href="../festiplan/other/css/style.css">

</head>
<body>
    
    <?php
        require("header.php");
        SetupHeadersAndDialog($titre, $controller, $open);
    ?>

    <br><br><br>
    <div class="container">

        <div class="row">
            
            <div class="col-8 offset-2 row liste fond-primary">


                <?php
                if (!isset($liste_spectacles) || empty($liste_spectacles)) {
                    echo "<div class='col-12 text-center fond-background'>Aucun spectacle</div>";
                    echo "<div class='col-12'>&nbsp;</div>";
                } else {
                    foreach ($liste_spectacles as $spectacle) {
                ?>

                <div class="col-9 liste-item fond-background nom">
                    <?php echo $spectacle["titreSpectacle"] ?>
                </div>

                <form class='col-1 text-center'>
                    <input type="hidden" name="controller" value="SpectacleAjouts">
                    <input type="hidden" name="idSpectacle" value="<?php echo $spectacle["idSpectacle"] ?>">
                    <button type="submit" class="col-6">
                        <span class="fa fa-plus"></span>
                    </button>
                </form>

                <form class='col-1 text-center'>
                    <input type="hidden" name="controller" value="CreationSpectacle">
                    <input type="hidden" name="idSpectacle" value="<?php echo $spectacle["idSpectacle"] ?>">
                    <button name="action" value="modifier"
                            type="submit" class="col-6">
                        <span class="fa fa-pen-to-square"></span>
                    </button>
                </form>

                <form class="col-1 text-center" action="">
                    <input type="hidden" name="controller" value="GestionSpectacles">
                    <input type="hidden" name="idSpectacle" value="<?php echo $spectacle["idSpectacle"] ?>">
                    <button name="action" value="confirmationSuppression"
                            type="submit" class="col-6">
                        <span class="fa fa-trash-can"></span>
                    </button>

                </form>

                <div class="col-12">&nbsp;</div>

                <?php
                    }
                }

                if ($_SESSION["organisateur"]) {
                ?>

                <a href="?controller=ListeTousSpectacles" class="col-4 ">
                    <button class="col-12 btn-valide">
                        Tous les spectacles
                    </button>
                </a>
                    <a href="?controller=CreationSpectacle" class="col-3 offset-5">
                <?php } else { ?>
                     <a href="?controller=CreationSpectacle" class="col-3 offset-9">
                <?php } ?>
                    <button class="col-12 btn-creer">
                        Créer    
                    </button>
                </a>

            </div>

            
        </div>
        

    </div>
    <br/><br/>
    <?php require("footer.php"); ?>

</body>
</html>