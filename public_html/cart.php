<?php
require_once 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$total_items = 0;
foreach($cart as $item) $total_items += $item['quantity'];
?>

<style>
    .cart-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--spacing-xl);
        margin-top: var(--spacing-lg);
    }
    .cart-items {
        background: var(--white);
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--gray-light);
    }
    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cart-table th {
        background: var(--gray-lighter);
        padding: var(--spacing-md);
        text-align: left;
        font-weight: 600;
        color: var(--gray);
        text-transform: uppercase;
        font-size: 0.875rem;
    }
    .cart-table td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--gray-light);
        vertical-align: middle;
    }
    .cart-table tr:last-child td {
        border-bottom: none;
    }
    .cart-product {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }
    .cart-img {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-md);
        object-fit: cover;
        background: var(--gray-lighter);
    }
    .qty-control {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .qty-input-sm {
        width: 50px;
        padding: 5px;
        text-align: center;
        border: 1px solid var(--gray-light);
        border-radius: var(--radius-sm);
    }
    .cart-summary {
        background: var(--white);
        padding: var(--spacing-lg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-light);
        position: sticky;
        top: 100px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--spacing-sm);
        color: var(--gray);
    }
    .summary-total {
        display: flex;
        justify-content: space-between;
        margin-top: var(--spacing-md);
        padding-top: var(--spacing-md);
        border-top: 1px solid var(--gray-light);
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
    }
    .empty-cart {
        text-align: center;
        padding: var(--spacing-2xl);
    }
    .empty-icon {
        font-size: 4rem;
        color: var(--gray-light);
        margin-bottom: var(--spacing-md);
    }
    .btn-remove {
        color: #EF4444; 
        font-size: 0.875rem; 
        display: inline-flex; 
        align-items: center; 
        gap: 4px;
        margin-top: 4px;
    }
    .btn-remove:hover { text-decoration: underline; }
    @media (max-width: 900px) {
        .cart-grid { grid-template-columns: 1fr; }
        .cart-summary { position: static; }
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .cart-grid {
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .cart-items {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .cart-table {
            min-width: 500px;
            font-size: 0.85rem;
        }
        
        .cart-table th {
            padding: 0.5rem;
            font-size: 0.75rem;
        }
        
        .cart-table td {
            padding: 0.5rem;
        }
        
        .cart-img {
            width: 50px;
            height: 50px;
        }
        
        .cart-product {
            gap: 0.5rem;
        }
        
        .cart-product h4 {
            font-size: 0.9rem;
        }
        
        .cart-summary {
            padding: 1rem;
        }
        
        .cart-summary h3 {
            font-size: 1.1rem;
        }
        
        .summary-row {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .summary-total {
            font-size: 1.1rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
        }
        
        .btn {
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
        }
        
        .empty-cart {
            padding: 2rem 1rem;
        }
        
        .empty-icon {
            font-size: 3rem;
        }
    }
</style>

<div class="page-header" style="padding: 2rem 0; background: var(--gray-lighter);">
    <div class="container">
        <h1 class="page-title animated-text" style="font-size: 2rem; margin-bottom: 0.5rem;">Your Inquiry List</h1>
        <p class="page-subtitle" style="font-size: 0.95rem;"><?php echo $total_items; ?> Items selected</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if (count($cart) > 0): ?>
            <div class="cart-grid">
                <!-- Items -->
                <div class="cart-items mb-4">
                    <div style="overflow-x: auto;">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th style="text-align: right;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $grand_total = 0;
                                foreach ($cart as $id => $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $grand_total += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <div class="cart-product">
                                            <img src="<?php echo $item['image']; ?>" class="cart-img" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                            <div>
                                                <a href="product.php?id=<?php echo $id; ?>" style="font-weight: 600; display: block; margin-bottom: 4px;"><?php echo htmlspecialchars($item['name']); ?></a>
                                                <form action="cart_actions.php" method="POST">
                                                    <input type="hidden" name="action" value="remove">
                                                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                                    <button type="submit" class="btn-remove"><i class="bi bi-trash"></i> Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    <td>₹<?php echo $item['price']; ?></td>
                                    <td>
                                        <form action="cart_actions.php" method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                            <div class="qty-control">
                                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="qty-input-sm" onchange="this.form.submit()">
                                            </div>
                                        </form>
                                    </td>
                                    <td style="text-align: right; font-weight: 600;">₹<?php echo number_format($subtotal, 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="cart-summary">
                    <h3 style="font-size: 1.25rem; margin-bottom: var(--spacing-md);">Summary</h3>
                    <div class="summary-row">
                        <span>Items Total</span>
                        <span>₹<?php echo number_format($grand_total, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Discount</span>
                        <span>--</span>
                    </div>
                    <div class="summary-total">
                        <span>Total Estimate</span>
                        <span style="color: var(--primary);">₹<?php echo number_format($grand_total, 2); ?></span>
                    </div>
                    
                    <a href="inquiry_form.php" class="btn btn-primary btn-full" style="margin-top: var(--spacing-lg);">Proceed to Inquiry</a>
                    
                    <form action="cart_actions.php" method="POST" style="margin-top: var(--spacing-sm);">
                         <input type="hidden" name="action" value="clear">
                         <button type="submit" class="btn btn-secondary btn-full" style="border-color: #EF4444; color: #EF4444;">Clear Cart</button>
                    </form>
                    
                    <a href="shop.php" style="display: block; text-align: center; margin-top: var(--spacing-md); color: var(--gray); font-size: 0.875rem;"><i class="bi bi-arrow-left"></i> Continue Shopping</a>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart reveal">
                <div class="empty-icon"><i class="bi bi-cart-x"></i></div>
                <h2>Your list is empty</h2>
                <p style="color: var(--gray); margin-bottom: var(--spacing-lg);">Looks like you haven't added any fireworks yet.</p>
                <a href="shop.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
