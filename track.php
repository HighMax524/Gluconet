<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlucoNet - Suivi de Glycémie</title>
    <link rel="stylesheet" href="res/style.css">
    <link href='res/logo_site.png' rel='icon'>
</head>

<body>
    <?php include 'nav_bar.php'; ?>
    <div id="top">
        <div class="box" id="rate_glucose_box">
            <div class="rate_glucose_box_side">
                <div class="cercle" id="cercle_exterieur_glucose">
                    <div class="cercle" id="cercle_interieur_glucose">
                        <p id="taux_glucose">0.3<br>mmol/L</p>
                    </div>
                </div>
            </div>
            <div class="rate_glucose_box_side">
                <h1 class="title_gucose_rate">Pic de glycémie max</h1>
                <div class="rate_glucose" id="max_value_box">
                    <p>20 mg/L</p>
                </div>
                <h1 class="title_gucose_rate">Pic de glycémie min</h1>
                <div class="rate_glucose" id="min_value_box">
                    <p>11.2 mg/L</p>
                </div>
            </div>
        </div>

        <div id="second_colone">
            <div class="box" id="alerte_box">
                <div id="button_and_range_display">
                    <button><span id="alert_bell">🔔</span> Alert ON</button>
                    <p class="range_display">
                        <span id="min_alert_value">0.4</span> - <span id="max_alert_value">0.6</span>
                    </p>
                </div>
                <div id="slider_container">
                    <input type="range" min="0" max="2" value="0.4" step="0.01" class="slider" id="glucose_range_min">
                    <input type="range" min="0" max="2" value="0.6" step="0.01" class="slider" id="glucose_range_max">
                </div>
                <div id="slider_labels">
                    <p>0</p>
                    <p>0.4</p>
                    <p>0.6</p>
                    <p>2</p>
                </div>
            </div>

            <div class="box" id="calorie_box">
                <div class="calorie_side">
                    <h1 class="title_calorie">Absorbées</h1>
                    <div class="cercle" id="cercle_exterieur_calorie_abs">
                        <div class="cercle" id="cercle_interieur_calorie_abs">
                            <p id="taux_calorie_abs">12<br>KCal</p>
                        </div>
                    </div>
                </div>

                <div class="calorie_side">
                    <h1 class="title_calorie">Dépensées</h1>
                    <div class="cercle" id="cercle_exterieur_calorie_dep">
                        <div class="cercle" id="cercle_interieur_calorie_dep">
                            <p id="taux_calorie_dep">11<br>KCal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Actions Rapides -->
    <!-- Section Actions Rapides -->
    <!-- Section Actions Rapides -->
    <div class="box quick-actions-box" id="actions_dashboard">

        <div class="action-item" onclick="window.location.href='page_activites.php'">
            <div class="action-icon-circle">
                <span class="material-symbols-outlined">directions_run</span>
            </div>
            <h3 class="action-title">Activités</h3>
        </div>

        <div class="action-item" onclick="window.location.href='page_medicaments.php'">
            <div class="action-icon-circle">
                <span class="material-symbols-outlined">medication</span>
            </div>
            <h3 class="action-title">Médicaments</h3>
        </div>

        <div class="action-item" onclick="window.location.href='paiement.php'">
            <div class="action-icon-circle">
                <span class="material-symbols-outlined">credit_card</span>
            </div>
            <h3 class="action-title">Abonnement</h3>
        </div>

        <div class="action-item" onclick="window.location.href='recherche_medecin.php'">
            <div class="action-icon-circle">
                <span class="material-symbols-outlined">person_search</span>
            </div>
            <h3 class="action-title">Mon Médecin</h3>
        </div>
    </div>

    <div class="box" id="chart">
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>