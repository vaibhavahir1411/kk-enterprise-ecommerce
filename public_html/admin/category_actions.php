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
    if ($action == 'add') {
        $name = $_POST['name'];
        $slug = createSlug($name);
        $order = $_POST['display_order'];
        $image = $_POST['image'];

        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, display_order, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $order, $image]);

    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $order = $_POST['display_order'];
        $image = $_POST['image'];

        $stmt = $pdo->prepare("UPDATE categories SET name=?, display_order=?, image=? WHERE id=?");
        $stmt->execute([$name, $order, $image, $id]);

    } elseif ($action == 'delete') {
        $id = $_GET['id'];
        $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
    }

    header("Location: categories.php");
} catch (PDOException $e) {
    die($e->getMessage());
}
?>
