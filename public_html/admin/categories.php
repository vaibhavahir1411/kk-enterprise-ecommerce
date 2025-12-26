<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$search = $_GET['search'] ?? '';
$where = $search ? "WHERE name LIKE '%$search%' OR id LIKE '%$search%'" : "";
$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count FROM categories c $where ORDER BY id DESC")->fetchAll();
?>

<h3>Categories</h3>
<div class="d-flex justify-content-between mb-3">
    <a href="category_form.php" class="btn btn-primary">Add Category</a>
    <form method="GET" class="d-flex" style="width: 300px;">
        <input type="text" name="search" class="form-control" placeholder="Search by name or ID..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-secondary ms-2">Search</button>
    </form>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Sr No</th>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Active</th>
            <th>Featured</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $sr_no = 1; foreach($categories as $cat): ?>
        <tr>
            <td><?php echo $sr_no++; ?></td>
            <td><?php echo $cat['id']; ?></td>
            <td>
                <?php if($cat['image']): ?>
                    <img src="../<?php echo $cat['image']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                <?php else: ?>
                    <span class="text-muted">No image</span>
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($cat['name']); ?></td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="activeSwitch<?php echo $cat['id']; ?>" 
                           <?php echo $cat['is_active'] ? 'checked' : ''; ?> 
                           onchange="toggleActive(<?php echo $cat['id']; ?>)">
                </div>
            </td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="featuredSwitch<?php echo $cat['id']; ?>" 
                           <?php echo $cat['is_featured'] ? 'checked' : ''; ?> 
                           onchange="toggleFeatured(<?php echo $cat['id']; ?>)">
                </div>
            </td>
            <td>
                <a href="category_form.php?id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                <a href="category_actions.php?action=delete&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete(<?php echo $cat['id']; ?>, <?php echo $cat['product_count']; ?>)">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
function toggleActive(id) {
    fetch('category_actions.php?action=toggle_active&id=' + id)
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            console.log('Active status toggled');
        } else {
            alert('Error updating status');
            document.getElementById('activeSwitch' + id).checked = !document.getElementById('activeSwitch' + id).checked;
        }
    })
    .catch(error => console.error('Error:', error));
}

function toggleFeatured(id) {
    fetch('category_actions.php?action=toggle_featured&id=' + id)
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            console.log('Toggled successfully');
        } else {
            alert('Error updating status');
            document.getElementById('featuredSwitch' + id).checked = !document.getElementById('featuredSwitch' + id).checked;
        }
    })
    .catch(error => console.error('Error:', error));
}

function confirmDelete(id, productCount) {
    if (productCount > 0) {
        return confirm(`This category has ${productCount} product(s). Deleting this category may affect these products. Continue?`);
    }
    return confirm('Delete this category?');
}
</script>

<?php require_once 'includes/footer.php'; ?>
