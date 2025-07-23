<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$base_path = '';
$project_folder = 'Hostel-Management';
$script_path = $_SERVER['SCRIPT_NAME'];
$base_path_pos = strpos($script_path, '/' . $project_folder);
if ($base_path_pos !== false) {
    $base_path = substr($script_path, 0, $base_path_pos + strlen('/' . $project_folder));
}
$css_path = $base_path . '/assets/style.css';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hostel Management</title>
    <link rel="stylesheet" href="<?= $css_path ?>">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background: #f6f6f6;
        }
        .main-wrapper {
            flex: 1 0 auto;
            padding: 30px 0 40px 0;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
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
        .student-dashboard h2 {
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 2em;
            text-align: center;
        }
        .dashboard-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 25px 30px;
            min-width: 260px;
            max-width: 300px;
            flex: 1 1 260px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .card h3 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 1.2em;
        }
        .btn {
            background: #00aced;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 4px;
            margin-top: 10px;
            text-decoration: none;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #0077b5;
        }
        .btn-outline {
            background: #fff;
            color: #00aced;
            border: 1px solid #00aced;
        }
        .status-pending { color: orange; }
        .status-approved { color: green; }
        .status-rejected { color: red; }
        @media (max-width: 900px) {
            .dashboard-cards {
                flex-direction: column;
                align-items: center;
            }
            .card {
                max-width: 90vw;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🏠 Hostel System</div>
        <div class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="<?= $base_path ?>/admin/dashboard.php">Home</a>
                    <a href="<?= $base_path ?>/admin/dashboard.php">Admin Panel</a>
                    <a href="<?= $base_path ?>/map.php">Hostel Map</a>
                    <a href="<?= $base_path ?>/admin/change_password.php">Change Password</a>
                <?php elseif ($_SESSION['role'] === 'student'): ?>
                    <a href="<?= $base_path ?>/student/dashboard.php">Home</a>
                    <a href="<?= $base_path ?>/student/dashboard.php">Student Panel</a>
                    <a href="<?= $base_path ?>/map.php">View Hostel Map</a>
                    <a href="<?= $base_path ?>/student/change_password.php">Change Password</a>
                <?php endif; ?>
                <a href="<?= $base_path ?>/logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= $base_path ?>/index.php">Home</a>
                <a href="<?= $base_path ?>/login.php">Login</a>
                <a href="<?= $base_path ?>/register.php">Register</a>
                <a href="<?= $base_path ?>/admin/login.php">Admin Login</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="main-wrapper">
