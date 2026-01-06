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
    <?php
    session_start();
    include 'nav_bar.php';
    ?>

    <main>
        <div class="card">

            <h1>Paiement</h1>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message" style="color: red; text-align: center; margin-bottom: 15px;">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

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
            <!-- FORMULAIRE -->
            <form id="paymentForm" action="backend/traitement_paiement.php" method="POST">
                <input type="hidden" id="selectedOffer" name="offre" value="">

                <!-- Le nom peut être pré-rempli ou laissé vide, Stripe le redemandera souvent de toute façon sur Checkout,
                     mais on peut l'envoyer ou juste l'utiliser pour notre base. Ici on le garde simple. -->
                <!-- <input type="text" id="name" name="nom_titulaire" placeholder="Nom du titulaire (facultatif)" > -->

                <div class="secure-payment-notice" style="text-align: center; margin: 20px 0; color: #555;">
                    <p>Vous allez être redirigé vers une plateforme de paiement sécurisée pour valider votre abonnement.
                    </p>
                </div>

                <button type="submit" class="pay-btn">Procéder au paiement sécurisé</button>

            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="paiement.js"></script>
</body>

</html>