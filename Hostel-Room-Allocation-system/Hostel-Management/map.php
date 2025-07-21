<?php
session_start();
include 'includes/config.php';
include 'includes/auth.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hostel Map</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        #hostel-map {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 20px;
        }
        .building {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .floor {
            margin-top: 10px;
        }
        .rooms {
            display: flex;
            gap: 5px;
        }
        .room {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .available {
            background-color: #2ecc71;
        }
        .occupied {
            background-color: #e74c3c;
        }
        .maintenance {
            background-color: #f39c12;
        }
        #room-info {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            background: #fff;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h2>Hostel Map</h2>
        <div id="hostel-map"></div>
        <div id="room-info"></div>
    </div>

    <script src="assets/map.js"></script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
