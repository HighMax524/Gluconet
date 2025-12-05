<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlucoNet - Suivi de Glycémie</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/track.css">
</head>
<body>

    <!-- Header from bar_nav.html -->
    <header>
        <div class="logo-container">
            <img src="<?= BASE_URL ?>/img/logo_nom_site.png" alt="Logo Gluconet" class="logo-icon" />
        </div>
        
        <nav>
            <button onclick="window.location.href='<?= BASE_URL ?>/home'" class="nav-btn">Accueil</button>
            <button onclick="window.location.href='<?= BASE_URL ?>/track'" class="nav-btn">Onglet</button>
            <button onclick="window.location.href='#'" class="nav-btn">Aide</button>
        </nav>

        <div class="user-profile">
            <svg class="user-icon" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
    </header>

    <!-- Content from trackView.php -->
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
                <div class="rate_glucose" id="max_value_box"><p>20 mg/L</p></div>
                <h1 class="title_gucose_rate">Pic de glycémie min</h1>
                <div class="rate_glucose" id="min_value_box"><p>11.2 mg/L</p></div>
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

    <div class="box" id="chart">
    </div>

    <script src="<?= BASE_URL ?>/js/track.js"></script>
</body>
</html>
