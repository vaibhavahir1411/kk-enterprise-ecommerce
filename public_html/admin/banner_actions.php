<?php
require_once '../config/database.php';

$action = $_REQUEST['action'];

if ($action == 'add') {
    $img = $_POST['image_path'];
    $title = $_POST['title'];
    $sub = $_POST['subtitle'];
    $theme = !empty($_POST['theme_id']) ? $_POST['theme_id'] : NULL;

    $stmt = $pdo->prepare("INSERT INTO banners (image_path, title, subtitle, theme_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$img, $title, $sub, $theme]);

} elseif ($action == 'delete') {
    $id = $_GET['id'];
    $pdo->prepare("DELETE FROM banners WHERE id = ?")->execute([$id]);
}

header("Location: banners.php");
?>
