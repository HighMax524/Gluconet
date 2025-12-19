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
    // On récupère la date fournie ou on utilise la date actuelle
    $date_heure = $_POST['date_poids'] ?? date('Y-m-d H:i:s');

    // Validation basique
    if ($poids && is_numeric($poids)) {
        if ($poids < 10 || $poids > 500) {
            header("Location: ../profil.php?error=" . urlencode("Le poids doit être compris entre 10 et 500 kg."));
            exit();
        }

        try {
            $stmt = $conn->prepare("INSERT INTO poids (id_utilisateur, poids, date_heure) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $poids, $date_heure]);

            header("Location: ../profil.php?success=1");
            exit();
        } catch (PDOException $e) {
            // En production, ne pas afficher l'erreur SQL brute à l'utilisateur
            // $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
            $error = "Une erreur est survenue lors de l'enregistrement.";
            header("Location: ../profil.php?error=" . urlencode($error));
            exit();
        }
    } else {
        header("Location: ../profil.php?error=" . urlencode("Veuillez entrer un poids valide."));
        exit();
    }
} else {
    // Si accès direct au fichier sans POST
    header("Location: ../profil.php");
    exit();
}
?>