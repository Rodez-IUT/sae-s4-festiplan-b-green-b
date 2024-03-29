<?php

function SetupHeadersAndDialog(string $nomPage="festiplan", string $controller="Home", $open="", $action="index"): void
{

    ?>
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
                <?php echo isset($_SESSION["user_nom"]) ? $_SESSION["user_nom"] : "" ?>
                <br>
                <?php echo isset($_SESSION["user_prenom"]) ? $_SESSION["user_prenom"] : "" ?>
            </div>

            <form class="col-2" method="post" action="">
                <input hidden name="controller" value="<?php echo $controller ?>">
                <input hidden name="action" value="<?php echo $action ?>">
                <button class="col-12" type="submit">
                    <span class="fa fa-xmark"></span>
                </button>
            </form>

        </div>

        <div class="col-12"><!--spacing-->&nbsp;</div>

        <hr>

        <div class="row">
            <div class="col-12">
                <form class="col-12" method="post" action="?">
                    <input hidden name="controller" value="InformationCompte">
                    <input hidden name="action" value="index">

                    <button class="btn btn-light col-12">
                        <span class="icon fa fa-circle-info">&nbsp;</span>
                        Mes informations
                    </button>
                </form>
            </div>

            <div class="col-12"><!--spacing-->&nbsp;</div>

            <div class="col-12">
                <a href="?controller=ModifInfoPerso" class="col-12">
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
                <a href="?controller=ListeFestival">
                    <button class="btn btn-light col-12">
                        <span class="icon fa fa-tent">&nbsp;</span>
                        Mes festivals
                    </button>
                </a>
            </div>

            <div class="col-12"><!--spacing-->&nbsp;</div>


            <div class="col-12">
                <a href="?controller=ListeSpectacle">
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
        <div class="col-3 row">
            <div class="col-5">
                <a href="?controller=Home">
                    <img src ="../festiplan/ressources/images/logo.png" alt="logo" id="logo">
                </a>
            </div>
            <div class="col-3"></div>

        </div>

        <div class="col-6 text-center">
            <div class="col-12">&nbsp;</div>
            <h2>
                <?php echo $nomPage ?>
            </h2>

        </div>
        <div class="col-3 row">
            <form class="col-2 offset-10" method="post" action="">
                <input hidden name="controller" value="<?php echo $controller ?>">
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

<?php
}

?>