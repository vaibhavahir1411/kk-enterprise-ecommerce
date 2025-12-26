<?php require_once 'includes/header.php'; ?>

<!-- Hero / Slider -->
<?php
// Fetch Banners for active theme or global
$theme_id_clause = $active_theme ? "OR theme_id = " . $active_theme['id'] : "";
$stmt = $pdo->query("SELECT * FROM banners WHERE is_active = 1 AND (theme_id IS NULL $theme_id_clause) ORDER BY display_order ASC");
$banners = $stmt->fetchAll();
?>

<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <?php if ($banners): ?>
        <?php foreach($banners as $index => $banner): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php echo $banner['image_path']; ?>" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="<?php echo $banner['title']; ?>">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                    <h5><?php echo $banner['title']; ?></h5>
                    <p><?php echo $banner['subtitle']; ?></p>
                    <?php if($banner['link']): ?>
                        <a href="<?php echo $banner['link']; ?>" class="btn btn-warning">Explore</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Default Static Banner -->
        <div class="carousel-item active">
            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 500px;">
                <div class="text-center">
                    <h1 class="display-3 fw-bold">Celebrate with a Bang!</h1>
                    <p class="lead">Premium Fireworks for Every Occasion</p>
                    <a href="shop.php" class="btn btn-lg btn-warning mt-3">Shop Now</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
  </div>
  <?php if(count($banners) > 1): ?>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
  <?php endif; ?>
</div>

<div class="container my-5">
    <!-- Featured Categories -->
    <h2 class="text-center mb-4">Top Categories</h2>
    <div class="row g-4">
        <?php
        $cats = $pdo->query("SELECT * FROM categories ORDER BY display_order ASC LIMIT 4")->fetchAll();
        foreach($cats as $cat):
        ?>
        <div class="col-md-3 col-6">
            <a href="shop.php?category=<?php echo $cat['slug']; ?>" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm category-card text-center p-3">
                    <?php if($cat['image']): ?>
                        <img src="<?php echo $cat['image']; ?>" class="img-fluid mb-2" style="height: 100px; object-fit: contain;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center mb-2" style="height:100px;">
                            <i class="bi bi-box-seam display-4 text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <h5 class="card-title mb-0"><?php echo $cat['name']; ?></h5>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Featured Products -->
    <h2 class="text-center my-5">Featured Crackers</h2>
    <div class="row g-4">
        <?php
        $feat = $pdo->query("SELECT * FROM products WHERE is_active = 1 AND is_featured = 1 LIMIT 8")->fetchAll();
        foreach($feat as $prod):
        ?>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100 shadow-sm product-card">
                <?php
                // Get primary image
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
                        
                        <div class="d-grid gap-2">
                             <a href="product.php?id=<?php echo $prod['id']; ?>" class="btn btn-outline-secondary btn-sm">View</a>
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
</div>

<?php require_once 'includes/footer.php'; ?>
