<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Gluconet</title>
    <link rel="stylesheet" href="res/style.css">
    <?php include 'nav_bar.php'; ?>
</head>

<body>

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
    <?php include 'footer.php'; ?>
    <script src="paiement.js"></script>
</body>

</html>