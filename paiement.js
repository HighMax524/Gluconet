// --- SELECTION OFFRES ---
const standard = document.getElementById("standard");
const premium = document.getElementById("premium");
const selectedOfferInput = document.getElementById("selectedOffer");

function deselectAll() {
    standard.classList.remove("active");
    premium.classList.remove("active");
}

standard.addEventListener("click", () => {
    deselectAll();
    standard.classList.add("active");
    selectedOfferInput.value = "Standard";
});

premium.addEventListener("click", () => {
    deselectAll();
    premium.classList.add("active");
    selectedOfferInput.value = "Premium";
});

// Pré-sélection depuis l'URL
const urlParams = new URLSearchParams(window.location.search);
const offerParam = urlParams.get('offre');
if (offerParam) {
    if (offerParam.toLowerCase() === 'standard') {
        standard.click();
    } else if (offerParam.toLowerCase() === 'premium') {
        premium.click();
    }
}


// --- VALIDATION FORMULAIRE ---
const cardNumber = document.getElementById("cardNumber");
const cvv = document.getElementById("cvv");


// Numéro de carte : 16 chiffres
cardNumber.addEventListener("input", () => {
    if (/^\d{16}$/.test(cardNumber.value)) {
        cardNumber.classList.remove("error");
    } else {
        cardNumber.classList.add("error");
    }
});

// CVV : 3 chiffres
cvv.addEventListener("input", () => {
    if (/^\d{3}$/.test(cvv.value)) {
        cvv.classList.remove("error");
    } else {
        cvv.classList.add("error");
    }
});

// Expiration > mois actuel
const expireInput = document.getElementById("expire");

expireInput.addEventListener("input", () => {
    let value = expireInput.value.replace(/\D/g, "");

    if (value.length > 4) value = value.slice(0, 4);

    if (value.length >= 3) {
        value = value.slice(0, 2) + " / " + value.slice(2);
    }

    expireInput.value = value;
});



// Soumission du formulaire
document.getElementById("paymentForm").addEventListener("submit", function (e) {

    // Valider la date une dernière fois
    function isValidExpire(value) {
        const match = value.match(/^(0[1-9]|1[0-2]) \/ (\d{2})$/);
        if (!match) return false;

        const month = parseInt(match[1], 10);
        const year = 2000 + parseInt(match[2], 10);

        const now = new Date();
        const expiry = new Date(year, month);

        return expiry > now;
    }

    if (!selectedOfferInput.value) {
        e.preventDefault();
        alert("Veuillez sélectionner une offre.");
        return;
    }

    if (!isValidExpire(expireInput.value)) {
        e.preventDefault();
        document.getElementById('expire-error').style.display = 'block';
        return;
    }

    // Si tout est bon, on laisse le formulaire s'envoyer (pas de preventDefault)
    // Le backend traitera la demande
});
