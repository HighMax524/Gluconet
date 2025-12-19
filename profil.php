<?php
session_start();
require_once 'backend/db_connect.php';

require_once 'backend/recuperation_profil.php';

$profilData = getProfilData($conn);

if (isset($profilData['redirect'])) {
    header("Location: " . $profilData['redirect']);
    exit();
}

$user_info = $profilData['user_info'];
$labels_poids = $profilData['labels_poids'];
$data_poids = $profilData['data_poids'];
$current_weight = $profilData['current_weight'];

$message_success = isset($_GET['success']) ? "Poids enregistré avec succès !" : "";
$message_error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "";
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
                <!-- Bouton Modifier -->
                <button onclick="openEditModal()" class="btn-edit-profile"
                    style="margin-top: 15px; background: transparent; border: 1px solid var(--primary-color); color: var(--primary-color); padding: 5px 15px; border-radius: 20px; cursor: pointer; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">edit</span> Modifier
                </button>
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

            <?php if (isset($_GET['success_update'])): ?>
                <div class="alert-success">
                    <span class="material-symbols-outlined"
                        style="vertical-align: middle; margin-right: 5px;">check_circle</span>
                    Profil mis à jour avec succès !
                </div>
            <?php endif; ?>

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

    <!-- Modal d'édition du profil -->
    <div id="editModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
        <div class="modal-content"
            style="background-color: #fefefe; padding: 30px; border-radius: 15px; width: 90%; max-width: 500px; position: relative; max-height: 90vh; overflow-y: auto;">
            <span class="close" onclick="closeEditModal()"
                style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 style="color: var(--primary-dark); margin-bottom: 20px; text-align: center;">Modifier mon profil</h2>

            <form action="backend/traitement_modification_profil.php" method="POST"
                style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="prenom"
                            style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Prénom</label>
                        <input type="text" name="prenom" id="prenom"
                            value="<?php echo htmlspecialchars($user_info['prenom']); ?>" required
                            style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1;">
                        <label for="nom" style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Nom</label>
                        <input type="text" name="nom" id="nom"
                            value="<?php echo htmlspecialchars($user_info['nom']); ?>" required
                            style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                    </div>
                </div>

                <div>
                    <label for="email" style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Email</label>
                    <input type="email" name="email" id="email"
                        value="<?php echo htmlspecialchars($user_info['email']); ?>" required
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">

                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="age" style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Age</label>
                        <input type="number" name="age" id="age"
                            value="<?php echo htmlspecialchars($user_info['age'] ?? ''); ?>"
                            style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1;">
                        <label for="sexe" style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Sexe</label>
                        <select name="sexe" id="sexe"
                            style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                            <option value="">Sélectionner</option>
                            <option value="Homme" <?php echo ($user_info['sexe'] ?? '') == 'Homme' ? 'selected' : ''; ?>>
                                Homme</option>
                            <option value="Femme" <?php echo ($user_info['sexe'] ?? '') == 'Femme' ? 'selected' : ''; ?>>
                                Femme</option>
                            <option value="Autre" <?php echo ($user_info['sexe'] ?? '') == 'Autre' ? 'selected' : ''; ?>>
                                Autre</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="taille" style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Taille
                            (cm)</label>
                        <input type="number" step="0.1" name="taille" id="taille"
                            value="<?php echo htmlspecialchars($user_info['taille'] ?? ''); ?>"
                            style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1;">
                        <label for="type_diabete" style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Type de
                            diabète</label>
                        <select name="type_diabete" id="type_diabete"
                            style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                            <option value="Type 1" <?php echo ($user_info['type_diabete'] ?? '') == 'Type 1' ? 'selected' : ''; ?>>Type 1</option>
                            <option value="Type 2" <?php echo ($user_info['type_diabete'] ?? '') == 'Type 2' ? 'selected' : ''; ?>>Type 2</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="date_diagnostic" style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Date de
                        diagnostic</label>
                    <input type="date" name="date_diagnostic" id="date_diagnostic"
                        value="<?php echo htmlspecialchars($user_info['date_diagnostic'] ?? ''); ?>"
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="btn-primary"
                        style="padding: 12px 30px; border: none; border-radius: 25px; cursor: pointer; font-size: 1rem;">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Gestion de la modale
        function openEditModal() {
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Fermer si clic en dehors
        window.onclick = function (event) {
            var modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

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