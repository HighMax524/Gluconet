<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données
    $offre = htmlspecialchars(trim($_POST['offre']));
    // $nom_titulaire = htmlspecialchars(trim($_POST['nom_titulaire'])); // Optionnel si on remet l'input

    // Validation
    $errors = [];
    if (empty($offre) || !in_array($offre, ['Standard', 'Premium'])) {
        $errors[] = "Offre invalide.";
    }

    if (empty($errors)) {
        // Simulation d'un délai bancaire (2 secondes)
        sleep(2);

        try {
            // Succès : Mise à jour en base
            $stmt = $conn->prepare("UPDATE utilisateur SET type_abonnement = ? WHERE id = ?");
            $stmt->execute([$offre, $_SESSION['user_id']]);

            header("Location: ../profil.php?success=paiement");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur technique : " . $e->getMessage();
            header("Location: ../paiement.php?error=" . urlencode($error));
            exit();
        }
    }

    // Gestion des erreurs (commune)
    if (!empty($errors)) {
        $error = implode(" ", $errors);
        header("Location: ../paiement.php?error=" . urlencode($error));
        exit();
    }

} else {
    // Accès direct interdit
    header("Location: ../paiement.php");
    exit();
}
?>