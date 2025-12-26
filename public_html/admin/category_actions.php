<?php
session_start();
require_once '../config/database.php';

$action = $_REQUEST['action'];

function createSlug($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    return trim($str, '-');
}

try {
    // Function to check featured limit
    function checkFeaturedLimit($pdo, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM categories WHERE is_featured = 1";
        if ($excludeId) {
            $sql .= " AND id != $excludeId";
        }
        return $pdo->query($sql)->fetchColumn();
    }

    if ($action == 'add') {
        $name = $_POST['name'];
        $slug = createSlug($name);
        $order = $_POST['display_order'];
        $image = $_POST['image'];
        $image = $_POST['image'];
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;

        if ($is_featured && checkFeaturedLimit($pdo) >= 3) {
            die("Error: You can only have 3 featured categories on the home page. Please uncheck another one first.");
        }

        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, display_order, image, is_featured) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $order, $image, $is_featured]);

    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $order = $_POST['display_order'];
        $image = $_POST['image'];
        $image = $_POST['image'];
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;

        if ($is_featured && checkFeaturedLimit($pdo, $id) >= 3) {
            die("Error: You can only have 3 featured categories on the home page. Please uncheck another one first.");
        }

        $stmt = $pdo->prepare("UPDATE categories SET name=?, display_order=?, image=?, is_featured=? WHERE id=?");
        $stmt->execute([$name, $order, $image, $is_featured, $id]);

    } elseif ($action == 'delete') {
        $id = $_GET['id'];
        $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
    }

    header("Location: categories.php");
} catch (PDOException $e) {
    die($e->getMessage());
}
?>
