<?php
session_start();
require_once 'backend/db_connect.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Offres - Gluconet</title>
    <link rel="stylesheet" href="res/style.css">
    <link href='res/logo_site.png' rel='icon'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" />
</head>

<body>
    <?php include 'nav_bar.php'; ?>

    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="card" style="width: auto; max-width: 90%; display: inline-block;">
            <h1 class="pricing-title">Choisissez votre offre</h1>

            <div class="pricing-container">
                <!-- Offre Standard -->
                <div class="pricing-card">
                    <h2 class="pricing-plan">Standard</h2>
                    <ul class="pricing-features">
                        <li>-Taux de glucose en temps réel</li>
                        <li>+</li>
                        <li>-Suivi avec ton médecin traitant</li>
                        <li>+</li>
                        <li>-Une proposition d'alimentation adaptée par jour</li>
                    </ul>
                    <div class="pricing-price">
                        4€<span class="period">/mois</span>
                    </div>
                    <button class="pricing-btn" onclick="selectOffer('Standard')">Choisir</button>
                </div>

                <!-- Offre Premium -->
                <div class="pricing-card premium">
                    <h2 class="pricing-plan">Premium</h2>
                    <ul class="pricing-features">
                        <li>-Standard</li>
                        <li>+</li>
                        <li>-Une proposition d'alimentation adaptée illimitée</li>
                        <li>+</li>
                        <li>-Suivi activité physique/médicament</li>
                    </ul>
                    <div class="pricing-price">
                        7€<span class="period">/mois</span>
                    </div>
                    <button class="pricing-btn" onclick="selectOffer('Premium')">Choisir</button>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script>
        function selectOffer(offerName) {
            // Redirection vers la page de paiement avec l'offre pré-sélectionnée
            window.location.href = 'paiement.php?offre=' + encodeURIComponent(offerName);
        }
    </script>
</body>

</html>