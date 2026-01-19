// connexion.js - Gestion de l'affichage du mot de passe

function afficheMdp(inputId, element) {
    const input = document.getElementById(inputId);
    const img = element.querySelector("img");

    if (input.type === "password") {
        input.type = "text";
        img.src = "res/oeil_barre.png";
        img.alt = "icone oeil";
    } else {
        input.type = "password";
        img.src = "res/oeil.png";
        img.alt = "icone oeil barré";
    }
}
