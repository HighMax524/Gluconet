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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
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
    <div class="box" id="actions_dashboard"
        style="display: flex; justify-content: center; gap: 40px; margin-top: 20px; align-items: center; padding: 40px; flex-wrap: wrap;">

        <div onclick="window.location.href='page_activites.php'"
            style="cursor: pointer; text-align: center; transition: all 0.3s ease; width: 150px;"
            onmouseover="this.querySelector('.icon-circle').style.transform='scale(1.1)'; this.querySelector('.icon-circle').style.backgroundColor='#c8e6c9';"
            onmouseout="this.querySelector('.icon-circle').style.transform='scale(1)'; this.querySelector('.icon-circle').style.backgroundColor='#e0f2f1';">
            <div class="icon-circle"
                style="background: #e0f2f1; padding: 20px; border-radius: 50%; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; border: 2px solid #2e7d32; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <span class="material-symbols-outlined" style="font-size: 48px; color: #2e7d32;">directions_run</span>
            </div>
            <h3 style="color: #2e7d32; font-size: 1.2rem; margin: 0; font-weight: 600;">Activités</h3>
        </div>

        <div onclick="window.location.href='page_medicaments.php'"
            style="cursor: pointer; text-align: center; transition: all 0.3s ease; width: 150px;"
            onmouseover="this.querySelector('.icon-circle').style.transform='scale(1.1)'; this.querySelector('.icon-circle').style.backgroundColor='#c8e6c9';"
            onmouseout="this.querySelector('.icon-circle').style.transform='scale(1)'; this.querySelector('.icon-circle').style.backgroundColor='#e0f2f1';">
            <div class="icon-circle"
                style="background: #e0f2f1; padding: 20px; border-radius: 50%; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; border: 2px solid #2e7d32; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <span class="material-symbols-outlined" style="font-size: 48px; color: #2e7d32;">medication</span>
            </div>
            <h3 style="color: #2e7d32; font-size: 1.2rem; margin: 0; font-weight: 600;">Médicaments</h3>
        </div>

        <div onclick="window.location.href='paiement.php'"
            style="cursor: pointer; text-align: center; transition: all 0.3s ease; width: 150px;"
            onmouseover="this.querySelector('.icon-circle').style.transform='scale(1.1)'; this.querySelector('.icon-circle').style.backgroundColor='#c8e6c9';"
            onmouseout="this.querySelector('.icon-circle').style.transform='scale(1)'; this.querySelector('.icon-circle').style.backgroundColor='#e0f2f1';">
            <div class="icon-circle"
                style="background: #e0f2f1; padding: 20px; border-radius: 50%; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; border: 2px solid #2e7d32; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <span class="material-symbols-outlined" style="font-size: 48px; color: #2e7d32;">credit_card</span>
            </div>
            <h3 style="color: #2e7d32; font-size: 1.2rem; margin: 0; font-weight: 600;">Abonnement</h3>
        </div>

        <div onclick="window.location.href='recherche_medecin.php'"
            style="cursor: pointer; text-align: center; transition: all 0.3s ease; width: 150px;"
            onmouseover="this.querySelector('.icon-circle').style.transform='scale(1.1)'; this.querySelector('.icon-circle').style.backgroundColor='#c8e6c9';"
            onmouseout="this.querySelector('.icon-circle').style.transform='scale(1)'; this.querySelector('.icon-circle').style.backgroundColor='#e0f2f1';">
            <div class="icon-circle"
                style="background: #e0f2f1; padding: 20px; border-radius: 50%; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; border: 2px solid #2e7d32; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <span class="material-symbols-outlined" style="font-size: 48px; color: #2e7d32;">person_search</span>
            </div>
            <h3 style="color: #2e7d32; font-size: 1.2rem; margin: 0; font-weight: 600;">Mon Médecin</h3>
        </div>
    </div>

    <div class="box" id="chart">
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>