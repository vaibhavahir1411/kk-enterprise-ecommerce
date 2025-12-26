<?php 
require_once 'includes/header.php'; 

// Filter Logic
$category_slug = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Build Query
$where = "WHERE is_active = 1";
$params = [];

if ($category_slug) {
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmt->execute([$category_slug]);
    $cat_id = $stmt->fetchColumn();
    if ($cat_id) {
        $where .= " AND category_id = ?";
        $params[] = $cat_id;
    }
}

if ($search) {
    $where .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Total Count
$stmt = $pdo->prepare("SELECT COUNT(*) FROM products $where");
$stmt->execute($params);
$total_products = $stmt->fetchColumn();
$total_pages = ceil($total_products / $limit);

// Fetch Products - Pure Positional for LIMIT/OFFSET
$sql = "SELECT * FROM products $where ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $pdo->prepare($sql);

// Bind filters first
foreach($params as $k => $v) {
    $stmt->bindValue($k+1, $v); // 1-indexed
}

// Bind limit/offset using the next available indices
$next_index = count($params) + 1;
$stmt->bindValue($next_index, (int)$limit, PDO::PARAM_INT);
$stmt->bindValue($next_index + 1, (int)$offset, PDO::PARAM_INT);

$stmt->execute();
$products = $stmt->fetchAll();

// Fetch Categories for Sidebar
$categories = $pdo->query("SELECT * FROM categories ORDER BY display_order ASC")->fetchAll();
?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="shop.php" class="list-group-item list-group-item-action <?php echo !$category_slug ? 'active' : ''; ?>">All Products</a>
                    <?php foreach($categories as $cat): ?>
                        <a href="shop.php?category=<?php echo $cat['slug']; ?>" class="list-group-item list-group-item-action <?php echo $category_slug == $cat['slug'] ? 'active' : ''; ?>">
                            <?php echo $cat['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Need Help?</h5>
                </div>
                <div class="card-body">
                    <p class="small">Can't find what you are looking for?</p>
                    <a href="contact.php" class="btn btn-outline-dark btn-sm w-100">Contact Us</a>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-md-9">
            <h3 class="mb-4"><?php echo $category_slug ? 'Category: ' . ucfirst($category_slug) : 'All Products'; ?></h3>
            
            <?php if (count($products) > 0): ?>
            <div class="row g-4">
                <?php foreach($products as $prod): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm product-card">
                         <?php
                        $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1");
                        $stmt->execute([$prod['id']]);
                        $img = $stmt->fetchColumn();
                        $img_src = $img ? $img : 'https://via.placeholder.com/300x300?text=No+Image';
                        ?>
                        <img src="<?php echo $img_src; ?>" class="card-img-top" alt="<?php echo $prod['name']; ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $prod['name']; ?></h5>
                            <div class="mt-auto">
                                <?php if($prod['sale_price']): ?>
                                    <p class="card-text mb-1">
                                        <span class="text-decoration-line-through text-muted small">₹<?php echo $prod['price']; ?></span>
                                        <span class="text-danger fw-bold">₹<?php echo $prod['sale_price']; ?></span>
                                    </p>
                                <?php else: ?>
                                    <p class="card-text fw-bold mb-1">₹<?php echo $prod['price']; ?></p>
                                <?php endif; ?>
                                
                                <div class="d-grid gap-2 mt-2">
                                     <a href="product.php?id=<?php echo $prod['id']; ?>" class="btn btn-outline-secondary btn-sm">Details</a>
                                     <form action="cart_actions.php" method="POST">
                                         <input type="hidden" name="action" value="add">
                                         <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                         <button type="submit" class="btn btn-primary btn-sm w-100">Add to List</button>
                                     </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav class="mt-5">
              <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&category=<?php echo $category_slug; ?>&search=<?php echo $search; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>
              </ul>
            </nav>
            <?php endif; ?>

            <?php else: ?>
                <div class="alert alert-info">No products found here.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
