<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'patient';
$action = $_GET['action'] ?? '';

try {
    if ($action == 'get_contacts') {
        $contacts = [];
        if ($role == 'patient') {
            // Un patient voit ses médecins "Approuve"
            // Note: Le champ est id_medecin dans relation_patient_medecin, qui est le RPPS.
            // Il faut rejoindre medecin puis utilisateur.
            $sql = "SELECT u.id, u.nom, u.prenom, m.etablissement
                    FROM relation_patient_medecin rpm
                    JOIN medecin m ON rpm.id_medecin = m.RPPS
                    JOIN utilisateur u ON m.Utilisateur_id = u.id
                    WHERE rpm.id_patient = ? AND rpm.statut = 'Approuve'";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$user_id]);
            $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } else {
            // Un médecin voit ses patients "Approuve"
            // On récupère le RPPS du médecin courant
            $rpps = $_SESSION['medecin_rpps'] ?? null;
            if (!$rpps) {
                $stmt = $conn->prepare("SELECT RPPS FROM medecin WHERE Utilisateur_id = ?");
                $stmt->execute([$user_id]);
                $rpps = $stmt->fetchColumn();
            }

            if ($rpps) {
                $sql = "SELECT u.id, u.nom, u.prenom
                        FROM relation_patient_medecin rpm
                        JOIN utilisateur u ON rpm.id_patient = u.id
                        WHERE rpm.id_medecin = ? AND rpm.statut = 'Approuve'";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$rpps]);
                $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        echo json_encode($contacts);

    } elseif ($action == 'get_messages') {
        $contact_id = $_GET['contact_id'] ?? 0;

        $sql = "SELECT m.*, 
                       CASE WHEN m.id_emetteur = ? THEN 'moi' ELSE 'autre' END as emetteur_type
                FROM message m
                WHERE (id_emetteur = ? AND id_destinataire = ?) 
                   OR (id_emetteur = ? AND id_destinataire = ?)
                ORDER BY date_heure ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $user_id, $contact_id, $contact_id, $user_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($messages);

    } elseif ($action == 'send_message') {
        $input = json_decode(file_get_contents('php://input'), true);
        $destinataire_id = $input['destinataire_id'] ?? 0;
        $contenu = $input['contenu'] ?? '';

        if ($destinataire_id && $contenu) {
            $sql = "INSERT INTO message (id_emetteur, id_destinataire, contenu, date_heure, recu) VALUES (?, ?, ?, NOW(), 0)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$user_id, $destinataire_id, $contenu])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erreur DB']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Données invalides']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>