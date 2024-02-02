<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Information</title>

    <link rel="stylesheet" href="../other/css/style.css">

</head>
<body>
    <?php require("header.php");
    SetupHeadersAndDialog($titre, $controller, $open);?>

    <br><br>
    <div class="container">
        <div class="row">

            <form method="post"
                action="?controller=ModifInfoPerso"
                class="col-md-6 offset-md-3
                        col-sm-8 offset-sm-2
                        col-10 offset-1 fond-primary">

                <!-- affichage des champs -->
                <div class="row">
                    <div class="col-12">&nbsp;</div>

                    <div class="col-10 offset-1">
                        <label for="nom" id="labelNom"
                        class="<?php echo $liste_classes["nom"]; ?>">
                            Nom :
                        </label>
                        <input required class="col-12"
                            type="text" placeholder="Entrez votre nom"
                            value="<?php echo $liste_valeurs["nomUser"] ?>"
                            name="nom" id="inputNom">
                    </div>

                    <div class="col-12">&nbsp;</div>

                    <div class="col-10 offset-1">
                        <label for="prenom" id="labelPrenom"
                        class="<?php echo $liste_classes["prenom"]; ?>">
                            Prenom :
                        </label>
                        <input required class="col-12"
                            value="<?php echo $liste_valeurs["prenomUser"]; ?>"
                            type="text" placeholder="Entrez votre prénom"
                            name="prenom" id="inputPrenom">
                    </div>

                    <div class="col-12">&nbsp;</div>

                    <div class="col-10 offset-1">
                        <label for="email" id="labelEmail"
                        class="<?php echo $liste_classes["email"]; ?>">
                            Adresse E-mail :
                            <?php if (isset($email_unique) && !$email_unique) { ?>
                                <span class="invalide"> Cet email est déjà utilisé </span>
                            <?php } ?>
                        </label>
                        <input required class="col-12"
                            value="<?php echo $liste_valeurs["emailUser"]; ?>"
                            type="email" placeholder="Entrez votre email"
                            name="email" id="inputEmail">
                    </div>

                    <div class="col-12">&nbsp;</div>

                    <div class="col-10 offset-1">
                        <label for="identifiant" id="labelIdentifiant"
                        class="<?php echo $liste_classes["identifiant"]; ?>">
                            Identifiant :
                            <?php if (isset($identifiant_unique) && !$identifiant_unique) { ?>
                                <span class="invalide"> Cet identifiant est déjà utilisé </span>
                            <?php } ?>
                        </label>
                        <input required class="col-12"
                            value="<?php echo $liste_valeurs["loginUser"]; ?>"
                            type="text" placeholder="Entrez votre identifiant"
                            name="identifiant" id="inputIdentifiant">
                    </div>

                    <div class="col-12">&nbsp;</div>

                    <div class="col-10 offset-1">
                        <label for="motDePasse" id="labelAncienMotDePasse"
                        class="<?php echo $liste_classes["motDePasse"]; ?>">
                            Mot de passe actuel :
                        </label>
                        <span>
                            <input required class="col-12"
                                type="password" placeholder="Mot de passe actuel"
                                name="ancienMotDePasse" id="inputAncienMotDePasse">

                            <span id="eyeMDP" class="fa fa-eye eye"></span>
                        </span>
                    </div>

<!--                     <div class="col-12">&nbsp;</div>-->

                    <div class="col-10 offset-1">
                        <label for="confirMotDePasse" id="labelNouveauMotDePasse"
                        class="<?php echo $liste_classes["confirMotDePasse"]; ?>">
                            Nouveau mot de passe :
                        </label>
                        <span>
                            <input class="col-12"
                                type="password" placeholder="Nouveau mot de passe"
                                name="nouveauMotDePasse" id="inputNouveauMotDePasse">

                            <span id="eyeConfirmMDP" class="fa fa-eye eye"></span>
                        </span>
                    </div>

                    <div class="col-10 offset-1">
                        <label for="confirMotDePasse" id="labelConfirmationMotDePasse"
                        class="<?php echo $liste_classes["confirMotDePasse"]; ?>">
                            Confirmez votre nouveau mot de passe :
                        </label>
                        <span>
                            <input class="col-12"
                                type="password" placeholder="Confirmer nouveau mot de passe"
                                name="confirMotDePasse" id="inputConfirmationMotDePasse">

                            <span id="eyeConfirmMDP" class="fa fa-eye eye"></span>
                        </span>
                    </div>



                    <div class="col-12">&nbsp;</div>



                    <div class="col-4 offset-7">
                        <button class="btn-creer col-12"
                                name="action" value="changeAccount"
                                type="submit" id="boutonValider">
                            Changer
                        </button>
                    </div>
                    <div class="col-12">&nbsp;</div>

                </div>

            </form>
        </div>
    </div>

    <br><br>

    <?php require("footer.php"); ?>

</body>
</html>
