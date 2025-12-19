<?php
session_start();
require_once 'db_connect.php';

// Vérification de la session et du rôle
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'medecin') {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    // Récupération et nettoyage des données
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $etablissement = trim($_POST['etablissement'] ?? '');
    $telephone_pro = trim($_POST['telephone_pro'] ?? '');
    $adresse_pro = trim($_POST['adresse_pro'] ?? '');

    if (empty($nom) || empty($prenom) || empty($email) || empty($etablissement) || empty($telephone_pro) || empty($adresse_pro)) {
        header("Location: ../profil_medecin.php?error=" . urlencode("Tous les champs sont obligatoires."));
        exit();
    }

    try {
        $conn->beginTransaction();

        // 1. Mise à jour de la table utilisateur
        $stmtUser = $conn->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ? WHERE id = ?");
        $stmtUser->execute([$nom, $prenom, $email, $user_id]);

        // Mise à jour de la session pour l'affichage immédiat
        $_SESSION['user_prenom'] = $prenom;

        // 2. Mise à jour table medecin
        $stmtMedecin = $conn->prepare("
            UPDATE medecin 
            SET etablissement = ?, telephone_pro = ?, adresse_pro = ? 
            WHERE Utilisateur_id = ?
        ");
        $stmtMedecin->execute([$etablissement, $telephone_pro, $adresse_pro, $user_id]);

        $conn->commit();
        header("Location: ../profil_medecin.php?success_update=1");
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        $error = "Erreur lors de la mise à jour : " . $e->getMessage();
        header("Location: ../profil_medecin.php?error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: ../profil_medecin.php");
    exit();
}
?>