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
const monthSelect = document.getElementById("expire-month");
const yearSelect = document.getElementById("expire-year");
const errorMsg = document.getElementById("expire-error");

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
function validateExpiration() {
    const month = monthSelect.value;
    const year = yearSelect.value;
    if (!month || !year) {
        errorMsg.style.display = "none";
        return;
    }

    const today = new Date();
    const inputDate = new Date(year, parseInt(month) - 1, 1);

    if (inputDate < new Date(today.getFullYear(), today.getMonth(), 1)) {
        // date passée
        errorMsg.style.display = "inline";
    } else {
        errorMsg.style.display = "none";
    }
}

// Événements
monthSelect.addEventListener("change", validateExpiration);
yearSelect.addEventListener("change", validateExpiration);


// Soumission du formulaire
// Soumission du formulaire
document.getElementById("paymentForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Empêcher le rechargement de page par défaut

    // Valider la date une dernière fois
    validateExpiration();

    const isDateEmpty = !monthSelect.value || !yearSelect.value;
    const isDateInvalid = errorMsg.style.display === "inline";

    if (
        cardNumber.classList.contains("error") ||
        cvv.classList.contains("error") ||
        isDateEmpty ||
        isDateInvalid
    ) {
        alert("Veuillez corriger les erreurs.");
        return;
    }

    // Redirection vers le profil avec un message de succès
    window.location.href = './profil.php?success=paiement';
});
