<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Gluconet</title>
    <link rel="stylesheet" href="res/style.css">
    <link href='res/logo_site.png' rel='icon'>
</head>

<body>
    <?php include 'nav_bar.php'; ?>

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
            <form id="paymentForm" action="backend/traitement_paiement.php" method="POST">
                <input type="hidden" name="offre" id="selectedOffer" value="">

                <input type="text" id="name" placeholder="Nom du titulaire de la carte" required>

                <input type="text" id="cardNumber" placeholder="Numéro de carte" maxlength="16" required>

                <div class="row">
                    <input type="text" id="cvv" placeholder="CVV" maxlength="3" required>
                    <label for="expire">Date d'expiration</label>

                    <input type="text" id="expire" placeholder="MM / AA" inputmode="numeric" maxlength="7" required>

                    <span id="expire-error" style="color:red; display:none;">
                        Date invalide
                    </span>


                    <button type="submit" class="pay-btn">Payer</button>

            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="JS/paiement.js"></script>
</body>

</html>