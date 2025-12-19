<?php
session_start();
require_once 'db_connect.php';

// Vérification de la session et du rôle (Patient uniquement)
if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'patient')) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $medecin_rpps = $_POST['id_medecin'];

    if (empty($medecin_rpps)) {
        header("Location: ../recherche_medecin.php?error=" . urlencode("Médecin invalide."));
        exit();
    }

    try {
        // 1. Récupérer l'ID utilisateur du patient correspondant
        // NOTE: On a déjà user_id (id de la table utilisateur), mais la table relation_patient_medecin utilise id_patient qui semble être la FK vers utilisateur?
        // Vérifions le schéma implicite : id_patient est probablement l'id utilisateur.

        // Vérifier si une demande existe déjà
        $stmtCheck = $conn->prepare("SELECT id FROM relation_patient_medecin WHERE id_patient = ? AND id_medecin = ?");
        $stmtCheck->execute([$user_id, $medecin_rpps]);

        if ($stmtCheck->rowCount() > 0) {
            header("Location: ../recherche_medecin.php?error=" . urlencode("Une demande existe déjà pour ce médecin."));
            exit();
        }

        // 2. Insérer la demande
        $stmtInsert = $conn->prepare("INSERT INTO relation_patient_medecin (id_patient, id_medecin, date_demande, statut) VALUES (?, ?, NOW(), 'En attente')");
        $stmtInsert->execute([$user_id, $medecin_rpps]);

        header("Location: ../recherche_medecin.php?success=1");
        exit();

    } catch (PDOException $e) {
        $error = "Erreur lors de l'envoi de la demande : " . $e->getMessage();
        header("Location: ../recherche_medecin.php?error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: ../recherche_medecin.php");
    exit();
}
?>