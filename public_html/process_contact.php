<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'] ?? '';
    $message = $_POST['message'];

    try {
        // Insert into inquiries table
        // Note: customer_address might be optional in schema, but we pass it.
        // Status is 'new'
        $stmt = $pdo->prepare("INSERT INTO inquiries (customer_name, customer_phone, customer_email, customer_address, message, status) VALUES (?, ?, ?, ?, ?, 'new')");
        $stmt->execute([$name, $phone, $email, $address, $message]);
        $inquiry_id = $pdo->lastInsertId();

        // Success Page
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Message Sent</title>
            <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
            <!-- Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
            <!-- CSS -->
            <link rel="stylesheet" href="assets/css/style.css">
            <style>
                body {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    background: linear-gradient(135deg, #FFF5F0 0%, #F0F9FF 100%);
                }
                .success-card {
                    background: white;
                    padding: 3rem;
                    border-radius: 20px;
                    text-align: center;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                    max-width: 500px;
                    width: 90%;
                    animation: fadeUp 0.6s ease-out forwards;
                }
                .success-icon {
                    width: 80px;
                    height: 80px;
                    background: rgba(16, 185, 129, 0.1);
                    color: #10B981;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 3rem;
                    margin: 0 auto 1.5rem;
                }
            </style>
        </head>
        <body>
            <div class="success-card">
                <div class="success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h2 style="font-size: 2rem; margin-bottom: 1rem;">Message Sent!</h2>
                <p style="color: #6B7280; margin-bottom: 2rem; line-height: 1.6;">
                    Thanks <strong><?php echo htmlspecialchars($name); ?></strong>. We have received your message and will get back to you shortly.
                </p>
                
                <a href="index.php" class="btn btn-primary btn-full">Back to Home</a>
            </div>
            
            <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
            <script>
                confetti({
                    particleCount: 150,
                    spread: 70,
                    origin: { y: 0.6 },
                    colors: ['#FF6B35', '#FFB627', '#6C5CE7']
                });
            </script>
        </body>
        </html>
        <?php

    } catch (Exception $e) {
        die("Error processing inquiry: " . $e->getMessage());
    }

} else {
    header("Location: contact.php");
}
?>
