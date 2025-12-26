<?php 
require_once 'includes/header.php'; 

// Filter Logic from original file
$category_slug = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$sort = $_GET['sort'] ?? 'newest'; // Added sort parameter
$limit = 20;
$offset = ($page - 1) * $limit;

$where = "WHERE is_active = 1";
$params = [];

if ($category_slug) {
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE slug = ? AND is_active = 1");
    $stmt->execute([$category_slug]);
    $cat_id = $stmt->fetchColumn();
    if ($cat_id) { $where .= " AND category_id = ?"; $params[] = $cat_id; }
}

if ($search) {
    $where .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%"; $params[] = "%$search%";
}

// Sorting logic
$order_by = "ORDER BY id DESC"; // Default
if ($sort === 'price_asc') {
    $order_by = "ORDER BY CASE WHEN sale_price IS NOT NULL THEN sale_price ELSE price END ASC";
} elseif ($sort === 'price_desc') {
    $order_by = "ORDER BY CASE WHEN sale_price IS NOT NULL THEN sale_price ELSE price END DESC";
}

// Count products
$stmt = $pdo->prepare("SELECT COUNT(*) FROM products $where");
$stmt->execute($params);
$total_products = $stmt->fetchColumn();
$total_pages = ceil($total_products / $limit);

// Fetch products
$sql = "SELECT * FROM products $where $order_by LIMIT ? OFFSET ?"; // Incorporated $order_by
$stmt = $pdo->prepare($sql);
foreach($params as $k => $v) { $stmt->bindValue($k+1, $v); }
$next_index = count($params) + 1;
$stmt->bindValue($next_index, (int)$limit, PDO::PARAM_INT);
$stmt->bindValue($next_index + 1, (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

// Fetch Categories for Sidebar
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY display_order ASC")->fetchAll();
?>

<!-- Page Header -->
<!-- <div class="page-header" style="padding: 0.5rem 0;">
    <div class="shop-container">
        <h1 class="page-title animated-text" style="font-size: 1.75rem; margin: 0;"><?php echo $category_slug ? ucfirst(str_replace('-', ' ', $category_slug)) : 'Our Collection'; ?></h1>
    </div>
</div> -->

<section class="section" style="padding-top: 100px;">
    <div class="shop-container">
        <!-- Sticky Filters Bar -->
        <div class="sticky-filters">
            <div class="filter-container">
                
                <!-- Category Dropdown Wrapper -->
                <div style="position: relative;">
                    <button class="btn-category-toggle" id="categoryToggle">
                        <i class="bi bi-grid-fill"></i> Categories
                    </button>
                    <div class="category-dropdown-menu" id="categoryDropdown">
                        <a href="shop.php" class="dropdown-item">All Products</a>
                        <?php foreach($categories as $cat): ?>
                            <a href="shop.php?category=<?php echo $cat['slug']; ?>" class="dropdown-item">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Search -->
                <div class="search-wrapper">
                     <div class="search-box-sidebar">
                         <input type="text" id="liveSearchInput" class="form-control search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                         <button type="button"><i class="bi bi-search"></i></button>
                     </div>
                </div>

                <div style="position: relative;">
                    <button class="btn-filter-toggle" id="filterToggle">
                        <i class="bi bi-filter-right"></i> <span class="d-none d-md-inline">Filter</span>
                    </button>
                    <div class="filter-dropdown-menu" id="filterDropdown">
                        <div class="dropdown-header px-3 py-2 text-muted small">Sort By</div>
                        <button class="dropdown-item" onclick="applySort('newest')">Newest First</button>
                        <button class="dropdown-item" onclick="applySort('price_asc')">Price: Low to High</button>
                        <button class="dropdown-item" onclick="applySort('price_desc')">Price: High to Low</button>
                    </div>
                </div>

            </div>
        </div>

        <script>
            // Live Search
            document.getElementById('liveSearchInput').addEventListener('input', function() {
                let query = this.value;
                let category = new URLSearchParams(window.location.search).get('category') || '';
                let sort = new URLSearchParams(window.location.search).get('sort') || 'newest';
                
                fetch(`ajax_search.php?search=${query}&category=${category}&sort=${sort}`)
                    .then(response => response.text())
                    .then(data => {
                        let container = document.getElementById('products-result-container');
                        if (container) {
                             container.innerHTML = data;
                        }
                    });
            });

            // Category Dropdown Toggle
            document.getElementById('categoryToggle').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('categoryDropdown').classList.toggle('show');
                document.getElementById('filterDropdown').classList.remove('show'); // HTML fix
            });

            // Filter Dropdown Toggle
            document.getElementById('filterToggle').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('filterDropdown').classList.toggle('show');
                document.getElementById('categoryDropdown').classList.remove('show');
            });

            // Apply Sort Function
            function applySort(sortValue) {
                let search = new URLSearchParams(window.location.search).get('search') || '';
                let category = new URLSearchParams(window.location.search).get('category') || '';
                window.location.href = `shop.php?category=${category}&search=${search}&sort=${sortValue}`;
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#categoryToggle') && !e.target.closest('#categoryDropdown')) {
                    document.getElementById('categoryDropdown').classList.remove('show');
                }
                if (!e.target.closest('#filterToggle') && !e.target.closest('#filterDropdown')) {
                    document.getElementById('filterDropdown').classList.remove('show');
                }
            });
        </script>

        <!-- Content -->
        <div class="shop-content">
            <div id="products-result-container">
                <div class="products-header">
                        <span class="products-count">
                            <?php echo $search ? $total_products . ' Products found' : 'Total Products : ' . $total_products; ?>
                        </span>
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
                            <h3 class="product-name">
                                <a href="product.php?slug=<?php echo $prod['slug']; ?>"><?php echo htmlspecialchars($prod['name']); ?></a>
                            </h3>
                            <div class="product-price">
                                <?php if($prod['sale_price']): ?>
                                    <span class="current-price">₹<?php echo number_format($prod['sale_price'], 2); ?></span>
                                    <span class="original-price">₹<?php echo number_format($prod['price'], 2); ?></span>
                                <?php else: ?>
                                    <span class="current-price">₹<?php echo number_format($prod['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if($prod['stock_status'] == 'out_of_stock'): ?>
                                <button class="btn-add-cart" style="background: #dc3545; cursor: not-allowed;" disabled>
                                    Out of Stock
                                </button>
                            <?php else: ?>
                                <form action="cart_actions.php" method="POST" class="mt-auto">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                    <button type="submit" class="btn-add-cart">
                                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </form>
                            <?php endif; ?>
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
</section>

<?php require_once 'includes/footer.php'; ?>
