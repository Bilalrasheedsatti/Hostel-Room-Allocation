<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Find the base path up to /Hostel-Management
$project_folder = 'Hostel-Management';
$script_path = $_SERVER['SCRIPT_NAME'];
$base_path_pos = strpos($script_path, '/' . $project_folder);
$base_path = $base_path_pos !== false
    ? substr($script_path, 0, $base_path_pos + strlen('/' . $project_folder))
    : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="<?= $base_path ?>/assets/style.css">
</head>
<body>
    <div class="header">
        <div class="logo">🏠 Hostel System</div>
        <div class="nav-links">
            <a href="<?= $base_path ?>/admin/dashboard.php">Dashboard</a>
            <a href="<?= $base_path ?>/admin/manage_hostel.php">Manage Hostel</a>
            <a href="<?= $base_path ?>/admin/allocations.php">Allocations</a>
            <a href="<?= $base_path ?>/admin/applications.php">Applications</a>
            <a href="<?= $base_path ?>/admin/rooms.php">Rooms</a>
            <a href="<?= $base_path ?>/admin/complaints.php">Complaints</a>
            <a href="<?= $base_path ?>/admin/change_password.php">Change Password</a>
            <a href="<?= $base_path ?>/logout.php">Logout</a>
        </div>
    </div>
    <div class="main-wrapper">
