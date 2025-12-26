<?php
session_start();
require_once 'config/database.php';

$action = $_POST['action'] ?? '';

if ($action == 'add') {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Fetch Product Details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $price = $product['sale_price'] ? $product['sale_price'] : $product['price'];
        
        // Fetch Image
        $s = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1");
        $s->execute([$product_id]);
        $img = $s->fetchColumn(); 
        $img = $img ? $img : 'https://via.placeholder.com/150';

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $price,
                'image' => $img,
                'quantity' => $quantity
            ];
        }
    }
    header("Location: cart.php");

} elseif ($action == 'update') {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
    header("Location: cart.php");

} elseif ($action == 'remove') {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");

} elseif ($action == 'clear') {
    unset($_SESSION['cart']);
    header("Location: cart.php");
} else {
    header("Location: index.php");
}
?>
