<?php
    if(!isset($_SESSION["session_id"]) || $_SESSION["session_id"] != session_id()) {
        header('Location: ?controller=Authentification');
        exit();
    }

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Création d'une contrainte journaliere</title>
        <link rel="stylesheet" href="../other/css/style.css">
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

                        <img class="col-5" src="../ressources/images/user.png" alt="">

                        <div class="col-5">
                            <?php echo isset($_SESSION["user_nom"]) ? $_SESSION["user_nom"] : "" ?>
                            <br>
                            <?php echo isset($_SESSION["user_prenom"]) ? $_SESSION["user_prenom"] : "" ?>
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
                                    <span class="icon fa fa-circle-info">&nbsp;</span>
                                    Mes informations
                                </button>
                            </a>
                        </div>

                        <div class="col-12"><!--spacing-->&nbsp;</div>

                        <div class="col-12">
                            <a href="" class="col-12">
                                <button class="btn btn-light col-12">
                                    <span class="icon fa fa-pen-to-square">&nbsp;</span>
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
                                    <span class="icon fa fa-tent">&nbsp;</span>
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
                                    <span class="icon fa fa-masks-theater">&nbsp;</span>
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
                                <span class="icon fa fa-arrow-right-to-bracket">&nbsp;</span>
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
                                <img src ="../ressources/images/logo.png" alt="logo" id="logo">
                            </a>
                        </div>
                        <div class="col-3"></div>

                    </div>

                    <div class="col-4 text-center">
                        <h1>
                            Création grille de contraintes
                        </h1>

                    </div>
                    <div class="col-4 row">
                        <form class="col-2 offset-10" method="post" action="">
                            <input hidden name="controller" value="Home">
                            <input hidden name="action" value="showMenu">

                            <button type="submit" class="normal">
                                <img src="../ressources/images/user_profil.png"
                                    alt="icone utilisateur"
                                    id="iconeMenu">

                            </button>
                        </form>

                    </div>
                    
                </div>            
            </header>

        <br><br>
        <div class="container">
            <div class="row">

                <div class="col-3">&nbsp;</div>

                <form class="col-6 bordure fond-primary" method="post" action="?">
                    <input type="hidden" name="controller" value="CreationGriJ">
                    <input type="hidden" name = "action" value="insertGriJ">


                    <div class="row">
                        <!-- spacing -->
                        <div class="col-12">&nbsp;</div>
                        <div class="col-12">&nbsp;</div>

                        <!-- champ heure début -->
                        <div class="col-10 offset-1">
                            <label for="heureDebut" id="labelHeureDebut" 
                                   class="<?php echo $liste_classes["heureDebut"]?>">
                                Heure de début :
                            </label>
                            <input required type="time" name="heureDebut" id="inputHeureDebut" 
                                   value="<?php echo $liste_valeurs["heureDebut"]?>">
                        </div>

                        <!-- spacing -->
                        <div class="col-12">&nbsp;</div>

                        <!-- champ heure fin -->
                        <div class="col-10 offset-1">
                            <label for="heureFin" id="labelHeureFin"
                                   class="<?php echo $liste_classes["heureFin"]?>">
                                Heure de fin :
                            </label>
                            <input required type="time" name="heureFin" id="inputHeureFin"
                                   value="<?php echo $liste_valeurs["heureFin"]?>">
                        </div>

                        <!-- spacing -->
                        <div class="col-12">&nbsp;</div>

                        <!-- champ de durée du spectacle -->
                        <div class="col-10 offset-1">
                            <label for="duree" id="labelDuree" 
                                   class="<?php echo $liste_classes["duree"]?>">
                                Durée entre deux spectacles :
                            </label>
                            <input required
                                type="number" placeholder="Temps entre deux spectacles en minutes" 
                                name="duree" id="dureeEntreSpectacles"
                                value="<?php echo $liste_valeurs["duree"]?>">
                        </div>

                        <!-- spacing -->
                        <div class="col-12">&nbsp;</div>
                        <div class="col-12">&nbsp;</div>

                        <!-- bouton annuler -->
                        <button class="btn-annuler col-3 offset-2">
                            Annuler
                        </button>

                        <!-- bouton valider -->
                        <button type="submit" class="btn-creer col-3 offset-2" id="boutonValider">
                            Créer
                        </button>

                        <!-- spacing -->
                        <div class="col-12">&nbsp;</div>
                    </div> 
                </form>  
            </div>
        </div>

        <br><br>


        <footer class="container-fluid">
            <div class="row">
                <div class="col-12">&nbsp;</div>
                <div class="col-12">
                    <h6> Copyright © 2023 Denamiel Clément / Roma Rafaël / Veyre Antonin / Vignals Lohan All rights reserved.</h6>
                </div>
            </div>
        </footer>


        <script src="../other/js/contraintesGrilles.js" defer></script>

    </body>
</html>