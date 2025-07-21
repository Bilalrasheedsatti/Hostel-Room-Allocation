<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php';
require_student();

$student_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT r.room_id FROM allocations a JOIN rooms r ON a.room_id = r.room_id WHERE a.student_id = ? AND a.status = 'active'");
$stmt->execute([$student_id]);
$room = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_text = trim($_POST['complaint_text']);
    if (!empty($complaint_text) && $room) {
        $stmt = $pdo->prepare("INSERT INTO complaints (student_id, room_id, complaint_text) VALUES (?, ?, ?)");
        if ($stmt->execute([$student_id, $room['room_id'], $complaint_text])) {
            $success = "Complaint submitted.";
        } else {
            $error = "Submission failed.";
        }
    } else {
        $error = "Please enter complaint text.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Submit a Complaint</h2>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <div class="form-group">
            <label for="complaint_text">Describe the issue</label>
            <textarea id="complaint_text" name="complaint_text" required></textarea>
        </div>
        <button type="submit" class="btn">Submit</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
