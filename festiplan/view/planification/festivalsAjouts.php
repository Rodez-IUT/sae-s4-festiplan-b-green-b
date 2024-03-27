<?php

if (!isset($_SESSION["session_id"])
    || $_SESSION["session_id"] != session_id()) {
    header("Location: ?controller=Home");
    exit();
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ajouts</title>

    <link rel="stylesheet" href="../festiplan/other/css/style.css">

</head>
<body>

<?php
require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/header.php");
SetupHeadersAndDialog($titre, $controller, $open);
?>
<br><br><br>

<div class="container">
    <div class="row">
        <div class="col-12 fond-primary">
            <div class="col-12">&nbsp;</div>
            <div class="col-12">&nbsp;</div>

            <div class="col-12 text-center">
                <h2 class="text-center">
                    Ajouter un spectacle
                </h2>
            </div>
            <div class="col-12">&nbsp;</div>

            <div class="col-10 offset-1 row liste ajout-container">
            <?php

                foreach ($spectacles_tous as $spectacle) {

            ?>

                <div class="col-6 liste-item fond-background">
                    <?php echo $spectacle["titreSpectacle"] ?>
                </div>

                <?php
                if (!in_array($spectacle, $spectacles)) {
                ?>

                <form action="?" method="post" class="col-3 liste-item">
                    <input type="hidden" name="controller" value="FestivalAjouts">
                    <input type="hidden" name="action" value="ajouterSpectacle">
                    <input type="hidden" name="idFestival" value="<?php echo $idFestival ?>">
                    <input type="hidden" name="idSpectacle" value="<?php echo $spectacle["idSpectacle"] ?>">

                    <button type="submit" class="btn-valide col-12">
                        Ajouter
                    </button>
                </form>

                <?php
                } else {
                ?>

                <form action="?" method="post" class="col-3 offset-3 liste-item">
                    <input type="hidden" name="controller" value="FestivalAjouts">
                    <input type="hidden" name="action" value="retirerSpectacle">
                    <input type="hidden" name="idFestival" value="<?php echo $idFestival ?>">
                    <input type="hidden" name="idSpectacle" value="<?php echo $spectacle["idSpectacle"] ?>">

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
        </div>

        <div class="col-12">&nbsp;</div>
        <div class="col-12">&nbsp;</div>
        <div class="col-12 fond-primary">
            <div class="col-12">&nbsp;</div>
            <div class="col-12">&nbsp;</div>
            <div class="col-12 text-center">
                <h2 class="text-center">
                    Ajouter des membres à l'équipe organisatrice
                </h2>
            </div>
            <div class="col-12">&nbsp;</div>

            <div class="col-10 offset-1 row liste ajout-container">
                <?php
                    foreach ($membres_tous as $membre) {
                ?>

                        <div class="col-6 liste-item fond-background">
                            <?php echo $membre["nomUser"] . " " . $membre["prenomUser"] ?>
                        </div>

                        <?php
                        if (!in_array($membre, $membres)) {
                        ?>

                        <form action="?" method="post" class="col-3 liste-item">
                            <input type="hidden" name="controller" value="FestivalAjouts">
                            <input type="hidden" name="action" value="ajouterMembre">
                            <input type="hidden" name="idFestival" value="<?php echo $idFestival ?>">
                            <input type="hidden" name="idUser" value="<?php echo $membre["idUser"] ?>">

                            <button type="submit" class="btn-valide col-12">
                                Ajouter
                            </button>
                        </form>

                        <?php
                        } else {
                        ?>

                        <form action="?" method="post" class="col-3 offset-3 liste-item">
                            <input type="hidden" name="controller" value="FestivalAjouts">
                            <input type="hidden" name="action" value="retirerMembre">
                            <input type="hidden" name="idFestival" value="<?php echo $idFestival ?>">
                            <input type="hidden" name="idUser" value="<?php echo $membre["idUser"] ?>">

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
        </div>

        <div class="col-12">&nbsp;</div>
        <div class="col-12">&nbsp;</div>
        <div class="col-12 fond-primary">
            <div class="col-12">&nbsp;</div>
            <div class="col-12">&nbsp;</div>
            <div class="col-12">
                <h2 class="text-center">
                    Ajouter une scène
                </h2>
            </div>

            <div class="col-12">&nbsp;</div>

            <div class="col-10 offset-1 row liste ajout-container">
                <?php
                    foreach ($scenes_tous as $scene) {

                        ?>

                        <div class="col-6 liste-item fond-background">
                            <?php echo $scene["nomScene"] ?>
                        </div>

                        <?php
                        if (!in_array($scene, $scenes)) {

                        ?>

                        <form action="?" method="post" class="col-3 liste-item">
                            <input type="hidden" name="controller" value="FestivalAjouts">
                            <input type="hidden" name="action" value="ajouterScene">
                            <input type="hidden" name="idFestival" value="<?php echo $idFestival ?>">
                            <input type="hidden" name="idScene" value="<?php echo $scene["idScene"] ?>">

                            <button type="submit" class="btn-valide col-12">
                                Ajouter
                            </button>
                        </form>

                        <?php
                        } else {
                        ?>

                        <form action="?" method="post" class="col-3 offset-3 liste-item">
                            <input type="hidden" name="controller" value="FestivalAjouts">
                            <input type="hidden" name="action" value="retirerScene">
                            <input type="hidden" name="idFestival" value="<?php echo $idFestival ?>">
                            <input type="hidden" name="idScene" value="<?php echo $scene["idScene"] ?>">

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

        </div>


    </div>
</div>

<br><br><br>

<?php
require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/footer.php");
?>

</body>
</html>