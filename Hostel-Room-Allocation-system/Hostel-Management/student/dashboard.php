<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php';

// Ensure only students can access
if ($_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$student_id = $_SESSION['user_id'];

// Check if student has applied for a room
$stmt = $pdo->prepare("SELECT * FROM applications WHERE student_id = ?");
$stmt->execute([$student_id]);
$application = $stmt->fetch();

// Check if student has an allocated room
$stmt = $pdo->prepare("SELECT a.*, r.room_number, r.building 
                      FROM allocations a 
                      JOIN rooms r ON a.room_id = r.room_id 
                      WHERE a.student_id = ? AND a.status = 'active'");
$stmt->execute([$student_id]);
$allocation = $stmt->fetch();

// Check for pending complaints
$stmt = $pdo->prepare("SELECT COUNT(*) FROM complaints WHERE student_id = ? AND status = 'pending'");
$stmt->execute([$student_id]);
$pending_complaints = $stmt->fetchColumn();
?>

<?php include '../includes/header.php'; ?>

<div class="student-dashboard">
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    
    <div class="dashboard-cards">
        <!-- Room Application Card -->
        <div class="card">
            <h3>Room Application</h3>
            <?php if ($application): ?>
                <p>Status: <span class="status-<?php echo $application['status']; ?>">
                    <?php echo ucfirst($application['status']); ?>
                </span></p>
                <p>Applied on: <?php echo date('d M Y', strtotime($application['apply_date'])); ?></p>
            <?php else: ?>
                <p>You haven't applied for a room yet.</p>
                <a href="apply.php" class="btn">Apply Now</a>
            <?php endif; ?>
        </div>
        
        <!-- Room Allocation Card -->
        <div class="card">
            <h3>Room Allocation</h3>
            <?php if ($allocation): ?>
                <p>Room: <?php echo $allocation['building'] . ' - ' . $allocation['room_number']; ?></p>
                <p>Allocated on: <?php echo date('d M Y', strtotime($allocation['allocate_date'])); ?></p>
                <a href="room.php" class="btn">View Details</a>
            <?php else: ?>
                <p>You don't have an allocated room yet.</p>
            <?php endif; ?>
        </div>
        
        <!-- Maintenance / Complaint Card -->
        <div class="card">
            <h3>Maintenance</h3>
            <p>Pending complaints: <?php echo $pending_complaints; ?></p>
            <a href="complaint.php" class="btn">Submit Complaint</a>
            <a href="status.php" class="btn btn-outline">View Status</a>
        </div>

        <!-- Hostel Map Card -->
        <div class="card">
            <h3>Hostel Map</h3>
            <p>Click below to view available rooms visually.</p>
            <a href="../map.php" class="btn">View Hostel Map</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
