<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$base_path = '/Hostel-Management'; // Make sure this matches your folder in htdocs
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hostel Management</title>
    <link rel="stylesheet" href="<?= $base_path ?>/assets/style.css">
    <style>
        body { font-family: Arial; margin: 0; padding: 0; }
        .header {
            background: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            transition: color 0.3s;
        }
        .header a:hover {
            color: #00aced;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🏠 Hostel System</div>
       <div class="nav-links">
    <a href="<?= $base_path ?>/index.php">Home</a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="<?= $base_path ?>/admin/dashboard.php">Admin Panel</a>
            <a href="<?= $base_path ?>/map.php">Hostel Map</a>
            <a href="<?= $base_path ?>/admin/change_password.php">Change Password</a>
        <?php elseif ($_SESSION['role'] === 'student'): ?>
            <a href="<?= $base_path ?>/student/dashboard.php">Student Panel</a>
            <a href="<?= $base_path ?>/map.php">View Hostel Map</a>
            <a href="<?= $base_path ?>/student/change_password.php">Change Password</a>
        <?php endif; ?>
        <a href="<?= $base_path ?>/logout.php">Logout</a>
    <?php else: ?>
        <a href="<?= $base_path ?>/login.php">Login</a>
        <a href="<?= $base_path ?>/register.php">Register</a>
        <a href="<?= $base_path ?>/admin/login.php">Admin Login</a>
    <?php endif; ?>
</div>

        </div>
    </div>
