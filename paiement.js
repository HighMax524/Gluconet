// --- SELECTION OFFRES ---
const standard = document.getElementById("standard");
const premium = document.getElementById("premium");

function deselectAll() {
    standard.classList.remove("active");
    premium.classList.remove("active");
}

standard.addEventListener("click", () => {
    deselectAll();
    standard.classList.add("active");
});

premium.addEventListener("click", () => {
    deselectAll();
    premium.classList.add("active");
});


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

// Remplir les mois
for (let m = 1; m <= 12; m++) {
    const month = m < 10 ? "0" + m : m;
    const option = document.createElement("option");
    option.value = month;
    option.textContent = month;
    monthSelect.appendChild(option);
}

// Remplir les années (2026 → 2035)
const currentYear = new Date().getFullYear();
for (let y = currentYear; y <= currentYear + 10; y++) {
    const option = document.createElement("option");
    option.value = y;
    option.textContent = y;
    yearSelect.appendChild(option);
}

// Validation de la date
function isValidExpire(value) {
    const match = value.match(/^(0[1-9]|1[0-2]) \/ (\d{2})$/);
    if (!match) return false;

    const month = parseInt(match[1], 10);
    const year = 2000 + parseInt(match[2], 10);

    const now = new Date();
    const expiry = new Date(year, month);

    return expiry > now;
}


// Événements
monthSelect.addEventListener("change", validateExpiration);
yearSelect.addEventListener("change", validateExpiration);


// Soumission du formulaire
document.getElementById("paymentForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Empêcher le rechargement de page par défaut

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


    // Redirection vers le profil avec un message de succès
    window.location.href = './profil.php?success=paiement';
});
