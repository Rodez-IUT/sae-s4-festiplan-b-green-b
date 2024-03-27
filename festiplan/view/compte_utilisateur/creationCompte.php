<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Création d'un Compte</title>
    
    <link rel="stylesheet" href="../festiplan/other/css/style.css">

</head>
<body>

    <header class="container-fluid">
        <div class="row">
            <div class="col-4 row">
            <div class="col-5">
                    <a href="?controller=Home">
                        <img src="../festiplan/ressources/images/logo.png" alt="logo" id="logo">
                    </a>
                </div>
                <div class="col-3"></div>

            </div>

            <div class="col-4 text-center">
                <div class="col-12">&nbsp;</div>
                <h1>
                    Créer un compte
                </h1>

            </div>

        </div>
    </header>

    <br><br>
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

                    <div class="col-10 offset-1">
                        <label for="nom" id="labelNom"
                        class="<?php /** @noinspection PhpUndefinedVariableInspection */
                        echo $liste_classes["nom"]; ?>">
                            Nom :
                        </label>
                        <input required class="col-12"
                            type="text" placeholder="Entrez votre nom" 
                            value="<?php /** @noinspection PhpUndefinedVariableInspection */
                            echo $liste_valeurs["nom"] ?>"
                            name="nom" id="inputNom">
                    </div>
    
<!--                     <div class="col-12">&nbsp;</div>-->

                    <div class="col-10 offset-1">
                        <label for="prenom" id="labelPrenom"
                        class="<?php echo $liste_classes["prenom"]; ?>">
                            Prenom :
                        </label>
                        <input required class="col-12"
                            value="<?php echo $liste_valeurs["prenom"]; ?>" 
                            type="text" placeholder="Entrez votre prénom" 
                            name="prenom" id="inputPrenom">
                    </div>

<!--                     <div class="col-12">&nbsp;</div>-->

                    <div class="col-10 offset-1">
                        <label for="email" id="labelEmail"
                        class="<?php echo $liste_classes["email"]; ?>">
                            Adresse E-mail :
                            <?php if (isset($email_unique) && !$email_unique) { ?>
                                <span class="invalide"> Cet email est déjà utilisé </span>
                            <?php } ?>
                        </label>
                        <input required class="col-12"
                            value="<?php echo $liste_valeurs["email"]; ?>"
                            type="email" placeholder="Entrez votre email" 
                            name="email" id="inputEmail">
                    </div>

<!--                     <div class="col-12">&nbsp;</div>-->

                    <div class="col-10 offset-1">
                        <label for="identifiant" id="labelIdentifiant"
                        class="<?php echo $liste_classes["identifiant"]; ?>">
                            Identifiant :
                            <?php if (isset($identifiant_unique) && !$identifiant_unique) { ?>
                                <span class="invalide"> Cet identifiant est déjà utilisé </span>
                            <?php } ?>
                        </label>
                        <input required class="col-12"
                            value="<?php echo $liste_valeurs["identifiant"]; ?>"
                            type="text" placeholder="Entrez votre identifiant" 
                            name="identifiant" id="inputIdentifiant">
                    </div>

<!--                     <div class="col-12">&nbsp;</div>-->

                    <div class="col-10 offset-1">
                        <label for="motDePasse" id="labelMotDePasse"
                        class="<?php echo $liste_classes["motDePasse"]; ?>">
                            Mot de passe :
                        </label>
                        <span>
                            <input required class="col-12"
                                type="password" placeholder="Entrez votre mot de passe" 
                                name="motDePasse" id="inputMotDePasse">
                            
                            <span id="eyeMDP" class="fa fa-eye eye"></span>
                        </span>
                    </div>

<!--                     <div class="col-12">&nbsp;</div>-->

                    <div class="col-10 offset-1">
                        <label for="confirMotDePasse" id="labelConfirmationMotDePasse"
                        class="<?php echo $liste_classes["confirMotDePasse"]; ?>">
                            Confirmez votre mot de passe :
                        </label>
                        <span>
                            <input required class="col-12"
                                type="password" placeholder="Entrez votre mot de passe" 
                                name="confirMotDePasse" id="inputConfirmationMotDePasse">

                            <span id="eyeConfirmMDP" class="fa fa-eye eye"></span>
                        </span>
                    </div>

                    <div class="col-12">&nbsp;</div>

                    <div class="col-4 offset-7">
                        <button class="btn-creer col-12"
                            type="submit" id="boutonValider">
                            Créer
                        </button>
                    </div>
                    <div class="col-12">&nbsp;</div>

                </div>

            </form>
        </div>
    </div>

    <br><br>

    <?php require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/footer.php"); ?>

    <script src="../festiplan/other/js/creationCompte.js"></script>

</body>
</html>
