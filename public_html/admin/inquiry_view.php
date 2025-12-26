<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM inquiries WHERE id = ?");
$stmt->execute([$id]);
$inq = $stmt->fetch();

$items = $pdo->prepare("SELECT * FROM inquiry_items WHERE inquiry_id = ?");
$items->execute([$id]);
$products = $items->fetchAll();
?>

<h3>Inquiry #<?php echo $inq['id']; ?></h3>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-light">Customer Details</div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($inq['customer_name']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($inq['customer_phone']); ?> 
                   <a href="https://wa.me/<?php echo str_replace(['+',' '], '', $inq['customer_phone']); ?>" target="_blank" class="btn btn-success btn-sm ms-2"><i class="bi bi-whatsapp"></i> Chat</a>
                </p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($inq['customer_email']); ?></p>
                <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($inq['customer_address'])); ?></p>
                <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($inq['message'])); ?></p>
            </div>
        </div>
        
        <form action="inquiry_actions.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $inq['id']; ?>">
            <label>Update Status:</label>
            <div class="input-group">
                <select name="status" class="form-select">
                    <option value="new" <?php echo $inq['status']=='new'?'selected':''; ?>>New</option>
                    <option value="contacted" <?php echo $inq['status']=='contacted'?'selected':''; ?>>Contacted</option>
                    <option value="closed" <?php echo $inq['status']=='closed'?'selected':''; ?>>Closed</option>
                </select>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">Requested Items</div>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price (Approx)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach($products as $p): 
                        $sub = $p['price_at_inquiry'] * $p['quantity'];
                        $total += $sub;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['product_name']); ?></td>
                        <td><?php echo $p['quantity']; ?></td>
                        <td>₹<?php echo $p['price_at_inquiry']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="fw-bold">
                        <td colspan="2" class="text-end">Total Estimated:</td>
                        <td>₹<?php echo $total; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
