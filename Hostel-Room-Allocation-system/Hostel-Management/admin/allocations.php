<?php 
session_start(); 
include '../includes/config.php'; 
include '../includes/auth.php';

// Ensure admin only access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];

    // Check if student already has an active allocation
    $stmt = $pdo->prepare("SELECT * FROM allocations WHERE student_id = ? AND status = 'active'");
    $stmt->execute([$student_id]);
    if ($stmt->fetch()) {
        $error = "This student already has an allocated room.";
    } else {
        // Check if room is available
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = ? AND status = 'available' AND occupied < capacity");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch();

        if ($room) {
            // Allocate room
            $stmt = $pdo->prepare("INSERT INTO allocations (student_id, room_id, allocate_date, status) VALUES (?, ?, NOW(), 'active')");
            if ($stmt->execute([$student_id, $room_id])) {
                // Update room occupied count
                $stmt = $pdo->prepare("UPDATE rooms SET occupied = occupied + 1 WHERE room_id = ?");
                $stmt->execute([$room_id]);

                // If room becomes full, update status
                if ($room['occupied'] + 1 >= $room['capacity']) {
                    $stmt = $pdo->prepare("UPDATE rooms SET status = 'occupied' WHERE room_id = ?");
                    $stmt->execute([$room_id]);
                }

                $message = "Room allocated successfully.";
            } else {
                $error = "Failed to allocate room.";
            }
        } else {
            $error = "Room is not available or already full.";
        }
    }
}

// Get unallocated students
$students = $pdo->query("
    SELECT u.id as student_id, u.full_name as name 
    FROM users u 
    LEFT JOIN allocations a ON u.id = a.student_id AND a.status = 'active'
    WHERE u.role = 'student' AND a.allocation_id IS NULL
    ORDER BY u.full_name
")->fetchAll();

// Get available rooms
$rooms = $pdo->query("
    SELECT r.*, f.floor_number, b.building_name
    FROM rooms r
    JOIN floors f ON r.floor_id = f.floor_id
    JOIN buildings b ON f.building_id = b.building_id
    WHERE r.status = 'available' AND r.occupied < r.capacity
    ORDER BY b.building_name, f.floor_number, r.room_number
")->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="allocation-container">
    <h2 class="allocation-title">Room Allocation</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="allocation-card">
        <form method="post" class="allocation-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="student_id">Student</label>
                    <select name="student_id" id="student_id" required>
                        <option value="">Select Student</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['student_id'] ?>">
                                <?= htmlspecialchars($student['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="room_id">Room</label>
                    <select name="room_id" id="room_id" required>
                        <option value="">Select Room</option>
                        <?php foreach ($rooms as $room): ?>
                            <option value="<?= $room['room_id'] ?>">
                                <?= htmlspecialchars($room['building_name']) ?> - Floor <?= $room['floor_number'] ?> - 
                                <?= htmlspecialchars($room['room_number']) ?> 
                                (<?= $room['occupied'] ?>/<?= $room['capacity'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn allocation-btn">Allocate Room</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>