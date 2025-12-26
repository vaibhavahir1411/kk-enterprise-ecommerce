<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Export to CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="inquiries_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');

// CSV Headers
fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Address', 'Message', 'Products', 'Total Price', 'Status', 'Date']);

// Fetch all inquiries
$stmt = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC");
$inquiries = $stmt->fetchAll();

foreach ($inquiries as $inq) {
    // Get products for this inquiry
    $stmt_items = $pdo->prepare("SELECT p.name, p.price, p.sale_price, ii.quantity FROM inquiry_items ii JOIN products p ON ii.product_id = p.id WHERE ii.inquiry_id = ?");
    $stmt_items->execute([$inq['id']]);
    $items = $stmt_items->fetchAll();
    
    $products_list = [];
    $total_price = 0;
    foreach ($items as $item) {
        $item_price = $item['sale_price'] ?? $item['price'];
        $item_total = $item_price * $item['quantity'];
        $total_price += $item_total;
        $products_list[] = $item['name'] . ' (x' . $item['quantity'] . ' @ ' . number_format($item_price, 2) . ')';
    }
    
    fputcsv($output, [
        $inq['id'],
        $inq['customer_name'] ?? '',
        $inq['customer_email'] ?? '',
        $inq['customer_phone'] ?? '',
        $inq['customer_address'] ?? '',
        $inq['customer_message'] ?? '',
        implode(', ', $products_list),
        number_format($total_price, 2),
        $inq['status'],
        $inq['created_at']
    ]);
}

fclose($output);
exit();
?>
