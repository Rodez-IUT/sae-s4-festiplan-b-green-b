// on attend que le DOM soit chargé
document.addEventListener("DOMContentLoaded", initialiserPage);


let labelNom;
let inputNom;
let labelPrenom;
let inputPrenom;
let labelEmail;
let inputEmail;
let labelIdentifiant;
let inputIdentifiant;
let labelMotDePasse;
let inputMotDePasse;
let labelConfirmationMotDePasse;
let inputConfirmationMotDePasse;
let boutonValider;

let eyeMDP;
let eyeConfirmMDP;

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
    labelIdentifiant = document.getElementById("labelIdentifiant");
    inputIdentifiant = document.getElementById("inputIdentifiant");
    labelMotDePasse = document.getElementById("labelMotDePasse");
    inputMotDePasse = document.getElementById("inputMotDePasse");
    labelConfirmationMotDePasse = document.getElementById("labelConfirmationMotDePasse");
    inputConfirmationMotDePasse = document.getElementById("inputConfirmationMotDePasse");
    boutonValider = document.getElementById("boutonValider");

    eyeMDP = document.getElementById("eyeMDP");
    eyeConfirmMDP = document.getElementById("eyeConfirmMDP");

    eyeMDP.addEventListener('mousedown', afficherMotDePasse);
    eyeMDP.addEventListener('mouseup', cacherMotDePasse);

    eyeConfirmMDP.addEventListener('mousedown', afficherMotDePasse);
    eyeConfirmMDP.addEventListener('mouseup', cacherMotDePasse);


    boutonValider.addEventListener("click", verifAllInput);
}


function afficherMotDePasse(event) {
    if (event.target.id == "eyeConfirmMDP") {
        inputConfirmationMotDePasse.type = "text";
    } else if (event.target.id = "eyeMDP") {
        inputMotDePasse.type = "text";
    }
}

function cacherMotDePasse(event) {
    if (event.target.id == "eyeConfirmMDP") {
        inputConfirmationMotDePasse.type = "password";
    } else if (event.target.id = "eyeMDP") {
        inputMotDePasse.type = "password";
    }
}


/**
 * La fonction verifInputNom vérifie la longueur de tous les champs.
 * S'ils ne sont pas valides, elle change la couleur des labels
 * en rouge et empeche l'envoi du formulaire.
 */
function verifAllInput(event) {
    
    let nomOK = verifInputTexte(inputNom, labelNom);
    let prenomOK = verifInputTexte(inputPrenom, labelPrenom);
    let emailOK = verifInputEmail();
    let identifiantOK = verifInputTexte(inputIdentifiant, labelIdentifiant);
    let motDePasseOK = verifInputTexte(inputMotDePasse, labelMotDePasse);
    let confirmationMotDePasseOK = verifInputTexte(inputConfirmationMotDePasse, labelConfirmationMotDePasse) && verifMotDePasse();

    if (nomOK && prenomOK 
        && emailOK && identifiantOK 
        && motDePasseOK && confirmationMotDePasseOK
        && motDePasseOK === confirmationMotDePasseOK) {
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
 * @param {HTMLElement} input
 * @param {HTMLElement} label
 * @returns {Boolean}
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
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regex.test(valeur)) {
        labelEmail.style.color = "red";
        return false;
    } else {
        labelEmail.style.color = "black";
        return true;
    }
}

/**
 * La fonction verifMotDePasse vérifie que le champ "confirmer mot de passe"
 * est bien le même que "mot de passe". Si ces deux champs sont diffrents 
 * l'étiquette de change de couleur en rouge.
 * Elle retourne true si les deux champs sont égaux et false sinon
 */
function verifMotDePasse() {
    let motDePasse = inputMotDePasse.value.trim();
    let confirmationMotDePasse = inputConfirmationMotDePasse.value.trim();

    if(motDePasse != confirmationMotDePasse) {
        labelConfirmationMotDePasse.style.color = "red";
        return false;
    } else {
        labelConfirmationMotDePasse.style.color = "black";
        return true;
    }
}