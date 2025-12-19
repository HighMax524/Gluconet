<?php
session_start();
require_once 'backend/db_connect.php';

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message_success = isset($_GET['success']) ? "Poids enregistré avec succès !" : "";
$message_error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "";

// Récupération des infos utilisateur et patient
try {
    // On récupère les infos de la table utilisateur et, si dispo, de la table patient
    $stmt = $conn->prepare("
        SELECT u.nom, u.prenom, u.email, 
               p.type_diabete, p.age, p.taille, p.sexe, p.date_diagnostic 
        FROM utilisateur u 
        LEFT JOIN patient p ON u.id = p.id_utilisateur 
        WHERE u.id = ?
    ");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_info) {
        // Cas rare : l'utilisateur existe en session mais pas en BDD
        session_destroy();
        header("Location: connexion.php");
        exit();
    }

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
            $labels_poids[] = $date->format('d/m/Y'); // Format pour le graphique
            $data_poids[] = $entry['poids'];
        }
        // Le dernier poids du tableau est le plus récent (car ORDER BY ASC)
        $current_weight = end($data_poids);
    } else {
        // Si aucun historique, on essaie de prendre le poids initial dans la table patient s'il y avait un champ poids (mais ici c'est taille)
        // Donc on laisse N/A
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
    <!-- Google Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" />
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
                <p style="color: #666; font-style: italic;">
                    <?php echo htmlspecialchars($user_info['type_diabete'] ?? 'Profil Utilisateur'); ?>
                </p>
            </div>

            <ul class="info-list">
                <li>
                    <span class="material-symbols-outlined">mail</span>
                    <span><?php echo htmlspecialchars($user_info['email']); ?></span>
                </li>
                <li>
                    <span class="material-symbols-outlined">cake</span>
                    <span><?php echo htmlspecialchars($user_info['age'] ?? '--'); ?> ans</span>
                </li>
                <li>
                    <span class="material-symbols-outlined">wc</span>
                    <span><?php echo htmlspecialchars($user_info['sexe'] ?? '--'); ?></span>
                </li>
                <li>
                    <span class="material-symbols-outlined">height</span>
                    <span><?php echo htmlspecialchars($user_info['taille'] ?? '--'); ?> cm</span>
                </li>
                <li>
                    <span class="material-symbols-outlined">monitor_weight</span>
                    <span>Poids actuel : <strong><?php echo htmlspecialchars($current_weight); ?> kg</strong></span>
                </li>
                <li>
                    <span class="material-symbols-outlined">medical_information</span>
                    <span>Diag : <?php echo htmlspecialchars($user_info['date_diagnostic'] ?? '--'); ?></span>
                </li>
            </ul>
        </div>

        <!-- Colonne Droite: Suivi du Poids -->
        <div class="profile-card weight-tracking">
            <h2>Suivi du Poids</h2>

            <?php if ($message_success): ?>
                <div class="alert-success">
                    <span class="material-symbols-outlined"
                        style="vertical-align: middle; margin-right: 5px;">check_circle</span>
                    <?php echo $message_success; ?>
                </div>
            <?php endif; ?>

            <?php if ($message_error): ?>
                <div class="alert-error">
                    <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 5px;">error</span>
                    <?php echo $message_error; ?>
                </div>
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
                <div class="form-group" style="flex: 0;">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-add">Ajouter</button>
                </div>
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

        // Données PHP injectées en JS
        const labels = <?php echo json_encode($labels_poids); ?>;
        const data = <?php echo json_encode($data_poids); ?>;

        const weightChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Poids (kg)',
                    data: data,
                    borderColor: '#2e7d32', // Couleur primaire
                    backgroundColor: 'rgba(46, 125, 50, 0.1)', // Fond sous la courbe
                    borderWidth: 2,
                    pointBackgroundColor: '#2e7d32',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Courbe un peu plus lisse
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false, // On ne commence pas à 0 pour mieux voir les variations
                        title: {
                            display: true,
                            text: 'Poids (kg)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: false,
                            text: 'Date'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            font: {
                                family: "'Outfit', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { family: "'Outfit', sans-serif" },
                        bodyFont: { family: "'Outfit', sans-serif" }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    </script>
</body>

</html>