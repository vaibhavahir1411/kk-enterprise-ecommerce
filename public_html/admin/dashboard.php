<?php
require_once 'includes/header.php';
require_once '../config/database.php';

// Fetch Statistics
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_inquiries = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$new_inquiries = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();
?>

<h3 class="mb-4">Dashboard</h3>

<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
        border: 2px solid transparent;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        border-color: #0d6efd;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .stat-label {
        color: #6c757d;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .bg-primary-light { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .bg-success-light { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .bg-warning-light { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .bg-info-light { background: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
</style>

<div class="row g-4 mb-4">
    <!-- Total Products -->
    <div class="col-md-3">
        <a href="products.php" class="stat-card">
            <div class="stat-icon bg-primary-light">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-number"><?php echo $total_products; ?></div>
            <div class="stat-label">Total Products</div>
        </a>
    </div>

    <!-- Total Categories -->
    <div class="col-md-3">
        <a href="categories.php" class="stat-card">
            <div class="stat-icon bg-success-light">
                <i class="bi bi-grid-3x3-gap"></i>
            </div>
            <div class="stat-number"><?php echo $total_categories; ?></div>
            <div class="stat-label">Total Categories</div>
        </a>
    </div>

    <!-- New Inquiries -->
    <div class="col-md-3">
        <a href="inquiries.php?status=new" class="stat-card">
            <div class="stat-icon bg-warning-light">
                <i class="bi bi-bell"></i>
            </div>
            <div class="stat-number"><?php echo $new_inquiries; ?></div>
            <div class="stat-label">New Inquiries</div>
        </a>
    </div>

    <!-- Total Inquiries -->
    <div class="col-md-3">
        <a href="inquiries.php" class="stat-card">
            <div class="stat-icon bg-info-light">
                <i class="bi bi-envelope"></i>
            </div>
            <div class="stat-number"><?php echo $total_inquiries; ?></div>
            <div class="stat-label">Total Inquiries</div>
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
