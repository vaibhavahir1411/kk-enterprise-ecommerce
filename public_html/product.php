<?php 
require_once 'includes/header.php'; 

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='container section text-center'><h2>Product not found</h2><a href='shop.php' class='btn btn-primary'>Back to Shop</a></div>";
    require_once 'includes/footer.php'; exit;
}

$stmt_img = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, display_order ASC");
$stmt_img->execute([$id]);
$images = $stmt_img->fetchAll();

$stmt_rel = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? AND is_active = 1 LIMIT 4");
$stmt_rel->execute([$product['category_id'], $id]);
$related = $stmt_rel->fetchAll();
?>

<style>
    .product-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-xl);
        margin-top: var(--spacing-lg);
    }
    .gallery-main {
        width: 100%;
        height: 500px;
        border-radius: var(--radius-lg);
        overflow: hidden;
        background: var(--gray-lighter);
        margin-bottom: var(--spacing-sm);
    }
    .gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .gallery-thumbs {
        display: flex;
        gap: var(--spacing-sm);
        overflow-x: auto;
        padding-bottom: var(--spacing-xs);
    }
    .thumb {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-sm);
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: var(--transition);
        flex-shrink: 0;
    }
    .thumb.active, .thumb:hover {
        border-color: var(--primary);
    }
    .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-badge-lg {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-full);
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: var(--spacing-sm);
    }
    .bg-green { background: #dcfce7; color: #166534; }
    .bg-red { background: #fee2e2; color: #991b1b; }
    
    .price-lg {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-right: var(--spacing-sm);
    }
    .price-old-lg {
        font-size: 1.5rem;
        color: var(--gray);
        text-decoration: line-through;
    }
    .qty-input {
        width: 80px;
        padding: 0.875rem;
        text-align: center;
        border: 2px solid var(--gray-light);
        border-radius: var(--radius-md);
        font-size: 1rem;
    }
    .cart-actions {
        display: flex;
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-lg);
    }
    @media (max-width: 768px) {
        .product-detail-grid { grid-template-columns: 1fr; }
        .gallery-main { height: 350px; }
    }
</style>

<div class="page-header" style="padding: 40px 0; background: var(--gray-lighter);">
    <div class="container">
        <div style="font-size: 0.875rem; color: var(--gray);">
            <a href="index.php">Home</a> / <a href="shop.php">Shop</a> / <span style="color: var(--dark);"><?php echo htmlspecialchars($product['name']); ?></span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="product-detail-grid">
            <!-- Gallery -->
            <div class="product-gallery">
                <div class="gallery-main">
                    <?php 
                    $main_img = $images ? $images[0]['image_path'] : 'https://via.placeholder.com/600x600?text=No+Image'; 
                    ?>
                    <img src="<?php echo $main_img; ?>" id="mainImg" alt="<?php echo $product['name']; ?>">
                </div>
                <?php if ($images): ?>
                <div class="gallery-thumbs">
                    <?php foreach($images as $img): ?>
                        <div class="thumb" onclick="document.getElementById('mainImg').src='<?php echo $img['image_path']; ?>'">
                             <img src="<?php echo $img['image_path']; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Details -->
            <div class="product-details">
                <?php if($product['stock_status'] == 'in_stock'): ?>
                    <span class="product-badge-lg bg-green"><i class="bi bi-check-circle"></i> In Stock</span>
                <?php else: ?>
                    <span class="product-badge-lg bg-red"><i class="bi bi-x-circle"></i> Out of Stock</span>
                <?php endif; ?>

                <h1 style="font-size: 2.5rem; margin-bottom: var(--spacing-sm);"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div style="display: flex; align-items: center; margin-bottom: var(--spacing-md);">
                    <span class="price-lg">₹<?php echo $product['sale_price'] ?: $product['price']; ?></span>
                    <?php if($product['sale_price']): ?>
                        <span class="price-old-lg">₹<?php echo $product['price']; ?></span>
                        <span style="background: var(--warning); color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; margin-left: 10px; font-weight: 600;">SAVE ₹<?php echo $product['price'] - $product['sale_price']; ?></span>
                    <?php endif; ?>
                </div>

                <p style="color: var(--gray); line-height: 1.8; margin-bottom: var(--spacing-lg);">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </p>

                <div class="card" style="padding: var(--spacing-lg); border: 1px solid var(--gray-light); border-radius: var(--radius-lg);">
                    <form action="cart_actions.php" method="POST">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        
                        <div class="cart-actions">
                            <input type="number" name="quantity" value="1" min="1" class="qty-input">
                            <button type="submit" class="btn btn-primary btn-full">
                                <i class="bi bi-cart-plus"></i> Add to Inquiry List
                            </button>
                        </div>
                    </form>
                    <a href="https://wa.me/?text=I am interested in <?php echo urlencode($product['name']); ?>" target="_blank" class="btn btn-secondary btn-full" style="border-color: #25D366; color: #25D366;">
                        <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>

                <div style="margin-top: var(--spacing-lg); display: flex; gap: var(--spacing-md); color: var(--gray);">
                    <span><i class="bi bi-shield-check text-primary"></i> 100% Genuine</span>
                    <span><i class="bi bi-truck text-primary"></i> Fast Delivery</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if ($related): ?>
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <h3 class="section-title" style="font-size: 2rem;">You May Also Like</h3>
        </div>
        <div class="products-grid">
            <?php foreach($related as $rel): ?>
                <?php
                $s = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1");
                $s->execute([$rel['id']]);
                $ri = $s->fetchColumn(); $ri = $ri ? $ri : 'https://via.placeholder.com/400x400';
                ?>
                <div class="product-card">
                     <div class="product-image">
                         <img src="<?php echo $ri; ?>" style="width:100%; height:100%; object-fit:cover;">
                         <div class="product-overlay">
                             <a href="product.php?id=<?php echo $rel['id']; ?>" class="quick-view-btn">View Details</a>
                         </div>
                     </div>
                     <div class="product-info">
                         <h3 class="product-name"><?php echo $rel['name']; ?></h3>
                         <div class="product-price">
                             <span class="current-price">₹<?php echo $rel['sale_price'] ?: $rel['price']; ?></span>
                         </div>
                     </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
