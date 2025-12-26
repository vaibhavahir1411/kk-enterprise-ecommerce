<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

// Get Active Theme
$stmt = $pdo->query("SELECT * FROM themes WHERE is_active = 1 LIMIT 1");
$active_theme = $stmt->fetch();
$theme_class = $active_theme ? 'theme-' . $active_theme['slug'] : 'theme-default';

// Cart Count
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fireworks Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="<?php echo $theme_class; ?>">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-warning" href="index.php">
        <i class="bi bi-stars"></i> Fireworks
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      </ul>
      <form class="d-flex me-3" action="shop.php" method="GET">
        <input class="form-control me-2" type="search" name="search" placeholder="Search crackers...">
        <button class="btn btn-outline-warning" type="submit">Search</button>
      </form>
      <a href="cart.php" class="btn btn-warning position-relative">
        <i class="bi bi-cart-fill"></i> Cart
        <?php if($cart_count > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo $cart_count; ?>
            </span>
        <?php endif; ?>
      </a>
    </div>
  </div>
</nav>
