<?php
session_start();
require_once 'backend/check_subscription.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}
require_once 'backend/db_connect.php';

$user_id = $_SESSION['user_id'];
$id_patient = $user_id;

// Initialisation des variables
$last_glucose = "N/A";
$max_glucose_today = "-";
$min_glucose_today = "-";
$alert_min = 0.70;
$alert_max = 1.40;
$cal_abs = 0;
$cal_dep = 0;
$chart_labels = [];
$chart_data = [];

try {
    // 1. Stats Glycémie (Dernière journée avec données)
    // On cherche d'abord la date la plus récente ayant des données
    $stmtDate = $conn->prepare("SELECT DATE(date_heure) as d FROM mesureglycemie WHERE id_patient = ? ORDER BY date_heure DESC LIMIT 1");
    $stmtDate->execute([$id_patient]);
    $lastDateRow = $stmtDate->fetch(PDO::FETCH_ASSOC);

    // Par défaut aujourd'hui, sinon la date trouvée
    $target_date = $lastDateRow ? $lastDateRow['d'] : date('Y-m-d');
    $display_date = date('d/m/Y', strtotime($target_date));

    // Récupération des données pour cette date cible
    $stmt = $conn->prepare("SELECT valeur, DATE_FORMAT(date_heure, '%H:%i') as heure FROM mesureglycemie WHERE id_patient = ? AND DATE(date_heure) = ? ORDER BY date_heure ASC");
    $stmt->execute([$id_patient, $target_date]);
    $resultats_jour = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultats_jour) > 0) {
        $valeurs = array_column($resultats_jour, 'valeur');
        $last_glucose = end($valeurs); // La dernière valeur (puisque trié ASC)
        $max_glucose_today = max($valeurs);
        $min_glucose_today = min($valeurs);

        // Données pour le graphique
        foreach ($resultats_jour as $row) {
            $chart_labels[] = $row['heure'];
            $chart_data[] = $row['valeur'];
        }
    }

    // 2. Seuils Alertes
    $stmt = $conn->prepare("SELECT seuil_alerte_bas, seuil_alerte_haut FROM patient WHERE id_utilisateur = ?");
    $stmt->execute([$id_patient]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($patient) {
        if ($patient['seuil_alerte_bas'])
            $alert_min = $patient['seuil_alerte_bas'];
        if ($patient['seuil_alerte_haut'])
            $alert_max = $patient['seuil_alerte_haut'];
    }

    // 3. Calories Absorbées (Repas du jour)
    $stmt = $conn->prepare("SELECT SUM(calories) as total FROM repas WHERE id_patient = ? AND DATE(date_heure) = CURDATE()");
    $stmt->execute([$id_patient]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $cal_abs = $row['total'] ? round($row['total']) : 0;

    // 4. Calories Dépensées (Activités du jour)
    // Hypothèse : 1 min d'activité = 5 KCal (si pas précisé ailleurs)
    $stmt = $conn->prepare("SELECT SUM(duree) as total_min FROM activites WHERE id_patient = ? AND date = CURDATE()");
    $stmt->execute([$id_patient]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $cal_dep = $row['total_min'] ? round($row['total_min'] * 5) : 0;

} catch (PDOException $e) {
    // Gestion d'erreur minimale
    error_log("Erreur Dashboard: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlucoNet - Suivi de Glycémie</title>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/track.css">
    <link href='res/logo_site.png' rel='icon'>
    <!-- Material Symbols pour les icônes -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- Chart.js pour le graphique -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include 'nav_bar.php'; ?>
    <div id="top">
        <div class="box" id="rate_glucose_box">
            <div class="rate_glucose_box_side">
                <div class="cercle" id="cercle_exterieur_glucose">
                    <div class="cercle" id="cercle_interieur_glucose">
                        <!-- Affichage de la dernière glycémie -->
                        <p id="taux_glucose"><?php echo htmlspecialchars($last_glucose); ?><br><span
                                style="font-size:0.5em">g/L</span></p>
                    </div>
                </div>
            </div>
            <div class="rate_glucose_box_side">
                <h1 class="title_gucose_rate">Pic de glycémie max</h1>
                <div class="rate_glucose" id="max_value_box">
                    <p><?php echo htmlspecialchars($max_glucose_today); ?> g/L</p>
                </div>
                <h1 class="title_gucose_rate">Pic de glycémie min</h1>
                <div class="rate_glucose" id="min_value_box">
                    <p><?php echo htmlspecialchars($min_glucose_today); ?> g/L</p>
                </div>
            </div>
        </div>

        <div id="second_colone">
            <div class="box" id="alerte_box">
                <div id="button_and_range_display">
                    <button><span id="alert_bell">🔔</span> Alert ON</button>
                    <p class="range_display">
                        <span id="min_alert_value"><?php echo htmlspecialchars($alert_min); ?></span> - <span
                            id="max_alert_value"><?php echo htmlspecialchars($alert_max); ?></span>
                    </p>
                </div>
                <!-- Sliders configurés avec les valeurs de la BDD -->
                <div id="slider_container">
                    <input type="range" min="0" max="3" value="<?php echo htmlspecialchars($alert_min); ?>" step="0.01"
                        class="slider" id="glucose_range_min" disabled>
                    <input type="range" min="0" max="3" value="<?php echo htmlspecialchars($alert_max); ?>" step="0.01"
                        class="slider" id="glucose_range_max" disabled>
                </div>
                <div id="slider_labels">
                    <p>0</p>
                    <p>3</p>
                </div>
            </div>

            <div class="box" id="calorie_box">
                <div class="calorie_side">
                    <h1 class="title_calorie">Absorbées</h1>
                    <div class="cercle" id="cercle_exterieur_calorie_abs">
                        <div class="cercle" id="cercle_interieur_calorie_abs">
                            <p id="taux_calorie_abs"><?php echo htmlspecialchars($cal_abs); ?><br>KCal</p>
                        </div>
                    </div>
                </div>

                <div class="calorie_side">
                    <h1 class="title_calorie">Dépensées</h1>
                    <div class="cercle" id="cercle_exterieur_calorie_dep">
                        <div class="cercle" id="cercle_interieur_calorie_dep">
                            <p id="taux_calorie_dep"><?php echo htmlspecialchars($cal_dep); ?><br>KCal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique -->
    <div class="box" id="chart">
        <canvas id="myGlucoseChart"></canvas>
    </div>

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

        <div class="action-item" onclick="window.location.href='journal_alimentaire.php'">
            <div class="action-icon-circle">
                <span class="material-symbols-outlined">fork_spoon</span>
            </div>
            <h3 class="action-title">Journal alimentaire</h3>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Injection de configuration pour le JS
        window.GluconetConfig = {
            lastGlucose: <?php echo json_encode($last_glucose); ?>,
            alertMin: <?php echo json_encode($alert_min); ?>,
            alertMax: <?php echo json_encode($alert_max); ?>,
            chartLabels: <?php echo json_encode($chart_labels); ?>,
            chartData: <?php echo json_encode($chart_data); ?>,
            displayDate: <?php echo json_encode($display_date); ?>
        };
    </script>
    <script src="JS/track.js?v=<?php echo time(); ?>"></script>
</body>

</html>