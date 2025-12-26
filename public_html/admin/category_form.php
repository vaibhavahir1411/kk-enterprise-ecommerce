<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$category = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $category = $stmt->fetch();
}
?>

<h3><?php echo $category ? 'Edit Category' : 'Add Category'; ?></h3>

<form action="category_actions.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="<?php echo $category ? 'update' : 'add'; ?>">
    <?php if ($category): ?>
        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $category ? htmlspecialchars($category['name']) : ''; ?>" required>
    </div>
    
    <div class="mb-3">
        <label>Display Order</label>
        <input type="number" name="display_order" class="form-control" value="<?php echo $category ? $category['display_order'] : 0; ?>">
    </div>

    <div class="mb-3">
        <label>Image</label>
        <div class="input-group mb-2">
            <span class="input-group-text">URL</span>
            <input type="text" name="image_url" class="form-control" value="<?php echo $category ? $category['image'] : ''; ?>" placeholder="https://...">
        </div>
        <div class="input-group">
            <input type="file" name="image_file" class="form-control" accept="image/*">
        </div>
        <small class="text-muted">Upload a file OR enter a URL. File takes precedence.</small>
        <?php if($category && $category['image']): ?>
            <div class="mt-2">
                <img src="<?php echo $category['image']; ?>" height="50">
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="is_featured" id="is_featured" value="1" <?php echo ($category && $category['is_featured']) ? 'checked' : ''; ?>>
        <label class="form-check-label" for="is_featured">Show on Home Page (Featured)</label>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
    <a href="categories.php" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once 'includes/footer.php'; ?>
