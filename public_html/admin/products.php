<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$where = $search ? "WHERE p.name LIKE '%$search%' OR p.id LIKE '%$search%' OR c.name LIKE '%$search%'" : "";

// Total Count
$total = $pdo->query("SELECT COUNT(*) FROM products p LEFT JOIN categories c ON p.category_id = c.id $where")->fetchColumn();
$pages = ceil($total / $limit);

// Fetch Products
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id $where ORDER BY p.id DESC LIMIT $limit OFFSET $offset");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<h3>Products</h3>
<div class="d-flex justify-content-between mb-3">
    <a href="product_form.php" class="btn btn-primary">Add New Product</a>
    <form method="GET" class="d-flex" style="width: 350px;">
        <input type="text" name="search" class="form-control" placeholder="Search by name, ID, or category..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-secondary ms-2">Search</button>
    </form>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Sr No</th>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Status</th>
            <th>Stock</th>
            <th>Top Seller</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $sr_no = ($page - 1) * $limit + 1; foreach($products as $prod): ?>
        <tr>
            <td><?php echo $sr_no++; ?></td>
            <td><?php echo $prod['id']; ?></td>
            <td>
                <?php
                    // Fetch first image
                    $stmt_img = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC LIMIT 1");
                    $stmt_img->execute([$prod['id']]);
                    $thumb = $stmt_img->fetchColumn();
                ?>
                <?php if ($thumb): ?>
                    <img src="../<?php echo $thumb; ?>" style="height: 50px; width: 50px; object-fit: cover;">
                <?php else: ?>
                    <span class="text-muted small">No Img</span>
                <?php endif; ?>
            </td>
            <td>
                <?php echo htmlspecialchars($prod['name']); ?>
                <?php if($prod['sale_price']): ?>
                    <span class="badge bg-danger">Sale</span>
                <?php endif; ?>
                <?php if($prod['is_featured']): ?>
                    <span class="badge bg-warning text-dark">Featured</span>
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($prod['category_name'] ?? 'Uncategorized'); ?></td>
            <td>
                <?php if($prod['sale_price']): ?>
                    <del><?php echo $prod['price']; ?></del> <strong><?php echo $prod['sale_price']; ?></strong>
                <?php else: ?>
                    <?php echo $prod['price']; ?>
                <?php endif; ?>
            </td>
            <td>
                <?php if($prod['is_active']): ?>
                    <span class="badge bg-success">Active</span>
                <?php else: ?>
                    <span class="badge bg-secondary">Inactive</span>
                <?php endif; ?>
            </td>
            <td><?php echo $prod['stock_status']; ?></td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="topSellerSwitch<?php echo $prod['id']; ?>" 
                           <?php echo $prod['is_top_seller'] ? 'checked' : ''; ?> 
                           onchange="toggleTopSeller(<?php echo $prod['id']; ?>)">
                </div>
            </td>
            <td>
                <a href="product_form.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                <a href="product_actions.php?action=delete&id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Pagination -->
<nav>
  <ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
    </li>
    <?php endfor; ?>
  </ul>
</nav>

<script>
function toggleTopSeller(id) {
    fetch('product_actions.php?action=toggle_top_seller&id=' + id)
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            console.log('Toggled successfully');
        } else {
            alert('Error updating status');
            document.getElementById('topSellerSwitch' + id).checked = !document.getElementById('topSellerSwitch' + id).checked;
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?php require_once 'includes/footer.php'; ?>
