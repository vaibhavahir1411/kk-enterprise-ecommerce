<?php
require_once 'includes/header.php';
require_once '../config/database.php';

function createSlug($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    return trim($str, '-');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, "r");
    
    // Skip header
    fgetcsv($handle);
    
    $success = 0;
    $errors = 0;

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // Expected Format: Name, Category, Price, Sale Price, Description, Stock Status
        try {
            $name = $data[0];
            $cat_name = $data[1];
            $price = $data[2];
            $sale_price = !empty($data[3]) ? $data[3] : NULL;
            $desc = $data[4];
            $stock = !empty($data[5]) ? $data[5] : 'in_stock';
            
            // Find/Create Category
            $params_cat = [$cat_name];
            $stmt_cat = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
            $stmt_cat->execute($params_cat);
            $cat_id = $stmt_cat->fetchColumn();
            
            if (!$cat_id) {
                // Create
                $c_slug = createSlug($cat_name);
                $stmt_c = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
                $stmt_c->execute([$cat_name, $c_slug]);
                $cat_id = $pdo->lastInsertId();
            }

            $slug = createSlug($name);
             // Uniqueness check
            $chk = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
            $chk->execute([$slug]);
            if($chk->fetchColumn() > 0) $slug .= '-' . time();

            $stmt_p = $pdo->prepare("INSERT INTO products (name, slug, category_id, price, sale_price, description, stock_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_p->execute([$name, $slug, $cat_id, $price, $sale_price, $desc, $stock]);
            $success++;
        } catch (Exception $e) {
            $errors++;
        }
    }
    fclose($handle);
    echo "<div class='alert alert-success'>Imported: $success, Failed: $errors</div>";
}
?>

<h3>Import Products CSV</h3>
<p>Upload a CSV file with columns: <strong>Name, Category, Price, Sale Price, Description, Stock Status (1 = in_stock, 0 = out_of_stock)</strong></p>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <input type="file" name="csv_file" class="form-control" required accept=".csv">
    </div>
    <button type="submit" class="btn btn-primary">Import</button>
</form>

<?php require_once 'includes/footer.php'; ?>
