<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Suppression festival </title>
        <link rel="stylesheet" href="../festiplan/other/css/messageConfirmation.css">
        <link rel="stylesheet" href="../festiplan/lib/bootstrap-5.3.2-dist/css/bootstrap.css">
        <link rel="stylesheet" href="../festiplan/other/css/style.css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 message">
                    <h1>
                        <?php
                            if (isset($message)) {
                                echo $message;
                            } else {
                                echo "Une erreur est survenue, veuillez réessayer plus tard";
                                ?>
                                <br>
                                <br>
                                <a href="?controller=Home">
                                    Revenir à l'accueil
                                </a>
                        <?php
                            }
                        ?>
                    </h1>
                </div>
            </div>

            <?php
            if (isset($controllerRetour)) {
                $controllerRetour = htmlspecialchars($controllerRetour);
            } else {
                $controllerRetour = "Home";
            }

            if (isset($actionRetour)) {
                $actionRetour = htmlspecialchars($actionRetour);
            } else {
                $actionRetour = "index";
            }

            if (isset($controllerValider)) {
                $controllerValider = htmlspecialchars($controllerValider);
            } else {
                $controllerValider = "Home";
            }

            if (isset($actionValider)) {
                $actionValider = htmlspecialchars($actionValider);
            } else {
                $actionValider = "index";
            }

            if (isset($id)) {
                $id = htmlspecialchars($id);
            } else {
                $id = "";
            }

            ?>


            <div class="row">
                <form class="col-4 offset-2" method="post" action="?">
                    <input type="hidden" name="controller" value="<?php echo $controllerValider ?>">
                    <input type="hidden" name="action" value="<?php echo $actionValider ?>">
                    <input type="hidden" name="id" value="<?php echo $id ?>">

                    <button type="submit" class="btn-supprimer ">
                        Supprimer
                    </button>
                </form>

                <form class="col-4 offset-2" method="post" action="?">
                    <input type="hidden" name="controller" value="<?php echo $controllerRetour?>">
                    <input type="hidden" name="action" value="<?php echo $actionRetour ?>">

                    <button type="submit" class="btn-annuler">
                        Annuler
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>