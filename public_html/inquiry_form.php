<?php
require_once 'includes/header.php';

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'];
$total = 0;
foreach($cart as $item) $total += $item['price'] * $item['quantity'];
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title animated-text">Complete Your Inquiry</h1>
        <p class="page-subtitle">Finalize your list and get a quote</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: var(--spacing-2xl);">
            <!-- Form -->
            <div class="contact-form-wrapper">
                <div class="form-header">
                    <h2>Your Details</h2>
                    <p>Please provide your contact information so we can reach you.</p>
                </div>
                
                <form action="submit_inquiry.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="name" id="name" class="form-input" placeholder=" " required>
                        <label for="name" class="form-label">Full Name</label>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <input type="tel" name="phone" id="phone" class="form-input" placeholder=" " required>
                            <label for="phone" class="form-label">Phone Number</label>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" id="email" class="form-input" placeholder=" " required>
                            <label for="email" class="form-label">Email Address</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea name="address" id="address" class="form-input form-textarea" placeholder=" " required style="min-height: 80px;"></textarea>
                        <label for="address" class="form-label">Delivery Address</label>
                    </div>
                    
                    <div class="form-group">
                        <textarea name="message" id="message" class="form-input form-textarea" placeholder=" " style="min-height: 80px;"></textarea>
                        <label for="message" class="form-label">Special Instructions (Optional)</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-full">Submit Inquiry</button>
                </form>
            </div>
            
            <!-- Summary -->
            <div>
                 <div class="cart-summary">
                    <h3 style="font-size: 1.25rem; margin-bottom: var(--spacing-md);">Inquiry Summary</h3>
                    
                    <div style="margin-bottom: var(--spacing-md); max-height: 300px; overflow-y: auto;">
                        <?php foreach($cart as $item): ?>
                        <div style="display: flex; gap: 10px; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--gray-light);">
                            <img src="<?php echo $item['image']; ?>" style="width: 50px; height: 50px; border-radius: 4px; object-fit: cover;">
                            <div>
                                <div style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div style="font-size: 0.8rem; color: var(--gray);"><?php echo $item['quantity']; ?> x ₹<?php echo $item['price']; ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-total">
                        <span>Total Estimate</span>
                        <span style="color: var(--primary);">₹<?php echo number_format($total, 2); ?></span>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</section>

<style>
    @media (max-width: 900px) {
        div[style*="grid-template-columns"] { grid-template-columns: 1fr !important; }
    }
</style>

<?php require_once 'includes/footer.php'; ?>
