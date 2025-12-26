<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

// Calculate cart count
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KK Enterprise | Premium Fireworks</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">
                <img src="assets/logo.png" alt="KK Enterprise" style="height: 50px; width: auto;">
                <span class="brand-text" style="margin-left: 10px; font-weight: bold; font-size: 1.5rem; background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">KK Enterprise</span>
            </a>

            <div class="nav-links">
                <a href="index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a>
                <a href="shop.php" class="nav-link <?= $current_page == 'shop.php' ? 'active' : '' ?>">Shop</a>
                <a href="about.php" class="nav-link <?= $current_page == 'about.php' ? 'active' : '' ?>">About Us</a>
                <a href="contact.php" class="nav-link <?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a>
            </div>

            <div class="nav-actions">
                <a href="cart.php" class="cart-btn">
                    <i class="bi bi-cart2 cart-icon"></i>
                    <span class="cart-count"><?= $cart_count ?></span>
                </a>
                <button class="mobile-toggle" id="mobileToggle">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-content">
            <div class="mobile-search">
                <input type="text" placeholder="Search products..." class="search-input">
            </div>
            <div class="mobile-links">
                <a href="index.php" class="mobile-link">Home</a>
                <a href="shop.php" class="mobile-link">Shop</a>
                <a href="about.php" class="mobile-link">About Us</a>
                <a href="contact.php" class="mobile-link">Contact</a>
            </div>
        </div>
    </div>
