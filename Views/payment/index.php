<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Gluconet</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/paiement.css">
</head>
<body>

<header>
    <div class="logo" onclick="window.location.href='<?= BASE_URL ?>/'">
        <img src="<?= BASE_URL ?>/img/logo_nom_site.png" class="logo-icon">
    </div>

    <button class="help-btn">Aide</button>

    <div class="user-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
         </svg>
    </div>
</header>

<main>
    <div class="card">

        <h1>Paiement</h1>

        <div class="offers">
            <div id="standard" class="offer">
                <h2>Standard</h2>
                <p>4€<span>/mois</span></p>
            </div>

            <div id="premium" class="offer">
                <h2>Premium</h2>
                <p>7€<span>/mois</span></p>
            </div>
        </div>

        <!-- FORMULAIRE -->
        <form id="paymentForm">

            <input type="text" id="name" placeholder="Nom du titulaire de la carte" required>

            <input type="text" id="cardNumber" placeholder="Numéro de carte" maxlength="16" required>

            <div class="row">
                <input type="text" id="cvv" placeholder="CVV" maxlength="3" required>
                <input type="month" id="expire" required>
            </div>

            <button type="submit" class="pay-btn">Payer</button>

        </form>
    </div>
</main>

<script src="<?= BASE_URL ?>/js/paiement.js"></script>
</body>
</html>
