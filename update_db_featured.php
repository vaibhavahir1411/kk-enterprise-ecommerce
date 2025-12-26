<?php
require_once 'public_html/config/database.php';

try {
    // Add is_featured column if it doesn't exist
    $sql = "ALTER TABLE categories ADD COLUMN is_featured TINYINT(1) DEFAULT 0 AFTER image";
    $pdo->exec($sql);
    echo "Column 'is_featured' added successfully.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Column 'is_featured' already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
