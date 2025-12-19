<?php
session_start();
require_once 'db_connect.php';

function getDossierPatientData($conn, $medecin_rpps, $patient_user_id)
{
    if (!$patient_user_id) {
        return ['error' => 'Patient non spécifié', 'redirect' => '../medecin_dashboard.php'];
    }

    try {
        // 1. Vérification Relation (Sécurité)
        $stmtCheck = $conn->prepare("
            SELECT id FROM relation_patient_medecin 
            WHERE id_medecin = ? AND id_patient = ? AND statut = 'Approuve'
        ");
        $stmtCheck->execute([$medecin_rpps, $patient_user_id]);

        if ($stmtCheck->rowCount() === 0) {
            return ['error' => 'Accès non autorisé à ce patient', 'redirect' => '../medecin_dashboard.php'];
        }

        // 2. Récupération Infos Patient
        $stmtInfo = $conn->prepare("
            SELECT u.nom, u.prenom, u.email, 
                   p.type_diabete, p.age, p.sexe, p.taille, p.date_diagnostic
            FROM utilisateur u
            JOIN patient p ON u.id = p.id_utilisateur
            WHERE u.id = ?
        ");
        $stmtInfo->execute([$patient_user_id]);
        $patientData = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if (!$patientData) {
            die("Patient introuvable.");
        }

        // 3. Récupération Historique Poids
        $stmtPoids = $conn->prepare("SELECT poids, date_heure FROM poids WHERE id_utilisateur = ? ORDER BY date_heure ASC");
        $stmtPoids->execute([$patient_user_id]);
        $historique_poids = $stmtPoids->fetchAll(PDO::FETCH_ASSOC);

        // Préparation données graphique Poids
        $labels_poids = [];
        $data_poids = [];
        $current_weight = "N/A";

        if ($historique_poids) {
            foreach ($historique_poids as $entry) {
                $date = new DateTime($entry['date_heure']);
                $labels_poids[] = $date->format('d/m/Y');
                $data_poids[] = $entry['poids'];
            }
            $current_weight = end($data_poids);
        }

        return [
            'patientData' => $patientData,
            'historique_poids' => $historique_poids,
            'labels_poids' => $labels_poids,
            'data_poids' => $data_poids,
            'current_weight' => $current_weight
        ];

    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>