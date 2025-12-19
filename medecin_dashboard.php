<?php
session_start();
require_once 'backend/db_connect.php';

// Vérification de la session et du rôle
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'medecin') {
    header("Location: connexion.php");
    exit();
}

require_once 'backend/recuperation_dashboard_medecin.php';

$dashboardData = getMedecinDashboardData($conn);

if (isset($dashboardData['redirect'])) {
    header("Location: " . $dashboardData['redirect']);
    exit();
}

$patients = $dashboardData['patients'];
$demandes = $dashboardData['demandes'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Médecin - GlucoNet</title>
    <link rel="stylesheet" href="res/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'nav_bar.php'; ?>

    <div class="dashboard-container">

        <!-- Demandes en attente -->
        <?php if (!empty($demandes)): ?>
            <div class="section-title">
                <span class="material-symbols-outlined">notifications_active</span>
                Demandes de suivi
            </div>
            <div class="card-list">
                <?php foreach ($demandes as $demande): ?>
                    <div class="patient-card" style="border-left: 5px solid #ff9800;">
                        <div class="patient-header">
                            <div class="patient-avatar" style="background: #fff3e0; color: #f57c00;">
                                <span class="material-symbols-outlined">person_add</span>
                            </div>
                            <div class="patient-info">
                                <h3><?php echo htmlspecialchars($demande['prenom'] . ' ' . $demande['nom']); ?></h3>
                                <p>Demande le <?php echo date('d/m/Y', strtotime($demande['date_demande'])); ?></p>
                            </div>
                        </div>
                        <div>
                            <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">
                                Email: <?php echo htmlspecialchars($demande['email']); ?>
                            </p>
                        </div>
                        <form action="backend/traitement_demande_patient.php" method="POST" class="request-actions">
                            <input type="hidden" name="id_relation" value="<?php echo $demande['id_relation']; ?>">
                            <button type="submit" name="action" value="accept" class="btn-accept">Accepter</button>
                            <button type="submit" name="action" value="refuse" class="btn-refuse">Refuser</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Mes Patients -->
        <div class="section-title">
            <span class="material-symbols-outlined">groups</span>
            Mes Patients
        </div>

        <div class="card-list">
            <?php if (empty($patients)): ?>
                <div class="empty-state">
                    <h3>Aucun patient pour le moment.</h3>
                    <p>Vos patients apparaîtront ici une fois que vous aurez accepté leurs demandes.</p>
                </div>
            <?php else: ?>
                <?php foreach ($patients as $patient): ?>
                    <div class="patient-card">
                        <div class="patient-header">
                            <div class="patient-avatar">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                            <div class="patient-info">
                                <h3><?php echo htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']); ?></h3>
                                <p><?php echo htmlspecialchars($patient['type_diabete'] ?? 'Type inconnu'); ?></p>
                            </div>
                        </div>

                        <div class="patient-details">
                            <div class="detail-item">
                                <span class="material-symbols-outlined" style="font-size: 16px;">cake</span>
                                <?php echo htmlspecialchars($patient['age'] ?? '-'); ?> ans
                            </div>
                            <div class="detail-item">
                                <span class="material-symbols-outlined" style="font-size: 16px;">wc</span>
                                <?php echo htmlspecialchars($patient['sexe'] ?? '-'); ?>
                            </div>
                        </div>

                        <a href="dossier_patient.php?id=<?php echo $patient['id_utilisateur']; ?>" class="btn-view">
                            Voir le dossier
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <?php include 'footer.php'; ?>
</body>

</html>