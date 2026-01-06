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
    document.getElementById("selectedOffer").value = "Standard";
});

premium.addEventListener("click", () => {
    deselectAll();
    premium.classList.add("active");
    document.getElementById("selectedOffer").value = "Premium";
});


// Soumission du formulaire
document.getElementById("paymentForm").addEventListener("submit", function (e) {
    if (document.getElementById("selectedOffer").value === "") {
        e.preventDefault();
        alert("Veuillez sélectionner une offre.");
    }
});
