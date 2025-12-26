<?php
// reset_admin.php
require_once 'public_html/config/database.php';

$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
        $stmt->execute([$hash, $username]);
        echo "Admin password RESET successfully.<br>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hash]);
        echo "Admin user CREATED successfully.<br>";
    }
    
    echo "Username: <strong>$username</strong><br>";
    echo "Password: <strong>$password</strong>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
