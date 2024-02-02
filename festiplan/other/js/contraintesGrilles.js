// on attend que le DOM soit chargé
document.addEventListener("DOMContentLoaded", initialiserPage);

// on initialise les variables globales
let labelDuree;
let inputDuree;

/**
 * Fonction qui initialise les variables globales
 * et qui ajoute les écouteurs d'événements
 */
function initialiserPage() {
    console.log("document chargéé");
    labelDuree = document.getElementById("labelDuree");
    inputDuree = document.getElementById("dureeEntreSpectacles");

    inputDuree.addEventListener("keyup", verifInputDuree);
}



/**
 * La fonction verifInputDuree vérifie si la valeur de l'élément 
 * inputDuree est un nombre positif * Si ce n'est pas le 
 * cas, elle change la couleur de labelDuree en rouge. 
 * Dans le cas contraire, elle change sa couleur en noir.
 */
function verifInputDuree() {
    // on recupere la valeur de l'input
    let valeur = inputDuree.value.trim();
    // la durée doit être un nombre positif
    if (valeur < 0) {
        labelDuree.style.color = "red";
    } else {
        labelDuree.style.color = "black";
    }
}