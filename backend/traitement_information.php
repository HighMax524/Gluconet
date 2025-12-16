<?php
session_start();
require_once 'db_connect.php';

// Vérification de la session utilisateur (on doit être connecté pour remplir ces infos)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $role_form = $_POST['role_form'] ?? '';

    if ($role_form === 'patient') {
        // Récupération des données patient
        $sexe = $_POST['sexe'] ?? '';
        $age = $_POST['age'] ?? '';
        $taille = $_POST['taille'] ?? '';
        $poids = $_POST['poids'] ?? '';
        $date_diagnostic = $_POST['date_diagnostic'] ?? '';
        $type_diabete = $_POST['type_diabete'] ?? '';

        // Validation basique
        if ($sexe && $age && $taille && $poids && $date_diagnostic && $type_diabete) {
            try {
                $conn->beginTransaction();

                // 1. Insertion dans la table PATIENT
                // Note: Seuils d'alerte laissés à NULL par défaut pour l'instant
                $stmtPatient = $conn->prepare("INSERT INTO patient (id_utilisateur, type_diabete, date_diagnostic, age, sexe, taille) VALUES (?, ?, ?, ?, ?, ?)");
                $stmtPatient->execute([$user_id, $type_diabete, $date_diagnostic, $age, $sexe, $taille]);

                // 2. Insertion dans la table POIDS (historique)
                $stmtPoids = $conn->prepare("INSERT INTO poids (id_utilisateur, poids) VALUES (?, ?)");
                $stmtPoids->execute([$user_id, $poids]);

                $conn->commit();

                // Redirection vers la page d'accueil ou tableau de bord
                header("Location: ../index.php");
                exit();

            } catch (PDOException $e) {
                $conn->rollBack();
                $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
                // Redirection avec erreur (on pourrait améliorer l'affichage des erreurs sur information.php)
                header("Location: ../information.php?error=" . urlencode($error));
                exit();
            }
        } else {
            header("Location: ../information.php?error=" . urlencode("Veuillez remplir tous les champs obligatoires"));
            exit();
        }

    } elseif ($role_form === 'medecin') {
        // Récupération des données médecin
        $rpps = $_POST['rpps'] ?? '';
        $etablissement = $_POST['etablissement'] ?? '';
        $adresse_pro = $_POST['adresse_pro'] ?? '';
        $telephone_pro = $_POST['telephone_pro'] ?? '';

        if ($rpps && $etablissement && $adresse_pro && $telephone_pro) {
            try {
                // Insertion dans la table MEDECIN
                // id_utilisateur correspond à la foreign key Utilisateur_id dans la table medecin
                $stmtMedecin = $conn->prepare("INSERT INTO medecin (RPPS, etablissement, adresse_pro, telephone_pro, Utilisateur_id) VALUES (?, ?, ?, ?, ?)");
                $stmtMedecin->execute([$rpps, $etablissement, $adresse_pro, $telephone_pro, $user_id]);

                // Redirection
                header("Location: ../index.php");
                exit();

            } catch (PDOException $e) {
                $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
                header("Location: ../information.php?error=" . urlencode($error));
                exit();
            }
        } else {
            header("Location: ../information.php?error=" . urlencode("Veuillez remplir tous les champs obligatoires"));
            exit();
        }

    } else {
        header("Location: ../information.php?error=" . urlencode("Formulaire invalide"));
        exit();
    }

} else {
    header("Location: ../information.php");
    exit();
}
?>