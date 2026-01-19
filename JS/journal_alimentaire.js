// journal_alimentaire.js - Gestion du journal alimentaire et API OpenFoodFacts

const meals = { petitdej: [], dejeuner: [], diner: [] };
let currentMeal = null;
let selectedFood = null;
let mood = "";

// Récupération de l'abonnement utilisateur injecté dans le HTML
const userSubscription = (typeof USER_SUBSCRIPTION !== 'undefined') ? USER_SUBSCRIPTION : 'Standard';

function openModal(mealKey) {
    currentMeal = mealKey;
    selectedFood = null;

    document.getElementById("overlay").classList.add("show");
    document.getElementById("searchFood").value = "";
    document.getElementById("manualName").value = "";
    document.getElementById("qty").value = "";
    document.getElementById("unit").value = "g";
    document.getElementById("selQty").value = "";
    document.getElementById("selUnit").value = "g";

    const map = { petitdej: "Petit-déj", dejeuner: "Déjeuner", diner: "Dîner" };
    document.getElementById("modalSub").textContent = "Repas : " + map[mealKey];

    renderSelectedBox();
    renderMealItems();
    filterFoods();
}

function closeModal() { document.getElementById("overlay").classList.remove("show"); }

function filterFoods() {
    const q = document.getElementById("searchFood").value.trim().toLowerCase();
    document.querySelectorAll(".food-item").forEach(btn => {
        const name = (btn.dataset.name || "").toLowerCase();
        btn.style.display = name.includes(q) ? "block" : "none";
    });
}

function selectFood(name) { selectedFood = name; renderSelectedBox(); }

function renderSelectedBox() {
    const box = document.getElementById("selectedBox");
    box.innerHTML = selectedFood ? ("<strong>" + esc(selectedFood) + "</strong>") : "Aucun aliment sélectionné.";
}

function addSelected() {
    if (!currentMeal) return;
    if (!selectedFood) { alert("Choisis un aliment."); return; }

    const qty = parseFloat(document.getElementById("selQty").value);
    const unit = document.getElementById("selUnit").value;

    if (!Number.isFinite(qty) || qty <= 0) { alert("Quantité invalide."); return; }

    meals[currentMeal].push({ name: selectedFood, qty, unit });
    document.getElementById("selQty").value = "";
    renderMealItems();
}

function addManual() {
    if (!currentMeal) return;

    const name = document.getElementById("manualName").value.trim();
    const qty = parseFloat(document.getElementById("qty").value);
    const unit = document.getElementById("unit").value;

    if (name === "" || !Number.isFinite(qty) || qty <= 0) {
        alert("Remplis nom + quantité.");
        return;
    }

    meals[currentMeal].push({ name, qty, unit });
    document.getElementById("manualName").value = "";
    document.getElementById("qty").value = "";
    renderMealItems();
}

function removeItem(index) {
    meals[currentMeal].splice(index, 1);
    renderMealItems();
}

function renderMealItems() {
    const area = document.getElementById("mealItems");
    const list = meals[currentMeal] || [];

    if (list.length === 0) {
        area.innerHTML = "<div style='opacity:.8;'>Aucun aliment.</div>";
        return;
    }

    area.innerHTML = list.map((it, i) => `
    <div class="meal-item">
      <div>
        <div style="font-weight:700;">${esc(it.name)}</div>
        <div style="opacity:.85;">${it.qty} ${esc(it.unit)}</div>
      </div>
      <button class="remove-btn" type="button" onclick="removeItem(${i})">Supprimer</button>
    </div>
  `).join("");
}

function saveAndClose() {
    updateSummary("petitdej");
    updateSummary("dejeuner");
    updateSummary("diner");
    closeModal();
}

function updateSummary(mealKey) {
    const target = document.getElementById("summary_" + mealKey);
    const list = meals[mealKey];

    if (list.length === 0) { target.textContent = "Aucun aliment ajouté."; return; }

    target.innerHTML = "<ul>" + list.map(it =>
        "<li>" + esc(it.name) + " — " + it.qty + " " + esc(it.unit) + "</li>"
    ).join("") + "</ul>";
}

function setMood(value, btn) {
    mood = value;
    document.getElementById("moodText").textContent = value;
    document.querySelectorAll(".mood-btn").forEach(b => b.classList.remove("selected"));
    btn.classList.add("selected");
}

/* ------------------ API Open Food Facts (direct) ------------------ */

function toNum(v) {
    const x = parseFloat(v);
    return Number.isFinite(x) ? x : 0;
}

async function fetchNutritionPer100g(foodName) {
    const offUrl =
        "https://world.openfoodfacts.org/api/v2/search?search_terms=" +
        encodeURIComponent(foodName) +
        "&page_size=1&fields=product_name,nutriments";

    // IMPORTANT : fetch direct (pas de proxy)
    const res = await fetch(offUrl);
    if (!res.ok) throw new Error("HTTP " + res.status);

    const data = await res.json();
    const p = (data && data.products && data.products[0]) ? data.products[0] : null;
    if (!p || !p.nutriments) return null;

    const n = p.nutriments;

    return {
        product_name: p.product_name || foodName,
        kcal_100g: toNum(n["energy-kcal_100g"]),
        carbs_100g: toNum(n["carbohydrates_100g"]),
        prot_100g: toNum(n["proteins_100g"]),
        fat_100g: toNum(n["fat_100g"])
    };
}

async function analyserJourneeAPI() {
    if (mood === "") {
        alert("Choisis ton ressenti avant l’analyse.");
        return;
    }

    // Check Limitation Standard
    if (userSubscription === 'Standard') {
        const today = new Date().toISOString().split('T')[0];
        const lastAnalysis = localStorage.getItem('last_diet_analysis_date');

        if (lastAnalysis === today) {
            alert("🚫 Limite atteinte (Standard)\n\nVous avez droit à une seule proposition par jour.\nPassez Premium pour un nombre illimité d'analyses !");
            return; // Bloque l'exécution
        }
        // On marque comme utilisé pour aujourd'hui
        localStorage.setItem('last_diet_analysis_date', today);
    }

    const resultBox = document.getElementById("resultBox");
    resultBox.textContent = "Résultat : analyse en cours...";

    let totalKcal = 0, totalCarbs = 0, totalProt = 0, totalFat = 0;
    let notes = [];

    const allMeals = ["petitdej", "dejeuner", "diner"];

    for (const mk of allMeals) {
        for (const item of meals[mk]) {
            const qty = Number(item.qty);
            const unit = item.unit;

            // calcul précis seulement si en g
            if (unit !== "g") {
                notes.push(`- ${item.name}: unité "${unit}" -> pas calculé précisément (mets en g pour test)`);
                continue;
            }

            try {
                const nut = await fetchNutritionPer100g(item.name);
                if (!nut) {
                    notes.push(`- ${item.name}: pas trouvé dans Open Food Facts`);
                    continue;
                }

                const facteur = qty / 100;

                totalKcal += nut.kcal_100g * facteur;
                totalCarbs += nut.carbs_100g * facteur;
                totalProt += nut.prot_100g * facteur;
                totalFat += nut.fat_100g * facteur;

            } catch (e) {
                // ici on affiche le vrai message d'erreur
                notes.push(`- ${item.name}: erreur API (${e.message})`);
            }
        }
    }

    let conseil = "";
    if (totalCarbs > 250) conseil = "Glucides assez élevés aujourd'hui, surveille les portions de féculents/sucrés.";
    else if (totalCarbs < 120) conseil = "Glucides plutôt bas, attention aux risques d'hypo si traitement.";
    else conseil = "Répartition glucides ok globalement (estimation).";

    resultBox.textContent =
        "Résultat :\n" +
        "- Ressenti : " + mood + "\n" +
        "- Total kcal : " + totalKcal.toFixed(0) + "\n" +
        "- Glucides (g) : " + totalCarbs.toFixed(1) + "\n" +
        "- Protéines (g) : " + totalProt.toFixed(1) + "\n" +
        "- Lipides (g) : " + totalFat.toFixed(1) + "\n\n" +
        "Conseil : " + conseil + "\n\n" +
        (notes.length ? ("Notes :\n" + notes.join("\n")) : "Notes : rien à signaler");
}

function esc(str) {
    return String(str)
        .replaceAll("&", "&amp;").replaceAll("<", "&lt;").replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;").replaceAll("'", "&#039;");
}
