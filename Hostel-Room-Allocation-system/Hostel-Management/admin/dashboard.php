<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php';

// Ensure only admins can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get counts for dashboard
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'");
$total_students = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM rooms");
$total_rooms = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'pending'");
$pending_applications = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM complaints WHERE status = 'pending'");
$pending_complaints = $stmt->fetchColumn();
?>

<?php include '../includes/admin_header.php'; ?>

<div class="admin-dashboard">
    <h2>Admin Dashboard</h2>

    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Total Students</h3>
            <p><?php echo $total_students; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Rooms</h3>
            <p><?php echo $total_rooms; ?></p>
        </div>
        <div class="stat-card">
            <h3>Pending Applications</h3>
            <p><?php echo $pending_applications; ?></p>
            <a href="applications.php" class="btn-small">View</a>
        </div>
        <div class="stat-card">
            <h3>Pending Complaints</h3>
            <p><?php echo $pending_complaints; ?></p>
            <a href="complaints.php" class="btn-small">View</a>
        </div>
    </div>

 <div class="quick-actions">
    <h3>Quick Actions</h3>
    <div class="action-buttons">
        <a href="allocations.php" class="btn">Manage Allocations</a>
        <a href="../map.php" class="btn">View Hostel Map</a>
        <a href="applications.php" class="btn">Process Applications</a>
        <a href="complaints.php" class="btn">Handle Complaints</a>
    </div>
</div>

</div>

<?php include '../includes/admin_footer.php'; ?>
