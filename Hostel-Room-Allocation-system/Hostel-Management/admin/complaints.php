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
    <div class="complaints-container">
        <h2>Complaints</h2>

        <?php
        // Status counts for graph
        $pending = 0; $in_progress = 0; $resolved = 0;
        foreach ($complaints as $c) {
            if ($c['status'] == 'pending') $pending++;
            elseif ($c['status'] == 'in_progress') $in_progress++;
            elseif ($c['status'] == 'resolved') $resolved++;
        }
        ?>

        <div class="status-bar">
            <div class="status-bar-item" style="background: linear-gradient(to right, #f7971e, #ffd200);">
                Pending<br><span><?= $pending ?></span>
            </div>
            <div class="status-bar-item" style="background: linear-gradient(to right, #43cea2, #185a9d);">
                In Progress<br><span><?= $in_progress ?></span>
            </div>
            <div class="status-bar-item" style="background: linear-gradient(to right, #24C6DC, #514A9D);">
                Resolved<br><span><?= $resolved ?></span>
            </div>
        </div>

        <div class="search-box">
            <input type="text" id="complaintSearch" placeholder="Search complaints...">
        </div>

        <table class="complaints-table" id="complaintsTable">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Room</th>
                    <th>Issue</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($complaints as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['full_name']) ?></td>
                    <td><?= htmlspecialchars($c['room_number']) ?></td>
                    <td><?= htmlspecialchars($c['complaint_text']) ?></td>
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
                            <?= htmlspecialchars($c['resolution_text']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
document.getElementById('complaintSearch').addEventListener('input', function() {
    const val = this.value.toLowerCase();
    const rows = document.querySelectorAll('#complaintsTable tbody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>

<?php include '../includes/footer.php'; ?>
