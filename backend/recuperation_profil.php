<?php
session_start();
require_once 'db_connect.php';

function getProfilData($conn)
{
    if (!isset($_SESSION['user_id'])) {
        return ['error' => 'Non connecté', 'redirect' => 'connexion.php'];
    }

    $user_id = $_SESSION['user_id'];
    $data = [];

    try {
        // On récupère les infos de la table utilisateur et, si dispo, de la table patient
        $stmt = $conn->prepare("
            SELECT u.nom, u.prenom, u.email, u.type_abonnement,
                   p.type_diabete, p.age, p.taille, p.sexe, p.date_diagnostic 
            FROM utilisateur u 
            LEFT JOIN patient p ON u.id = p.id_utilisateur 
            WHERE u.id = ?
        ");
        $stmt->execute([$user_id]);
        $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user_info) {
            // Cas rare : l'utilisateur existe en session mais pas en BDD
            session_destroy();
            return ['error' => 'Utilisateur introuvable', 'redirect' => 'connexion.php'];
        }

        $data['user_info'] = $user_info;

        // Récupération de l'historique de poids
        $stmtPoids = $conn->prepare("SELECT poids, date_heure FROM poids WHERE id_utilisateur = ? ORDER BY date_heure ASC");
        $stmtPoids->execute([$user_id]);
        $historique_poids = $stmtPoids->fetchAll(PDO::FETCH_ASSOC);

        // Préparation des données pour le graphique
        $labels_poids = [];
        $data_poids = [];
        $current_weight = "N/A";

        if ($historique_poids) {
            foreach ($historique_poids as $entry) {
                $date = new DateTime($entry['date_heure']);
                $labels_poids[] = $date->format('d/m/Y'); // Format pour le graphique
                $data_poids[] = $entry['poids'];
            }
            // Le dernier poids du tableau est le plus récent (car ORDER BY ASC)
            $current_weight = end($data_poids);
        }

        $data['labels_poids'] = $labels_poids;
        $data['data_poids'] = $data_poids;
        $data['current_weight'] = $current_weight;

        // Récupération des médecins associés
        $stmtMedecins = $conn->prepare("
            SELECT u.nom, u.prenom, m.etablissement, m.telephone_pro, m.RPPS
            FROM relation_patient_medecin r
            JOIN medecin m ON r.id_medecin = m.RPPS
            JOIN utilisateur u ON m.Utilisateur_id = u.id
            WHERE r.id_patient = ? AND r.statut = 'Approuve'
        ");
        $stmtMedecins->execute([$user_id]);
        $data['medecins'] = $stmtMedecins->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    return $data;
}

?>