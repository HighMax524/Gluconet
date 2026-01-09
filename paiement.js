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
const expire = document.getElementById("expire");

expire.addEventListener("input", () => {
    const minDate = new Date("2026-01");
    const inputDate = new Date(expire.value);

    if (expire.value && inputDate >= minDate) {
        expire.classList.remove("error");
    } else {
        expire.classList.add("error");
    }
});


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
