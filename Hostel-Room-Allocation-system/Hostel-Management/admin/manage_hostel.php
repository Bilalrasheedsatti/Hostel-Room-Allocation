<?php
session_start();
include '../includes/config.php';
include '../includes/admin_header.php';

// --- Handle Add/Edit/Delete for Building ---
if (isset($_POST['add_building'])) {
    $name = trim($_POST['building_name']);
    if ($name !== '') {
        $stmt = $pdo->prepare("INSERT INTO buildings (building_name) VALUES (?)");
        $stmt->execute([$name]);
    }
}
if (isset($_POST['edit_building'])) {
    $id = $_POST['building_id'];
    $name = trim($_POST['building_name']);
    $stmt = $pdo->prepare("UPDATE buildings SET building_name=? WHERE building_id=?");
    $stmt->execute([$name, $id]);
}
if (isset($_POST['delete_building'])) {
    $id = $_POST['building_id'];
    $stmt = $pdo->prepare("DELETE FROM buildings WHERE building_id=?");
    $stmt->execute([$id]);
}

// --- Handle Add/Edit/Delete for Floor ---
if (isset($_POST['add_floor'])) {
    $building_id = $_POST['building_id'];
    $floor_number = $_POST['floor_number'];
    $stmt = $pdo->prepare("INSERT INTO floors (building_id, floor_number) VALUES (?, ?)");
    $stmt->execute([$building_id, $floor_number]);
}
if (isset($_POST['edit_floor'])) {
    $id = $_POST['floor_id'];
    $floor_number = $_POST['floor_number'];
    $stmt = $pdo->prepare("UPDATE floors SET floor_number=? WHERE floor_id=?");
    $stmt->execute([$floor_number, $id]);
}
if (isset($_POST['delete_floor'])) {
    $id = $_POST['floor_id'];
    $stmt = $pdo->prepare("DELETE FROM floors WHERE floor_id=?");
    $stmt->execute([$id]);
}

// --- Handle Add/Edit/Delete for Room ---
if (isset($_POST['add_room'])) {
    $floor_id = $_POST['floor_id'];
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];
    $stmt = $pdo->prepare("INSERT INTO rooms (floor_id, room_number, capacity) VALUES (?, ?, ?)");
    $stmt->execute([$floor_id, $room_number, $capacity]);
}
if (isset($_POST['edit_room'])) {
    $id = $_POST['room_id'];
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];
    $stmt = $pdo->prepare("UPDATE rooms SET room_number=?, capacity=? WHERE room_id=?");
    $stmt->execute([$room_number, $capacity, $id]);
}
if (isset($_POST['delete_room'])) {
    $id = $_POST['room_id'];
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE room_id=?");
    $stmt->execute([$id]);
}

// --- Fetch all buildings, floors, rooms ---
$buildings = $pdo->query("SELECT * FROM buildings")->fetchAll();
$floors = $pdo->query("SELECT * FROM floors")->fetchAll();
$rooms = $pdo->query("SELECT * FROM rooms")->fetchAll();
?>

<div class="container">
    <h2>Manage Hostel</h2>
    
    <div class="admin-card">
        <!-- Add Building -->
        <form method="post">
            <h3>Add Building</h3>
            <input type="text" name="building_name" placeholder="Building Name" required>
            <button type="submit" name="add_building" class="btn">Add Building</button>
        </form>
        <!-- Buildings List -->
        <h3>Buildings</h3>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($buildings as $b): ?>
                <tr>
                    <form method="post">
                        <td>
                            <input type="hidden" name="building_id" value="<?= $b['building_id'] ?>">
                            <input type="text" name="building_name" value="<?= htmlspecialchars($b['building_name']) ?>" required>
                        </td>
                        <td><button type="submit" name="edit_building" class="btn btn-small">Edit</button></td>
                        <td><button type="submit" name="delete_building" class="btn btn-small" onclick="return confirm('Delete building?')">Delete</button></td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="admin-card">
        <!-- Add Floor -->
        <form method="post">
            <h3>Add Floor</h3>
            <select name="building_id" required>
                <option value="">Select Building</option>
                <?php foreach ($buildings as $b): ?>
                    <option value="<?= $b['building_id'] ?>"><?= htmlspecialchars($b['building_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="floor_number" placeholder="Floor Number" required>
            <button type="submit" name="add_floor" class="btn">Add Floor</button>
        </form>
        <!-- Floors List -->
        <h3>Floors</h3>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Building</th>
                    <th>Floor Number</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($floors as $f): ?>
                <tr>
                    <form method="post">
                        <td>
                            <?php
                            $b = array_filter($buildings, fn($bb) => $bb['building_id'] == $f['building_id']);
                            echo htmlspecialchars(reset($b)['building_name']);
                            ?>
                            <input type="hidden" name="floor_id" value="<?= $f['floor_id'] ?>">
                        </td>
                        <td><input type="number" name="floor_number" value="<?= $f['floor_number'] ?>" required></td>
                        <td><button type="submit" name="edit_floor" class="btn btn-small">Edit</button></td>
                        <td><button type="submit" name="delete_floor" class="btn btn-small" onclick="return confirm('Delete floor?')">Delete</button></td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="admin-card">
        <!-- Add Room -->
        <form method="post">
            <h3>Add Room</h3>
            <select name="floor_id" required>
                <option value="">Select Floor</option>
                <?php foreach ($floors as $f): ?>
                    <option value="<?= $f['floor_id'] ?>">
                        <?php
                        $b = array_filter($buildings, fn($bb) => $bb['building_id'] == $f['building_id']);
                        echo htmlspecialchars(reset($b)['building_name']) . " - Floor " . $f['floor_number'];
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="room_number" placeholder="Room Number" required>
            <input type="number" name="capacity" placeholder="Capacity" min="1" value="1" required>
            <button type="submit" name="add_room" class="btn">Add Room</button>
        </form>
        <!-- Rooms List -->
        <h3>Rooms</h3>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Building</th>
                    <th>Floor</th>
                    <th>Room Number</th>
                    <th>Capacity</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $r): ?>
                <tr>
                    <form method="post">
                        <td>
                            <?php
                            $f = array_filter($floors, fn($ff) => $ff['floor_id'] == $r['floor_id']);
                            $floor = reset($f);
                            $b = array_filter($buildings, fn($bb) => $bb['building_id'] == $floor['building_id']);
                            echo htmlspecialchars(reset($b)['building_name']);
                            ?>
                        </td>
                        <td><?= $floor['floor_number'] ?? '' ?></td>
                        <td>
                            <input type="hidden" name="room_id" value="<?= $r['room_id'] ?>">
                            <input type="text" name="room_number" value="<?= htmlspecialchars($r['room_number']) ?>" required>
                        </td>
                        <td><input type="number" name="capacity" value="<?= $r['capacity'] ?>" min="1" required></td>
                        <td><button type="submit" name="edit_room" class="btn btn-small">Edit</button></td>
                        <td><button type="submit" name="delete_room" class="btn btn-small" onclick="return confirm('Delete room?')">Delete</button></td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>