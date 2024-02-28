<?php

if (!isset($_SESSION["session_id"])
    || $_SESSION["session_id"] != session_id()) {
    header("Location: ?controller=Home");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Information Compte</title>

    <link rel="stylesheet" href="../festiplan/other/css/style.css">

</head>
<body>

    <?php
        require("header.php");
        SetupHeadersAndDialog($titre, $controller, $open);
    ?>

    <br><br><br><br><br>

    <!-- Contenu de la page -->
    <div class="container">
        <div class="row">

            <div class = "col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1 fond-primary">

                <div class="row">
                    <div class="col-12">&nbsp;</div>

                    <div class="col-12 offset-1">
                        <div class = "row" >
                            <div class = "col-3">
                                <h5>
                                    <u><b>Identifiant</b></u>
                                </h5>
                            </div>
                            <div class = "col-7 fond-background">
                                <?php echo isset($identifiant) ? $identifiant : ""?>
                            </div>
                            <div class = "col-2">&nbsp;</div>
                        </div>
                    </div>

                    <div class="col-12">&nbsp;</div>

                    <div class="col-12 offset-1">
                        <div class = "row" >
                            <div class = "col-3">
                                <h5>
                                    <u><b>Nom</b></u>
                                </h5>
                            </div>
                            <div class = "col-7 fond-background">
                            <?php echo isset($nom) ? $nom : ""?>
                            </div>
                            <div class = "col-2">&nbsp;</div>
                        </div>
                    </div>

                    <div class="col-12">&nbsp;</div>

                    <div class="col-12 offset-1">
                        <div class = "row" >
                            <div class = "col-3">
                                <h5>
                                    <u><b>Prénom</b></u>
                                </h5>
                            </div>
                            <div class = "col-7 fond-background">
                                <?php if (isset($prenom)) { echo $prenom; }?>
                            </div>
                            <div class = "col-2">&nbsp;</div>
                        </div>
                    </div>

                    <div class="col-12">&nbsp;</div>

                    <div class="col-12 offset-1">
                        <div class = "row" >
                            <div class = "col-3">
                                <h5>
                                    <u><b>Email</b></u>
                                </h5>
                            </div>
                            <div class = "col-7 fond-background">
                            <?php if (isset($email)) { echo $email; }?>
                            </div>
                            <div class = "col-2">&nbsp;</div>
                        </div>
                    </div>

                </div>

                <div class="col-12">&nbsp;</div>

            </div>

            <div class="col-12">&nbsp;</div>

            <div class="col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-10 offset-1">
                <a href="?controller=ModifInfoPerso" class="col-12">
                    <button class="btn btn-dark col-12">
                        <span class="icon fa fa-pen-to-square">&nbsp;</span>
                        Modifier mes informations
                    </button>
                </a>
            </div>

            <div class="col-12">&nbsp;</div>

        </div>
    </div>

    <br><br><br><br><br>

    <?php require("footer.php"); ?>

</body>
</html>