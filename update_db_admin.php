<?php
require_once 'public_html/config/database.php';

try {
    // Add is_active column to categories table
    $pdo->exec("ALTER TABLE categories ADD COLUMN is_active TINYINT(1) DEFAULT 1");
    echo "Added is_active column to categories table.<br>";
} catch (PDOException $e) {
    echo "Column is_active might already exist or error: " . $e->getMessage() . "<br>";
}

echo "Database update completed.";
?>
