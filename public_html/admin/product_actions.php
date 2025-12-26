<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$action = $_REQUEST['action'] ?? '';

function createSlug($str) {
    // Basic slugify function
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    return trim($str, '-');
}

function handleImageUploads($pdo, $product_id, $files) {
    if (empty($files['name'][0])) return;

    $count = count($files['name']);
    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            // Allowed list expanded to all common types
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'jfif', 'svg', 'bmp', 'tiff', 'ico'];
            
            if (in_array($ext, $allowed)) {
                $new_name = uniqid() . '.' . $ext;
                
                // Relative path for DB
                $db_path = "uploads/products/" . date('Y') . "/" . date('m') . "/" . $new_name;
                
                // Absolute path for file system (FIXED)
                $target_dir = __DIR__ . "/../uploads/products/" . date('Y') . "/" . date('m') . "/";
                
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $target_path = $target_dir . $new_name;

                if (move_uploaded_file($files['tmp_name'][$i], $target_path)) {
                    // Save to DB
                    $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                    $stmt->execute([$product_id, $db_path]);
                } else {
                    $_SESSION['error'] = "Failed to move uploaded file: " . $files['name'][$i];
                }
            } else {
                 $_SESSION['error'] = "File not allowed: " . $files['name'][$i];
            }
        }
    }
}

try {
    if ($action == 'add') {
        $name = $_POST['name'];
        $slug = createSlug($name);
        $cat_id = $_POST['category_id'];
        $price = !empty($_POST['price']) ? $_POST['price'] : 0;
        $sale_price = !empty($_POST['sale_price']) ? $_POST['sale_price'] : NULL;
        $desc = $_POST['description'];
        $stock = $_POST['stock_status'];
        $active = isset($_POST['is_active']) ? 1 : 0;
        $featured = isset($_POST['is_featured']) ? 1 : 0;

        // Uniqueness check for slug
        $chk = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
        $chk->execute([$slug]);
        if($chk->fetchColumn() > 0) $slug .= '-' . time();

        $stmt = $pdo->prepare("INSERT INTO products (name, slug, category_id, price, sale_price, description, stock_status, is_active, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $cat_id, $price, $sale_price, $desc, $stock, $active, $featured]);
        $product_id = $pdo->lastInsertId();

        // Handle Images
        if (isset($_FILES['images'])) {
            handleImageUploads($pdo, $product_id, $_FILES['images']);
            // Set first image as primary if none exists
            $pdo->exec("UPDATE product_images SET is_primary = 1 WHERE product_id = $product_id ORDER BY id ASC LIMIT 1");
        }

        $_SESSION['success'] = "Product created successfully!";
        header("Location: products.php");

    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $cat_id = $_POST['category_id'];
        $price = !empty($_POST['price']) ? $_POST['price'] : 0;
        $sale_price = !empty($_POST['sale_price']) ? $_POST['sale_price'] : NULL;
        $desc = $_POST['description'];
        $stock = $_POST['stock_status'];
        $active = isset($_POST['is_active']) ? 1 : 0;
        $featured = isset($_POST['is_featured']) ? 1 : 0;

        $stmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, price=?, sale_price=?, description=?, stock_status=?, is_active=?, is_featured=? WHERE id=?");
        $stmt->execute([$name, $cat_id, $price, $sale_price, $desc, $stock, $active, $featured, $id]);

        // Handle Image Deletions
        if (isset($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $img_id) {
                // Get path
                $s = $pdo->prepare("SELECT image_path FROM product_images WHERE id = ?");
                $s->execute([$img_id]);
                $path = $s->fetchColumn();
                // Delete file
                if ($path && file_exists('../' . $path)) unlink('../' . $path);
                // Delete DB record
                $pdo->prepare("DELETE FROM product_images WHERE id = ?")->execute([$img_id]);
            }
        }

        // Handle New Images
        if (isset($_FILES['images'])) {
            handleImageUploads($pdo, $id, $_FILES['images']);
        }
        
        // Handle Primary Image Update
        if (isset($_POST['primary_image'])) {
            $pid = $_POST['primary_image'];
            // Reset all for this product
            $pdo->prepare("UPDATE product_images SET is_primary = 0 WHERE product_id = ?")->execute([$id]);
            // Set new primary
            $pdo->prepare("UPDATE product_images SET is_primary = 1 WHERE id = ?")->execute([$pid]);
        }

        $_SESSION['success'] = "Product updated successfully!";
        header("Location: products.php");

    } elseif ($action == 'delete') {
        $id = $_GET['id'];
        // Get all images
        $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
        $stmt->execute([$id]);
        $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($images as $path) {
            if (file_exists('../' . $path)) unlink('../' . $path);
        }

        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        $_SESSION['success'] = "Product deleted successfully!";
        header("Location: products.php");
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: products.php");
}
?>
