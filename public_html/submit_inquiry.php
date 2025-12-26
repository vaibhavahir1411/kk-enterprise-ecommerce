<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
    
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $message = $_POST['message'];

    try {
        $pdo->beginTransaction();

        // 1. Create Inquiry
        $stmt = $pdo->prepare("INSERT INTO inquiries (customer_name, customer_phone, customer_email, customer_address, message, status) VALUES (?, ?, ?, ?, ?, 'new')");
        $stmt->execute([$name, $phone, $email, $address, $message]);
        $inquiry_id = $pdo->lastInsertId();

        // 2. Add Items
        $stmt_item = $pdo->prepare("INSERT INTO inquiry_items (inquiry_id, product_id, product_name, quantity, price_at_inquiry) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($_SESSION['cart'] as $pid => $item) {
            $stmt_item->execute([$inquiry_id, $pid, $item['name'], $item['quantity'], $item['price']]);
        }

        $pdo->commit();
        
        // Clear Cart
        unset($_SESSION['cart']);

        // Success Page (Simple inline for now)
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Order Placed</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5 pt-5">
                <div class="card text-center shadow p-5">
                    <div class="card-body">
                        <h1 class="text-success display-1">🎉</h1>
                        <h2 class="card-title">Inquiry Submitted Successfully!</h2>
                        <p class="card-text lead mt-3">Thank you, <strong><?php echo htmlspecialchars($name); ?></strong>. Your Inquiry ID is <strong>#<?php echo $inquiry_id; ?></strong>.</p>
                        <p>We will contact you shortly at <strong><?php echo htmlspecialchars($phone); ?></strong> to verify and process your order.</p>
                        <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error processing inquiry: " . $e->getMessage());
    }

} else {
    header("Location: index.php");
}
?>
