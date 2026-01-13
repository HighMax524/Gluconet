<?php
require_once 'backend/db_connect.php';
session_start();
$id = $_SESSION['user_id'] ?? 1; // Default to 1 if no session

echo "<h1>Debug Data for User ID: $id</h1>";

echo "<h2>All Measurements</h2>";
$stmt = $conn->query("SELECT * FROM mesureglycemie");
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($res);
echo "</pre>";

echo "<h2>Current Server Date</h2>";
echo date('Y-m-d H:i:s');
?>