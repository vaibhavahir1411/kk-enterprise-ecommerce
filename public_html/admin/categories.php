<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$search = $_GET['search'] ?? '';
$where = $search ? "WHERE name LIKE '%$search%' OR id LIKE '%$search%'" : "";
$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count FROM categories c $where ORDER BY id DESC")->fetchAll();
?>

<h3>Categories</h3>
<div class="mb-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
        <a href="category_form.php" class="btn btn-primary">Add Category</a>
        <form method="GET" class="d-flex gap-2" style="width: 100%; max-width: 300px;">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>
    </div>
</div>

<div class="table-responsive">
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
                <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); confirmDelete(<?php echo $cat['id']; ?>, <?php echo $cat['product_count']; ?>);">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

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
    let message = productCount > 0 
        ? `This category has ${productCount} product(s). Deleting this category may affect these products. Continue?`
        : 'Are you sure you want to delete this category?';
    
    showConfirm(message, function(confirmed) {
        if (confirmed) {
            window.location.href = 'category_actions.php?action=delete&id=' + id;
        }
    });
}
</script>

<script>
// Live Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const categoryRows = document.querySelectorAll('tbody tr');
            
            categoryRows.forEach(row => {
                // Get category name (4th column), ID (2nd column), and Sr No (1st column)
                const categoryNameCell = row.querySelector('td:nth-child(4)');
                const idCell = row.querySelector('td:nth-child(2)');
                
                if (categoryNameCell && idCell) {
                    const categoryName = categoryNameCell.textContent.toLowerCase();
                    const categoryId = idCell.textContent.toLowerCase();
                    
                    // Show/hide row based on search match
                    if (categoryName.includes(searchTerm) || categoryId.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
