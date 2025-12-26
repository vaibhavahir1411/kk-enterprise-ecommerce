<?php
require_once 'includes/header.php'; 
require_once 'config/database.php';

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Create table if not exists (safety check)
    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100),
        phone VARCHAR(20),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $email, $phone, $message])) {
        $msg = "<div class='alert alert-success'>Message sent successfully! We will get back to you.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Failed to send message.</div>";
    }
}
?>

<div class="container my-5">
    <h1 class="text-center mb-4">Contact Us</h1>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php echo $msg; ?>
            <div class="card shadow">
                <div class="card-body p-4">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-4 mt-md-0">
            <h4>Contact Info</h4>
            <ul class="list-unstyled">
                <li class="mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i> 123 Firework St, Sparkle City</li>
                <li class="mb-3"><i class="bi bi-telephone-fill text-success me-2"></i> +91 98765 43210</li>
                <li class="mb-3"><i class="bi bi-envelope-fill text-primary me-2"></i> support@fireworks.com</li>
            </ul>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
