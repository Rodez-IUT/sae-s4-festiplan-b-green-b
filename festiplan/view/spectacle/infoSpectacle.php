<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Festiplan</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../festiplan/other/css/style.css">

    </head>
    <body>



        <?php
            require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/header.php");
            SetupHeadersAndDialog($titre, $controller, $open);
        ?>

        <br>

        <div class="container">
            <div class="row">
                <div class="col-12 fond-primary row">
                    <div class="col-12">&nbsp;</div>
                    <div class="col-5">
                        <div class="col-12">
                            <img class="col-12 img-spectacle"
                                 src="../festiplan/stockage/images/<?php echo $searchStmt['image_name']; ?>"
                                 alt="<?php echo $searchStmt["titreSpectacle"]; ?>" class="">
                        </div>
                        <div class="col-12">&nbsp;</div>
                        <div class="col-12 fond-background">
                            <div class="col-12">&nbsp;</div>
                            <div class="col-12 row">
                                <div class="col-5">
                                    <h4 class="fond-background">Catégories :</h4>
                                </div>
                                <div class="col-7 fond-primary">
                                    <?php
                                    foreach ($searchStmt["categories"] as $cat) {
                                        echo $cat . " ";
                                    }
                                    ?>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-5">
                                    <h4 class="fond-background">Duree :</h4>
                                </div>
                                <div class="col-7 fond-primary">
                                    <?php echo (int)($searchStmt["dureeSpectacle"]/60);?> heures
                                    <?php echo $searchStmt["dureeSpectacle"]%60;?> minutes
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-5">
                                    <h4 class="fond-background">Taille scène :</h4>
                                </div>
                                <div class="col-7 fond-primary">
                                    <?php echo $searchStmt["tailleScene"];?>
                                </div>
                                <div class="col-12">&nbsp;</div>
                                <div class="col-6">
                                    <h4 class="fond-background">Responsable :</h4>
                                </div>
                                <div class="col-6 fond-primary">
                                    <?php echo $searchStmt["responsable"]["nomUser"] . " " . $searchStmt["responsable"]["prenomUser"]; ?>
                                </div>

                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <div class="col-12">&nbsp;</div>
                    </div>
                    <div class="col-7 row">
                        <div class="col-12">&nbsp;</div>
                        <div class="col-11 offset-1 text-center fond-background">
                            <h1> <?php echo $searchStmt["titreSpectacle"]; ?> </h1>
                        </div>
                        <div class="col-12">&nbsp;</div>
                        <div class="col-11 offset-1 fond-background">
                            <h4>Description :</h4>
                            <span class="description fond-background">
                                <?php echo $searchStmt["descriptionSpectacle"]; ?>
                            </span>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <div class="col-12">&nbsp;</div>
                        <div class="col-12">&nbsp;</div>
                        <div class="col-12">&nbsp;</div>
                        <div class="col-5 offset-1 fond-background">
                            <div class="col-12">&nbsp;</div>
                            <div class="col-12 text-center">
                                <div class="col-12 fond-primary">
                                    <h6>Intervenant (sur Scene) :</h6>
                                </div>
                            </div>
                            <div class="col-12 liste-item">
                                <div class="input-container">
                                    <?php if (isset($searchStmt["intervenantScene"]["nomPrenom"])) {
                                        foreach ($searchStmt["intervenantScene"]["nomPrenom"] as $intervenant) {?>
                                            <span class="fond-background inputs">
                                                <?php echo $intervenant["nomIntervenant"] . " " . $intervenant["prenomIntervenant"] . "<br>";?>
                                            </span>
                                            <?php
                                        }
                                    }
                                        ?>
                                </div>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        <div class="col-5 offset-1 fond-background">
                            <div class="col-12">&nbsp;</div>
                            <div class="col-12 text-center">
                                <div class="col-12 fond-primary">
                                    <h6>Intervenant (hors Scene) :</h6>
                                </div>

                            </div>
                            <div class="col-12 liste-item">
                                <div class="input-container">
                                    <?php if (isset($searchStmt["intervenantHors"]["nomPrenom"])){
                                     foreach ($searchStmt["intervenantHors"]["nomPrenom"] as $intervenant) {?>
                                         <span class="fond-background inputs">
                                        <?php echo $intervenant["nomIntervenant"] . " " . $intervenant["prenomIntervenant"] . "<br> ";?>
                                        </span>
                                        <?php
                                        }
                                    }
                                        ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>

        <br><br>

        <?php require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/footer.php"); ?>

    </body>
</html>
