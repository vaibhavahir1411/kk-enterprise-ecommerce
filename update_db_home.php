<?php
require_once 'public_html/config/database.php';

try {
    // Add is_top_seller to products
    $pdo->exec("ALTER TABLE products ADD COLUMN is_top_seller TINYINT(1) DEFAULT 0");
    echo "Added is_top_seller column to products table.<br>";
} catch (PDOException $e) {
    echo "Column is_top_seller might already exist or error: " . $e->getMessage() . "<br>";
}

echo "Database update completed.";
?>
