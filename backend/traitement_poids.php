<?php
session_start();
require_once 'db_connect.php';

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $poids = $_POST['poids'] ?? '';
    // On peut aussi récupérer une date personnalisée ou utiliser NOW() par défaut
    $date_heure = $_POST['date_poids'] ?? date('Y-m-d H:i:s');

    if ($poids && is_numeric($poids)) {
        try {
            $stmt = $conn->prepare("INSERT INTO poids (id_utilisateur, poids, date_heure) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $poids, $date_heure]);

            header("Location: ../profil.php?success=1");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
            header("Location: ../profil.php?error=" . urlencode($error));
            exit();
        }
    } else {
        header("Location: ../profil.php?error=" . urlencode("Veuillez entrer un poids valide."));
        exit();
    }
} else {
    header("Location: ../profil.php");
    exit();
}
?>