<?php 
require_once 'includes/header.php'; 

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='container my-5'><h2>Product not found</h2></div>";
    require_once 'includes/footer.php';
    exit;
}

// Fetch Images
$stmt_img = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, display_order ASC");
$stmt_img->execute([$id]);
$images = $stmt_img->fetchAll();

// Fetch Related
$stmt_rel = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? AND is_active = 1 LIMIT 4");
$stmt_rel->execute([$product['category_id'], $id]);
$related = $stmt_rel->fetchAll();
?>

<div class="container my-5">
    <div class="row">
        <!-- Image Gallery -->
        <div class="col-md-6 mb-4">
            <?php if ($images): ?>
                <div class="main-image mb-3">
                    <img src="<?php echo $images[0]['image_path']; ?>" id="mainImg" class="img-fluid rounded shadow border" style="width: 100%; max-height: 500px; object-fit: contain;">
                </div>
                <div class="d-flex gap-2 overflow-auto">
                    <?php foreach($images as $img): ?>
                        <img src="<?php echo $img['image_path']; ?>" 
                             class="img-thumbnail" 
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                             onclick="document.getElementById('mainImg').src=this.src">
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <img src="https://via.placeholder.com/600x400?text=No+Image" class="img-fluid rounded">
            <?php endif; ?>
        </div>

        <!-- Details -->
        <div class="col-md-6">
            <h1 class="fw-bold"><?php echo $product['name']; ?></h1>
            <div class="mb-3">
                 <?php if($product['sale_price']): ?>
                    <h3 class="text-danger fw-bold d-inline me-2">₹<?php echo $product['sale_price']; ?></h3>
                    <h5 class="text-muted text-decoration-line-through d-inline">₹<?php echo $product['price']; ?></h5>
                <?php else: ?>
                    <h3 class="fw-bold">₹<?php echo $product['price']; ?></h3>
                <?php endif; ?>
            </div>
            
            <p class="badge <?php echo $product['stock_status'] == 'in_stock' ? 'bg-success' : 'bg-danger'; ?>">
                <?php echo str_replace('_', ' ', ucfirst($product['stock_status'])); ?>
            </p>

            <p class="lead mt-3"><?php echo nl2br($product['description']); ?></p>

            <form action="cart_actions.php" method="POST" class="mt-4">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label class="col-form-label fw-bold">Qty:</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" name="quantity" value="1" min="1" class="form-control" style="width: 80px;">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-lg px-4">Add to List</button>
                    </div>
                </div>
            </form>
            
            <div class="mt-4">
                <a href="https://wa.me/?text=I am interested in <?php echo urlencode($product['name']); ?>" target="_blank" class="btn btn-success">
                    <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                </a>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if ($related): ?>
    <hr class="my-5">
    <h3>Related Items</h3>
    <div class="row g-4 mt-2">
        <?php foreach($related as $rel): ?>
            <div class="col-md-3 col-6">
                <div class="card h-100 shadow-sm product-card">
                    <!-- Simplified card for related -->
                     <?php
                    $s = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1");
                    $s->execute([$rel['id']]);
                    $ri = $s->fetchColumn(); $ri = $ri ? $ri : 'https://via.placeholder.com/200';
                    ?>
                    <img src="<?php echo $ri; ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title"><?php echo $rel['name']; ?></h6>
                        <a href="product.php?id=<?php echo $rel['id']; ?>" class="btn btn-sm btn-outline-primary stretched-link">View</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
