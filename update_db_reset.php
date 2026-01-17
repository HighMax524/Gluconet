<?php
require_once 'backend/db_connect.php';

try {
    $sql = "ALTER TABLE utilisateur 
            ADD COLUMN token_recuperation VARCHAR(255) DEFAULT NULL,
            ADD COLUMN token_expiration DATETIME DEFAULT NULL";
    
    $conn->exec($sql);
    echo "Columns added successfully.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Columns already exist.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
