<?php
session_start();
require_once 'db_connect.php';

function getProfilMedecinData($conn)
{
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'medecin') {
        return ['error' => 'Non autorisé', 'redirect' => 'connexion.php'];
    }

    $user_id = $_SESSION['user_id'];
    $data = [];

    try {
        // Récupération des infos médecin
        $stmt = $conn->prepare("
            SELECT u.nom, u.prenom, u.email, m.RPPS, m.etablissement, m.adresse_pro, m.telephone_pro
            FROM utilisateur u 
            JOIN medecin m ON u.id = m.Utilisateur_id 
            WHERE u.id = ?
        ");
        $stmt->execute([$user_id]);
        $medecin_info = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$medecin_info) {
            // Erreur critique : médecin connecté mais pas dans la table médecin
            session_destroy();
            return ['error' => 'Erreur critique compte', 'redirect' => 'connexion.php'];
        }

        $data['medecin_info'] = $medecin_info;

    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    return $data;
}
?>