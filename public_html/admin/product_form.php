<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$product = null;
$product_images = [];
$is_edit = false;

// Fetch categories for dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch();
    
    if ($product) {
        $is_edit = true;
        // Fetch images
        $stmt_img = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY display_order ASC");
        $stmt_img->execute([$product['id']]);
        $product_images = $stmt_img->fetchAll();
    }
}
?>

<h3><?php echo $is_edit ? 'Edit Product' : 'Add New Product'; ?></h3>

<form action="product_actions.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="<?php echo $is_edit ? 'update' : 'add'; ?>">
    <?php if ($is_edit): ?>
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">Basic Info</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $is_edit ? htmlspecialchars($product['name']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?php echo $is_edit ? htmlspecialchars($product['description']) : ''; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Images (Max 5-6)</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="images" class="form-label">Upload New Images</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                        <small class="text-muted">You can select multiple files.</small>
                    </div>

                    <?php if ($is_edit && !empty($product_images)): ?>
                        <h6>Current Images:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($product_images as $img): ?>
                                <div class="text-center border p-2">
                                    <img src="../<?php echo $img['image_path']; ?>" style="height: 80px; width: 80px; object-fit: cover; display: block;" class="mb-1">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="checkbox" name="delete_images[]" value="<?php echo $img['id']; ?>" id="del_<?php echo $img['id']; ?>">
                                        <label class="form-check-label text-danger" for="del_<?php echo $img['id']; ?>">Del</label>
                                    </div>
                                    <div class="form-check d-inline-block ms-1">
                                         <input class="form-check-input" type="radio" name="primary_image" value="<?php echo $img['id']; ?>" <?php echo $img['is_primary'] ? 'checked' : ''; ?>>
                                         <label class="form-check-label small" title="Set as Primary">Main</label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Pricing & Category</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($is_edit && $product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Regular Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $is_edit ? $product['price'] : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="sale_price" class="form-label">Sale Price (Optional)</label>
                        <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" value="<?php echo $is_edit ? $product['sale_price'] : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock Status</label>
                        <select class="form-select" name="stock_status">
                            <option value="in_stock" <?php echo ($is_edit && $product['stock_status'] == 'in_stock') ? 'selected' : ''; ?>>In Stock</option>
                            <option value="out_of_stock" <?php echo ($is_edit && $product['stock_status'] == 'out_of_stock') ? 'selected' : ''; ?>>Out of Stock</option>
                        </select>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?php echo (!$is_edit || $product['is_active']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_active">Active (Visible)</label>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo ($is_edit && $product['is_featured']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_featured">Featured Product</label>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 btn-lg"><?php echo $is_edit ? 'Update Product' : 'Create Product'; ?></button>
            <a href="products.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </div>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
