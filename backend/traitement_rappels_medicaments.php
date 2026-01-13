<?php
// Afficher les erreurs pour debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion
require __DIR__ . '/db_connect.php';
session_start();

// ⚠️ À adapter à ton système de connexion
$id_patient = $_SESSION['id_patient'] ?? 1;

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

// ------------------------
// Lire tous les rappels
// ------------------------
if ($method === "GET") {
    $stmt = $conn->prepare(
        "SELECT id, heure, frequence, jour_semaine, date_mensuelle
         FROM rappel
         WHERE id_patient = ? AND type = 'Medicament'
         ORDER BY heure"
    );
    $stmt->execute([$id_patient]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ------------------------
// Ajouter un rappel
// ------------------------
if ($method === "POST" && $action === "create") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['heure']) || empty($data['frequence'])) {
        http_response_code(400);
        echo json_encode(["error" => "Données manquantes"]);
        exit;
    }

    $frequences_valides = ['Quotidien', 'Hebdomadaire', 'Mensuel'];
    if (!in_array($data['frequence'], $frequences_valides)) {
        http_response_code(400);
        echo json_encode(["error" => "Fréquence invalide"]);
        exit;
    }

    try {
        $stmt = $conn->prepare(
            "INSERT INTO rappel (id_patient, type, heure, frequence, jour_semaine, date_mensuelle)
            VALUES (?, 'Medicament', ?, ?, ?, ?)"
        );
        $stmt->execute([$id_patient, $data['heure'], $data['frequence'], $data['jour_semaine'], $data['date_mensuelle']]);
        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
    exit;
}

// ------------------------
// Modifier un rappel
// ------------------------
if ($method === "POST" && $action === "update") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['id']) || empty($data['heure']) || empty($data['frequence'])) {
        http_response_code(400);
        echo json_encode(["error" => "Données manquantes"]);
        exit;
    }

    $frequences_valides = ['Quotidien', 'Hebdomadaire', 'Mensuel'];
    if (!in_array($data['frequence'], $frequences_valides)) {
        http_response_code(400);
        echo json_encode(["error" => "Fréquence invalide"]);
        exit;
    }

    try {
        $stmt = $conn->prepare(
            "UPDATE rappel
            SET heure = ?, frequence = ?, jour_semaine = ?, date_mensuelle = ?
            WHERE id = ? AND id_patient = ? AND type = 'Medicament'"
        );
        $stmt->execute([$data['heure'], $data['frequence'], $data['jour_semaine'], $data['date_mensuelle'], $data['id'], $id_patient]);
        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
    exit;
}

// ------------------------
// Supprimer un rappel
// ------------------------
if ($method === "POST" && $action === "delete") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID manquant"]);
        exit;
    }

    try {
        $stmt = $conn->prepare(
            "DELETE FROM rappel
             WHERE id = ? AND id_patient = ? AND type = 'Medicament'"
        );
        $stmt->execute([$data['id'], $id_patient]);
        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
    exit;
}

// ------------------------
// Requête invalide
// ------------------------
http_response_code(400);
echo json_encode(["error" => "Requête invalide"]);
