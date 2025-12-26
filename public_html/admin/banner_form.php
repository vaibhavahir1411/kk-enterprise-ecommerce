<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$themes = $pdo->query("SELECT * FROM themes")->fetchAll();
?>

<h3>Add Banner</h3>
<form action="banner_actions.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    
    <div class="mb-3">
        <label>Banner Image URL</label>
        <input type="text" name="image_path" class="form-control" required placeholder="https://...">
    </div>
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control">
    </div>
    <div class="mb-3">
        <label>Subtitle</label>
        <input type="text" name="subtitle" class="form-control">
    </div>
    <div class="mb-3">
        <label>Assign to Theme</label>
        <select name="theme_id" class="form-select">
            <option value="">All Themes</option>
            <?php foreach($themes as $t): ?>
                <option value="<?php echo $t['id']; ?>"><?php echo $t['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Save Banner</button>
</form>

<?php require_once 'includes/footer.php'; ?>
