<?php
session_start();
require_once 'backend/db_connect.php';

// Vérification de la session et du rôle
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'medecin') {
    header("Location: connexion.php");
    exit();
}

$rpps = $_SESSION['medecin_rpps'];

// Récupération de la liste des patients
try {
    // On veut les patients liés à RELATIONS acceptées
    // Il faudrait aussi gérer les demandes "En attente" pour les accepter

    // 1. Liste des patients suivis
    $sqlPatients = "
        SELECT u.nom, u.prenom, u.email, p.type_diabete, p.age, p.sexe, r.date_reponse, p.id_utilisateur
        FROM relation_patient_medecin r
        JOIN patient p ON r.id_patient = p.id_utilisateur
        JOIN utilisateur u ON p.id_utilisateur = u.id
        WHERE r.id_medecin = ? AND r.statut = 'Approuve'
    ";
    $stmtPatients = $conn->prepare($sqlPatients);
    $stmtPatients->execute([$rpps]);
    $patients = $stmtPatients->fetchAll(PDO::FETCH_ASSOC);

    // 2. Demandes en attente
    $sqlDemandes = "
        SELECT u.nom, u.prenom, u.email, r.date_demande, r.id as id_relation
        FROM relation_patient_medecin r
        JOIN patient p ON r.id_patient = p.id_utilisateur
        JOIN utilisateur u ON p.id_utilisateur = u.id
        WHERE r.id_medecin = ? AND r.statut = 'En attente'
    ";
    $stmtDemandes = $conn->prepare($sqlDemandes);
    $stmtDemandes->execute([$rpps]);
    $demandes = $stmtDemandes->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
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
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .patient-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .patient-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .patient-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .patient-avatar {
            width: 50px;
            height: 50px;
            background: #e0f2f1;
            color: var(--primary-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .patient-info h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #333;
        }

        .patient-info p {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
        }

        .patient-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .detail-item {
            color: #555;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view {
            display: block;
            width: 100%;
            padding: 10px;
            text-align: center;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn-view:hover {
            background: var(--primary-dark);
        }

        .request-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-accept,
        .btn-refuse {
            flex: 1;
            padding: 8px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-accept {
            background: #81c784;
            color: white;
        }

        .btn-refuse {
            background: #e57373;
            color: white;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 15px;
            color: #888;
        }
    </style>
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