<?php
session_start();
require_once 'db_connect.php';

function getMedecinDashboardData($conn)
{
    // Vérification de la session et du rôle
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'medecin') {
        return ['error' => 'Non autorisé', 'redirect' => 'connexion.php'];
    }

    $rpps = $_SESSION['medecin_rpps'];
    $data = [];

    // Récupération de la liste des patients
    try {
        // 1. Liste des patients suivis (Relations approuvées)
        $sqlPatients = "
            SELECT u.nom, u.prenom, u.email, p.type_diabete, p.age, p.sexe, r.date_reponse, p.id_utilisateur
            FROM relation_patient_medecin r
            JOIN patient p ON r.id_patient = p.id_utilisateur
            JOIN utilisateur u ON p.id_utilisateur = u.id
            WHERE r.id_medecin = ? AND r.statut = 'Approuve'
        ";
        $stmtPatients = $conn->prepare($sqlPatients);
        $stmtPatients->execute([$rpps]);
        $data['patients'] = $stmtPatients->fetchAll(PDO::FETCH_ASSOC);

        // 2. Demandes en attente
        $sqlDemandes = "
            SELECT u.nom, u.prenom, u.email, r.date_demande, r.id as id_relation
            FROM relation_patient_medecin r
            JOIN patient p ON r.id_patient = p.id_utilisateur
            JOIN utilisateur u ON p.id_utilisateur = u.id
            WHERE r.id_medecin = ? AND r.statut = 'En attente'
        ";
        $stmtDemandes = $conn->prepare($sqlDemandes);
        $stmtDemandes->execute([$rpps]);
        $data['demandes'] = $stmtDemandes->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }

    return $data;
}
?>