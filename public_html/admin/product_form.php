<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$product = null;
$product_images = [];
$is_edit = false;

// Fetch categories for dropdown
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC")->fetchAll();

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
                <div class="card-header">Images (Max 6)</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="images" class="form-label">Upload New Images (Max 6)</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" onchange="validateFileCount(this)">
                        <small class="text-muted">You can select multiple files (maximum 6 total including existing images).</small>
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
                        <label for="price" class="form-label">Regular Price (₹)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $is_edit ? $product['price'] : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="discount_percent" class="form-label">Discount (%)</label>
                        <input type="number" step="0.01" class="form-control" id="discount_percent" name="discount_percent" value="<?php echo $is_edit && $product['sale_price'] ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100, 2) : ''; ?>" min="0" max="100">
                        <small class="text-muted">Auto-calculates sale price</small>
                    </div>

                    <div class="mb-3">
                        <label for="sale_price" class="form-label">Sale Price (₹)</label>
                        <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" value="<?php echo $is_edit ? $product['sale_price'] : ''; ?>">
                        <small class="text-muted">Must be less than regular price</small>
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

<script>
// Price validation and discount calculation
const priceInput = document.getElementById('price');
const salePriceInput = document.getElementById('sale_price');
const discountInput = document.getElementById('discount_percent');

// Calculate sale price from discount percentage
discountInput.addEventListener('input', function() {
    const price = parseFloat(priceInput.value) || 0;
    const discount = parseFloat(this.value) || 0;
    if (price > 0 && discount > 0) {
        const salePrice = price - (price * discount / 100);
        salePriceInput.value = salePrice.toFixed(2);
    }
});

// Calculate discount from sale price
salePriceInput.addEventListener('input', function() {
    const price = parseFloat(priceInput.value) || 0;
    const salePrice = parseFloat(this.value) || 0;
    if (price > 0 && salePrice > 0) {
        const discount = ((price - salePrice) / price) * 100;
        discountInput.value = discount.toFixed(2);
    }
});

// Validate on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    const price = parseFloat(priceInput.value) || 0;
    const salePrice = parseFloat(salePriceInput.value) || 0;
    
    if (salePrice > 0 && salePrice >= price) {
        e.preventDefault();
        alert('Sale price must be less than regular price!');
        return false;
    }
});

// Validate file count for multiple file input
function validateFileCount(input) {
    const MAX_IMAGES = 6;
    const existingImages = <?php echo $is_edit ? count($images) : 0; ?>;
    const selectedFiles = input.files.length;
    const totalImages = existingImages + selectedFiles;
    
    if (totalImages > MAX_IMAGES) {
        alert(`You can only upload ${MAX_IMAGES - existingImages} more image(s). You currently have ${existingImages} image(s) and selected ${selectedFiles} file(s).`);
        input.value = ''; // Clear the selection
        return false;
    }
    
    if (selectedFiles > MAX_IMAGES) {
        alert(`Maximum ${MAX_IMAGES} images allowed. You selected ${selectedFiles} files.`);
        input.value = ''; // Clear the selection
        return false;
    }
}


// Image limit functionality
const MAX_IMAGES = 6;
let imageCount = 0;

// Count existing image rows on page load
document.addEventListener('DOMContentLoaded', function() {
    imageCount = document.querySelectorAll('.image-row').length;
    updateAddButton();
});

function addImageRow() {
    if (imageCount >= MAX_IMAGES) {
        alert('Maximum 6 images allowed per product');
        return;
    }
    
    imageCount++;
    const container = document.getElementById('image-rows');
    const newRow = document.createElement('div');
    newRow.className = 'image-row mb-3';
    newRow.innerHTML = `
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" name="image_urls[]" class="form-control" placeholder="Image URL">
            </div>
            <div class="col-md-5">
                <input type="file" name="image_files[]" class="form-control" accept="image/*">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100" onclick="removeImageRow(this)">Remove</button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    updateAddButton();
}

function removeImageRow(btn) {
    btn.closest('.image-row').remove();
    imageCount--;
    updateAddButton();
}

function updateAddButton() {
    const addBtn = document.querySelector('button[onclick="addImageRow()"]');
    if (!addBtn) return;
    
    if (imageCount >= MAX_IMAGES) {
        addBtn.disabled = true;
        addBtn.classList.add('disabled');
        addBtn.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Maximum 6 Images';
    } else {
        addBtn.disabled = false;
        addBtn.classList.remove('disabled');
        addBtn.innerHTML = '<i class="bi bi-plus-circle"></i> Add More Images';
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
