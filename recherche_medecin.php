<?php
session_start();
require_once 'backend/db_connect.php';

require_once 'backend/traitement_recherche_medecin.php';

$search = $_GET['search'] ?? '';
$searchData = searchMedecin($conn, $search);

if (isset($searchData['redirect'])) {
    header("Location: " . $searchData['redirect']);
    exit();
}

$results = $searchData['results'] ?? [];
if (isset($searchData['error_msg'])) {
    $error = $searchData['error_msg'];
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