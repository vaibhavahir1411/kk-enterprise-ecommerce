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
    <div class="hero-scroll">
        <span>Scroll</span>
        <div class="scroll-indicator"></div>
    </div>
</section>

<!-- Features Section -->
<section class="section features">
    <div class="container">
        <div class="features-grid">
            <div class="feature-card animate-fade-up delay-1">
                <div class="feature-icon">🚀</div>
                <h3>Premium Quality</h3>
                <p>Hand-picked fireworks from the best manufacturers ensuring top-notch performance.</p>
            </div>
            <div class="feature-card animate-fade-up delay-2">
                <div class="feature-icon">🛡️</div>
                <h3>Safety First</h3>
                <p>100% certified and safe products. Your safety is our top priority.</p>
            </div>
            <div class="feature-card animate-fade-up delay-3">
                <div class="feature-icon">🚚</div>
                <h3>Fast Delivery</h3>
                <p>Quick and secure delivery to your location with tracking support.</p>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Explore</span>
            <h2 class="section-title">Shop By Category</h2>
            <p class="section-subtitle">Find the perfect fireworks for every occasion</p>
        </div>
        
        <?php
        // Fetch Featured Categories
        $stmt_cat = $pdo->prepare("SELECT * FROM categories WHERE is_featured = 1 ORDER BY display_order ASC LIMIT 3");
        $stmt_cat->execute();
        $home_categories = $stmt_cat->fetchAll();
        ?>

        <div class="categories-grid" id="categoriesGrid">
            <?php if ($home_categories): ?>
                <?php foreach($home_categories as $cat): 
                    // Use a default gradient if no specific image logic (or add category image logic later)
                    // For now, rotating gradients based on ID
                    $gradients = [
                        'linear-gradient(135deg, #FF6B35 0%, #FFB627 100%)',
                        'linear-gradient(135deg, #6C5CE7 0%, #a29bfe 100%)',
                        'linear-gradient(135deg, #00cec9 0%, #81ecec 100%)',
                        'linear-gradient(135deg, #fd79a8 0%, #e84393 100%)',
                        'linear-gradient(135deg, #0984e3 0%, #74b9ff 100%)'
                    ];
                    $bg = $gradients[$cat['id'] % count($gradients)];
                    
                    // Check if image exists
                    $bg_style = "background: " . $bg . ";";
                    if (!empty($cat['image'])) {
                        // Assuming images are stored in uploads/ or full URL
                        $img_url = $cat['image'];
                        $bg_style = "background-image: url('" . $img_url . "'); background-size: cover; background-position: center;";
                    }
                ?>
                <div class="category-card click-card" onclick="window.location.href='shop.php?category=<?php echo $cat['slug']; ?>'">
                    <div class="category-image" style="<?php echo $bg_style; ?>"></div>
                    <div class="category-overlay"></div>
                    <div class="category-content">
                        <h3><?php echo htmlspecialchars($cat['name']); ?></h3>
                        <p><?php echo htmlspecialchars($cat['description']); ?></p>
                        <span class="category-link">Shop Now <i class="bi bi-arrow-right"></i></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No featured categories found.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center" style="margin-top: 60px;">
            <a href="shop.php" class="btn btn-secondary px-5 py-3 rounded-pill shadow-sm">View All Categories</a>
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
