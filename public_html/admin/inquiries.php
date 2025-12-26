<?php
require_once 'includes/header.php';
require_once '../config/database.php';

$status = $_GET['status'] ?? '';
$where = $status ? "WHERE status = '$status'" : "";

$inquiries = $pdo->query("SELECT * FROM inquiries $where ORDER BY id DESC")->fetchAll();
?>

<h3>Inquiries</h3>
<div class="btn-group mb-3">
    <a href="inquiries.php" class="btn btn-outline-secondary <?php echo !$status ? 'active' : ''; ?>">All</a>
    <a href="inquiries.php?status=new" class="btn btn-outline-primary <?php echo $status=='new' ? 'active' : ''; ?>">New</a>
    <a href="inquiries.php?status=contacted" class="btn btn-outline-warning <?php echo $status=='contacted' ? 'active' : ''; ?>">Contacted</a>
    <a href="inquiries.php?status=closed" class="btn btn-outline-success <?php echo $status=='closed' ? 'active' : ''; ?>">Closed</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($inquiries as $inq): ?>
        <tr>
            <td><?php echo $inq['id']; ?></td>
            <td><?php echo htmlspecialchars($inq['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($inq['customer_phone']); ?></td>
            <td><?php echo $inq['created_at']; ?></td>
            <td>
                <span class="badge bg-<?php echo match($inq['status']){'new'=>'primary','contacted'=>'warning','closed'=>'success'}; ?>">
                    <?php echo ucfirst($inq['status']); ?>
                </span>
            </td>
            <td>
                <a href="inquiry_view.php?id=<?php echo $inq['id']; ?>" class="btn btn-sm btn-info">View</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
