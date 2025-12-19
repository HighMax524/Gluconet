<?php
session_start();
require_once 'backend/db_connect.php';

// Vérification de la session et du rôle (Patient uniquement)
if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'patient')) {
    header("Location: connexion.php");
    exit();
}

$search = $_GET['search'] ?? '';
$results = [];

if ($search) {
    try {
        $sql = "
            SELECT u.nom, u.prenom, m.etablissement, m.adresse_pro, m.telephone_pro, m.RPPS
            FROM medecin m
            JOIN utilisateur u ON m.Utilisateur_id = u.id
            WHERE u.nom LIKE ? OR u.prenom LIKE ? OR m.etablissement LIKE ? OR m.adresse_pro LIKE ?
        ";
        $stmt = $conn->prepare($sql);
        $wildcard = "%$search%";
        $stmt->execute([$wildcard, $wildcard, $wildcard, $wildcard]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Erreur de recherche : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche Médecin - GlucoNet</title>
    <link rel="stylesheet" href="res/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        .search-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .search-box {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            margin-bottom: 40px;
        }

        .search-box h1 {
            color: var(--primary-dark);
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            border-color: var(--primary-color);
        }

        .search-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0 30px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.3s;
        }

        .search-btn:hover {
            background: var(--primary-dark);
        }

        .results-list {
            display: grid;
            gap: 20px;
        }

        .doctor-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s;
        }

        .doctor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .doctor-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .doctor-avatar {
            width: 60px;
            height: 60px;
            background: #e3f2fd;
            color: #1565c0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doctor-details h3 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .doctor-details p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .btn-request {
            background: #81c784;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
            text-decoration: none;
        }

        .btn-request:hover {
            background: #66bb6a;
        }

        .empty-state {
            text-align: center;
            color: #888;
            padding: 40px;
        }
    </style>
</head>

<body>

    <?php include 'nav_bar.php'; ?>

    <div class="search-container">

        <?php if (isset($_GET['success'])): ?>
            <div class="alert-success"
                style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
                Demande envoyée avec succès !
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert-error"
                style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <div class="search-box">
            <h1>Trouver mon médecin</h1>
            <form action="" method="GET" class="search-form">
                <input type="text" name="search" class="search-input" placeholder="Nom, établissement, ville..."
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-btn">
                    <span class="material-symbols-outlined">search</span>
                    Rechercher
                </button>
            </form>
        </div>

        <div class="results-list">
            <?php if ($search && empty($results)): ?>
                <div class="empty-state">
                    <span class="material-symbols-outlined"
                        style="font-size: 48px; display: block; margin-bottom: 10px;">search_off</span>
                    Aucun médecin trouvé pour "<?php echo htmlspecialchars($search); ?>"
                </div>
            <?php elseif (!empty($results)): ?>
                <?php foreach ($results as $doctor): ?>
                    <div class="doctor-card">
                        <div class="doctor-info">
                            <div class="doctor-avatar">
                                <span class="material-symbols-outlined" style="font-size: 30px;">medical_services</span>
                            </div>
                            <div class="doctor-details">
                                <h3>Dr. <?php echo htmlspecialchars($doctor['prenom'] . ' ' . $doctor['nom']); ?></h3>
                                <p><strong><?php echo htmlspecialchars($doctor['etablissement']); ?></strong></p>
                                <p><?php echo htmlspecialchars($doctor['adresse_pro']); ?></p>
                            </div>
                        </div>
                        <form action="backend/traitement_demande_medecin.php" method="POST">
                            <input type="hidden" name="id_medecin" value="<?php echo htmlspecialchars($doctor['RPPS']); ?>">
                            <button type="submit" class="btn-request">Demander</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>