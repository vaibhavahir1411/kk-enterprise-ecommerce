<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_inquiries = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$active_theme = $pdo->query("SELECT name FROM themes WHERE is_active=1")->fetchColumn();
?>

<h3>Dashboard</h3>
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <h1 class="display-4"><?php echo $total_products; ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Inquiries</h5>
                <h1 class="display-4"><?php echo $total_inquiries; ?></h1>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
