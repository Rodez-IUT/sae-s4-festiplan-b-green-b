document.addEventListener('DOMContentLoaded', initialiserDonnees);

let labelNom;
let inputNom;
let labelDescription;
let inputDescription;


let boutonValider;
// let selectionCategorie;
// let selectionScene;

function initialiserDonnees() {
    boutonValider = document.getElementById("btn-valider");
    boutonValider.addEventListener("click", validerCreationFestival);
}

/**
 * la fonction validerCreationFestival vérifie que tous les champs du formumaire
 * soient remplis de manières correctes
 * @returns true si c'est le cas et false sinon 
 */
function validerCreationFestival(event) {
    console.log(verifAllCheckList());
    if(verifAllInput() && verifAllCheckList()) {
        return true;
    }
    return false;
}

/**
 * la fonction verifie tous les input de type texte du formulaire
 * @param event 
 * @return true si tous les input texte sont valides et false sinon
 */
function verifAllInput(event) {
    labelNom = document.getElementById("labelNom");
    inputNom = document.getElementById("inputNom");
    labelDescription = document.getElementById("labelDescription");
    inputDescription = document.getElementById("inputDescription");
    console.log(verifInputTexte(inputDescription, labelDescription));
    return verifInputTexte(inputNom, labelNom) && verifInputTexte(inputDescription, labelDescription);

}

/**
 * la fonction vérifie toutes les checklist du formulaire
 * @returns true si toutes les checklist sont valide
 */
function verifAllCheckList() {
    let listeLabel = ["labelCategorie", "labelScene", "labelGrille", "labelSpectacle", "labelMembre", "labelOrganisateur"];
    let listeCheckbox = ["categories[]", "scenes[]", "grilles[]", "spectacles[]", "membres[]", "organisateur"];
    for(let i = 0; i < listeLabel.length; i++) {
        // on récupère le label
        let label = document.getElementById(listeLabel[i]);
        // on teste la validité d ela checkbox
        if(!verifCheckList(listeCheckbox[i])) {
            label.classList.add("invalide");
            return false;
        }
        //on ajoute la bonne couleur au label lorsque il est ok
        if(listeCheckbox[i] == "categories[]" || listeCheckbox[i] == "scenes[]") {
            label.classList.add("ok");
        } else {
            label.style.color = "white";
        }
    }
    return true;
}

/**
 * la fonction permet de vérifier la validité d'un input de type texte
 * Ici il faut qu'il ait plus de deux caractères pour qu'il soit valide
 * @param input 
 * @param label 
 * @returns true si l'input est valide et false sinon
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
 * Vérifie la bonne validité d'une checklsit
 * @param name le nom de la checklist 
 * @returns true si la checklist est valide c'està dire si elle a au moins un élément coché
 */
function verifCheckList(name) {
    let elements = document.getElementsByName(name);
    let nbCaseCocher = 0;
    for(let i = 0; i < elements.length; i++) {
        if(elements[i].checked == true) {
            nbCaseCocher++
        }
    }
    if(nbCaseCocher > 0) {
        return true;
    }
    return false;
}
