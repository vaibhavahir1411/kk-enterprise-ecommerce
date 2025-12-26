<?php
session_start();
require_once '../config/database.php';

$action = $_REQUEST['action'];

function createSlug($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    return trim($str, '-');
}

try {
    // Function to check featured limit
    function checkFeaturedLimit($pdo, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM categories WHERE is_featured = 1";
        if ($excludeId) {
            $sql .= " AND id != $excludeId";
        }
        return $pdo->query($sql)->fetchColumn();
    }

    if ($action == 'add') {
        $name = $_POST['name'];
        $slug = createSlug($name);
        $order = 0; // Default display order
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;     
        // Handle Image
        $image = $_POST['image_url']; // Default to URL
        if(isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'jfif', 'bmp', 'svg', 'tiff', 'ico'];
            if(in_array($ext, $allowed)) {
                $new_name = 'cat_' . uniqid() . '.' . $ext;
                $upload_dir = '../uploads/categories/';
                if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                if(move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_dir . $new_name)) {
                    $image = 'uploads/categories/' . $new_name;
                }
            }
        }
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;



        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, display_order, image, is_featured) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $order, $image, $is_featured]);

    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        // Keep existing display_order
        $stmt_order = $pdo->prepare("SELECT display_order FROM categories WHERE id = ?");
        $stmt_order->execute([$id]);
        $order = $stmt_order->fetchColumn();
        
        // Handle Image
        $image = $_POST['image_url']; // Default to existing URL input
        if (empty($image)) {
            // If URL cleared, maybe keep old? Logic: If new file, overwrite. If no new file and URL blank, check logic.
            // Actually, keep old image if not provided?
            // Let's first check file.
            // Fetch existing to revert if needed? Or allow clearing?
        }

        if(isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'jfif', 'bmp', 'svg', 'tiff', 'ico'];
            if(in_array($ext, $allowed)) {
                $new_name = 'cat_' . uniqid() . '.' . $ext;
                $upload_dir = '../uploads/categories/';
                if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                if(move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_dir . $new_name)) {
                    $image = 'uploads/categories/' . $new_name;
                }
            }
        } elseif (empty($image)) {
             // If no file and no URL, retain old? API doesn't pass old image in hidden field easily unless I add it.
             // We can fetch old.
             $stmt_old = $pdo->prepare("SELECT image FROM categories WHERE id = ?");
             $stmt_old->execute([$id]);
             $image = $stmt_old->fetchColumn();
             if(!empty($_POST['image_url'])) $image = $_POST['image_url']; // If URL explicitly changed
        }
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;



        $stmt = $pdo->prepare("UPDATE categories SET name=?, display_order=?, image=?, is_featured=? WHERE id=?");
        $stmt->execute([$name, $order, $image, $is_featured, $id]);

    } elseif ($action == 'delete') {
        $id = $_GET['id'];
        $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
    
    } elseif ($action == 'toggle_active') {
        $id = $_GET['id'];
        $stmt = $pdo->prepare("UPDATE categories SET is_active = NOT is_active WHERE id = ?");
        $result = $stmt->execute([$id]);
        echo json_encode(['success' => $result]);
        exit;
    
    } elseif ($action == 'toggle_featured') {
        $id = $_GET['id'];
        // Check current status
        $stmt = $pdo->prepare("SELECT is_featured FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetchColumn();
        
        // If enabling, check limit (excluding self, effectively check total count)
        if (!$current && checkFeaturedLimit($pdo) >= 30) { // Limit increased to 30 as per user request "admin can add as many as he want"
             echo json_encode(['success' => false, 'message' => 'Limit reached']);
             exit;
        }

        $stmt = $pdo->prepare("UPDATE categories SET is_featured = NOT is_featured WHERE id = ?");
        $result = $stmt->execute([$id]);
        echo json_encode(['success' => $result]);
        exit;
    }

    header("Location: categories.php");
} catch (PDOException $e) {
    die($e->getMessage());
}
?>
