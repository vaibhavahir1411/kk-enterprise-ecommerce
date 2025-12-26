<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_inquiries = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
?>

<h3>Dashboard</h3>
<div class="row mt-4">
    <div class="col-md-4">
        <a href="products.php" style="text-decoration: none;">
            <div class="card text-white bg-primary mb-3" style="cursor: pointer;">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h1 class="display-4"><?php echo $total_products; ?></h1>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="inquiries.php" style="text-decoration: none;">
            <div class="card text-white bg-success mb-3" style="cursor: pointer;">
                <div class="card-body">
                    <h5 class="card-title">Total Inquiries</h5>
                    <h1 class="display-4"><?php echo $total_inquiries; ?></h1>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="categories.php" style="text-decoration: none;">
            <div class="card text-white bg-info mb-3" style="cursor: pointer;">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <h1 class="display-4"><?php echo $total_categories; ?></h1>
                </div>
            </div>
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
