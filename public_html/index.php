<?php require_once 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="container hero-container">
        <div class="hero-content animate-fade-up">
            <span class="section-tag">Premium Collection</span>
            <h1 class="hero-title gradient-text">Light Up The<br>Night Sky</h1>
            <p class="hero-subtitle">Experience the magic with our premium range of certified fireworks. Safe, spectacular, and delivered to your doorstep.</p>
            <div class="hero-cta">
                <a href="shop.php" class="btn btn-primary">Shop Now</a>
                <a href="contact.php" class="btn btn-secondary">Contact Us</a>
            </div>
        </div>
        <div class="hero-image animate-fade-up delay-1">
            <div class="hero-card">
                 <div class="firework-display"></div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section features">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-rocket-takeoff" style="font-size: 3rem; color: #ff6b35;"></i>
                    </div>
                    <h3 class="h5 mb-3">Premium Quality</h3>
                    <p class="text-muted">Hand-picked fireworks from the best manufacturers ensuring top-notch performance.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-shield-check" style="font-size: 3rem; color: #4ecdc4;"></i>
                    </div>
                    <h3 class="h5 mb-3">Safety First</h3>
                    <p class="text-muted">100% certified and safe products. Your safety is our top priority.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-truck" style="font-size: 3rem; color: #f7b731;"></i>
                    </div>
                    <h3 class="h5 mb-3">Fast Delivery</h3>
                    <p class="text-muted">Quick and secure delivery to your location with tracking support.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section bg-light">
    <div class="container container-wide">
        <div class="section-header">
            <span class="section-tag">Explore</span>
            <h2 class="section-title">Shop By Category</h2>
            <p class="section-subtitle">Find the perfect fireworks for every occasion</p>
        </div>
        
        <?php
        // Fetch Featured Categories with Product Count (only active)
        $stmt_cat = $pdo->prepare("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id AND p.is_active = 1) as product_count FROM categories c WHERE is_featured = 1 AND is_active = 1 ORDER BY display_order ASC");
        $stmt_cat->execute();
        $home_categories = $stmt_cat->fetchAll();
        ?>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3 justify-content-center">
            <?php if ($home_categories): ?>
                <?php foreach($home_categories as $cat): 
                    $img_url = !empty($cat['image']) ? $cat['image'] : 'assets/images/placeholder-category.jpg';
                ?>
                <div class="col">
                    <div class="category-card click-card" onclick="window.location.href='shop.php?category=<?php echo $cat['slug']; ?>'" style="height: 220px; position: relative; overflow: hidden; border-radius: var(--radius-lg); cursor: pointer;">
                        <div class="category-image" style="background-image: url('<?php echo $img_url; ?>'); background-size: cover; background-position: center; height: 100%; transition: transform 0.5s ease;"></div>
                        <div class="category-overlay" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); position: absolute; inset: 0;"></div>
                        <div class="category-content" style="position: absolute; bottom: 0; left: 0; padding: 1rem; width: 100%; color: #fff;">
                            <h3 style="margin-bottom: 0.25rem; font-size: 1.1rem; font-weight: 700;"><?php echo htmlspecialchars($cat['name']); ?></h3>
                            <p class="category-count" style="margin: 0; opacity: 0.8; font-size: 0.85rem; transform: translateY(20px); transition: all 0.3s ease; opacity: 0;"><?php echo $cat['product_count']; ?> Products</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No featured categories found.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center" style="margin-top: 40px;">
            <a href="shop.php" class="btn btn-secondary px-5 py-3 rounded-pill shadow-sm">View All Categories</a>
        </div>
    </div>
</section>

<!-- Top Selling Products Section -->
<section class="section">
    <div class="container container-wide">
        <div class="section-header">
            <span class="section-tag">Best Sellers</span>
            <h2 class="section-title">Top Selling Products</h2>
            <p class="section-subtitle">Customer favorites you shouldn't miss</p>
        </div>

        <?php
        $stmt_top = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_top_seller = 1 AND p.is_active = 1 ORDER BY p.id DESC");
        $stmt_top->execute();
        $top_products = $stmt_top->fetchAll();
        ?>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-2 g-md-3 justify-content-center">
            <?php if ($top_products): ?>
                <?php foreach($top_products as $prod): 
                     // Get Image
                    $stmt_img = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1");
                    $stmt_img->execute([$prod['id']]);
                    $img_path = $stmt_img->fetchColumn();
                    $img_src = $img_path ? $img_path : 'assets/images/placeholder.jpg';
                ?>
                <div class="col">
                   <div class="product-card">
                        <div class="product-image">
                            <a href="product.php?slug=<?php echo $prod['slug']; ?>">
                                <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="product-thumb">
                            </a>
                            <?php if($prod['sale_price']): ?>
                                <span class="product-badge badge-sale">Sale</span>
                            <?php endif; ?>
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
                                <form action="cart_actions.php" method="POST">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                    <button type="submit" class="btn-add-cart">
                                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                 <div class="col-12 text-center">
                    <p>No top selling products found.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center" style="margin-top: 40px;">
            <a href="shop.php" class="btn btn-secondary px-5 py-3 rounded-pill shadow-sm">View All Products</a>
        </div>
    </div>
</section>

<!-- Why Choose Us / Stats -->
<section class="section" id="about">
     <div class="container">
        <div class="why-grid">
            <div class="why-content reveal">
                <span class="section-tag">Why Choose Us</span>
                <h2 class="section-title">Experience the Magic of Celebration</h2>
                <p class="why-text">
                    For over 15 years, KK Enterprise has been the trusted name in premium fireworks. 
                    We believe in quality, safety, and creating unforgettable moments.
                </p>
                <div class="why-list">
                    <li><span class="check-icon"><i class="bi bi-check"></i></span> Certified Products</li>
                    <li><span class="check-icon"><i class="bi bi-check"></i></span> Best Market Prices</li>
                    <li><span class="check-icon"><i class="bi bi-check"></i></span> Expert Support</li>
                </div>
                <a href="about.php" class="btn btn-primary">Learn More</a>
            </div>
            <div class="why-stats reveal">
                <div class="stats-card">
                    <div class="stat">
                        <h3>15+</h3>
                        <p>Years Experience</p>
                    </div>
                    <div class="stat">
                        <h3>500+</h3>
                        <p>Products</p>
                    </div>
                    <div class="stat">
                        <h3>50k+</h3>
                        <p>Happy Customers</p>
                    </div>
                    <div class="stat">
                        <h3>100%</h3>
                        <p>Safety Record</p>
                    </div>
                </div>
            </div>
        </div>
     </div>
</section>

<!-- CTA Section -->
<section class="section">
    <div class="container">
        <div class="cta-card reveal">
            <div class="cta-content">
                 <h2>Ready to Celebrate?</h2>
                 <p>Get exclusive deals on bulk orders for weddings and events.</p>
            </div>
            <div class="cta-buttons">
                <a href="shop.php" class="btn btn-primary" style="background: var(--white); color: var(--primary);">Shop Now</a>
                <a href="contact.php" class="btn btn-secondary" style="background: transparent; color: var(--white); border-color: var(--white);">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
