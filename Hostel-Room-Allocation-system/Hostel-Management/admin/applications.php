<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php';
require_admin();

// Fetch applications
$stmt = $pdo->query("
    SELECT a.application_id, u.full_name, u.username, a.apply_date, a.status 
    FROM applications a 
    JOIN users u ON a.student_id = u.id 
    ORDER BY a.apply_date DESC
");

$applications = $stmt->fetchAll();

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $app_id = $_POST['app_id'];
    $action = $_POST['action'];

    if (in_array($action, ['approved', 'rejected'])) {
        $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE application_id = ?");
        $stmt->execute([$action, $app_id]);
        header("Location: applications.php");
        exit;
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>📄 Pending Applications</h2>

    <div class="card" style="    max-width: max-content;">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Applied On</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?= htmlspecialchars($app['full_name']) ?></td>
                        <td><?= htmlspecialchars($app['username']) ?></td>
                        <td><?= date('d M Y', strtotime($app['apply_date'])) ?></td>
                        <td>
                            <?php if ($app['status'] == 'pending'): ?>
                                <span class="status-pending">Pending</span>
                            <?php elseif ($app['status'] == 'approved'): ?>
                                <span class="status-approved">Approved</span>
                            <?php else: ?>
                                <span class="status-rejected">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($app['status'] === 'pending'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="app_id" value="<?= $app['application_id'] ?>">
                                    <button type="submit" name="action" value="approved" class="btn btn-success btn-small">Approve</button>
                                    <button type="submit" name="action" value="rejected" class="btn btn-danger btn-small">Reject</button>
                                </form>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
