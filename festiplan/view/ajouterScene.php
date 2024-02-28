<?php

$_SESSION = array();

if (isset($user_ok) && $user_ok) {
    $_SESSION = array();
    $_SESSION["session_id"] = session_id();
    $_SESSION["user_id"] = $user_id;
    $_SESSION["user_nom"] = $user_nom;
    $_SESSION["user_prenom"] = $user_prenom;
    header("Location: ?controller=Home");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr²">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../festiplan/other/css/style.css">
    
    <title>Ajouter scène</title>
</head>
<body>

<dialog <?php
if (isset($open)) {
    echo $open;
}
?> id="menu_compte" class="fond-primary">
    <br>

    <?php
    if (isset($_SESSION["user_id"])) {
        ?>
        <div class="container">

            <div class="row col-12">

                <img class="col-5" src="../festiplan/ressources/images/user.png" alt="">

                <div class="col-5">
                    <?php echo isset($_SESSION["user_nom"]) ? $_SESSION["user_nom"] : "non" ?>
                    <br>
                    <?php echo isset($_SESSION["user_prenom"]) ? $_SESSION["user_prenom"] : "connecte" ?>
                </div>

                <form class="col-2" method="post" action="">
                    <input hidden name="controller" value="Home">
                    <input hidden name="action" value="index">

                    <button class="col-12" type="submit">
                        <span class="fa fa-xmark"></span>
                    </button>
                </form>

            </div>

            <div class="col-12"><!--spacing-->&nbsp;</div>

            <hr>

            <div class="row">
                <div class="col-12">
                    <a href="" class="col-12">
                        <button class="btn btn-light col-12">
                            <span class="fa fa-circle-info">&nbsp;</span>
                            Mes informations
                        </button>
                    </a>
                </div>

                <div class="col-12"><!--spacing-->&nbsp;</div>

                <div class="col-12">
                    <a href="" class="col-12">
                        <button class="btn btn-light col-12">
                            <span class="fa fa-pen-to-square">&nbsp;</span>
                            Modifier mes informations
                        </button>
                    </a>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12">
                    <a href="?controller=ListeFestival&id=<?php
                    if (isset($_SESSION["user_id"])) {
                        echo $_SESSION['user_id'];
                    }
                    ?>">
                        <button class="btn btn-light col-12">
                            <span class="fa fa-tent">&nbsp;</span>
                            Mes festivals
                        </button>
                    </a>
                </div>

                <div class="col-12"><!--spacing-->&nbsp;</div>


                <div class="col-12">
                    <a href="?controller=ListeSpectacle&id=<?php
                    if (isset($_SESSION["user_id"])) {
                        echo $_SESSION['user_id'];
                    }
                    ?>">
                        <button class="btn btn-light col-12">
                            <span class="fa fa-masks-theater">&nbsp;</span>
                            Mes spectacles
                        </button>
                    </a>
                </div>
            </div>

            <hr>

            <div class="row">

                <a class="col-12 text-center"
                   href="?controller=Authentification&action=deconnexion">
                    <button class="btn btn-light col-12">
                        <span class="fa fa-arrow-right-to-bracket">&nbsp;</span>
                        Déconnexion
                    </button>
                </a>

                <div class="col-12"><!--spacing-->&nbsp;</div>

                <a class="col-12 text-center"
                   href="?controller=Authentification&action=confirmSuppression&id=<?php echo isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 0?> ">
                    <button class="col-12 btn btn-danger">
                        <span class="fa fa-trash-can">&nbsp;</span>
                        Supprimer mon compte
                    </button>
                </a>

            </div>

        </div>

        <?php
    } else {

        ?>

        <div class="container">
            <div class="col-12">&nbsp;</div>

            <div class="col-12 text-center">
                <a class="col-12 text-center"
                   href="?controller=Authentification&action=deconnexion">
                    <button class="col-12">
                        Se connecter
                    </button>
                </a>
            </div>

            <div class="col-12">&nbsp;</div>

            <div class="col-12 text-center">
                <a class="col-12 text-center"
                   href="?controller=CreationCompte">
                    <button class="col-12">
                        S'inscrire
                    </button>
                </a>
            </div>
        </div>

        <?php
    }
    ?>

</dialog>

<header class="container-fluid">
    <div class="row">
        <div class="col-4 row">
            <div class="col-5">
                <a href="/?controller=Home">
                    <img src ="../festiplan/ressources/images/logo.png" alt="logo" id="logo">
                </a>
            </div>
            <div class="col-3"></div>

        </div>

        <div class="col-4 text-center">
            <div class="col-12">&nbsp;</div>
            <h1>
                Ajout d'une scène
            </h1>

        </div>
        <div class="col-4 row">
            <form class="col-2 offset-10" method="post" action="">
                <input hidden name="controller" value="Home">
                <input hidden name="action" value="showMenu">

                <button type="submit" class="normal">
                    <img src="../festiplan/ressources/images/user_profil.png"
                         alt="icone utilisateur"
                         id="iconeMenu">

                </button>
            </form>

        </div>

    </div>
</header>

    <br><br><br>
    <div class="container">
        <div class="row">

            <form method="post" 
                action="?controller=CreationCompte&action=createAccount"
                class="col-md-6 offset-md-3
                        col-sm-8 offset-sm-2
                        col-10 offset-1 fond-primary">

                <!-- affichage des champs -->
                <div class="row">
                    <div class="col-12">&nbsp;</div>

                    <div class="col-12 row">
                        <label for="nom" class="col-3" id="labelNom">
                            Nom Scene:
                        </label>
                        <input required class="col-9"
                            type="text" placeholder="Entrez le nom"
                            value=""
                            name="nom" id="inputNom">
                    </div>
    
                    <div class="col-12">&nbsp;</div>

                    <div class="col-12 row">
                        <label for="tailleScene"  class="col-4">
                            Taille de la scène:
                        </label>
                        <select class="col-8" name="tailleScene">
                            <option value="petite">Petite</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="grande">Grande</option>
                        </select>
                    </div>
                    <div class="col-12">&nbsp;</div>
                    <div class="col-12 row">
                        <label for="nbSpec" class="col-7" id="labelNbSpec">
                            Nombre de spectateur Maximum:
                        </label>
                        <input required class="col-5"
                            type="text" placeholder="Ex: 1000" 
                            value="" 
                            name="nbSpec" id="inputNbSpec">
                    </div>

                    <div class="col-12">&nbsp;</div>

                    <div class="col-12">
                            Coordonnées de la scène
                    </div>
                    <div class="col-12 row">
                        <div class="col-6">
                            <label for="coord" class="col-2" id="labelcoordLat">
                                LAT
                            </label>
                            <input required class="col-6"
                                   type="text" placeholder="Ex: 43.548003"
                                   value=""
                                   name="coord" id="inputCoordLat">

                        </div>
                         <div class="col-6">
                             <label for="coord" class="col-2" id="labelcoordLon">
                                LON
                            </label>
                            <input required class="col-9"
                                   type="text" placeholder="Ex: 1.485676"
                                   value=""
                                   name="coord" id="inputCoordLon">
                        </div>
                    </div>
                    <div class="col-12">&nbsp;</div>
                    <div class="col-4 offset-1">
                        <button class="btn-annuler col-12">
                            Annuler
                        </button>
                    </div>

                    <div class="col-4 offset-2">
                        <button class="btn-creer col-12"
                            type="submit" id="boutonValider">
                            Ajouter
                        </button>
                    </div>
                    <div class="col-12">&nbsp;</div>

                </div>

            </form>
        </div>
    </div>
    <div class="col-12">&nbsp;</div>

    
    <?php require("footer.php"); ?>

</body>
</html>