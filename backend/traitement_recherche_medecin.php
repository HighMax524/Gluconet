<?php
session_start();
require_once 'db_connect.php';

function searchMedecin($conn, $searchQuery)
{
    // Vérification de la session et du rôle (Patient uniquement)
    if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'patient')) {
        return ['error' => 'Non autorisé', 'redirect' => 'connexion.php'];
    }

    $results = [];

    if ($searchQuery) {
        try {
            $sql = "
                SELECT u.nom, u.prenom, m.etablissement, m.adresse_pro, m.telephone_pro, m.RPPS
                FROM medecin m
                JOIN utilisateur u ON m.Utilisateur_id = u.id
                WHERE u.nom LIKE ? OR u.prenom LIKE ? OR m.etablissement LIKE ? OR m.adresse_pro LIKE ?
            ";
            $stmt = $conn->prepare($sql);
            $wildcard = "%$searchQuery%";
            $stmt->execute([$wildcard, $wildcard, $wildcard, $wildcard]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error_msg' => "Erreur de recherche : " . $e->getMessage()];
        }
    }

    return ['results' => $results];
}
?>