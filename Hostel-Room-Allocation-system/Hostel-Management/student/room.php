<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php';
require_student();

$student_id = $_SESSION['user_id'];

// Prevent duplicate applications
$stmt = $pdo->prepare("SELECT * FROM applications WHERE student_id = ?");
$stmt->execute([$student_id]);
$existing = $stmt->fetch();

if ($existing) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO applications (student_id) VALUES (?)");
    if ($stmt->execute([$student_id])) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Failed to apply. Try again.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Apply for Hostel Room</h2>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <p>Click below to submit your application for a hostel room.</p>
        <button type="submit" class="btn">Apply</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
