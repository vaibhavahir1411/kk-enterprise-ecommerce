<?php
require_once 'public_html/config/database.php';
try {
    // Feature first 3 categories
    $sql = "UPDATE categories SET is_featured=1 ORDER BY id ASC LIMIT 3";
    $pdo->exec($sql);
    echo "First 3 categories marked as featured.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
