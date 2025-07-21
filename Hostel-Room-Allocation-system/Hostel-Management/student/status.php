<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php';
require_student();

$student_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM complaints WHERE student_id = ?");
$stmt->execute([$student_id]);
$complaints = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Complaint Status</h2>
    <?php if ($complaints): ?>
        <table>
            <tr>
                <th>Complaint</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Resolved</th>
            </tr>
            <?php foreach ($complaints as $c): ?>
                <tr>
                    <td><?php echo $c['complaint_text']; ?></td>
                    <td><?php echo ucfirst($c['status']); ?></td>
                    <td><?php echo date('d M Y', strtotime($c['complaint_date'])); ?></td>
                    <td><?php echo $c['resolved_date'] ? date('d M Y', strtotime($c['resolved_date'])) : 'N/A'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No complaints found.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
