<?php
require_once '../config/database.php';

$action = $_REQUEST['action'];

if ($action == 'add') {
    $img_path = $_POST['image_path']; // Default to URL if provided

    // Handle File Upload
    if (!empty($_FILES['banner_file']['name'])) {
        $target_dir = "../uploads/banners/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['banner_file']['name']);
        $target_file = $target_dir . $file_name;
        $db_path = "uploads/banners/" . $file_name; // Relative path for DB

        if (move_uploaded_file($_FILES['banner_file']['tmp_name'], $target_file)) {
            $img_path = $db_path; // Override if upload successful
        }
    }

    if (!$img_path) {
        // Fallback or error if neither file nor URL
        die("Please provide an image URL or upload a file.");
    }

    $title = $_POST['title'];
    $sub = $_POST['subtitle'];
    $theme = $_POST['theme_id'] ?: null; // Handle optional theme
    
    // Default link if not provided
    $link = $_POST['link'];

    $stmt = $pdo->prepare("INSERT INTO banners (image_path, title, subtitle, theme_id, link, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->execute([$img_path, $title, $sub, $theme, $link]);

} elseif ($action == 'delete') {
    $id = $_GET['id'];
    $pdo->prepare("DELETE FROM banners WHERE id = ?")->execute([$id]);
}

header("Location: banners.php");
?>
