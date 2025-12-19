<?php
session_start();
require_once 'db_connect.php';

// Vérification de la session et du rôle (Médecin uniquement)
if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'medecin')) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_relation = $_POST['id_relation'];
    $action = $_POST['action'];

    if (empty($id_relation) || empty($action)) {
        header("Location: ../medecin_dashboard.php?error=" . urlencode("Action invalide."));
        exit();
    }

    try {
        if ($action === 'accept') {
            $stmt = $conn->prepare("UPDATE relation_patient_medecin SET statut = 'Approuve', date_reponse = NOW() WHERE id = ?");
            $stmt->execute([$id_relation]);
            $msg = "Demande acceptée avec succès.";
        } elseif ($action === 'refuse') {
            // On peut soit supprimer la ligne, soit mettre un statut 'Refuse'
            // Pour l'instant, disons qu'on refuse
            $stmt = $conn->prepare("UPDATE relation_patient_medecin SET statut = 'Refuse', date_reponse = NOW() WHERE id = ?");
            $stmt->execute([$id_relation]);
            $msg = "Demande refusée.";
        } else {
            header("Location: ../medecin_dashboard.php?error=" . urlencode("Action inconnue."));
            exit();
        }

        header("Location: ../medecin_dashboard.php?success=" . urlencode($msg));
        exit();

    } catch (PDOException $e) {
        $error = "Erreur lors du traitement : " . $e->getMessage();
        header("Location: ../medecin_dashboard.php?error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: ../medecin_dashboard.php");
    exit();
}
?>