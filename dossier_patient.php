<?php
session_start();
require_once 'backend/db_connect.php';

// 1. Vérification Médecin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'medecin') {
    header("Location: connexion.php");
    exit();
}

$medecin_rpps = $_SESSION['medecin_rpps'];
$patient_user_id = $_GET['id'] ?? null;

if (!$patient_user_id) {
    header("Location: medecin_dashboard.php?error=Patient non spécifié");
    exit();
}

try {
    // 2. Vérification Relation (Sécurité)
    // On vérifie que ce médecin (RPPS) suit bien ce patient (id_patient = patient_user_id)
    $stmtCheck = $conn->prepare("
        SELECT id FROM relation_patient_medecin 
        WHERE id_medecin = ? AND id_patient = ? AND statut = 'Approuve'
    ");
    $stmtCheck->execute([$medecin_rpps, $patient_user_id]);

    if ($stmtCheck->rowCount() === 0) {
        header("Location: medecin_dashboard.php?error=Accès non autorisé à ce patient");
        exit();
    }

    // 3. Récupération Infos Patient
    $stmtInfo = $conn->prepare("
        SELECT u.nom, u.prenom, u.email, 
               p.type_diabete, p.age, p.sexe, p.taille, p.date_diagnostic
        FROM utilisateur u
        JOIN patient p ON u.id = p.id_utilisateur
        WHERE u.id = ?
    ");
    $stmtInfo->execute([$patient_user_id]);
    $patientData = $stmtInfo->fetch(PDO::FETCH_ASSOC);

    if (!$patientData) {
        die("Patient introuvable.");
    }

    // 4. Récupération Historique Poids
    $stmtPoids = $conn->prepare("SELECT poids, date_heure FROM poids WHERE id_utilisateur = ? ORDER BY date_heure ASC");
    $stmtPoids->execute([$patient_user_id]);
    $historique_poids = $stmtPoids->fetchAll(PDO::FETCH_ASSOC);

    // Préparation données graphique Poids
    $labels_poids = [];
    $data_poids = [];
    $current_weight = "N/A";

    if ($historique_poids) {
        foreach ($historique_poids as $entry) {
            $date = new DateTime($entry['date_heure']);
            $labels_poids[] = $date->format('d/m/Y');
            $data_poids[] = $entry['poids'];
        }
        $current_weight = end($data_poids);
    }

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier Patient - GlucoNet</title>
    <link rel="stylesheet" href="res/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dossier-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #666;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        .patient-header-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
        }

        .ph-avatar {
            width: 100px;
            height: 100px;
            background: #e0f2f1;
            color: var(--primary-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .ph-info h1 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .tags {
            display: flex;
            gap: 10px;
        }

        .tag {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .tag-diabetes {
            background: #e3f2fd;
            color: #1976d2;
        }

        .tag-age {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .metric-title {
            color: #666;
            font-size: 1rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-box {
            height: 300px;
            position: relative;
        }

        .data-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 250px;
            overflow-y: auto;
        }

        .data-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-size: 0.95rem;
        }
    </style>
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