<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gluconet – Journal alimentaire</title>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="res/style.css">
  <link href='res/logo_site.png' rel='icon'>
  <link rel="stylesheet" href="res/css_journalAlim.css"
</head>

<body>
<?php include 'nav_bar.php'; ?>
  <main class="page-wrapper">
    <section class="card">
      <h1 class="card-title">Journal alimentaire </h1>

      <div class="content-grid">

        <div class="left-actions">
          <button class="boutton_form" type="button" onclick="openModal('petitdej')">Petit-déj</button>
          <button class="boutton_form" type="button" onclick="openModal('dejeuner')">Déjeuner</button>
          <button class="boutton_form" type="button" onclick="openModal('diner')">Dîner</button>

          <div style="height: 8px;"></div>

          <button class="boutton_form" type="button" onclick="analyserJourneeAPI()">Analyser ma journée (API)</button>

          <div class="result-box" id="resultBox">Résultat : (vide)</div>

          <div style="margin-top:10px; font-size:12px; opacity:.85;">
            Astuce test : mets les quantités en <b>g</b> (ex : Pomme 150 g) pour calculer.
          </div>
        </div>

        <div>
          <div class="form-section">
            <p class="section-title">Résumé de la journée</p>

            <div class="summary-block">
              <div class="summary-title">Petit-déj</div>
              <div id="summary_petitdej">Aucun aliment ajouté.</div>
            </div>

            <div class="summary-block">
              <div class="summary-title">Déjeuner</div>
              <div id="summary_dejeuner">Aucun aliment ajouté.</div>
            </div>

            <div class="summary-block" style="margin-bottom:0;">
              <div class="summary-title">Dîner</div>
              <div id="summary_diner">Aucun aliment ajouté.</div>
            </div>
          </div>

          <div style="height: 14px;"></div>

          <div class="form-section">
            <p class="section-title">Ressenti</p>

            <div class="mood-row">
              <button class="mood-btn" type="button" onclick="setMood('bien', this)">🙂</button>
              <button class="mood-btn" type="button" onclick="setMood('moyen', this)">😐</button>
              <button class="mood-btn" type="button" onclick="setMood('mal', this)">🙁</button>
            </div>

            <p id="moodText" style="margin-top:10px; opacity:.85;">Non renseigné</p>
          </div>
        </div>

      </div>
    </section>
  </main>

  <!-- modal -->
  <div class="overlay" id="overlay">
    <div class="modal">

      <div class="modal-top">
        <div>
          <h2 style="font-size:22px;">Ajouter un repas</h2>
          <p id="modalSub" style="opacity:.85;">Repas : ...</p>
        </div>
        <button class="close-btn" type="button" onclick="closeModal()">✕</button>
      </div>

      <div class="input-group">
        <label class="input-label" for="searchFood">Rechercher</label>
        <input class="text-input" id="searchFood" type="text" placeholder="Ex : Riz, Banane..." oninput="filterFoods()">
      </div>

      <div class="picker-grid">

        <!-- liste test -->
        <div class="food-list" id="foodList">
          <button class="food-item" type="button" data-name="Pomme" onclick="selectFood('Pomme')">Pomme</button>
          <button class="food-item" type="button" data-name="Banane" onclick="selectFood('Banane')">Banane</button>
          <button class="food-item" type="button" data-name="Riz cuit" onclick="selectFood('Riz cuit')">Riz cuit</button>
          <button class="food-item" type="button" data-name="Poulet" onclick="selectFood('Poulet')">Poulet</button>
          <button class="food-item" type="button" data-name="Yaourt nature" onclick="selectFood('Yaourt nature')">Yaourt nature</button>
          <button class="food-item" type="button" data-name="Pain complet" onclick="selectFood('Pain complet')">Pain complet</button>
          <button class="food-item" type="button" data-name="Couscous" onclick="selectFood('Couscous')">Couscous</button>
          <button class="food-item" type="button" data-name="Fromage blanc" onclick="selectFood('Fromage blanc')">Fromage blanc</button>
        </div>

        <div class="form-section" style="text-align:left;">
          <p class="section-title" style="text-align:center;">Ajout manuel</p>

          <div class="input-group">
            <label class="input-label" for="manualName">Nom</label>
            <input class="text-input" id="manualName" type="text" placeholder="Ex : Couscous">
          </div>

          <div class="row2">
            <div class="input-group">
              <label class="input-label" for="qty">Quantité</label>
              <input class="text-input" id="qty" type="number" placeholder="Ex : 150" min="0" step="0.1">
            </div>
            <div class="input-group">
              <label class="input-label" for="unit">Unité</label>
              <select class="text-input" id="unit">
                <option value="g">g</option>
                <option value="ml">ml</option>
                <option value="portion">portion</option>
                <option value="pièce">pièce</option>
              </select>
            </div>
          </div>

          <button class="boutton_form" type="button" onclick="addManual()">Ajouter manuellement</button>
        </div>

      </div>

      <hr style="margin: 16px 0; opacity:.25;">

      <div class="form-section" style="text-align:left;">
        <p class="section-title" style="text-align:center;">Aliment sélectionné</p>

        <div id="selectedBox" style="opacity:.85; text-align:center;">Aucun aliment sélectionné.</div>

        <div class="row2" style="margin-top:10px;">
          <div class="input-group">
            <label class="input-label" for="selQty">Quantité</label>
            <input class="text-input" id="selQty" type="number" placeholder="Ex : 100" min="0" step="0.1">
          </div>
          <div class="input-group">
            <label class="input-label" for="selUnit">Unité</label>
            <select class="text-input" id="selUnit">
              <option value="g">g</option>
              <option value="ml">ml</option>
              <option value="portion">portion</option>
              <option value="pièce">pièce</option>
            </select>
          </div>
        </div>

        <button class="boutton_form" type="button" onclick="addSelected()">Ajouter la sélection</button>
      </div>

      <hr style="margin: 16px 0; opacity:.25;">

      <div class="form-section" style="text-align:left;">
        <p class="section-title" style="text-align:center;">Contenu du repas</p>

        <div class="meal-items" id="mealItems"></div>

        <div style="display:flex; gap:10px; margin-top:12px;">
          <button class="boutton_form" type="button" onclick="saveAndClose()">Enregistrer</button>
          <button class="boutton_form" type="button" style="opacity:.85;" onclick="closeModal()">Annuler</button>
        </div>
      </div>

    </div>
  </div>

  <script>
    const meals = { petitdej: [], dejeuner: [], diner: [] };
    let currentMeal = null;
    let selectedFood = null;
    let mood = "";

    function openModal(mealKey){
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

    function closeModal(){ document.getElementById("overlay").classList.remove("show"); }

    function filterFoods(){
      const q = document.getElementById("searchFood").value.trim().toLowerCase();
      document.querySelectorAll(".food-item").forEach(btn => {
        const name = (btn.dataset.name || "").toLowerCase();
        btn.style.display = name.includes(q) ? "block" : "none";
      });
    }

    function selectFood(name){ selectedFood = name; renderSelectedBox(); }

    function renderSelectedBox(){
      const box = document.getElementById("selectedBox");
      box.innerHTML = selectedFood ? ("<strong>" + esc(selectedFood) + "</strong>") : "Aucun aliment sélectionné.";
    }

    function addSelected(){
      if(!currentMeal) return;
      if(!selectedFood){ alert("Choisis un aliment."); return; }

      const qty = parseFloat(document.getElementById("selQty").value);
      const unit = document.getElementById("selUnit").value;

      if(!Number.isFinite(qty) || qty <= 0){ alert("Quantité invalide."); return; }

      meals[currentMeal].push({ name: selectedFood, qty, unit });
      document.getElementById("selQty").value = "";
      renderMealItems();
    }

    function addManual(){
      if(!currentMeal) return;

      const name = document.getElementById("manualName").value.trim();
      const qty = parseFloat(document.getElementById("qty").value);
      const unit = document.getElementById("unit").value;

      if(name === "" || !Number.isFinite(qty) || qty <= 0){
        alert("Remplis nom + quantité.");
        return;
      }

      meals[currentMeal].push({ name, qty, unit });
      document.getElementById("manualName").value = "";
      document.getElementById("qty").value = "";
      renderMealItems();
    }

    function removeItem(index){
      meals[currentMeal].splice(index, 1);
      renderMealItems();
    }

    function renderMealItems(){
      const area = document.getElementById("mealItems");
      const list = meals[currentMeal] || [];

      if(list.length === 0){
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

    function saveAndClose(){
      updateSummary("petitdej");
      updateSummary("dejeuner");
      updateSummary("diner");
      closeModal();
    }

    function updateSummary(mealKey){
      const target = document.getElementById("summary_" + mealKey);
      const list = meals[mealKey];

      if(list.length === 0){ target.textContent = "Aucun aliment ajouté."; return; }

      target.innerHTML = "<ul>" + list.map(it =>
        "<li>" + esc(it.name) + " — " + it.qty + " " + esc(it.unit) + "</li>"
      ).join("") + "</ul>";
    }

    function setMood(value, btn){
      mood = value;
      document.getElementById("moodText").textContent = value;
      document.querySelectorAll(".mood-btn").forEach(b => b.classList.remove("selected"));
      btn.classList.add("selected");
    }

    /* ------------------ API Open Food Facts (direct) ------------------ */

    function toNum(v){
      const x = parseFloat(v);
      return Number.isFinite(x) ? x : 0;
    }

    async function fetchNutritionPer100g(foodName){
      const offUrl =
        "https://world.openfoodfacts.org/api/v2/search?search_terms=" +
        encodeURIComponent(foodName) +
        "&page_size=1&fields=product_name,nutriments";

      // IMPORTANT : fetch direct (pas de proxy)
      const res = await fetch(offUrl);
      if(!res.ok) throw new Error("HTTP " + res.status);

      const data = await res.json();
      const p = (data && data.products && data.products[0]) ? data.products[0] : null;
      if(!p || !p.nutriments) return null;

      const n = p.nutriments;

      return {
        product_name: p.product_name || foodName,
        kcal_100g: toNum(n["energy-kcal_100g"]),
        carbs_100g: toNum(n["carbohydrates_100g"]),
        prot_100g: toNum(n["proteins_100g"]),
        fat_100g:  toNum(n["fat_100g"])
      };
    }

    async function analyserJourneeAPI(){
      if(mood === ""){
        alert("Choisis ton ressenti avant l’analyse.");
        return;
      }

      const resultBox = document.getElementById("resultBox");
      resultBox.textContent = "Résultat : analyse en cours...";

      let totalKcal = 0, totalCarbs = 0, totalProt = 0, totalFat = 0;
      let notes = [];

      const allMeals = ["petitdej","dejeuner","diner"];

      for(const mk of allMeals){
        for(const item of meals[mk]){
          const qty = Number(item.qty);
          const unit = item.unit;

          // calcul précis seulement si en g
          if(unit !== "g"){
            notes.push(`- ${item.name}: unité "${unit}" -> pas calculé précisément (mets en g pour test)`);
            continue;
          }

          try{
            const nut = await fetchNutritionPer100g(item.name);
            if(!nut){
              notes.push(`- ${item.name}: pas trouvé dans Open Food Facts`);
              continue;
            }

            const facteur = qty / 100;

            totalKcal  += nut.kcal_100g  * facteur;
            totalCarbs += nut.carbs_100g * facteur;
            totalProt  += nut.prot_100g  * facteur;
            totalFat   += nut.fat_100g   * facteur;

          }catch(e){
            // ici on affiche le vrai message d'erreur
            notes.push(`- ${item.name}: erreur API (${e.message})`);
          }
        }
      }

      let conseil = "";
      if(totalCarbs > 250) conseil = "Glucides assez élevés aujourd'hui, surveille les portions de féculents/sucrés.";
      else if(totalCarbs < 120) conseil = "Glucides plutôt bas, attention aux risques d'hypo si traitement.";
      else conseil = "Répartition glucides ok globalement (estimation).";

      resultBox.textContent =
        "Résultat :\n" +
        "- Ressenti : " + mood + "\n" +
        "- Total kcal : " + totalKcal.toFixed(0) + "\n" +
        "- Glucides (g) : " + totalCarbs.toFixed(1) + "\n" +
        "- Protéines (g) : " + totalProt.toFixed(1) + "\n" +
        "- Lipides (g) : " + totalFat.toFixed(1) + "\n\n" +
        "Conseil : " + conseil + "\n\n" +
        (notes.length ? ("Notes :\n" + notes.join("\n")) : "Notes : rien à signaler") +
        "\n\n" +
        "Si ça bloque encore en local, lance un petit serveur :\n" +
        "python -m http.server 8000 puis ouvre http://localhost:8000/";
    }

    function esc(str){
      return String(str)
        .replaceAll("&", "&amp;").replaceAll("<", "&lt;").replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;").replaceAll("'", "&#039;");
    }
  </script>

<?php include 'footer.php'; ?>
</body>
</html>
