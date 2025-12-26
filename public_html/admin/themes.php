<?php
require_once 'includes/header.php';
require_once '../config/database.php';

// Activate Theme Logic
if (isset($_GET['activate'])) {
    $id = $_GET['activate'];
    // Deactivate all
    $pdo->exec("UPDATE themes SET is_active = 0");
    // Activate one
    $pdo->prepare("UPDATE themes SET is_active = 1 WHERE id = ?")->execute([$id]);
    header("Location: themes.php");
    exit;
}

$themes = $pdo->query("SELECT * FROM themes")->fetchAll();
?>

<h3>Theme Manager</h3>
<p>Select the active festival theme for the website.</p>

<div class="row">
    <?php foreach($themes as $theme): ?>
    <div class="col-md-3">
        <div class="card mb-3 <?php echo $theme['is_active'] ? 'border-success' : ''; ?>">
            <div class="card-body text-center">
                <h5 class="card-title"><?php echo $theme['name']; ?></h5>
                <p class="small text-muted"><?php echo $theme['slug']; ?></p>
                <?php if ($theme['is_active']): ?>
                    <button class="btn btn-success" disabled>Active</button>
                <?php else: ?>
                    <a href="themes.php?activate=<?php echo $theme['id']; ?>" class="btn btn-outline-primary">Activate</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
