<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$banners = $pdo->query("SELECT b.*, t.name as theme_name FROM banners b LEFT JOIN themes t ON b.theme_id = t.id")->fetchAll();
?>

<h3>Banners</h3>
<a href="banner_form.php" class="btn btn-primary mb-3">Add Banner</a>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Assigned Theme</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($banners as $b): ?>
        <tr>
            <td><img src="../<?php echo $b['image_path']; ?>" style="height:50px; object-fit: cover;"></td>
            <td><?php echo $b['title']; ?></td>
            <td><?php echo $b['theme_name'] ? $b['theme_name'] : 'All Themes'; ?></td>
            <td>
                <a href="banner_actions.php?action=delete&id=<?php echo $b['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
