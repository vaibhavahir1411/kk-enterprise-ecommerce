<?php
require_once 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$total_items = 0;
foreach($cart as $item) $total_items += $item['quantity'];
?>

<div class="container my-5">
    <h1 class="mb-4">My Inquiry List</h1>
    
    <?php if (count($cart) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
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
                            <div class="d-flex align-items-center">
                                <img src="<?php echo $item['image']; ?>" style="width: 50px; height: 50px; object-fit: cover;" class="me-2 rounded">
                                <a href="product.php?id=<?php echo $id; ?>" class="text-dark text-decoration-none fw-bold">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </a>
                            </div>
                        </td>
                        <td>₹<?php echo $item['price']; ?></td>
                        <td style="width: 150px;">
                            <form action="cart_actions.php" method="POST" class="d-flex">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control form-control-sm me-2">
                                <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-clockwise"></i></button>
                            </form>
                        </td>
                        <td>₹<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                             <form action="cart_actions.php" method="POST">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">Total Estimated Value:</td>
                        <td colspan="2" class="text-danger">₹<?php echo number_format($grand_total, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="shop.php" class="btn btn-outline-secondary">Continue Shopping</a>
            <div>
                 <form action="cart_actions.php" method="POST" class="d-inline">
                    <input type="hidden" name="action" value="clear">
                    <button type="submit" class="btn btn-danger me-2">Clear List</button>
                </form>
                <a href="inquiry_form.php" class="btn btn-success btn-lg">Proceed to Inquiry</a>
            </div>
        </div>

    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <p class="lead mt-3">Your list is empty.</p>
            <a href="shop.php" class="btn btn-warning">Start Adding Crackers</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
