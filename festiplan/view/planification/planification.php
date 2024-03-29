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
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Planification</title>

    <link rel="stylesheet" href="../festiplan/other/css/style.css">

</head>
<body>

<?php
require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/header.php");
SetupHeadersAndDialog($titre, $controller, $open);
?>

    <main class="container-fluid">
        <div class="row">
            <?php
            $compteurJour = 1;
            if (isset($searchStmt) && !empty($searchStmt)) {
                foreach($searchStmt as $jour => $spectaclesDuJour) {
                    echo '<div class="col-12"> &nbsp;</div>';
                    echo '<div class="col-12"><h1 class="center primary">Jour '.$compteurJour .'</h1></div>';
                    echo '<div class="col-12"> &nbsp;</div>';
                    $compteurJour++;

                    foreach ($spectaclesDuJour as $spectacle) {
                        ?>
                        <div class="col-5 offset-1 row bordure fond-primary">
                            <div class="col-12">
                                <h2 class="center"><?php echo $spectacle["titreSpectacle"] ?></h2>
                            </div>
                            <?php
                            $image_name = $spectacle['nomImage'];
                            ?>
                            <div class="col-6">
                                <img class="img-fest"
                                    src="../festiplan/stockage/images/<?php echo $image_name; ?>"
                                    alt="<?php echo $spectacle["titreSpectacle"]; ?>"
                                >
                            </div>
                            <div class="col-5 offset-1">
                                duree : <?php echo $spectacle["dureeSpectacle"] ?>
                                <br/><br/>
                                <?php echo $spectacle["heureDebutSpectacle"].' / '.$spectacle["heureFinSpectacle"]; ?>
                                <br/><br/>
                                <?php echo $spectacle["scene"]; ?>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
    </main>

    <br><br>

        <?php require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/footer.php"); ?>

</body>
</html>