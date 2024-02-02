// on attend que le DOM soit chargé
document.addEventListener("DOMContentLoaded", initialiserPage);

// on initialise les variables globales
let labelIdentifiant;
let inputIdentifiant;
let labelMotDePasse;
let inputMotDePasse;
let boutonValider;

/**
 * Fonction qui initialise les variables globales
 * et qui ajoute les écouteurs d'événements
 */
function initialiserPage() {
    labelIdentifiant = document.getElementById("labelIdentifiant");
    inputIdentifiant = document.getElementById("inputIdentifiant");
    labelMotDePasse = document.getElementById("labelMotDePasse");
    inputMotDePasse = document.getElementById("inputMotDePasse");
    boutonValider = document.getElementById("boutonValider");

    boutonValider.addEventListener("click", verifUserInputs);
}


/**
 * La fonction verifUserInputs vérifie la longueur des 
 * valeurs inputIdentifiant et inputMotDePasse. 
 * Si elles sont inférieures à 3 caractères, elle change la couleur 
 * de leur étiquette en rouge. 
 * Sinon, elle change la couleur de leur étiquette en noir.
 * Elle empêche le formulaire de s'envoyer si les valeurs sont incorrectes.
 * 
 * @param event Prevent the form from being submitted
 */
function verifUserInputs(event) {
    let identifiantOK = true;
    let motDePasseOK = true;

    let valeurIdentifiant = inputIdentifiant.value.trim();
    let valeurMotDePasse = inputMotDePasse.value.trim();

    if (valeurIdentifiant.length < 3) {
        labelIdentifiant.style.color = "red";
        identifiantOK = false;
    } else {
        labelIdentifiant.style.color = "black";
    }

    if (valeurMotDePasse.length < 3) {
        labelMotDePasse.style.color = "red";
        motDePasseOK = false;
    } else {
        labelMotDePasse.style.color = "black";
    }

    if (identifiantOK && motDePasseOK) {
        // On laisse le formulaire s'envoyer
    } else {
        event.preventDefault();
    }
}