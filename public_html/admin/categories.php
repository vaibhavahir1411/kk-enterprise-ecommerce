<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY display_order ASC")->fetchAll();
?>

<h3>Categories</h3>
<a href="category_form.php" class="btn btn-primary mb-3">Add Category</a>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Display Order</th>
            <th>Image</th>
            <th>Featured</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($categories as $cat): ?>
        <tr>
            <td><?php echo htmlspecialchars($cat['name']); ?></td>
            <td><?php echo $cat['display_order']; ?></td>
            <td>
                <?php if($cat['image']): ?>
                    <img src="<?php echo $cat['image']; ?>" style="height: 50px;">
                <?php endif; ?>
            </td>
            <td>
                <input type="checkbox" disabled <?php echo $cat['is_featured'] ? 'checked' : ''; ?>>
            </td>
            <td>
                <a href="category_form.php?id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                <a href="category_actions.php?action=delete&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
