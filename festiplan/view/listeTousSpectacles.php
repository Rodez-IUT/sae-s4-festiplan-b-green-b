<?php

if (!isset($_SESSION["session_id"])
    || $_SESSION["session_id"] != session_id()) {
    header("Location: ?controller=Authentification");
    exit();
}
if(!$_SESSION["organisateur"]){
    header("Location: ?controller=ListeSpectacle");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste de tous les spectacles</title>

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

                <form class="col-10 liste-item fond-blanc" method="post" action="?">
                    <input type="hidden" name="controller" value="InfoSpectacle">
                    <input type="hidden" name="idSpectacle" value="<?php echo $spectacle["idSpectacle"] ?>">
                    <button name="action" value="index"
                            type="submit" class="col-12 btn-light btn">
                    <?php echo $spectacle["titreSpectacle"] ?>
                    </button>
                </form>

                <?php if ($spectacle["idResponsableSpectacle"] == $idUtilisateur) {?>
                <form class='col-2 liste-icon text-center'>
                    <input type="hidden" name="controller" value="GestionSpectacle">
                    <input type="hidden" name="idSpectacle" value="<?php echo $spectacle["idSpectacle"] ?>">
                    <button name="action" value="modifier"
                            type="submit" class="col-6">
                        <span class="fa fa-pen-to-square"></span>
                    </button>
                    <button name="action" value="supprimer"
                            type="submit" class="col-6">
                        <span class="fa fa-trash-can"></span>
                    </button>
                </form>
                <?php } ?>

                <div class="col-12">&nbsp;</div>

                <?php
                    }
                }
                ?>
            
                <a href="?controller=CreationSpectacle" class="col-3 offset-9">
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