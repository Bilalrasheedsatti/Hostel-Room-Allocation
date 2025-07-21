<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$base_path = '/Hostel-Management';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Hostel Management</title>
    <link rel="stylesheet" href="<?= $base_path ?>/assets/style.css">
</head>
<body>
    <div class="header">
        <div class="logo">🏢 Admin Panel</div>
  <div class="nav-links">
    <a href="<?= $base_path ?>/index.php">Home</a>
    <a href="<?= $base_path ?>/admin/dashboard.php">Dashboard</a>
    <a href="<?= $base_path ?>/admin/applications.php">Applications</a>
    <a href="<?= $base_path ?>/admin/allocations.php">Allocations</a>
    <a href="<?= $base_path ?>/admin/complaints.php">Complaints</a>
    <a href="<?= $base_path ?>/map.php">Map</a> 
    <a href="<?= $base_path ?>/logout.php">Logout</a>
</div>

    </div>
