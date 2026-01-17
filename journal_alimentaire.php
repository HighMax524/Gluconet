<?php require_once 'backend/check_subscription.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gluconet – Journal alimentaire</title>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style/main.css">
  <link href='res/logo_site.png' rel='icon'>
  <link rel="stylesheet" href="style/css_journalAlim.css">
  <script>
    const USER_SUBSCRIPTION = "<?php echo $_SESSION['type_abonnement'] ?? 'Standard'; ?>";
  </script>
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

          <div class="spacer-8"></div>
          
          <button class="boutton_form" type="button" onclick="analyserJourneeAPI()">Analyser ma journée (API)</button>

          <div class="result-box" id="resultBox">Résultat : (vide)</div>

          <div class="astuce-text">
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

            <div class="summary-block no-margin-bottom">
              <div class="summary-title">Dîner</div>
              <div id="summary_diner">Aucun aliment ajouté.</div>
            </div>
          </div>

          <div class="spacer-14"></div>

          <div class="form-section">
            <p class="section-title">Ressenti</p>

            <div class="mood-row">
              <button class="mood-btn" type="button" onclick="setMood('bien', this)">🙂</button>
              <button class="mood-btn" type="button" onclick="setMood('moyen', this)">😐</button>
              <button class="mood-btn" type="button" onclick="setMood('mal', this)">🙁</button>
            </div>

            <p id="moodText" class="mood-text">Non renseigné</p>
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
          <h2 class="modal-h2">Ajouter un repas</h2>
          <p id="modalSub" class="modal-sub">Repas : ...</p>
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
          <button class="food-item" type="button" data-name="Riz cuit" onclick="selectFood('Riz cuit')">Riz
            cuit</button>
          <button class="food-item" type="button" data-name="Poulet" onclick="selectFood('Poulet')">Poulet</button>
          <button class="food-item" type="button" data-name="Yaourt nature" onclick="selectFood('Yaourt nature')">Yaourt
            nature</button>
          <button class="food-item" type="button" data-name="Pain complet" onclick="selectFood('Pain complet')">Pain
            complet</button>
          <button class="food-item" type="button" data-name="Couscous"
            onclick="selectFood('Couscous')">Couscous</button>
          <button class="food-item" type="button" data-name="Fromage blanc"
            onclick="selectFood('Fromage blanc')">Fromage blanc</button>
        </div>

        <div class="form-section manual-input-section">
          <p class="section-title center">Ajout manuel</p>

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

      <hr class="divider">

      <div class="form-section manual-input-section">
        <p class="section-title center">Aliment sélectionné</p>

        <div id="selectedBox" class="selected-display">Aucun aliment sélectionné.</div>

        <div class="row2 mt-10">
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

      <hr class="divider">

      <div class="form-section manual-input-section">
        <p class="section-title center">Contenu du repas</p>

        <div class="meal-items" id="mealItems"></div>

        <div class="actions-bottom">
          <button class="boutton_form" type="button" onclick="saveAndClose()">Enregistrer</button>
          <button class="boutton_form btn-cancel" type="button" onclick="closeModal()">Annuler</button>
        </div>
      </div>

    </div>
  </div>

  <script src="JS/journal_alimentaire.js?v=<?php echo time(); ?>"></script>

  <?php include 'footer.php'; ?>
</body>

</html>