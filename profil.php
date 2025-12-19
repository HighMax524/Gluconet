<?php
session_start();
require_once 'backend/db_connect.php';

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message_success = isset($_GET['success']) ? "Poids enregistré avec succès!" : "";
$message_error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "";

// Récupération des infos utilisateur et patient
try {
    $stmt = $conn->prepare("
        SELECT u.nom, u.prenom, u.email, p.type_diabete, p.age, p.taille, p.sexe, p.date_diagnostic 
        FROM utilisateur u 
        LEFT JOIN patient p ON u.id = p.id_utilisateur 
        WHERE u.id = ?
    ");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupération de l'historique de poids
    $stmtPoids = $conn->prepare("SELECT poids, date_heure FROM poids WHERE id_utilisateur = ? ORDER BY date_heure ASC");
    $stmtPoids->execute([$user_id]);
    $historique_poids = $stmtPoids->fetchAll(PDO::FETCH_ASSOC);

    // Préparation des données pour le graphique
    $labels_poids = [];
    $data_poids = [];
    $current_weight = "N/A";

    if ($historique_poids) {
        foreach ($historique_poids as $entry) {
            $date = new DateTime($entry['date_heure']);
            $labels_poids[] = $date->format('d/m/Y');
            $data_poids[] = $entry['poids'];
        }
        $current_weight = end($data_poids); // Le dernier poids (le plus récent car trié ASC pour le graphe, mais attention si on veut le plus récent absolu qui peut être le dernier inséré)
    }

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - GlucoNet</title>
    <link rel="stylesheet" href="res/style.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <?php include 'nav_bar.php'; ?>

    <div class="profile-container">
        <!-- Colonne Gauche: Infos Profil -->
        <div class="profile-card">
            <div class="user-header">
                <div class="user-avatar">
                    <span class="material-symbols-outlined" style="font-size: 50px;">person</span>
                </div>
                <h2><?php echo htmlspecialchars($user_info['prenom'] . ' ' . $user_info['nom']); ?></h2>
                <p style="color: #666;"><?php echo htmlspecialchars($user_info['type_diabete'] ?? 'Patient'); ?></p>
            </div>

            <ul class="info-list">
                <li>
                    <span class="material-symbols-outlined">mail</span>
                    <?php echo htmlspecialchars($user_info['email']); ?>
                </li>
                <li>
                    <span class="material-symbols-outlined">cake</span>
                    <?php echo htmlspecialchars($user_info['age'] ?? 'N/A'); ?> ans
                </li>
                <li>
                    <span class="material-symbols-outlined">wc</span>
                    <?php echo htmlspecialchars($user_info['sexe'] ?? 'N/A'); ?>
                </li>
                <li>
                    <span class="material-symbols-outlined">height</span>
                    <?php echo htmlspecialchars($user_info['taille'] ?? 'N/A'); ?> cm
                </li>
                <li>
                    <span class="material-symbols-outlined">monitor_weight</span>
                    Poids actuel: <strong><?php echo htmlspecialchars($current_weight); ?> kg</strong>
                </li>
                <li>
                    <span class="material-symbols-outlined">medical_information</span>
                    Diag: <?php echo htmlspecialchars($user_info['date_diagnostic'] ?? 'N/A'); ?>
                </li>
            </ul>
        </div>

        <!-- Colonne Droite: Suivi du Poids -->
        <div class="profile-card weight-tracking">
            <h2>Suivi du Poids</h2>

            <?php if ($message_success): ?>
                <div class="alert-success"><?php echo $message_success; ?></div>
            <?php endif; ?>
            <?php if ($message_error): ?>
                <div class="alert-error"><?php echo $message_error; ?></div>
            <?php endif; ?>

            <form action="backend/traitement_poids.php" method="POST" class="weight-form">
                <div class="form-group">
                    <label for="poids">Nouveau poids (kg)</label>
                    <input type="number" step="0.1" name="poids" id="poids" class="poids-input" placeholder="Ex: 75.5"
                        required>
                </div>
                <div class="form-group">
                    <label for="date_poids">Date</label>
                    <input type="datetime-local" name="date_poids" id="date_poids" class="poids-input"
                        value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                </div>
                <button type="submit" class="btn-add">Ajouter</button>
            </form>

            <div class="chart-container">
                <canvas id="weightChart"></canvas>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Configuration du graphique Chart.js
        const ctx = document.getElementById('weightChart').getContext('2d');
        const weightChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels_poids); ?>,
                datasets: [{
                    label: 'Poids (kg)',
                    data: <?php echo json_encode($data_poids); ?>,
                    borderColor: '#2e7d32', // --primary-color
                    backgroundColor: 'rgba(46, 125, 50, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#2e7d32',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.3 // Courbe lisse
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Poids (kg)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>

</html>