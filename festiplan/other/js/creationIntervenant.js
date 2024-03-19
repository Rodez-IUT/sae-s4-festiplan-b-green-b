// on attend que le DOM soit chargé
document.addEventListener("DOMContentLoaded", initialiserPage);

let labelNom;
let inputNom;
let labelPrenom;
let inputPrenom;
let labelEmail;
let inputEmail;
let boutonValider;

/**
 * Fonction qui initialise les variables globales
 * et qui ajoute les écouteurs d'événements
 */
function initialiserPage() {
    labelNom = document.getElementById("labelNom");
    inputNom = document.getElementById("inputNom");
    labelPrenom = document.getElementById("labelPrenom");
    inputPrenom = document.getElementById("inputPrenom");
    labelEmail = document.getElementById("labelEmail");
    inputEmail = document.getElementById("inputEmail");
    boutonValider = document.getElementById("boutonValider");

    boutonValider.addEventListener("click", verifAllInput);
}

/**
 * La fonction verifInputNom vérifie la longueur de tous les champs.
 * S'ils ne sont pas valides, elle change la couleur des labels
 * en rouge et empeche.
 */
function verifAllInput(event) {
    // on vérifie que les champs sont corrects
    let nomOK = verifInputTexte(inputNom, labelNom);
    let prenomOK = verifInputTexte(inputPrenom, labelPrenom);
    let emailOK = verifInputEmail();

    // si les champs sont corrects, on envoie le formulaire
    if (nomOK && prenomOK && emailOK) {
        // On laisse le formulaire s'envoyer
    } else {
        event.preventDefault();
    }
}

/**
 * La fonction vérifie la longueur de la valeur de 'inputNom'.
 * Si elle est inférieure à 2 caractères, elle change la couleur de 
 * l'étiquette en rouge. 
 * Sinon, elle change la couleur de l'étiquette en noir.
 * Elle retourne true si la valeur est valide, false sinon.
 */
function verifInputTexte(input, label) {
    let valeur = input.value.trim();
    if (valeur.length < 2) {
        label.style.color = "red";
        return false;
    } else {
        label.style.color = "black";
        return true;
    }
}


/**
 * La fonction verifInputEmail vérifie la longueur de la valeur de 'inputEmail'.
 * Si elle est ne vérifie pas la regepx des mails, elle change la couleur de 
 * l'étiquette en rouge. 
 * Sinon, elle change la couleur de l'étiquette en noir.
 * Elle retourne true si la valeur est valide, false sinon.
 */
function verifInputEmail() {
    let valeur = inputEmail.value.trim();

    // regex pour les mails
    let regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$/;

    if (!regex.test(valeur)) {
        labelEmail.style.color = "red";
        return false;
    } else {
        labelEmail.style.color = "black";
        return true;
    }
}