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

function verifierNom(input) {
input.value = input.value.replace(/[^A-Za-zÀ-ÿ -]/g, "");
}

// Validation des champs nom et prénom en temps réel
document.getElementById("nom").addEventListener("input", function () {
verifierNom(this);
});
document.getElementById("prenom").addEventListener("input", function () {
verifierNom(this);
});

// Limiter le champ téléphone à 10 chiffres numériques
const telInput = document.getElementById("tel");

telInput.addEventListener("input", () => {
telInput.value = telInput.value.replace(/\D/g, "");
if (telInput.value.length > 10) {
telInput.value = telInput.value.slice(0, 10);
}
});