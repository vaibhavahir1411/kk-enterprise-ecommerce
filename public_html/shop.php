<?php 
require_once 'includes/header.php'; 

// Filter Logic from original file
$category_slug = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

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

// Count products
$stmt = $pdo->prepare("SELECT COUNT(*) FROM products $where");
$stmt->execute($params);
$total_products = $stmt->fetchColumn();
$total_pages = ceil($total_products / $limit);

// Fetch products
$sql = "SELECT * FROM products $where ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $pdo->prepare($sql);
foreach($params as $k => $v) { $stmt->bindValue($k+1, $v); }
$next_index = count($params) + 1;
$stmt->bindValue($next_index, (int)$limit, PDO::PARAM_INT);
$stmt->bindValue($next_index + 1, (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

// Fetch Categories for Sidebar
$categories = $pdo->query("SELECT * FROM categories ORDER BY display_order ASC")->fetchAll();
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title animated-text"><?php echo $category_slug ? ucfirst(str_replace('-', ' ', $category_slug)) : 'Our Collection'; ?></h1>
        <p class="page-subtitle">Discover our premium range of certified fireworks</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="shop-layout">
            <!-- Sidebar -->
            <div class="shop-sidebar" style="position: sticky; top: 100px;">
                <div class="filter-group">
                    <h3 class="filter-title">Search</h3>
                    <form action="shop.php" method="GET" class="mb-3" onsubmit="return false;">
                         <div class="search-box-sidebar" style="position: relative;">
                             <input type="text" name="search" id="liveSearchInput" class="form-control search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" style="padding-right: 40px; border-radius: 20px;">
                             <button type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--primary);"><i class="bi bi-search"></i></button>
                         </div>
                    </form>
                    <script>
                        document.getElementById('liveSearchInput').addEventListener('input', function() {
                            let query = this.value;
                            let category = new URLSearchParams(window.location.search).get('category') || '';
                            
                            fetch(`ajax_search.php?search=${query}&category=${category}`)
                                .then(response => response.text())
                                .then(data => {
                                    // Replace the grid container itself
                                    let grid = document.querySelector('.products-grid');
                                    if (grid) {
                                        grid.outerHTML = data;
                                    } else {
                                        // If grid was removed (e.g. no results previously), append to shop-content
                                        document.querySelector('.shop-content').insertAdjacentHTML('beforeend', data);
                                    }
                                });
                        });
                    </script>
                </div>

                <div class="filter-group">
                    <h3 class="filter-title">Categories</h3>
                    <div class="filter-list">
                        <a href="shop.php" class="filter-item" style="text-decoration: none; color: inherit;">
                             <label style="cursor: pointer;">
                                <input type="checkbox" <?php echo !$category_slug ? 'checked' : ''; ?> onclick="window.location.href='shop.php'"> 
                                <span>All Products</span>
                             </label>
                        </a>
                        <?php foreach($categories as $cat): ?>
                            <a href="shop.php?category=<?php echo $cat['slug']; ?>" class="filter-item" style="text-decoration: none; color: inherit;">
                                <label style="cursor: pointer;">
                                    <input type="checkbox" <?php echo $category_slug == $cat['slug'] ? 'checked' : ''; ?> onclick="window.location.href='shop.php?category=<?php echo $cat['slug']; ?>'">
                                    <span><?php echo $cat['name']; ?></span>
                                </label>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="filter-group">
                    <h3 class="filter-title">Sort By</h3>
                    <select class="filter-select" onchange="/* Sort logic could go here */">
                        <option>Newest First</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Content -->
            <div class="shop-content">
                <div class="products-header">
                     <span class="products-count"><?php echo $total_products; ?> Products found</span>
                </div>

                <div class="products-grid">
                    <?php if (count($products) > 0): ?>
                        <?php foreach($products as $prod): 
                             // Image logic
                             $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1");
                             $stmt->execute([$prod['id']]);
                             $img = $stmt->fetchColumn(); 
                             $img = $img ? $img : 'https://via.placeholder.com/400x400?text=No+Image';
                        ?>
                        <div class="product-card">
                             
                             <div class="product-image">
                                 <img src="<?php echo $img; ?>" alt="<?php echo $prod['name']; ?>" style="width:100%; height:100%; object-fit:cover;">
                                 <div class="product-overlay">
                                     <a href="product.php?id=<?php echo $prod['id']; ?>" class="quick-view-btn">View Details</a>
                                 </div>
                             </div>
                             
                             <div class="product-info">
                                 <a href="product.php?id=<?php echo $prod['id']; ?>">
                                     <h3 class="product-name"><?php echo htmlspecialchars($prod['name']); ?></h3>
                                 </a>
                                 <div class="product-price">
                                     <span class="current-price">₹<?php echo $prod['sale_price'] ?: $prod['price']; ?></span>
                                     <?php if($prod['sale_price']): ?>
                                         <span class="original-price">₹<?php echo $prod['price']; ?></span>
                                     <?php endif; ?>
                                 </div>
                                 <form action="cart_actions.php" method="POST" class="mt-2">
                                     <input type="hidden" name="action" value="add">
                                     <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                     <input type="hidden" name="quantity" value="1">
                                     <button type="submit" class="btn-add-cart"><i class="bi bi-cart-plus"></i> Add to Cart</button>
                                 </form>
                             </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center" style="grid-column: 1/-1;">
                            <p class="text-muted">No products found for this selection.</p>
                            <a href="shop.php" class="btn btn-primary">View All Products</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                 <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>&category=<?php echo $category_slug; ?>&search=<?php echo $search; ?>" class="page-btn <?php echo $page == $i ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
