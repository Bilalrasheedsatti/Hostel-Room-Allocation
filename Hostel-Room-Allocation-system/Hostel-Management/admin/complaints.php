<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php';
require_admin();

$stmt = $pdo->query("
    SELECT c.*, u.full_name, r.room_number 
    FROM complaints c 
    JOIN users u ON c.student_id = u.id 
    JOIN rooms r ON c.room_id = r.room_id 
    ORDER BY c.complaint_date DESC
");

$complaints = $stmt->fetchAll();

// Update complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['complaint_id'];
    $resolution = trim($_POST['resolution_text']);
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE complaints SET status = ?, resolution_text = ?, resolved_date = NOW() WHERE complaint_id = ?");
    $stmt->execute([$status, $resolution, $id]);

    header("Location: complaints.php");
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Complaints</h2>

    <table>
        <tr>
            <th>Student</th>
            <th>Room</th>
            <th>Issue</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($complaints as $c): ?>
            <tr>
                <td><?= $c['full_name'] ?></td>
                <td><?= $c['room_number'] ?></td>
                <td><?= $c['complaint_text'] ?></td>
                <td><?= ucfirst($c['status']) ?></td>
                <td>
                    <?php if ($c['status'] !== 'resolved'): ?>
                        <form method="POST">
                            <input type="hidden" name="complaint_id" value="<?= $c['complaint_id'] ?>">
                            <textarea name="resolution_text" placeholder="Resolution text" required></textarea><br>
                            <select name="status">
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                            </select>
                            <button type="submit" class="btn btn-small">Update</button>
                        </form>
                    <?php else: ?>
                        <?= $c['resolution_text'] ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
