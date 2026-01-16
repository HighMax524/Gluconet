<?php
session_start();
require_once 'backend/check_subscription.php';
// 1. Vérification Médecin (reste ici pour la redirection immédiate si accès direct)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'medecin') {
    header("Location: connexion.php");
    exit();
}

require_once 'backend/recuperation_dossier_patient.php';

$medecin_rpps = $_SESSION['medecin_rpps'];
$patient_user_id = $_GET['id'] ?? null;

$dossierData = getDossierPatientData($conn, $medecin_rpps, $patient_user_id);

if (isset($dossierData['error'])) {
    header("Location: " . $dossierData['redirect'] . "?error=" . urlencode($dossierData['error']));
    exit();
}

// Extraction des variables pour l'affichage
$patientData = $dossierData['patientData'];
$historique_poids = $dossierData['historique_poids'];
$labels_poids = $dossierData['labels_poids'];
$data_poids = $dossierData['data_poids'];
$current_weight = $dossierData['current_weight'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier Patient - GlucoNet</title>
    <link rel="stylesheet" href="res/main.css">
    <link rel="stylesheet" href="style/dossier_patient.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href='res/logo_site.png' rel='icon'>
</head>

<body>
    <?php include 'nav_bar.php'; ?>

    <div class="dossier-container">
        <a href="medecin_dashboard.php" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span> Retour au tableau de bord
        </a>

        <!-- En-tête Patient -->
        <div class="patient-header-card">
            <div class="ph-avatar">
                <span class="material-symbols-outlined" style="font-size: 50px;">person</span>
            </div>
            <div class="ph-info">
                <h1><?php echo htmlspecialchars($patientData['prenom'] . ' ' . $patientData['nom']); ?></h1>
                <div class="tags">
                    <span
                        class="tag tag-diabetes"><?php echo htmlspecialchars($patientData['type_diabete'] ?? 'Non précisé'); ?></span>
                    <span class="tag tag-age"><?php echo htmlspecialchars($patientData['age'] ?? '?'); ?> ans</span>
                    <span class="tag"
                        style="background: #e8f5e9; color: #2e7d32;"><?php echo htmlspecialchars($patientData['sexe'] ?? ''); ?></span>
                </div>
                <p style="margin-top: 10px; color: #666;">
                    <span class="material-symbols-outlined" style="vertical-align: sub; font-size: 18px;">mail</span>
                    <?php echo htmlspecialchars($patientData['email']); ?>
                </p>
                <p style="color: #666;">
                    <strong>Diagnostic :</strong>
                    <?php echo htmlspecialchars($patientData['date_diagnostic'] ?? 'Non renseigné'); ?>
                </p>
            </div>
        </div>

        <div class="metrics-grid">
            <!-- Graphique Poids -->
            <div class="metric-card">
                <div class="metric-title">
                    <span class="material-symbols-outlined">monitor_weight</span>
                    Évolution du Poids
                </div>
                <div class="chart-box">
                    <canvas id="weightChart"></canvas>
                </div>
                <p style="text-align: center; margin-top: 10px; font-weight: bold; color: #2e7d32;">
                    Poids Actuel : <?php echo htmlspecialchars($current_weight); ?> kg
                </p>
            </div>

            <!-- Graphique Glycémie (Placeholder / Statique pour l'instant) -->
            <div class="metric-card">
                <div class="metric-title">
                    <span class="material-symbols-outlined">water_drop</span>
                    Glycémie (Simulation)
                </div>
                <div class="chart-box"
                    style="display: flex; align-items: center; justify-content: center; background: #f9f9f9; border-radius: 10px;">
                    <p style="color: #888; text-align: center;">Aucune donnée de glycémie connectée.<br>Affichage des
                        données simulées.</p>
                </div>
            </div>
        </div>

        <!-- Détails / Historique -->
        <div class="metric-card">
            <div class="metric-title">
                <span class="material-symbols-outlined">history</span>
                Historique des pesées
            </div>
            <?php if (empty($historique_poids)): ?>
                <p style="color: #888; font-style: italic;">Aucune donnée enregistrée.</p>
            <?php else: ?>
                <ul class="data-list">
                    <?php foreach (array_reverse($historique_poids) as $h): ?>
                        <li class="data-item">
                            <span><?php echo date('d/m/Y H:i', strtotime($h['date_heure'])); ?></span>
                            <strong><?php echo htmlspecialchars($h['poids']); ?> kg</strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Graphique Poids
        const ctxWeight = document.getElementById('weightChart').getContext('2d');
        new Chart(ctxWeight, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels_poids); ?>,
                datasets: [{
                    label: 'Poids (kg)',
                    data: <?php echo json_encode($data_poids); ?>,
                    borderColor: '#2e7d32',
                    backgroundColor: 'rgba(46, 125, 50, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#2e7d32',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: false }
                }
            }
        });
    </script>
</body>

</html>