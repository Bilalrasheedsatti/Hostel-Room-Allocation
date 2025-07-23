<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php';
require_student();

$student_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT r.room_id FROM allocations a JOIN rooms r ON a.room_id = r.room_id WHERE a.student_id = ? AND a.status = 'active'");
$stmt->execute([$student_id]);
$room = $stmt->fetch();

// Uncomment for debugging in network tab only
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     error_log('POST: ' . print_r($_POST, true));
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_text = isset($_POST['complaint_text']) ? trim($_POST['complaint_text']) : '';
    if ($complaint_text === '') {
        $error = "Please enter complaint text.";
    } elseif (!$room) {
        $error = "You must have an active room allocation to submit a complaint.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO complaints (student_id, room_id, complaint_text) VALUES (?, ?, ?)");
        if ($stmt->execute([$student_id, $room['room_id'], $complaint_text])) {
            $success = "Complaint submitted.";
        } else {
            $error = "Submission failed.";
            // Uncomment for SQL error debugging in logs
            // error_log('SQL Error: ' . print_r($stmt->errorInfo(), true));
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Submit a Complaint</h2>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="complaint_text">Describe the issue</label>
            <textarea id="complaint_text" name="complaint_text" required <?= !$room ? 'disabled' : '' ?>></textarea>
        </div>
        <button type="submit" class="btn" <?= !$room ? 'disabled' : '' ?>>Submit</button>
    </form>
    <?php if (!$room): ?>
        <div class="alert alert-danger" style="margin-top:15px;">
            You must have an active room allocation to submit a complaint.
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
