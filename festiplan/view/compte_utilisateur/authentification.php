<?php
$_SESSION = array();

if (isset($user_ok) && $user_ok) {
    $_SESSION = array();
    $_SESSION["session_id"] = session_id();
    /** @noinspection PhpUndefinedVariableInspection */
    $_SESSION["user_id"] = $user_id;
    /** @noinspection PhpUndefinedVariableInspection */
    $_SESSION["user_nom"] = $user_nom;
    /** @noinspection PhpUndefinedVariableInspection */
    $_SESSION["user_prenom"] = $user_prenom;
    /** @noinspection PhpUndefinedVariableInspection */
    $_SESSION["organisateur"] = $organisateur;
    header("Location: ?controller=Home");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Authentification</title>
    
    <link rel="stylesheet" href="../festiplan/lib/bootstrap-5.3.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../festiplan/lib/fontawesome-free-6.2.1-web/css/all.css">
    
    <link rel="stylesheet" href="../festiplan/other/css/style.css">

</head>
<body>

    <header class="container-fluid">
        <div class="row">
            <div class="col-4 row">
            <div class="col-5">
                    <a href="?controller=Home">
                        <img src ="../festiplan/ressources/images/logo.png" alt="logo" id="logo">
                    </a>
                </div>
                <div class="col-3"></div>

            </div>

            <div class="col-4 text-center">
                <div class="col-12">&nbsp;</div>
                <h1>
                    Authentification
                </h1>

            </div>
            
        </div>            
    </header>


    <br><br><br>
    <div class="container">
        <div class="row">

            <?php if (isset($user_valid) && !$user_valid) { ?>
            <!-- spacing -->
            <div class="col-12">&nbsp;</div>
            <?php } ?>
            <div class="col-12">&nbsp;</div>

            <form action="?controller=Authentification&action=auth"
                  method="post"
                  class="col-md-6 offset-md-3
                    col-sm-8 offset-sm-2
                    col-10 offset-1 fond-primary">

                <!-- affichage des champs -->
                <div class="row">
                    <div class="col-12">&nbsp;</div>

                    <?php if (isset($user_valid) && !$user_valid) { ?>

                    <div class="col-10 offset-1 text-center">
                        <p class="invalide">
                        <span class="fa-solid fa-triangle-exclamation"></span>
                        Identifiant ou mot de passe invalide
                        </p>
                    </div>

                    <!-- <div class="col-12">&nbsp;</div> -->

                    <?php } ?>


                    <a class="col-6 offset-6" href="?controller=CreationCompte">
                        <button type="button" class="col-12 btn-annuler">
                            <span class="fa fa-user-plus">&nbsp;</span>
                            Créer un compte
                        </button>
                    </a>

                    <div class="col-12">&nbsp;</div>

                    <div class="col-10 offset-1">
                        <label for="identifiant" id="labelIdentifiant">
                            Identifiant :
                        </label>
                        <input required type="text" name="identifiant" id="inputIdentifiant">
                    </div>
    
                    <div class="col-12">&nbsp;</div>

                    <div class="col-10 offset-1">
                        <label for="motDePasse" id="labelMotDePasse">
                            Mot de passe :
                        </label>
                        <input required type="password" name="motDePasse" id="inputMotDePasse">
                    </div>
                    
                    <div class="col-12">&nbsp;</div>

                    <div class="col-8 offset-2">
                        <button type="submit" class="col-12 btn-creer">
                            Se connecter
                        </button>
                    </div>

                    <div class="col-12">&nbsp;</div>

                    <hr>

                    <div class="col-12">&nbsp;</div>


                    <!-- mot de passe oublie -->
                    <a href="?" class="col-6">
                        <button type="button" class="col-12 btn-invalide">
                            <span class="fa fa-lock">&nbsp;</span>
                            Mot de passe oublié
                        </button>
                    </a>

                    <a href="" class="col-6">
                        <button type="button" class="col-12 btn-invalide">
                            <span class="fa fa-head-side-virus">&nbsp;</span>
                            Identifiant oublié
                        </button>

                    </a>

                    <div class="col-12">&nbsp;</div>

                </div>

            </form>

        </div>
    </div>

    <br><br>

    <?php require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/footer.php"); ?>

    <script src="../festiplan/js/authentification.js"></script>

</body>
</html>