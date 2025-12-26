<?php
require_once 'config/database.php';

$search = $_GET['search'] ?? '';
$category_slug = $_GET['category'] ?? '';

$where = "WHERE is_active = 1";
$params = [];

if ($category_slug) {
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmt->execute([$category_slug]);
    $cat_id = $stmt->fetchColumn();
    if ($cat_id) { $where .= " AND category_id = ?"; $params[] = $cat_id; }
}

if ($search) {
    $where .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%"; $params[] = "%$search%";
}

$sql = "SELECT * FROM products $where ORDER BY id DESC LIMIT 12";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

if (count($products) > 0) {
    echo '<div class="products-grid">';
    foreach($products as $prod) {
        // Image logic
        $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1");
        $stmt->execute([$prod['id']]);
        $img = $stmt->fetchColumn(); 
        $img = $img ? $img : 'https://via.placeholder.com/400x400?text=No+Image';
        
        echo '
        <div class="product-card">
            
            <div class="product-image">
                <img src="'.$img.'" class="product-thumb">
                <div class="product-overlay">
                    <a href="product.php?id='.$prod['id'].'" class="quick-view-btn">View Details</a>
                </div>
            </div>
            <div class="product-info">
                <a href="product.php?id='.$prod['id'].'">
                    <h3 class="product-name">'.htmlspecialchars($prod['name']).'</h3>
                </a>
                <div class="product-price">
                    <span class="current-price">₹'.($prod['sale_price'] ?: $prod['price']).'</span>
                    ' . ($prod['sale_price'] ? '<span class="original-price">₹'.$prod['price'].'</span>' : '') . '
                </div>
                <form action="cart_actions.php" method="POST" class="mt-2">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="'.$prod['id'].'">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-add-cart"><i class="bi bi-cart-plus"></i> Add to Cart</button>
                </form>
            </div>
        </div>';
    }
    echo '</div>';
} else {
    echo '<div class="no-results text-center py-5">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h3>No products found</h3>
            <p class="text-muted">Try removing filters or search for something else.</p>
          </div>';
}
?>
