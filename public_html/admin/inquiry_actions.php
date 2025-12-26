<?php
session_start();
require_once '../config/database.php';

$action = $_REQUEST['action'] ?? 'update_status';

if ($action == 'update_status' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE inquiries SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    
    $_SESSION['success'] = "Status updated!";
    header("Location: inquiry_view.php?id=$id");
    exit;
}

if ($action == 'delete') {
    $id = $_GET['id'];
    // Delete inquiry items first
    $pdo->prepare("DELETE FROM inquiry_items WHERE inquiry_id = ?")->execute([$id]);
    // Delete inquiry
    $pdo->prepare("DELETE FROM inquiries WHERE id = ?")->execute([$id]);
    $_SESSION['success'] = "Inquiry deleted successfully!";
    header("Location: inquiries.php");
    exit;
}
?>
