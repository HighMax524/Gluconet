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
const expire = document.getElementById("expire");

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
const expMonth = document.getElementById("expMonth");
const expYear = document.getElementById("expYear");
const expire = document.getElementById("expire");

const startYear = 2026;
const endYear = startYear + 15; // 15 ans max

// Remplir les années
for (let year = startYear; year <= endYear; year++) {
    const option = document.createElement("option");
    option.value = year;
    option.textContent = year;
    expYear.appendChild(option);
}

// Remplir les mois
for (let month = 1; month <= 12; month++) {
    const option = document.createElement("option");
    option.value = String(month).padStart(2, "0");
    option.textContent = String(month).padStart(2, "0");
    expMonth.appendChild(option);
}

// Validation
function validateExpire() {
    const month = expMonth.value;
    const year = expYear.value;

    if (!month || !year) {
        expire.classList.add("error");
        return;
    }

    const selectedDate = new Date(year, month - 1);
    const minDate = new Date(2026, 0); // Janvier 2026

    if (selectedDate >= minDate) {
        expire.value = `${month} / ${year}`;
        expire.classList.remove("error");
    } else {
        expire.classList.add("error");
    }
}

expMonth.addEventListener("change", validateExpire);
expYear.addEventListener("change", validateExpire);

// Soumission du formulaire
document.getElementById("paymentForm").addEventListener("submit", function(e) {
    if (
        cardNumber.classList.contains("error") ||
        cvv.classList.contains("error") ||
        expire.classList.contains("error")
    ) {
        e.preventDefault();
        alert("Veuillez corriger les erreurs.");
    }
});
