<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $admin_id = $_SESSION['admin_id'];

    if (!empty($new_password)) {
        // Update both username and password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admins SET username = ?, password = ? WHERE id = ?");
        $stmt->execute([$new_username, $hashed_password, $admin_id]);
    } else {
        // Update only username
        $stmt = $pdo->prepare("UPDATE admins SET username = ? WHERE id = ?");
        $stmt->execute([$new_username, $admin_id]);
    }

    $_SESSION['admin_username'] = $new_username;
    $_SESSION['success'] = "Settings updated successfully!";
    header("Location: settings.php");
    exit();
}

require_once 'includes/header.php';

$admin_id = $_SESSION['admin_id'];
$admin = $pdo->prepare("SELECT username FROM admins WHERE id = ?");
$admin->execute([$admin_id]);
$admin_data = $admin->fetch();
?>

<h3>Account Settings</h3>
<div class="card shadow-sm p-4" style="max-width: 500px;">
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($admin_data['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control" placeholder="Enter new password">
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Settings</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
